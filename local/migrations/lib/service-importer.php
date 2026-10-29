<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

function usticomImportService(array $payload, bool $apply = false): array
{
    global $DB;
    foreach (['xml_id', 'name', 'code', 'iblock_id', 'section_id', 'faq_iblock_id', 'prices_iblock_id', 'prices_parent_id'] as $key) {
        if (empty($payload[$key])) throw new RuntimeException('Missing field: ' . $key);
    }
    if (!preg_match('/^[a-z0-9-]+$/D', $payload['code'])) throw new RuntimeException('Invalid service URL code');
    if (!in_array($payload['active'] ?? 'N', ['N', 'Y'], true)) throw new RuntimeException('Invalid active status');
    $active = $payload['active'] ?? 'N';
    $iblockId = (int)$payload['iblock_id'];
    $existing = CIBlockElement::GetList([], ['IBLOCK_ID' => $iblockId, '=XML_ID' => $payload['xml_id']], false, false, ['ID', 'NAME', 'CODE'])->Fetch();
    if ($existing) {
        return ['status' => 'already_exists', 'service' => $existing];
    }
    if (CIBlockElement::GetList([], ['IBLOCK_ID' => $iblockId, '=CODE' => $payload['code']], false, false, ['ID'])->Fetch()) {
        throw new RuntimeException('The service URL is already used; no existing record was changed');
    }
    foreach ([[$iblockId, (int)$payload['section_id']], [(int)$payload['prices_iblock_id'], (int)$payload['prices_parent_id']]] as [$block, $section]) {
        if (!CIBlockSection::GetList([], ['IBLOCK_ID' => $block, 'ID' => $section], false, ['ID'])->Fetch()) {
            throw new RuntimeException('The target section does not belong to its information block');
        }
    }
    $properties = [];
    $query = CIBlockProperty::GetList([], ['IBLOCK_ID' => $iblockId]);
    while ($property = $query->Fetch()) $properties[$property['CODE']] = $property;
    foreach (['SERVICE_PREVIEW_TEXT', 'REASONS', 'INCLUDES', 'WORKFLOW', 'FAQ_LINKED', 'PRICES'] as $code) {
        if (!isset($properties[$code])) throw new RuntimeException('Missing service property: ' . $code);
    }
    if ((int)$properties['FAQ_LINKED']['LINK_IBLOCK_ID'] !== (int)$payload['faq_iblock_id']
        || (int)$properties['PRICES']['LINK_IBLOCK_ID'] !== (int)$payload['prices_iblock_id']) {
        throw new RuntimeException('FAQ or price property points to a different information block');
    }
    $compound = static function (array $rows, string $prefix): array {
        return array_map(static fn(array $row): array => ['SUBPROP_VALUES' => [
            $prefix . '_TITLE' => $row['title'],
            // These templates escape text themselves; storing <p> tags would show literal markup.
            $prefix . '_TEXT' => ['TYPE' => 'TEXT', 'TEXT' => $row['text'] ?? html_entity_decode(
                strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $row['html'] ?? '')),
                ENT_QUOTES | ENT_HTML5, 'UTF-8'
            )],
        ]], $rows);
    };
    $plan = ['status' => 'preview', 'service_name' => $payload['name'], 'service_code' => $payload['code'],
        'active' => $active, 'faq_count' => count($payload['faq']), 'price_count' => count($payload['prices']),
        'reasons_count' => count($payload['reasons']), 'includes_count' => count($payload['includes']),
        'workflow_count' => count($payload['workflow'])];
    if (!$apply) return $plan;
    $created = ['faq_ids' => [], 'price_ids' => []];
    // The migration runner owns the transaction and journal.

    try {
        $section = new CIBlockSection();
        $pricesSectionId = $section->Add([
            'IBLOCK_ID' => (int)$payload['prices_iblock_id'], 'IBLOCK_SECTION_ID' => (int)$payload['prices_parent_id'],
            'NAME' => $payload['name'], 'CODE' => $payload['code'], 'XML_ID' => $payload['xml_id'] . ':prices',
            'ACTIVE' => $active, 'SORT' => 900,
        ]);
        if (!$pricesSectionId) throw new RuntimeException($section->LAST_ERROR);
        $created['prices_section_id'] = (int)$pricesSectionId;
        foreach ($payload['prices'] as $index => $price) {
            $element = new CIBlockElement();
            $id = $element->Add([
                'IBLOCK_ID' => (int)$payload['prices_iblock_id'], 'IBLOCK_SECTION_ID' => $pricesSectionId,
                'XML_ID' => $payload['xml_id'] . ':price:' . ($index + 1), 'NAME' => $price['name'],
                'CODE' => $payload['code'] . '-' . ($index + 1), 'ACTIVE' => $active, 'SORT' => ($index + 1) * 100,
                'PROPERTY_VALUES' => ['PRICE' => $price['price'], 'NOTE' => $price['note'] ?? ''],
            ]);
            if (!$id) throw new RuntimeException($element->LAST_ERROR);
            $created['price_ids'][] = (int)$id;
        }
        foreach ($payload['faq'] as $index => $question) {
            $element = new CIBlockElement();
            $id = $element->Add([
                'IBLOCK_ID' => (int)$payload['faq_iblock_id'], 'XML_ID' => $payload['xml_id'] . ':faq:' . ($index + 1),
                'NAME' => $question['question'], 'CODE' => $payload['code'] . '-faq-' . ($index + 1),
                'ACTIVE' => $active, 'SORT' => ($index + 1) * 100,
                'PREVIEW_TEXT' => $question['answer'], 'PREVIEW_TEXT_TYPE' => 'html',
                'DETAIL_TEXT' => $question['answer'], 'DETAIL_TEXT_TYPE' => 'html',
            ]);
            if (!$id) throw new RuntimeException($element->LAST_ERROR);
            $created['faq_ids'][] = (int)$id;
        }
        $cardsSection = new CIBlockSection();
        $cardsId = $cardsSection->Add(['IBLOCK_ID' => (int)$payload['tizzers_iblock_id'],
            'NAME' => $payload['name'], 'CODE' => $payload['code'], 'XML_ID' => $payload['xml_id'].':hero', 'ACTIVE' => $active]);
        if (!$cardsId) throw new RuntimeException($cardsSection->LAST_ERROR);
        foreach ($payload['hero_cards'] as $i => $card) {
            $cardElement = new CIBlockElement();
            if (!$cardElement->Add(['IBLOCK_ID' => (int)$payload['tizzers_iblock_id'], 'IBLOCK_SECTION_ID' => $cardsId,
                'NAME' => $card['title'], 'PREVIEW_TEXT' => '<strong>'.htmlspecialchars($card['title'], ENT_QUOTES, 'UTF-8').'</strong><br>'.htmlspecialchars($card['text'], ENT_QUOTES, 'UTF-8'), 'PREVIEW_TEXT_TYPE' => 'html',
                'XML_ID' => $payload['xml_id'].':hero:'.($i+1), 'ACTIVE' => $active, 'SORT' => ($i+1)*100])) {
                throw new RuntimeException($cardElement->LAST_ERROR);
            }
        }
        $values = [
            'HERO_TIZZERS' => $cardsId,
            'COST_INTRO' => ['VALUE' => ['TYPE' => 'HTML', 'TEXT' => '<p>'.htmlspecialchars($payload['cost_intro'], ENT_QUOTES, 'UTF-8').'</p>']],
            'SEO_HEADING' => $payload['seo_heading'], 'SEO_BADGE' => $payload['seo_badge'],
            'RU_ORIGINAL' => $payload['ru_original_id'] ?? false,
            'SERVICE_PREVIEW_TEXT' => ['VALUE' => ['TYPE' => 'HTML', 'TEXT' => $payload['short_html']]],
            'SERVICE_PRICE_FROM' => $payload['price_from'],
            'REASONS_SECTION_TITLE' => $payload['reasons_title'], 'REASONS' => $compound($payload['reasons'], 'REASONS'),
            'INCLUDES_MAIN_TITLE' => $payload['includes_title'], 'INCLUDES' => $compound($payload['includes'], 'INCLUDES'),
            'WORKFLOW_MAIN_TITLE' => $payload['workflow_title'], 'WORKFLOW' => $compound($payload['workflow'], 'WORKFLOW'),
            'FAQ_PREVIEW_TEXT' => ['VALUE' => ['TYPE' => 'HTML', 'TEXT' => $payload['faq_intro_html']]],
            'FAQ_LINKED' => $created['faq_ids'], 'PRICES' => $pricesSectionId,
        ];
        $element = new CIBlockElement();
        $id = $element->Add([
            'IBLOCK_ID' => $iblockId, 'IBLOCK_SECTION_ID' => (int)$payload['section_id'],
            'XML_ID' => $payload['xml_id'], 'NAME' => $payload['name'], 'CODE' => $payload['code'],
            'ACTIVE' => $active, 'SORT' => 900,
            'PREVIEW_TEXT' => $payload['hero_html'], 'PREVIEW_TEXT_TYPE' => 'html',
            'DETAIL_TEXT' => $payload['detail_html'], 'DETAIL_TEXT_TYPE' => 'html',
            'PROPERTY_VALUES' => $values,
            'IPROPERTY_TEMPLATES' => ['ELEMENT_META_TITLE' => $payload['meta_title'],
                                     'ELEMENT_META_DESCRIPTION' => $payload['meta_description'],
                                     'ELEMENT_PAGE_TITLE' => $payload['name']],
        ]);
        if (!$id) throw new RuntimeException($element->LAST_ERROR);
        $created['service_id'] = (int)$id;
        $saved = CIBlockElement::GetList([], ['IBLOCK_ID' => $iblockId, 'ID' => $id])->GetNextElement();
        $savedProperties = $saved->GetProperties();
        foreach (['REASONS' => 'reasons', 'INCLUDES' => 'includes', 'WORKFLOW' => 'workflow'] as $propertyCode => $payloadKey) {
            $rows = array_values(array_filter((array)$savedProperties[$propertyCode]['VALUE'],
                static fn($row): bool => is_array($row) && !empty($row['SUB_VALUES'])));
            if (count($rows) !== count($payload[$payloadKey])) {
                throw new RuntimeException('Complex property readback did not match: ' . $propertyCode);
            }
        }

    } catch (Throwable $error) {

        throw $error;
    }
    return ['status' => 'created', 'active' => $active] + $created;

}
