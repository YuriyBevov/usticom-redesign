<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$icons = ['whatsapp' => '/whatsapp|ватсап|вацап/iu', 'tg' => '/telegram|телеграм|t\.me|\btg\b/iu', 'max' => '/\bmax\b|макс/iu', 'youtube' => '/youtube|ютуб|youtu\.be/iu', 'vk' => '/\bvk\b|вконтакте|vk\.com/iu', 'm' => '/\bm\b|tenchat|тенчат/iu'];
$items = [];
foreach ($arResult['ITEMS'] as $item) {
    $url = trim((string)$item['PROPERTIES']['SOCIAL_LINK']['VALUE']);
    if (!preg_match('~^https?://~i', $url)) continue;
    $text = $item['PROPERTIES']['SOCIAL_TEXT']['VALUE'] ?? '';
    if (is_array($text)) $text = $text['TEXT'] ?? '';
    $identity = $item['NAME'] . ' ' . ($item['CODE'] ?? '') . ' ' . $text . ' ' . $url;
    foreach ($icons as $icon => $pattern) {
        if (!preg_match($pattern, $identity)) continue;
        if (!$arParams['ICON_FILTER'] || in_array($icon, $arParams['ICON_FILTER'], true)) {
            $item['ICON'] = $icon;
            $item['URL'] = $url;
            $items[$icon] = $item;
        }
        break;
    }
}
$arResult['ITEMS'] = array_values(array_replace(array_intersect_key($icons, $items), $items));
