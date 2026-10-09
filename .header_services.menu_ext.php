<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (!\Bitrix\Main\Loader::includeModule('iblock')) return;

// The bitrix:menu component caches these links, including the user's permissions.
global $CACHE_MANAGER;
if (defined('BX_COMP_MANAGED_CACHE')) $CACHE_MANAGER->RegisterTag('iblock_id_5');

$sections = CIBlockSection::GetList(
    ['SORT' => 'ASC', 'ID' => 'ASC'],
    ['IBLOCK_ID' => 5, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'DEPTH_LEVEL' => 1, 'CHECK_PERMISSIONS' => 'Y', 'MIN_PERMISSION' => 'R'],
    false,
    ['ID', 'IBLOCK_ID', 'NAME', 'CODE', 'SECTION_PAGE_URL', 'UF_MENU_TITLE']
);
$sections->SetUrlTemplates('', (string)CIBlock::GetArrayByID(5, 'SECTION_PAGE_URL'));

while ($section = $sections->GetNext()) {
    $menuTitle = trim((string)($section['~UF_MENU_TITLE'] ?? $section['UF_MENU_TITLE'] ?? ''));
    $children = [];
    $elements = CIBlockElement::GetList(
        ['SORT' => 'ASC', 'NAME' => 'ASC', 'ID' => 'ASC'],
        ['IBLOCK_ID' => 5, 'SECTION_ID' => (int)$section['ID'], 'INCLUDE_SUBSECTIONS' => 'Y', 'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y', 'CHECK_PERMISSIONS' => 'Y', 'MIN_PERMISSION' => 'R'],
        false,
        false,
        ['ID', 'IBLOCK_ID', 'IBLOCK_SECTION_ID', 'NAME', 'CODE', 'DETAIL_PAGE_URL']
    );
    $elements->SetUrlTemplates((string)CIBlock::GetArrayByID(5, 'DETAIL_PAGE_URL'));
    // GetList can return an element more than once when it belongs to several sections.
    while ($element = $elements->GetNext()) {
        $children[$element['ID']] = [
            $element['~NAME'], $element['~DETAIL_PAGE_URL'], [],
            ['FROM_IBLOCK' => true, 'DEPTH_LEVEL' => 2, 'IS_PARENT' => false], ''
        ];
    }
    $aMenuLinks[] = [
        $menuTitle !== '' ? $menuTitle : $section['~NAME'], $section['~SECTION_PAGE_URL'], [],
        ['FROM_IBLOCK' => true, 'DEPTH_LEVEL' => 1, 'IS_PARENT' => !empty($children), 'SECTION_ID' => (int)$section['ID']], ''
    ];
    $aMenuLinks = array_merge($aMenuLinks, array_values($children));
}
