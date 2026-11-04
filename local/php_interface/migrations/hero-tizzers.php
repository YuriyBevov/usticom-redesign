<?php
// php -d short_open_tag=1 local/tools/migrate-hero-tizzers.php [--apply]
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__, 3);
$_SERVER['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('NO_KEEP_STATISTIC', true);
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
if (!\Bitrix\Main\Loader::includeModule('iblock')) throw new RuntimeException('Модуль инфоблоков недоступен');

$apply = in_array('--apply', $argv, true);
$optionModule = 'usticom_tizzers';
if (COption::GetOptionString($optionModule, 'element_links_v1_done', '') === 'Y') {
    echo "Миграция уже выполнена. Последующие изменения контент-менеджеров сохранены.\n";
    exit;
}

$defaults = CIBlockSection::GetList([], ['IBLOCK_ID' => 29, 'ID' => 5158], false, ['ID', 'NAME'])->Fetch();
if (!$defaults || $defaults['NAME'] !== 'По умолчанию') throw new RuntimeException('Не найден ожидаемый раздел «По умолчанию» #5158');
$properties = CIBlockProperty::GetList([], ['IBLOCK_ID' => 5, 'CODE' => 'HERO_TIZZERS']);
$property = $properties->Fetch();
if ($properties->Fetch()) throw new RuntimeException('Найдено несколько свойств HERO_TIZZERS');
if ($property && (!in_array($property['PROPERTY_TYPE'], ['G', 'E'], true) || (int)$property['LINK_IBLOCK_ID'] !== 29)) {
    throw new RuntimeException('HERO_TIZZERS имеет неожиданный тип или целевой инфоблок');
}
$userField = CUserTypeEntity::GetList([], ['ENTITY_ID' => 'IBLOCK_5_SECTION', 'FIELD_NAME' => 'UF_HERO_TIZZERS'])->Fetch();
if ($userField && ($userField['USER_TYPE_ID'] !== 'iblock_element' || $userField['MULTIPLE'] !== 'Y' || (int)$userField['SETTINGS']['IBLOCK_ID'] !== 29)) {
    throw new RuntimeException('UF_HERO_TIZZERS должен быть множественной привязкой к элементам инфоблока 29');
}

// Do not flatten sections still used by unrelated section-link fields.
$references = CIBlockProperty::GetList([], ['LINK_IBLOCK_ID' => 29, 'PROPERTY_TYPE' => 'G']);
while ($reference = $references->Fetch()) {
    if ((int)$reference['ID'] !== (int)($property['ID'] ?? 0)) throw new RuntimeException('Сначала перенесите привязки свойства #' . $reference['ID']);
}
$references = CUserTypeEntity::GetList([], ['USER_TYPE_ID' => 'iblock_section']);
while ($reference = $references->Fetch()) {
    if ((int)($reference['SETTINGS']['IBLOCK_ID'] ?? 0) === 29) throw new RuntimeException('Сначала перенесите привязки поля ' . $reference['FIELD_NAME']);
}

// Keep the original memberships and legacy selections in the database so an interrupted run can resume.
$savedPlan = COption::GetOptionString($optionModule, 'element_links_v1_plan', '');
if ($savedPlan !== '') {
    $plan = json_decode($savedPlan, true, 512, JSON_THROW_ON_ERROR);
} else {
    $plan = ['memberships' => [], 'legacy_links' => [], 'sections' => []];
    $elements = CIBlockElement::GetList(['ID' => 'ASC'], ['IBLOCK_ID' => 29], false, false, ['ID']);
    while ($element = $elements->Fetch()) {
        $groups = CIBlockElement::GetElementGroups((int)$element['ID'], true, ['ID']);
        $plan['memberships'][(int)$element['ID']] = [];
        while ($group = $groups->Fetch()) $plan['memberships'][(int)$element['ID']][] = (int)$group['ID'];
    }
    $sections = CIBlockSection::GetList(['DEPTH_LEVEL' => 'DESC'], ['IBLOCK_ID' => 29], false, ['ID']);
    while ($section = $sections->Fetch()) {
        if ((int)$section['ID'] !== 5158) $plan['sections'][] = (int)$section['ID'];
    }
    if ($property && $property['PROPERTY_TYPE'] === 'G') {
        $services = CIBlockElement::GetList([], ['IBLOCK_ID' => 5], false, false, ['ID']);
        while ($service = $services->Fetch()) {
            $values = CIBlockElement::GetProperty(5, (int)$service['ID'], [], ['ID' => (int)$property['ID']]);
            $selectedSections = [];
            while ($value = $values->Fetch()) {
                if ((int)$value['VALUE'] > 0) $selectedSections[] = (int)$value['VALUE'];
            }
            if (!$selectedSections) continue;
            $ids = [];
            foreach ($plan['memberships'] as $id => $groups) {
                if (array_intersect($selectedSections, $groups)) $ids[] = (int)$id;
            }
            if (!$ids) throw new RuntimeException('Выбран пустой раздел тиззеров у услуги #' . $service['ID'] . '; перенос отменён, чтобы не потерять выбор');
            $plan['legacy_links'][(int)$service['ID']] = $ids;
        }
    }
}

echo "HERO_TIZZERS: множественная привязка к элементам инфоблока 29.\n";
echo "UF_HERO_TIZZERS: множественная привязка к элементам для разделов услуг.\n";
foreach ($plan['legacy_links'] as $id => $ids) echo 'Услуга #' . $id . ': тиззеры ' . implode(', ', $ids) . PHP_EOL;
foreach ($plan['memberships'] as $id => $groups) {
    echo 'Тиззер #' . $id . ': ' . (in_array(5158, $groups, true) ? 'раздел «По умолчанию»' : 'корень инфоблока') . PHP_EOL;
}
if (!$apply) { echo "Предпросмотр. Для сохранения добавьте --apply.\n"; exit; }

if ($savedPlan === '') COption::SetOptionString($optionModule, 'element_links_v1_plan', json_encode($plan, JSON_THROW_ON_ERROR));
$DB->StartTransaction();
try {
    $propertyEditor = new CIBlockProperty();
    $propertyFields = ['PROPERTY_TYPE' => 'E', 'LINK_IBLOCK_ID' => 29, 'MULTIPLE' => 'Y'];
    if ($property) {
        if (!$propertyEditor->Update((int)$property['ID'], $propertyFields)) throw new RuntimeException($propertyEditor->LAST_ERROR);
    } else {
        if (!$propertyEditor->Add(array_merge($propertyFields, ['IBLOCK_ID' => 5, 'CODE' => 'HERO_TIZZERS', 'NAME' => 'Карточки первого экрана', 'ACTIVE' => 'Y', 'SORT' => 500]))) throw new RuntimeException($propertyEditor->LAST_ERROR);
    }
    if (!$userField) {
        $userFieldEditor = new CUserTypeEntity();
        $fieldId = $userFieldEditor->Add([
            'ENTITY_ID' => 'IBLOCK_5_SECTION', 'FIELD_NAME' => 'UF_HERO_TIZZERS', 'USER_TYPE_ID' => 'iblock_element',
            'MULTIPLE' => 'Y', 'MANDATORY' => 'N', 'SORT' => 500,
            'EDIT_FORM_LABEL' => ['ru' => 'Карточки первого экрана', 'en' => 'Hero teasers'],
            'LIST_COLUMN_LABEL' => ['ru' => 'Карточки первого экрана', 'en' => 'Hero teasers'],
            'HELP_MESSAGE' => ['ru' => 'Выберите тиззеры. Если выбор пуст, выводится набор «По умолчанию».'],
            'SETTINGS' => ['IBLOCK_ID' => 29, 'DISPLAY' => 'LIST', 'LIST_HEIGHT' => 8, 'ACTIVE_FILTER' => 'Y'],
        ]);
        if (!$fieldId) throw new RuntimeException($APPLICATION->GetException() ? $APPLICATION->GetException()->GetString() : 'Не удалось создать UF_HERO_TIZZERS');
    }
    foreach ($plan['legacy_links'] as $id => $ids) {
        CIBlockElement::SetPropertyValuesEx((int)$id, 5, ['HERO_TIZZERS' => $ids]);
        $actual = [];
        $values = CIBlockElement::GetProperty(5, (int)$id, [], ['CODE' => 'HERO_TIZZERS']);
        while ($value = $values->Fetch()) if ((int)$value['VALUE'] > 0) $actual[] = (int)$value['VALUE'];
        sort($actual); sort($ids);
        if ($actual !== $ids) throw new RuntimeException('Не удалось сохранить привязку услуги #' . $id);
    }
    foreach ($plan['memberships'] as $id => $groups) {
        $targetGroups = in_array(5158, $groups, true) ? [5158] : [];
        CIBlockElement::SetElementSection((int)$id, $targetGroups);
        $actual = [];
        $currentGroups = CIBlockElement::GetElementGroups((int)$id, true, ['ID']);
        while ($group = $currentGroups->Fetch()) $actual[] = (int)$group['ID'];
        if ($actual !== $targetGroups) throw new RuntimeException('Не удалось перенести тиззер #' . $id);
    }
    foreach ($plan['sections'] as $id) {
        // Only remove empty old containers; never delete content added after planning.
        if (CIBlockElement::GetList([], ['IBLOCK_ID' => 29, 'SECTION_ID' => $id, 'INCLUDE_SUBSECTIONS' => 'Y'], false, ['nTopCount' => 1], ['ID'])->Fetch()) continue;
        if (CIBlockSection::GetList([], ['IBLOCK_ID' => 29, 'SECTION_ID' => $id], false, ['ID'])->Fetch()) continue;
        if (CIBlockSection::GetList([], ['IBLOCK_ID' => 29, 'ID' => $id], false, ['ID'])->Fetch() && !CIBlockSection::Delete($id)) throw new RuntimeException('Не удалось удалить пустой раздел #' . $id);
    }
    COption::SetOptionString($optionModule, 'element_links_v1_done', 'Y');
    $DB->Commit();
} catch (Throwable $error) {
    $DB->Rollback();
    throw $error;
}
CIBlock::clearIblockTagCache(5);
CIBlock::clearIblockTagCache(29);
echo "Сохранено. Исходные привязки сохранены в опции usticom_tizzers/element_links_v1_plan.\n";
