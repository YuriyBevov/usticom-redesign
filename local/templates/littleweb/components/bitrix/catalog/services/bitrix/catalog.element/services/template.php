<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$this->setFrameMode(true);

$name = (string)($arResult["~NAME"] ?? $arResult["NAME"] ?? "");
$preview = trim((string)($arResult["PROPERTIES"]["SERVICE_PREVIEW_TEXT"]["~VALUE"]["TEXT"] ?? $arResult["PROPERTIES"]["SERVICE_PREVIEW_TEXT"]["~VALUE"]["TEXT"] ?? ""));
$previewType = (string)($arResult["PREVIEW_TEXT_TYPE"] ?? "text");
$picture = !empty($arResult["DETAIL_PICTURE"]) ? $arResult["DETAIL_PICTURE"] : ($arResult["PREVIEW_PICTURE"] ?? null);
$imageBase = SITE_TEMPLATE_PATH . "/components/bitrix/catalog/services/_src/images/detail/";

$detail = trim((string)($arResult["~DETAIL_TEXT"] ?? $arResult["DETAIL_TEXT"] ?? ""));
$detailType = (string)($arResult["DETAIL_TEXT_TYPE"] ?? "text");

$heroEyebrow = (string)($arResult["SECTION"]["NAME"] ?? "Услуги");
?>


<?php
$heroTitle = $arResult["NAME"];
$heroDescription = $arResult["PREVIEW_TEXT"] ?? "";
$heroDescriptionType = $arResult["PREVIEW_TEXT_TYPE"];

$heroPicture = $picture ?: ["SRC" => $imageBase . "hero.png", "WIDTH" => 1086, "HEIGHT" => 1448];
$heroPrimaryLabel = "Получить расчёт стоимости";
$heroSecondaryLabel = "Контакты";
$heroPrimaryUrl = "#service-request";
$heroSecondaryUrl = "/contacts/";

include dirname(__DIR__, 3) . "/partials/hero.php";
?>

<?php if ($preview !== ""): ?>
	<section class="section section--bg-initial">
		<div class="container">
			<div class="section-header">
				<div class="eyebrow">расскажем</div>
				<h2 class="section-title">Коротко об услуге</h2>
			</div>
			<div class="content-block"><?= $previewType === "html" ? $preview : nl2br(htmlspecialcharsbx($preview)) ?></div>
		</div>
	</section>
<?php endif; ?>

<?php
$reasonsFilterName = "serviceReasonsFilter";
$GLOBALS[$reasonsFilterName] = ["ID" => (int)$arResult["ID"]];
$APPLICATION->IncludeComponent("bitrix:news.list", "reasons", [
	"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
	"IBLOCK_ID" => (int)$arResult["IBLOCK_ID"],
	"NEWS_COUNT" => 1,
	"FILTER_NAME" => $reasonsFilterName,
	"CACHE_FILTER" => "Y",
	"CACHE_TYPE" => $arParams["CACHE_TYPE"],
	"CACHE_TIME" => $arParams["CACHE_TIME"],
	"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
	"FIELD_CODE" => ["ID"],
	"PROPERTY_CODE" => ["REASONS", "REASONS_SECTION_TITLE"],
	"CHECK_DATES" => "Y",
	"SET_TITLE" => "N",
	"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
	"ADD_SECTIONS_CHAIN" => "N",
], $component, ["HIDE_ICONS" => "Y"]);
unset($GLOBALS[$reasonsFilterName]);
?>

