<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$arResult['PRICE_SECTION_LINK'] = getLinkedServicePriceSection((int)$arParams['IBLOCK_ID'], (int)$arResult['ID']);

$preview = $arResult["~UF_SERVICE_SECTION_PREVIEW_TEXT"] ?? $arResult["UF_SERVICE_SECTION_PREVIEW_TEXT"] ?? "";
$arResult["HERO_PREVIEW_TEXT"] = trim(is_array($preview) ? (string)($preview["TEXT"] ?? "") : (string)$preview);
$arResult["HERO_PREVIEW_TEXT_TYPE"] = is_array($preview) ? strtolower((string)($preview["TYPE"] ?? "html")) : "html";

$faqText = $arResult["~UF_SERVICE_SECTION_FAQ_TEXT"] ?? $arResult["UF_SERVICE_SECTION_FAQ_TEXT"] ?? "";
$arResult["FAQ_DESCRIPTION"] = trim(is_array($faqText) ? (string)($faqText["TEXT"] ?? "") : (string)$faqText);
$arResult["FAQ_DESCRIPTION_TYPE"] = is_array($faqText) ? strtolower((string)($faqText["TYPE"] ?? "html")) : "html";

$picture = $arResult["PICTURE"] ?? false;
$arResult["HERO_PICTURE"] = is_array($picture) ? $picture : ($picture ? CFile::GetFileArray((int)$picture) : false);

foreach ($arResult["ITEMS"] as &$item) {
	$price = $item["PROPERTIES"]["SERVICE_PRICE_FROM"]["~VALUE"] ?? $item["PROPERTIES"]["SERVICE_PRICE_FROM"]["VALUE"] ?? "";
	if (is_array($price)) {
		$price = reset($price);
	}
	$price = trim((string)$price);
	$item["HAS_PRICE"] = $price !== "";
	$item["PRICE_TEXT"] = "Цена по запросу";

	if ($item["HAS_PRICE"]) {
		$numericPrice = str_replace(["\xc2\xa0", " ", ","], ["", "", "."], $price);
		if (is_numeric($numericPrice)) {
			$decimals = (float)$numericPrice === floor((float)$numericPrice) ? 0 : 2;
			$price = number_format((float)$numericPrice, $decimals, ",", " ");
		}
		$item["PRICE_TEXT"] = "от " . $price . " ₽";
	}
}
unset($item);

$linkedDocsIds = array_values(array_unique(array_filter(
	array_map("intval", (array)($arResult["UF_LINKED_DOCS"] ?? [])),
	static function ($id) { return $id > 0; }
)));
$arResult["DOCS_IBLOCK_ID"] = 0;
$arResult["DOCS_FILTER"] = [];
$arResult["DOCS_FILE_PROPERTY_CODE"] = "";

if ($linkedDocsIds) {
	$docsField = CUserTypeEntity::GetList([], [
		"ENTITY_ID" => "IBLOCK_" . (int)$arParams["IBLOCK_ID"] . "_SECTION",
		"FIELD_NAME" => "UF_LINKED_DOCS",
	])->Fetch();
	$docsIblockId = (int)($docsField["SETTINGS"]["IBLOCK_ID"] ?? 0);
	$docsUserType = (string)($docsField["USER_TYPE_ID"] ?? "");
	if ($docsIblockId > 0 && in_array($docsUserType, ["iblock_element", "iblock_section"], true)) {
		$filePropertyCodes = [];
		$fileProperties = CIBlockProperty::GetList(["SORT" => "ASC", "ID" => "ASC"], [
			"IBLOCK_ID" => $docsIblockId,
			"ACTIVE" => "Y",
			"PROPERTY_TYPE" => "F",
		]);
		while ($fileProperty = $fileProperties->Fetch()) {
			$filePropertyCodes[] = (string)($fileProperty["CODE"] ?: $fileProperty["ID"]);
		}
		$filePropertyCode = in_array("FILE", $filePropertyCodes, true)
			? "FILE"
			: (count($filePropertyCodes) === 1 ? $filePropertyCodes[0] : "");
		$arResult["DOCS_FILE_PROPERTY_CODE"] = $filePropertyCode;
		$arResult["DOCS_IBLOCK_ID"] = $docsIblockId;
		$arResult["DOCS_FILTER"] = $docsUserType === "iblock_element"
			? ["ID" => $linkedDocsIds]
			: ["SECTION_ID" => $linkedDocsIds, "INCLUDE_SUBSECTIONS" => "Y", "SECTION_GLOBAL_ACTIVE" => "Y"];
		CIBlock::registerWithTagCache($docsIblockId);
	}
}

$faqSectionIds = array_values(array_unique(array_filter(
	array_map("intval", (array)($arResult["UF_LINKED_FAQ"] ?? [])),
	static function ($id) { return $id > 0; }
)));
$arResult["FAQ_GROUPS"] = [];

if ($faqSectionIds) {
	$sections = CIBlockSection::GetList(
		["SORT" => "ASC", "ID" => "ASC"],
		["ID" => $faqSectionIds, "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y"],
		false,
		["ID", "IBLOCK_ID"]
	);
	while ($section = $sections->Fetch()) {
		$iblockId = (int)$section["IBLOCK_ID"];
		if (!isset($arResult["FAQ_GROUPS"][$iblockId])) {
			$arResult["FAQ_GROUPS"][$iblockId] = [
				"IBLOCK_ID" => $iblockId,
				"SECTION_IDS" => [],
			];
			CIBlock::registerWithTagCache($iblockId);
		}
		$arResult["FAQ_GROUPS"][$iblockId]["SECTION_IDS"][] = (int)$section["ID"];
	}
}
