<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
?>
<section class="section"><div class="container">
    <div class="section-header"><div class="eyebrow">our services</div><h1 class="section-title"><?php $APPLICATION->ShowTitle(false); ?></h1></div>
    <div class="content-block">
        <?php
        $block = usticomIblock('services');
        if ($block && \Bitrix\Main\Loader::includeModule('iblock')) {
            $items = CIBlockElement::GetList(['SORT' => 'ASC', 'ID' => 'ASC'], ['IBLOCK_ID' => $block, 'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'], false, false, ['ID', 'IBLOCK_ID', 'NAME', 'IBLOCK_SECTION_ID', 'DETAIL_PAGE_URL', 'PREVIEW_TEXT', 'PREVIEW_TEXT_TYPE']);
            $items->SetUrlTemplates('/en/services/#SECTION_CODE#/#ELEMENT_CODE#/');
            while ($item = $items->GetNext()) {
                echo '<article><h2><a href="'.htmlspecialcharsbx($item['~DETAIL_PAGE_URL']).'">'.htmlspecialcharsbx($item['~NAME']).'</a></h2>';
                echo $item['PREVIEW_TEXT_TYPE'] === 'html' ? $item['~PREVIEW_TEXT'] : '<p>'.htmlspecialcharsbx($item['~PREVIEW_TEXT']).'</p>';
                echo '</article>';
            }
        }
        ?>
    </div>
</div></section>
