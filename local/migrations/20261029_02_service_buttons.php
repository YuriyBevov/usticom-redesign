<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

return static function (bool $apply): array {
    if (!$apply) return ['plan' => 'Add editable hero button labels to the two new service cards.'];
    \Bitrix\Main\Loader::includeModule('iblock');
    $english = CIBlock::GetList([], ['TYPE' => 'usticom_site_content_en', '=CODE' => 'usticom_en_services'])->Fetch();
    if (!$english) throw new RuntimeException('English services are missing');
    foreach ([5 => 'ru-hr-audit', (int)$english['ID'] => 'en-hr-outsourcing'] as $iblock => $file) {
        $payload = json_decode(file_get_contents(__DIR__.'/content/'.$file.'.json'), true, 512, JSON_THROW_ON_ERROR);
        foreach (['HERO_CTA_PRIMARY' => 'hero_cta_primary', 'HERO_CTA_SECONDARY' => 'hero_cta_secondary'] as $code => $key) {
            if (!CIBlockProperty::GetList([], ['IBLOCK_ID' => $iblock, 'CODE' => $code])->Fetch()) {
                $api = new CIBlockProperty();
                if (!$api->Add(['IBLOCK_ID' => $iblock, 'CODE' => $code, 'NAME' => $code === 'HERO_CTA_PRIMARY' ? 'Основная кнопка' : 'Вторая кнопка',
                    'ACTIVE' => 'Y', 'PROPERTY_TYPE' => 'S', 'MULTIPLE' => 'N', 'SORT' => 550])) throw new RuntimeException($api->LAST_ERROR);
            }
            $element = CIBlockElement::GetList([], ['IBLOCK_ID' => $iblock, '=XML_ID' => $payload['xml_id']], false, false, ['ID'])->Fetch();
            if (!$element) throw new RuntimeException('The new service is missing');
            $existing = CIBlockElement::GetProperty($iblock, $element['ID'], [], ['CODE' => $code])->Fetch();
            if (trim((string)($existing['VALUE'] ?? '')) === '') {
                CIBlockElement::SetPropertyValuesEx($element['ID'], $iblock, [$code => $payload[$key]]);
            }
        }
    }
    return ['status' => 'created'];
};
