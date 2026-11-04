<?php
// php -d short_open_tag=1 local/php_interface/migrations/service-display.php [--apply]
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__, 3);
$_SERVER['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('NO_KEEP_STATISTIC', true);
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
if (!\Bitrix\Main\Loader::includeModule('iblock')) throw new RuntimeException('Модуль инфоблоков недоступен');
$apply = in_array('--apply', $argv, true);
$definitions = [
    'SEO_EYEBROW' => ['NAME' => 'Бровь SEO-блока', 'PROPERTY_TYPE' => 'S'],
    'SEO_TITLE' => ['NAME' => 'Заголовок SEO-блока', 'PROPERTY_TYPE' => 'S'],
    'SHOW_IN_SLIDER_ON_LISTING_PAGE' => ['NAME' => 'Выводить в слайдере на странице раздела', 'PROPERTY_TYPE' => 'L'],
];
$properties = [];
foreach ($definitions as $code => $definition) {
    $property = CIBlockProperty::GetList([], ['IBLOCK_ID' => 5, 'CODE' => $code])->Fetch();
    if ($property && ($property['PROPERTY_TYPE'] !== $definition['PROPERTY_TYPE'] || $property['MULTIPLE'] !== 'N')) throw new RuntimeException('Неожиданный тип свойства ' . $code);
    $properties[$code] = $property;
    echo $code . ': ' . ($property ? 'существует, значения сохраняются' : 'будет создано') . PHP_EOL;
}
$field = CUserTypeEntity::GetList([], ['ENTITY_ID' => 'IBLOCK_5_SECTION', 'FIELD_NAME' => 'UF_SHOW_IN_DESKTOP_MENU'])->Fetch();
if ($field && !in_array($field['USER_TYPE_ID'], ['boolean', 'enumeration'], true)) throw new RuntimeException('Неожиданный тип UF_SHOW_IN_DESKTOP_MENU');
echo 'UF_SHOW_IN_DESKTOP_MENU: ' . ($field ? 'существует, выбор сохраняется' : 'будет создано; первоначально выбраны audit, lawyer, nalog, autsorsing-ved, consult, employ, buhgalter') . PHP_EOL;
if (!$properties['SHOW_IN_SLIDER_ON_LISTING_PAGE']) echo "Первоначальный выбор слайдера: услуга #1949 vnutrennij-audit-buxgalterskogo-ucheta, если она есть на этом сайте.\n";
if (!$apply) { echo "Предпросмотр. Для сохранения добавьте --apply.\n"; exit; }

foreach ($definitions as $code => $definition) {
    if ($properties[$code]) continue;
    $editor = new CIBlockProperty();
    $id = $editor->Add(array_merge($definition, ['IBLOCK_ID' => 5, 'CODE' => $code, 'ACTIVE' => 'Y', 'MULTIPLE' => 'N', 'SORT' => 500]));
    if (!$id) throw new RuntimeException($editor->LAST_ERROR);
    $properties[$code] = ['ID' => $id, 'CREATED' => true];
}
$sliderCode = 'SHOW_IN_SLIDER_ON_LISTING_PAGE';
$enum = CIBlockPropertyEnum::GetList([], ['PROPERTY_ID' => (int)$properties[$sliderCode]['ID'], 'XML_ID' => 'Y'])->Fetch();
if (!$enum) {
    $enumEditor = new CIBlockPropertyEnum();
    $id = $enumEditor->Add(['PROPERTY_ID' => (int)$properties[$sliderCode]['ID'], 'VALUE' => 'Да', 'XML_ID' => 'Y', 'DEF' => 'N', 'SORT' => 500]);
    if (!$id) throw new RuntimeException('Не удалось создать вариант Y для слайдера');
    $enum = ['ID' => $id];
}
if (!empty($properties[$sliderCode]['CREATED'])) {
    $service = CIBlockElement::GetList([], ['IBLOCK_ID' => 5, 'ID' => 1949, '=CODE' => 'vnutrennij-audit-buxgalterskogo-ucheta'], false, false, ['ID'])->Fetch();
    if ($service) CIBlockElement::SetPropertyValuesEx((int)$service['ID'], 5, [$sliderCode => (int)$enum['ID']]);
}
if (!$field) {
    $editor = new CUserTypeEntity();
    $id = $editor->Add([
        'ENTITY_ID' => 'IBLOCK_5_SECTION', 'FIELD_NAME' => 'UF_SHOW_IN_DESKTOP_MENU', 'USER_TYPE_ID' => 'boolean',
        'MULTIPLE' => 'N', 'MANDATORY' => 'N', 'SORT' => 100,
        'EDIT_FORM_LABEL' => ['ru' => 'Показывать в меню на ПК', 'en' => 'Show in desktop menu'],
        'LIST_COLUMN_LABEL' => ['ru' => 'Показывать в меню на ПК', 'en' => 'Show in desktop menu'],
        'SETTINGS' => ['DEFAULT_VALUE' => 0, 'DISPLAY' => 'CHECKBOX'],
    ]);
    if (!$id) throw new RuntimeException('Не удалось создать UF_SHOW_IN_DESKTOP_MENU');
    $sections = CIBlockSection::GetList([], ['IBLOCK_ID' => 5, 'DEPTH_LEVEL' => 1, 'CODE' => ['audit', 'lawyer', 'nalog', 'autsorsing-ved', 'consult', 'employ', 'buhgalter']], false, ['ID']);
    $sectionEditor = new CIBlockSection();
    while ($section = $sections->Fetch()) {
        if (!$sectionEditor->Update((int)$section['ID'], ['UF_SHOW_IN_DESKTOP_MENU' => 1])) throw new RuntimeException($sectionEditor->LAST_ERROR);
    }
}
CIBlock::clearIblockTagCache(5);
echo "Готово. Существовавшие поля и их значения сохранены.\n";
