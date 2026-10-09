<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$APPLICATION->IncludeComponent('bitrix:news.list', 'header-socials', [
    'IBLOCK_TYPE' => 'usticom_site_content', 'IBLOCK_ID' => 15,
    'NEWS_COUNT' => 100, 'SORT_BY1' => 'SORT', 'SORT_ORDER1' => 'ASC',
    'SORT_BY2' => 'ID', 'SORT_ORDER2' => 'ASC',
    'PROPERTY_CODE' => ['SOCIAL_LINK', 'SOCIAL_TEXT'],
    'FIELD_CODE' => ['CODE'],
    'CHECK_DATES' => 'Y', 'CACHE_TYPE' => 'A', 'CACHE_TIME' => 3600, 'CACHE_GROUPS' => 'Y',
    'SET_TITLE' => 'N', 'SET_BROWSER_TITLE' => 'N', 'SET_META_KEYWORDS' => 'N',
    'SET_META_DESCRIPTION' => 'N', 'ADD_SECTIONS_CHAIN' => 'N', 'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
    'DISPLAY_TOP_PAGER' => 'N', 'DISPLAY_BOTTOM_PAGER' => 'N',
    'ICON_FILTER' => $headerSocialFilter ?? [],
], false, ['HIDE_ICONS' => 'Y']);
