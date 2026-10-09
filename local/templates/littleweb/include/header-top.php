<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="header-top">
    <div class="container header-top__inner">
        <?php $APPLICATION->IncludeComponent('bitrix:menu', 'header-top', [
            'ROOT_MENU_TYPE' => 'header_top',
            'MAX_LEVEL' => 1,
            'USE_EXT' => 'N',
            'MENU_CACHE_TYPE' => 'A',
            'MENU_CACHE_TIME' => 3600,
            'MENU_CACHE_USE_GROUPS' => 'Y',
            'MENU_CACHE_GET_VARS' => [],
            'ALLOW_MULTI_SELECT' => 'N',
        ], false, ['HIDE_ICONS' => 'N']); ?>
        <div class="header-top__contacts">
            <address class="header-top__address"><?php $APPLICATION->IncludeFile(SITE_DIR . 'include/address.php', [], ['MODE' => 'html', 'NAME' => 'Адрес', 'SHOW_BORDER' => true]); ?></address>
            <div class="header-top__mail"><?php $APPLICATION->IncludeFile(SITE_DIR . 'include/mail.php', [], ['MODE' => 'html', 'NAME' => 'Почта', 'SHOW_BORDER' => true]); ?></div>
            <div class="header-top__phone"><?php $APPLICATION->IncludeFile(SITE_DIR . 'include/phone.php', [], ['MODE' => 'html', 'NAME' => 'Телефон', 'SHOW_BORDER' => true]); ?></div>
            <?php $APPLICATION->IncludeComponent('bitrix:news.list', 'social-list', [
                'IBLOCK_TYPE' => 'usticom_site_content',
                'IBLOCK_ID' => 15,
                'NEWS_COUNT' => 10,
                'SORT_BY1' => 'ACTIVE_FROM',
                'SORT_ORDER1' => 'DESC',
                'SORT_BY2' => 'SORT',
                'SORT_ORDER2' => 'ASC',
                'PROPERTY_CODE' => ['SOCIAL_LINK', 'SOCIAL_TEXT', 'SOCIAL_ICON'],
                'CHECK_DATES' => 'Y',
                'CACHE_TYPE' => 'A',
                'CACHE_TIME' => 36000000,
                'CACHE_GROUPS' => 'Y',
                'SET_TITLE' => 'N',
                'SET_BROWSER_TITLE' => 'N',
                'SET_META_KEYWORDS' => 'N',
                'SET_META_DESCRIPTION' => 'N',
                'ADD_SECTIONS_CHAIN' => 'N',
                'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
                'DISPLAY_TOP_PAGER' => 'N',
                'DISPLAY_BOTTOM_PAGER' => 'N',
            ], false, ['HIDE_ICONS' => 'Y']); ?>
        </div>
    </div>
</div>