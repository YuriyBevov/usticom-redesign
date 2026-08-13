<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<?if ($arResult["ITEMS"]):?>
<div class="lw-section">
<div class="lw-container">
<blockquote>
<span>&laquo;<?= $arResult["ITEMS"][0]["PREVIEW_TEXT"]?>&raquo;</span>
<cite> &mdash; <?=$arResult["ITEMS"][0]["NAME"]?></cite>
</blockquote>
</div>
</div>
<?endif?>




