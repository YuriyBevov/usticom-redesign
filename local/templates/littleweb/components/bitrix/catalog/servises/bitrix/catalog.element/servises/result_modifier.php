<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$arResult['PRICE_SECTION_LINK'] = getLinkedServicePriceSection((int)$arParams['IBLOCK_ID'], (int)($arResult['IBLOCK_SECTION_ID'] ?? 0));

$priceSectionIds = array_values(array_unique(array_filter(
	array_map("intval", (array)($arResult["PROPERTIES"]["PRICES"]["VALUE"] ?? [])),
	static function ($id) { return $id > 0; }
)));
$arResult["PRICE_SECTION_IDS"] = [];
if ($priceSectionIds) {
	$sections = CIBlockSection::GetList(
		["SORT" => "ASC", "ID" => "ASC"],
		["IBLOCK_ID" => usticomIblock('prices'), "ID" => $priceSectionIds, "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y"],
		false,
		["ID", "IBLOCK_ID"]
	);
	while ($section = $sections->Fetch()) {
		$arResult["PRICE_SECTION_IDS"][] = (int)$section["ID"];
	}
	if ($arResult["PRICE_SECTION_IDS"]) {
		CIBlock::registerWithTagCache(usticomIblock('prices'));
	}
}
