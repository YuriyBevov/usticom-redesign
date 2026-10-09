<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$sections = [];
$current = null;
foreach ($arResult as $item) {
    if ((int)$item['DEPTH_LEVEL'] === 1) {
        $sections[] = $item + ['CHILDREN' => []];
        $current = count($sections) - 1;
    } elseif ($current !== null) {
        $sections[$current]['CHILDREN'][] = $item;
        if ($item['SELECTED']) $sections[$current]['SELECTED'] = true;
    }
}
$arResult = $sections;
