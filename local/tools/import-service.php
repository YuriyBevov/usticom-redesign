<?php
// Add a service and its owned FAQ/price records through Bitrix APIs. No template edits.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$options = getopt('', ['root:', 'payload:', 'apply']);
if (empty($options['root']) || empty($options['payload'])) {
    fwrite(STDERR, "Usage: php import-service.php --root=/site/root --payload=/private/service.json [--apply]\n");
    exit(2);
}
$_SERVER['DOCUMENT_ROOT'] = rtrim($options['root'], '/');
$_SERVER['HTTP_HOST'] = 'localhost';
define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_CHECK', true);
define('BX_CRONTAB', true);
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
if (!\Bitrix\Main\Loader::includeModule('iblock')) throw new RuntimeException('Iblock module is unavailable');
\Bitrix\Main\Loader::includeModule('simai.complexprop');

$payload = json_decode(file_get_contents($options['payload']), true, 512, JSON_THROW_ON_ERROR);
foreach (['xml_id', 'name', 'code', 'iblock_id', 'section_id', 'faq_iblock_id', 'prices_iblock_id', 'prices_parent_id'] as $key) {
    if (empty($payload[$key])) throw new RuntimeException('Missing field: ' . $key);
}
if (!preg_match('/^[a-z0-9-]+$/D', $payload['code'])) throw new RuntimeException('Invalid service URL code');
if (!in_array($payload['active'] ?? 'N', ['N', 'Y'], true)) throw new RuntimeException('Invalid active status');
$active = $payload['active'] ?? 'N';
$iblockId = (int)$payload['iblock_id'];
$existing = CIBlockElement::GetList([], ['IBLOCK_ID' => $iblockId, '=XML_ID' => $payload['xml_id']], false, false, ['ID', 'NAME', 'CODE'])->Fetch();
if ($existing) {
    echo json_encode(['status' => 'already_exists', 'service' => $existing], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n";
    exit;
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
if (!array_key_exists('apply', $options)) {
    echo json_encode($plan, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n";
    exit;
}
$created = ['faq_ids' => [], 'price_ids' => []];
$DB->StartTransaction();
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
    $values = [
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
    $DB->Commit();
} catch (Throwable $error) {
    $DB->Rollback();
    throw $error;
}
echo json_encode(['status' => 'created', 'active' => $active] + $created, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n";
