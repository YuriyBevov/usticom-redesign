<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$this->setFrameMode(true);

$APPLICATION->IncludeComponent(
	"bitrix:catalog.element",
	"services",
	[
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"] ?? 0,
		"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"] ?? "",
		"SECTION_ID" => $arParams["CURRENT_SECTION_ID"] ?? $arResult["VARIABLES"]["SECTION_ID"] ?? 0,
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"] ?? "",
		"SECTION_URL" => (string)CIBlock::GetArrayByID((int)$arParams["IBLOCK_ID"], "SECTION_PAGE_URL"),
		"DETAIL_URL" => (string)CIBlock::GetArrayByID((int)$arParams["IBLOCK_ID"], "DETAIL_PAGE_URL"),
		"PROPERTY_CODE" => array_values(array_unique(array_merge((array)($arParams["DETAIL_PROPERTY_CODE"] ?? []), ["RELATED_SECTIONS", "FAQ_PREVIEW_TEXT", "FAQ_LINKED"]))),
		"SET_TITLE" => "Y",
		"SET_BROWSER_TITLE" => "Y",
		"SET_META_DESCRIPTION" => "Y",
		"SET_STATUS_404" => "Y",
		"SHOW_404" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"ADD_ELEMENT_CHAIN" => "Y",
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
	],
	$component,
	["HIDE_ICONS" => "Y"]
);
