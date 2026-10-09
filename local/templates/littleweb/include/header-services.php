<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$APPLICATION->IncludeComponent('bitrix:menu', 'header-services', [
    'ROOT_MENU_TYPE' => 'header_services',
    'CHILD_MENU_TYPE' => 'header_services',
    'MAX_LEVEL' => 2,
    'USE_EXT' => 'Y',
    'ALLOW_MULTI_SELECT' => 'N',
    'MENU_CACHE_TYPE' => 'A',
    'MENU_CACHE_TIME' => 3600,
    'MENU_CACHE_USE_GROUPS' => 'Y',
    'MENU_CACHE_GET_VARS' => [],
    'VIEW_MODE' => $headerMenuView ?? 'desktop',
], false, ['HIDE_ICONS' => 'Y']);
