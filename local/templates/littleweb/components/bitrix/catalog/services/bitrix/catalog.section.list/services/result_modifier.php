<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$iblockResult = CIBlock::GetByID((int)$arParams["IBLOCK_ID"]);
$arResult["IBLOCK_INFO"] = $iblockResult->Fetch() ?: [];
$arResult["IBLOCK_PICTURE"] = !empty($arResult["IBLOCK_INFO"]["PICTURE"])
	? CFile::GetFileArray((int)$arResult["IBLOCK_INFO"]["PICTURE"])
	: false;

$sectionIds = array_map("intval", array_column($arResult["SECTIONS"], "ID"));
$servicesBySection = array_fill_keys($sectionIds, []);

if ($sectionIds) {
	$elementUrl = (string)CIBlock::GetArrayByID((int)$arParams["IBLOCK_ID"], "DETAIL_PAGE_URL");
	$elements = CIBlockElement::GetList(
		["SORT" => "ASC", "ID" => "ASC"],
		[
			"IBLOCK_ID" => (int)$arParams["IBLOCK_ID"],
			"SECTION_ID" => $sectionIds,
			"INCLUDE_SUBSECTIONS" => "N",
			"ACTIVE" => "Y",
			"ACTIVE_DATE" => "Y",
		],
		false,
		false,
		["ID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "CODE", "NAME", "DETAIL_PAGE_URL"]
	);
	$elements->SetUrlTemplates($elementUrl);

	while ($item = $elements->GetNext()) {
		$sectionId = (int)$item["IBLOCK_SECTION_ID"];
		if (isset($servicesBySection[$sectionId])) {
			$servicesBySection[$sectionId][] = $item;
		}
	}
}

$arResult["SERVICES_BY_SECTION"] = $servicesBySection;
