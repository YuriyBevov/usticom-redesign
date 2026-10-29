<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

return static function (bool $apply): array {
    if (!$apply) return ['plan' => [
        'Create a separate Russian HR audit service; keep the existing service.',
        'Create /en/ site and Контент сайта(EN): services, prices, FAQ and hero cards.',
        'Import the English HR outsourcing service and create an English request form.',
        'Add /en/ routing without replacing existing URL rules.',
    ]];
    foreach (['iblock', 'simai.complexprop', 'form'] as $module) {
        if (!\Bitrix\Main\Loader::includeModule($module)) throw new RuntimeException('Missing module: '.$module);
    }
    require_once __DIR__.'/lib/service-importer.php';
    $check = static function ($value, string $message) {
        if (!$value) throw new RuntimeException($message.': '.($GLOBALS['APPLICATION']->GetException() ?: $GLOBALS['strError'] ?? ''));
        return (int)$value;
    };
    // Existing project IDs are verified before adding properties or data.
    foreach ([5 => 'Услуги', 22 => 'Прайс-лист', 26 => 'Вопросы  и ответы', 29 => 'Тизеры'] as $id => $name) {
        $block = CIBlock::GetArrayByID($id);
        if (!$block || $block['IBLOCK_TYPE_ID'] !== 'usticom_site_content' || $block['NAME'] !== $name) {
            throw new RuntimeException('Unexpected Russian information block: '.$id);
        }
    }
    $site = CSite::GetByID('en')->Fetch();
    if ($site && ($site['DIR'] !== '/en/' || $site['LANGUAGE_ID'] !== 'en')) throw new RuntimeException('Site en is already used');
    if (!$site) {
        $ruSite = CSite::GetByID('s1')->Fetch();
        $culture = \Bitrix\Main\Localization\CultureTable::getRow(['filter' => ['=CODE' => 'en']]);
        if (!$culture) {
            $added = \Bitrix\Main\Localization\CultureTable::add(['CODE' => 'en', 'NAME' => 'English',
                'FORMAT_DATE' => 'DD/MM/YYYY', 'FORMAT_DATETIME' => 'DD/MM/YYYY HH:MI:SS', 'CHARSET' => 'UTF-8']);
            if (!$added->isSuccess()) throw new RuntimeException(implode('; ', $added->getErrorMessages()));
            $culture = ['ID' => $added->getId()];
        }
        $api = new CSite();
        $check($api->Add(['LID' => 'en', 'ACTIVE' => 'Y', 'SORT' => 20, 'DEF' => 'N',
            'NAME' => 'Usticom (EN)', 'SITE_NAME' => 'Usticom', 'DIR' => '/en/', 'LANGUAGE_ID' => 'en',
            'DOC_ROOT' => '', 'SERVER_NAME' => $ruSite['SERVER_NAME'], 'EMAIL' => $ruSite['EMAIL'], 'CULTURE_ID' => $culture['ID'],
            'FORMAT_DATE' => 'DD/MM/YYYY', 'FORMAT_DATETIME' => 'DD/MM/YYYY HH:MI:SS', 'CHARSET' => 'UTF-8',
            'TEMPLATE' => [['SORT' => 1, 'CONDITION' => '', 'TEMPLATE' => 'littleweb']],
        ]), $api->LAST_ERROR ?? 'Cannot create site');
    }
    $type = 'usticom_site_content_en';
    if (!CIBlockType::GetByID($type)->Fetch()) {
        $api = new CIBlockType();
        $check($api->Add(['ID' => $type, 'SECTIONS' => 'Y', 'IN_RSS' => 'N', 'SORT' => 110,
            'LANG' => ['ru' => ['NAME' => 'Контент сайта(EN)', 'SECTION_NAME' => 'Разделы', 'ELEMENT_NAME' => 'Элементы'],
                       'en' => ['NAME' => 'Website content (EN)', 'SECTION_NAME' => 'Sections', 'ELEMENT_NAME' => 'Elements']]]), 'Cannot create EN type');
    }
    $blocks = [];
    foreach (['services' => 'Услуги (EN)', 'prices' => 'Прайс-лист (EN)', 'faq' => 'Вопросы и ответы (EN)', 'tizzers' => 'Тизеры (EN)'] as $role => $name) {
        $code = 'usticom_en_'.$role;
        $existing = CIBlock::GetList([], ['TYPE' => $type, '=CODE' => $code])->Fetch();
        if ($existing) { $blocks[$role] = (int)$existing['ID']; continue; }
        $api = new CIBlock();
        $blocks[$role] = $check($api->Add(['IBLOCK_TYPE_ID' => $type, 'LID' => ['en'], 'NAME' => $name,
            'CODE' => $code, 'XML_ID' => $code, 'ACTIVE' => 'Y', 'SORT' => 100,
            'VERSION' => 1, 'GROUP_ID' => [1 => 'X', 2 => 'R'],
            'LIST_PAGE_URL' => '/en/services/', 'SECTION_PAGE_URL' => '/en/services/#SECTION_CODE#/',
            'DETAIL_PAGE_URL' => '/en/services/#SECTION_CODE#/#ELEMENT_CODE#/',
            'FIELDS' => ['CODE' => ['IS_REQUIRED' => 'N'], 'NAME' => ['IS_REQUIRED' => 'Y']],
        ]), 'Cannot create '.$name.': '.$api->LAST_ERROR);
    }
    $property = static function (int $iblock, string $code, string $name, array $extra = []) use ($check): int {
        $found = CIBlockProperty::GetList([], ['IBLOCK_ID' => $iblock, 'CODE' => $code])->Fetch();
        if ($found) return (int)$found['ID'];
        $api = new CIBlockProperty();
        return $check($api->Add($extra + ['IBLOCK_ID' => $iblock, 'CODE' => $code, 'NAME' => $name,
            'ACTIVE' => 'Y', 'PROPERTY_TYPE' => 'S', 'MULTIPLE' => 'N', 'SORT' => 500]), 'Cannot create property '.$code.': '.$api->LAST_ERROR);
    };
    foreach (['PRICE' => 'Price', 'NOTE' => 'Description'] as $code => $name) $property($blocks['prices'], $code, $name);
    $service = $blocks['services'];
    foreach (['SERVICE_PREVIEW_TEXT' => 'Briefly about the service', 'FAQ_PREVIEW_TEXT' => 'FAQ introduction'] as $code => $name) {
        $property($service, $code, $name, ['USER_TYPE' => 'HTML']);
    }
    $property($service, 'SERVICE_PRICE_FROM', 'Starting price');
    foreach (['REASONS' => 'When the service is needed', 'INCLUDES' => 'What is included', 'WORKFLOW' => 'How we work'] as $prefix => $name) {
        $title = $property($service, $prefix.'_TITLE', $name.': title', ['MULTIPLE' => 'Y']);
        $text = $property($service, $prefix.'_TEXT', $name.': text', ['MULTIPLE' => 'Y', 'USER_TYPE' => 'HTML']);
        $property($service, $prefix, $name, ['MULTIPLE' => 'Y', 'USER_TYPE' => 'simai_complex',
            'USER_TYPE_SETTINGS' => ['SUBPROPS' => [$title, $text], 'SUBPROPS_REQ' => [0, 0]]]);
    }
    foreach (['REASONS_SECTION_TITLE' => 'Situations heading', 'INCLUDES_MAIN_TITLE' => 'Includes heading', 'WORKFLOW_MAIN_TITLE' => 'Workflow heading'] as $code => $name) $property($service, $code, $name);
    $property($service, 'FAQ_LINKED', 'FAQ', ['PROPERTY_TYPE' => 'E', 'MULTIPLE' => 'Y', 'LINK_IBLOCK_ID' => $blocks['faq']]);
    $property($service, 'PRICES', 'Price section', ['PROPERTY_TYPE' => 'G', 'LINK_IBLOCK_ID' => $blocks['prices']]);
    $property($service, 'RU_ORIGINAL', 'Russian version', ['PROPERTY_TYPE' => 'E', 'LINK_IBLOCK_ID' => 5]);
    foreach ([5 => 29, $service => $blocks['tizzers']] as $id => $tizzers) {
        $property($id, 'HERO_TIZZERS', 'Карточки первого экрана', ['PROPERTY_TYPE' => 'G', 'LINK_IBLOCK_ID' => $tizzers]);
        $property($id, 'COST_INTRO', 'Текст перед тарифами', ['USER_TYPE' => 'HTML']);
        $property($id, 'SEO_HEADING', 'Заголовок подробного описания');
        $property($id, 'SEO_BADGE', 'Бейдж подробного описания');
    }
    $section = static function (int $block, string $code, string $name) use ($check): int {
        $found = CIBlockSection::GetList([], ['IBLOCK_ID' => $block, '=CODE' => $code], false, ['ID'])->Fetch();
        if ($found) return (int)$found['ID'];
        $api = new CIBlockSection();
        return $check($api->Add(['IBLOCK_ID' => $block, 'CODE' => $code, 'NAME' => $name, 'ACTIVE' => 'Y']), 'Cannot create section '.$code);
    };
    $ruSection = CIBlockSection::GetList([], ['IBLOCK_ID' => 5, '=CODE' => 'employ'], false, ['ID'])->Fetch();
    $ruPrices = CIBlockSection::GetList([], ['IBLOCK_ID' => 22, '=CODE' => 'kadrovye'], false, ['ID'])->Fetch();
    if (!$ruSection || !$ruPrices) throw new RuntimeException('Russian HR sections are missing');
    $ru = json_decode(file_get_contents(__DIR__.'/content/ru-hr-audit.json'), true, 512, JSON_THROW_ON_ERROR);
    $ru += ['iblock_id' => 5, 'section_id' => (int)$ruSection['ID'], 'faq_iblock_id' => 26,
        'prices_iblock_id' => 22, 'prices_parent_id' => (int)$ruPrices['ID'], 'tizzers_iblock_id' => 29];
    $results['ru'] = usticomImportService($ru, true);
    $original = CIBlockElement::GetList([], ['IBLOCK_ID' => 5, '=CODE' => 'autsorsing-kadrovogo-deloproizvodstva-s-nulya'], false, false, ['ID'])->Fetch();
    if (!$original) throw new RuntimeException('Russian outsourcing original is missing');
    $en = json_decode(file_get_contents(__DIR__.'/content/en-hr-outsourcing.json'), true, 512, JSON_THROW_ON_ERROR);
    $en += ['iblock_id' => $service, 'section_id' => $section($service, 'hr', 'HR Services'),
        'faq_iblock_id' => $blocks['faq'], 'prices_iblock_id' => $blocks['prices'],
        'prices_parent_id' => $section($blocks['prices'], 'hr', 'HR Services'), 'tizzers_iblock_id' => $blocks['tizzers'],
        'ru_original_id' => (int)$original['ID']];
    $results['en'] = usticomImportService($en, true);
    $by = 's_id'; $order = 'asc'; $filtered = false;
    $form = CForm::GetList($by, $order, ['SID' => 'USTICOM_EN_REQUEST', 'SID_EXACT_MATCH' => 'Y'], $filtered, 'N')->Fetch();
    if (!$form) {
        $formId = $check(CForm::Set(['SID' => 'USTICOM_EN_REQUEST', 'NAME' => 'Get expert advice', 'BUTTON' => 'Submit a Request',
            'DESCRIPTION' => 'Tell us what you need — we will suggest a solution.', 'DESCRIPTION_TYPE' => 'text',
            'USE_CAPTCHA' => 'N', 'USE_DEFAULT_TEMPLATE' => 'Y', 'arSITE' => ['en'], 'arMENU' => ['en' => 'Requests (EN)', 'ru' => 'Заявки (EN)'],
            'arGROUP' => [1 => 30, 2 => 10]], false, 'N'), 'Cannot create EN form');
        $check(CFormStatus::Set(['FORM_ID' => $formId, 'TITLE' => 'New request', 'ACTIVE' => 'Y', 'DEFAULT_VALUE' => 'Y',
            'arPERMISSION_MOVE' => [0], 'arPERMISSION_VIEW' => [1], 'arPERMISSION_EDIT' => [1], 'arPERMISSION_DELETE' => [1]], false, 'N'), 'Cannot create request status');
        foreach ([['NAME', 'Your name', 'text', 'Y'], ['PHONE', 'Phone', 'text', 'Y'], ['EMAIL', 'Email', 'email', 'N'],
                  ['TEXT', 'Your question', 'textarea', 'N'], ['THEME', 'Subject', 'hidden', 'N']] as $index => [$sid, $title, $fieldType, $required]) {
            $check(CFormField::Set(['FORM_ID' => $formId, 'SID' => $sid, 'TITLE' => $title, 'TITLE_TYPE' => 'text',
                'ACTIVE' => 'Y', 'ADDITIONAL' => 'N', 'REQUIRED' => $required, 'C_SORT' => ($index+1)*100,
                'arANSWER' => [['MESSAGE' => ' ', 'FIELD_TYPE' => $fieldType, 'ACTIVE' => 'Y', 'C_SORT' => 100,
                    'FIELD_WIDTH' => 40, 'FIELD_HEIGHT' => $fieldType === 'textarea' ? 4 : 1]]], false, 'N'), 'Cannot create EN field '.$sid);
        }
        // Native notifications use this site's configured administrator address.
        CForm::SetMailTemplate($formId);
        $results['form_id'] = $formId;
    }
    return $results;
};
