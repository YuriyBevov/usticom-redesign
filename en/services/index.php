<?php
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';
$path = (string)parse_url($APPLICATION->GetCurPage(false), PHP_URL_PATH);
$parts = array_values(array_filter(explode('/', trim(substr($path, strlen('/en/services/')), '/'))));
if (!$parts || $parts === ['hr'] || $parts === ['index.php']) {
    usticomSetPageMeta('services', 'HR Services');
    require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/include/english-services.php';
} elseif (count($parts) === 2) {
    $APPLICATION->IncludeComponent('bitrix:catalog', 'servises', [
        'IBLOCK_TYPE' => 'usticom_site_content_en', 'IBLOCK_ID' => usticomIblock('services'),
        'SEF_MODE' => 'Y', 'SEF_FOLDER' => '/en/services/',
        'SEF_URL_TEMPLATES' => ['sections' => '', 'section' => '#SECTION_CODE#/', 'element' => '#SECTION_CODE#/#ELEMENT_CODE#/'],
        'CACHE_TYPE' => 'A', 'CACHE_TIME' => 36000000, 'CACHE_GROUPS' => 'Y',
        'SET_STATUS_404' => 'Y', 'SHOW_404' => 'Y', 'FILE_404' => '/en/404.php',
        'DETAIL_PROPERTY_CODE' => ['*'],
    ], false);
} else {
    CHTTP::SetStatus('404 Not Found');
    define('ERROR_404', 'Y');
    $APPLICATION->SetTitle('Page Not Found');
    $APPLICATION->SetPageProperty('title', 'Page Not Found — Usticom');
    echo '<section class="section"><div class="container"><h1>Page Not Found</h1><a href="/en/">Home</a></div></section>';
}
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';
