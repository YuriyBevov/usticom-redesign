<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

// main.map returns a flat list; restore the hierarchy described by LEVEL.
$tree = [];
$parents = [];
foreach ($arResult['arMap'] ?? [] as $item) {
    $level = (int)$item['LEVEL'];
    $item['CHILDREN'] = [];
    if ($level === 0) {
        $tree[] = $item;
        $parents = [0 => &$tree[array_key_last($tree)]];
    } elseif (isset($parents[$level - 1])) {
        $children = &$parents[$level - 1]['CHILDREN'];
        $children[] = $item;
        $parents[$level] = &$children[array_key_last($children)];
        unset($children);
    }
}
unset($parents);

$arResult['PAGES'] = [SITE_DIR => ['NAME' => 'Главная', 'FULL_PATH' => SITE_DIR]];
$arResult['SERVICES'] = [];
foreach ($tree as $item) {
    if ($item['FULL_PATH'] === SITE_DIR . 'services/') {
        $arResult['SERVICES'] = $item['CHILDREN'];
    }
    if ($item['FULL_PATH'] !== SITE_DIR . 'sitemap/') {
        $arResult['PAGES'][$item['FULL_PATH']] = $item;
    }
}

// The footer also contains public sections absent from the top menu.
$menu = new CMenu('bottom');
$menu->Init(SITE_DIR, true, '/bitrix/components/bitrix/menu/stub.php', true);
$menu->RecalcMenu(false, false);
foreach ($menu->arMenu as $item) {
    if ($item['PERMISSION'] <= 'D' || $item['LINK'] === SITE_DIR . 'sitemap/') {
        continue;
    }
    $arResult['PAGES'][$item['LINK']] = [
        'NAME' => $item['TEXT'],
        'FULL_PATH' => $item['LINK'],
    ];
}

$privacyPath = SITE_DIR . 'privacy-policy/';
if ($APPLICATION->GetFileAccessPermission($privacyPath) > 'D') {
    $arResult['PAGES'][$privacyPath] = [
        'NAME' => 'Политика конфиденциальности',
        'FULL_PATH' => $privacyPath,
    ];
}
