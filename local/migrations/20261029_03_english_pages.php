<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

return static function (bool $apply): array {
    if (!$apply) return ['plan' => 'Create editable EN page headings, META TITLE and META DESCRIPTION.'];
    \Bitrix\Main\Loader::includeModule('iblock');
    $block = CIBlock::GetList([], ['TYPE' => 'usticom_site_content_en', '=CODE' => 'usticom_en_pages'])->Fetch();
    if (!$block) {
        $api = new CIBlock();
        $id = $api->Add(['IBLOCK_TYPE_ID' => 'usticom_site_content_en', 'LID' => ['en'], 'NAME' => 'Страницы и SEO (EN)',
            'CODE' => 'usticom_en_pages', 'XML_ID' => 'usticom_en_pages', 'ACTIVE' => 'Y',
            'GROUP_ID' => [1 => 'X', 2 => 'R'], 'VERSION' => 1]);
        if (!$id) throw new RuntimeException($api->LAST_ERROR);
        $block = ['ID' => $id];
    }
    foreach (['home' => ['Business Support in Moscow', 'HR Services in Moscow — Usticom',
                'HR records management setup and outsourced support for your business in Moscow.'],
              'services' => ['HR Services', 'HR Outsourcing Services in Moscow — Usticom',
                'HR records management setup and ongoing HR administration. Explore our English-language services.'],
              'contacts' => ['Contacts', 'Contact Usticom — Moscow',
                'Contact Usticom to discuss HR records management and outsourced HR support.']] as $code => [$name, $title, $description]) {
        if (CIBlockElement::GetList([], ['IBLOCK_ID' => $block['ID'], '=CODE' => $code], false, false, ['ID'])->Fetch()) continue;
        $api = new CIBlockElement();
        if (!$api->Add(['IBLOCK_ID' => $block['ID'], 'CODE' => $code, 'XML_ID' => 'usticom:en:page:'.$code,
            'ACTIVE' => 'Y', 'NAME' => $name, 'IPROPERTY_TEMPLATES' => [
                'ELEMENT_META_TITLE' => $title, 'ELEMENT_META_DESCRIPTION' => $description, 'ELEMENT_PAGE_TITLE' => $name]])) {
            throw new RuntimeException($api->LAST_ERROR);
        }
    }
    return ['status' => 'created', 'iblock_id' => (int)$block['ID']];
};