<?php
$compoundFilterName = "serviceCompoundFilter";
$GLOBALS[$compoundFilterName] = ["ID" => (int)$arResult["ID"]];
foreach (
	[
		"includes" => ["INCLUDES", "INCLUDES_MAIN_TITLE"],
		"workflow" => ["WORKFLOW", "WORKFLOW_MAIN_TITLE"],
		"profit" => ["PROFIT_SECTION_TITLE", "PROFIT_SECTION_IMG", "PROFIT", "PROFIT_TITLE", "UNPROFIT_TITLE", "PROFIT_TEXT", "UNPROFIT_TEXT", "UNPROFIT"],
	] as $templateName => $properties
) {
	$APPLICATION->IncludeComponent("bitrix:news.list", $templateName, [
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => (int)$arResult["IBLOCK_ID"],
		"NEWS_COUNT" => 1,
		"FILTER_NAME" => $compoundFilterName,
		"CACHE_FILTER" => "Y",
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"FIELD_CODE" => ["ID"],
		"PROPERTY_CODE" => $properties,
		"CHECK_DATES" => "Y",
		"SET_TITLE" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
	], $component, ["HIDE_ICONS" => "Y"]);
}
unset($GLOBALS[$compoundFilterName]);
?>

Стоимость тут

<?php $APPLICATION->IncludeComponent("bitrix:news.list", "cases", [
	"IBLOCK_TYPE" => "usticom_site_content",
	"IBLOCK_ID" => 28,
	"NEWS_COUNT" => 6,
	"SORT_BY1" => "ACTIVE_FROM",
	"SORT_ORDER1" => "DESC",
	"FIELD_CODE" => ["NAME", "PREVIEW_TEXT"],
	"PROPERTY_CODE" => ["TAG"],
	"CHECK_DATES" => "Y",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => 36000000,
	"SET_TITLE" => "N",
	"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
	"ADD_SECTIONS_CHAIN" => "N",
	"EYEBROW_TEXT" => "результаты",
	"USE_SWIPER_NAVIGATION" => "Y",
	"USE_SWIPER_PAGINATION" => "Y",
	"SECTION_CLASS" => "section--bg-alternative"
], $component, ["HIDE_ICONS" => "Y"]); ?>

<?php $APPLICATION->IncludeComponent("bitrix:news.list", "features", [
	"IBLOCK_TYPE" => "usticom_site_content",
	"IBLOCK_ID" => 9,
	"NEWS_COUNT" => 5,
	"SORT_BY1" => "SORT",
	"SORT_ORDER1" => "ASC",
	"FIELD_CODE" => ["NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE"],
	"CHECK_DATES" => "Y",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => 36000000,
	"SET_TITLE" => "N",
	"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
	"ADD_SECTIONS_CHAIN" => "N",
], $component, ["HIDE_ICONS" => "Y"]); ?>

<?php $APPLICATION->IncludeComponent("bitrix:news.list", "industries", [
	"IBLOCK_TYPE" => "usticom_site_content",
	"IBLOCK_ID" => 32,
	"NEWS_COUNT" => 100,
	"SORT_BY1" => "SORT",
	"SORT_ORDER1" => "ASC",
	"SORT_BY2" => "ID",
	"SORT_ORDER2" => "ASC",
	"FIELD_CODE" => ["NAME"],
	"PROPERTY_CODE" => [],
	"CHECK_DATES" => "Y",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => 36000000,
	"CACHE_GROUPS" => "Y",
	"SET_TITLE" => "N",
	"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
	"ADD_SECTIONS_CHAIN" => "N",
	"DISPLAY_TOP_PAGER" => "N",
	"DISPLAY_BOTTOM_PAGER" => "N",
], $component, ["HIDE_ICONS" => "Y"]); ?>

<?php $APPLICATION->IncludeComponent("bitrix:news.list", "partnership", [
	"IBLOCK_TYPE" => "usticom_site_content",
	"IBLOCK_ID" => 31,
	"NEWS_COUNT" => 50,
	"SORT_BY1" => "SORT",
	"SORT_ORDER1" => "ASC",
	"SORT_BY2" => "ID",
	"SORT_ORDER2" => "ASC",
	"FIELD_CODE" => ["NAME", "PREVIEW_PICTURE"],
	"CHECK_DATES" => "Y",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => 36000000,
	"CACHE_GROUPS" => "Y",
	"SET_TITLE" => "N",
	"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
	"ADD_SECTIONS_CHAIN" => "N",
	"DISPLAY_TOP_PAGER" => "N",
	"DISPLAY_BOTTOM_PAGER" => "N",
], $component, ["HIDE_ICONS" => "Y"]); ?>


