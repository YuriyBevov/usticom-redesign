<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$this->setFrameMode(true);

$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	"services",
	[
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"SECTION_ID" => $arParams["CURRENT_SECTION_ID"] ?? $arResult["VARIABLES"]["SECTION_ID"] ?? 0,
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"] ?? "",
		"SECTION_URL" => (string)CIBlock::GetArrayByID((int)$arParams["IBLOCK_ID"], "SECTION_PAGE_URL"),
		"DETAIL_URL" => (string)CIBlock::GetArrayByID((int)$arParams["IBLOCK_ID"], "DETAIL_PAGE_URL"),
		"INCLUDE_SUBSECTIONS" => "N",
		"PAGE_ELEMENT_COUNT" => 1000,
		"ELEMENT_SORT_FIELD" => "SORT",
		"ELEMENT_SORT_ORDER" => "ASC",
		"ELEMENT_SORT_FIELD2" => "ID",
		"ELEMENT_SORT_ORDER2" => "ASC",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"CHECK_DATES" => "Y",
		"SET_TITLE" => "Y",
		"SET_BROWSER_TITLE" => "Y",
		"SET_META_DESCRIPTION" => "Y",
		"SET_STATUS_404" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"EYEBROW_TEXT" => $arParams["EYEBROW_TEXT"] ?? "наши услуги",
	],
	$component,
	["HIDE_ICONS" => "Y"]
);
