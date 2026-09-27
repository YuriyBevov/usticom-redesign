<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$this->setFrameMode(true);

$APPLICATION->IncludeComponent(
	"bitrix:catalog.section.list",
	"services",
	[
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"SECTION_ID" => 0,
		"SECTION_CODE" => "",
		"TOP_DEPTH" => 1,
		"COUNT_ELEMENTS" => "N",
		"SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
		"EYEBROW_TEXT" => $arParams["EYEBROW_TEXT"] ?? "наши услуги",
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"ADD_SECTIONS_CHAIN" => "N",
	],
	$component,
	["HIDE_ICONS" => "Y"]
);
