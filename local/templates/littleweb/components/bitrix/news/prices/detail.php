<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$this->setFrameMode(true);
// До отдельной разработки детальной страницы используется стандартный вывод Битрикс.
?>
<section class="section"><div class="container">
	<?php $APPLICATION->IncludeComponent("bitrix:news.detail", ".default", [
		"IBLOCK_ID" => (int)$arParams["IBLOCK_ID"],
		"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"] ?? 0,
		"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"] ?? "",
		"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
		"DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["detail"],
		"IBLOCK_URL" => $arResult["FOLDER"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"CHECK_DATES" => "Y",
		"SET_TITLE" => $arParams["SET_TITLE"],
		"ADD_ELEMENT_CHAIN" => "Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"SHOW_404" => $arParams["SHOW_404"],
		"DISPLAY_DATE" => "N",
		"DISPLAY_PICTURE" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
	], $component, ["HIDE_ICONS" => "Y"]); ?>
</div></section>
