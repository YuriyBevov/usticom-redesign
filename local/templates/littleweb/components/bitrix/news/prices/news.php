<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$isPriceSection = $isPriceSection ?? false;
$this->setFrameMode(true);
includeComponentAssets("news/prices");
$APPLICATION->SetPageProperty("HIDE_CALLBACK", "Y");

$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "prices", [
	"IBLOCK_ID" => (int)$arParams["IBLOCK_ID"],
	"SECTION_ID" => $isPriceSection ? (int)($arResult["VARIABLES"]["SECTION_ID"] ?? 0) : 0,
	"SECTION_CODE" => $isPriceSection ? (string)($arResult["VARIABLES"]["SECTION_CODE"] ?? "") : "",
	"IS_SECTION" => $isPriceSection ? "Y" : "N",
	"GROUPS_LIMIT" => $isPriceSection ? 0 : 4,
	"TOP_DEPTH" => 2,
	"COUNT_ELEMENTS" => "N",
	"SECTION_FIELDS" => ["ID", "NAME", "CODE", "IBLOCK_SECTION_ID", "DEPTH_LEVEL", "DESCRIPTION", "DESCRIPTION_TYPE"],
	"SECTION_USER_FIELDS" => [],
	"DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["detail"],
	"IBLOCK_URL" => $arResult["FOLDER"],
	"SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
	"CACHE_TYPE" => $arParams["CACHE_TYPE"],
	"CACHE_TIME" => $arParams["CACHE_TIME"],
	"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
	"ADD_SECTIONS_CHAIN" => $isPriceSection ? "Y" : "N",
], $component, ["HIDE_ICONS" => "Y"]);
?>

<section class="callback section prices-request" aria-labelledby="prices-request-title">
	<div class="container">
		<div class="prices-request__inner">
			<div class="section-header">
				<div class="eyebrow">заявка</div>
				<h2 class="section-title" id="prices-request-title">Получите консультацию эксперта</h2>
				<p>Расскажите о вашей задаче — мы предложим лучшее решение.</p>
			</div>
			<?php $APPLICATION->IncludeComponent("bitrix:form.result.new", "service-request", [
				"WEB_FORM_ID" => (int)($arParams["REQUEST_FORM_ID"] ?? 1),
				"CACHE_TYPE" => "N",
				"CACHE_TIME" => 0,
				"AJAX_MODE" => "N",
				"USE_EXTENDED_ERRORS" => "Y",
				"SEF_MODE" => "N",
				"COMPACT_LAYOUT" => "Y",
			], $component, ["HIDE_ICONS" => "Y"]); ?>
		</div>
	</div>
</section>
