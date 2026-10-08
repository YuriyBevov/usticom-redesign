<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$priceSectionIds = array_values(array_unique(array_filter(
	array_map("intval", (array)($arResult["PROPERTIES"]["PRICES"]["VALUE"] ?? [])),
	static function ($id) { return $id > 0; }
)));
$arResult["PRICE_SECTION_IDS"] = [];
if ($priceSectionIds) {
	$sections = CIBlockSection::GetList(
		["SORT" => "ASC", "ID" => "ASC"],
		["IBLOCK_ID" => 22, "ID" => $priceSectionIds, "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y"],
		false,
		["ID", "IBLOCK_ID"]
	);
	while ($section = $sections->Fetch()) {
		$arResult["PRICE_SECTION_IDS"][] = (int)$section["ID"];
	}
	if ($arResult["PRICE_SECTION_IDS"]) {
		CIBlock::registerWithTagCache(22);
	}
}
