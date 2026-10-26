<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

$APPLICATION->SetTitle('Карта сайта');
$APPLICATION->SetPageProperty('title', 'Карта сайта компании Юстиком');
$APPLICATION->SetPageProperty('description', 'Все разделы сайта компании Юстиком: услуги, цены, кейсы, новости, публикации и контакты.');
?>

<section class="section sitemap-page" aria-labelledby="sitemap-title">
    <div class="container">
        <div class="section-header">
            <h1 class="section-title" id="sitemap-title"><?php $APPLICATION->ShowTitle(false); ?></h1>
            <p class="sitemap-page__intro">Все разделы и услуги компании — на одной странице.</p>
        </div>
        <?php $APPLICATION->IncludeComponent('bitrix:main.map', 'sitemap', [
            'LEVEL' => 3,
            'COL_NUM' => 3,
            'SHOW_DESCRIPTION' => 'N',
            'SET_TITLE' => 'N',
            'CACHE_TYPE' => 'A',
            'CACHE_TIME' => 3600,
        ], false, ['HIDE_ICONS' => 'Y']); ?>
    </div>
</section>

<?php require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