<?php if ($detail !== ""): ?>
	<section class="section">
		<div class=" container">
			<div class="section-header">
				<div class="eyebrow">об услуге</div>
				<h2 class="section-title">Подробно про услугу</h2>
			</div>
			<div class="content-block"><?= $detailType === "html" ? $detail : nl2br(htmlspecialcharsbx($detail)) ?></div>
		</div>
	</section>
<?php endif; ?>

<?php
$faqIds = array_values(array_unique(array_filter(
	array_map("intval", (array)($arResult["PROPERTIES"]["FAQ_LINKED"]["VALUE"] ?? []))
)));
if ($faqIds):
	$faqPreview = $arResult["PROPERTIES"]["FAQ_PREVIEW_TEXT"]["~VALUE"]
		?? $arResult["PROPERTIES"]["FAQ_PREVIEW_TEXT"]["VALUE"]
		?? "";
	$faqDescription = is_array($faqPreview) ? (string)($faqPreview["TEXT"] ?? "") : (string)$faqPreview;
	$faqDescriptionType = is_array($faqPreview) ? (string)($faqPreview["TYPE"] ?? "text") : "text";
	$faqFilterName = "serviceFaqFilter";
	$GLOBALS[$faqFilterName] = ["ID" => $faqIds];
	$APPLICATION->IncludeComponent("bitrix:news.list", "faq", [
		"IBLOCK_TYPE" => "usticom_site_content",
		"IBLOCK_ID" => 26,
		"NEWS_COUNT" => count($faqIds),
		"FILTER_NAME" => $faqFilterName,
		"CACHE_FILTER" => "Y",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ID",
		"SORT_ORDER2" => "ASC",
		"FIELD_CODE" => ["NAME", "PREVIEW_TEXT", "DETAIL_TEXT"],
		"CHECK_DATES" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => 36000000,
		"CACHE_GROUPS" => "Y",
		"SET_TITLE" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"FAQ_DESCRIPTION" => $faqDescription,
		"FAQ_DESCRIPTION_TYPE" => $faqDescriptionType,
	], $component, ["HIDE_ICONS" => "Y"]);
	unset($GLOBALS[$faqFilterName]);
endif;
?>


<?php

$relatedElementIds = array_values(array_unique(array_diff(
	array_map("intval", (array)($arResult["PROPERTIES"]["RELATED_SECTIONS"]["VALUE"] ?? [])),
	[0, (int)$arResult["ID"]]
)));
if ($relatedElementIds):
	$relatedFilterName = "serviceRelatedFilter";
	$GLOBALS[$relatedFilterName] = [
		"ID" => $relatedElementIds,
	];
	$APPLICATION->IncludeComponent("bitrix:news.list", "related-slider", [
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => (int)$arResult["IBLOCK_ID"],
		"NEWS_COUNT" => 12,
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ID",
		"SORT_ORDER2" => "ASC",
		"FIELD_CODE" => ["NAME"],
		"FILTER_NAME" => $relatedFilterName,
		"CACHE_FILTER" => "Y",
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"CHECK_DATES" => "Y",
		"INCLUDE_SUBSECTIONS" => "N",
		"DETAIL_URL" => (string)CIBlock::GetArrayByID((int)$arResult["IBLOCK_ID"], "DETAIL_PAGE_URL"),
		"SET_TITLE" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"SECTION_CLASS" => "section--bg-initial"
	], $component, ["HIDE_ICONS" => "Y"]);
	unset($GLOBALS[$relatedFilterName]);
endif;
?>

<?/*
<section class="section service-detail__final-request" id="service-request">
	<div class="container">
		<div class="section-header">
			<div class="eyebrow">заявка</div>
			<h2 class="section-title">Обсудите вашу задачу с экспертом</h2>
			<p>Расскажите о вашей задаче — мы предложим лучшее решение.</p>
		</div><?php include dirname(__DIR__, 3) . "/partials/request-form.php"; ?>
	</div>
</section>*/ ?>