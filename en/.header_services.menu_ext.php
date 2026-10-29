<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$block = usticomIblock('services');
$aMenuLinksExt = [];
global $CACHE_MANAGER;
if ($block && defined('BX_COMP_MANAGED_CACHE')) $CACHE_MANAGER->RegisterTag('iblock_id_'.$block);
if ($block && \Bitrix\Main\Loader::includeModule('iblock')) {
    $sections = CIBlockSection::GetList(['SORT' => 'ASC', 'ID' => 'ASC'], ['IBLOCK_ID' => $block, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y'], false, ['ID', 'IBLOCK_ID', 'NAME', 'CODE']);
    while ($section = $sections->Fetch()) {
        $items = CIBlockElement::GetList(['SORT' => 'ASC', 'ID' => 'ASC'], ['IBLOCK_ID' => $block, 'SECTION_ID' => $section['ID'], 'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'], false, false, ['ID', 'IBLOCK_ID', 'NAME', 'IBLOCK_SECTION_ID', 'DETAIL_PAGE_URL']);
        $items->SetUrlTemplates('/en/services/#SECTION_CODE#/#ELEMENT_CODE#/');
        $children = [];
        while ($item = $items->GetNext()) $children[] = [$item['~NAME'], $item['~DETAIL_PAGE_URL'], [], ['FROM_IBLOCK' => true, 'DEPTH_LEVEL' => 2, 'IS_PARENT' => false, 'SECTION_ID' => $section['ID']], ''];
        if (!$children) continue;
        $aMenuLinksExt[] = [$section['NAME'], '/en/services/'.$section['CODE'].'/', [], ['FROM_IBLOCK' => true, 'DEPTH_LEVEL' => 1, 'IS_PARENT' => true, 'SECTION_ID' => $section['ID']], ''];
        $aMenuLinksExt = array_merge($aMenuLinksExt, $children);
    }
}
$aMenuLinks = array_merge($aMenuLinks ?? [], $aMenuLinksExt);
