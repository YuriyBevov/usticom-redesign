<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$arResult["PRICE_CATEGORIES"] = [];
$arResult['SERVICE_SECTION_LINK'] = null;
$subsections = [];
$sections = $arResult["SECTIONS"];
if (($arParams["IS_SECTION"] ?? "N") === "Y") {
	if (empty($arResult["SECTION"]["ID"]) || (int)$arResult["SECTION"]["DEPTH_LEVEL"] !== 1) {
		return;
	}
	$arResult['SERVICE_SECTION_LINK'] = getLinkedServicePriceSection((int)$arParams['IBLOCK_ID'], (int)$arResult['SECTION']['ID']);
	array_unshift($sections, $arResult["SECTION"]);
}
foreach ($sections as $section) {
	$id = (int)$section["ID"];
	if ((int)$section["DEPTH_LEVEL"] === 1) {
		$arResult["PRICE_CATEGORIES"][$id] = [
			"ID" => $id,
			"NAME" => (string)($section["~NAME"] ?? $section["NAME"]),
			"URL" => (string)($section["~SECTION_PAGE_URL"] ?? $section["SECTION_PAGE_URL"] ?? ""),
			"GROUPS" => [],
		];
	} elseif ((int)$section["DEPTH_LEVEL"] === 2) {
		$subsections[$id] = [
			"ID" => $id,
			"PARENT_ID" => (int)$section["IBLOCK_SECTION_ID"],
			"NAME" => (string)($section["~NAME"] ?? $section["NAME"]),
			"ITEMS" => [],
		];
	}
}

if ($subsections) {
	$iblockId = (int)$arParams["IBLOCK_ID"];
	$filter = [
		"IBLOCK_ID" => $iblockId,
		"SECTION_ID" => array_keys($subsections),
		"INCLUDE_SUBSECTIONS" => "N",
		"SECTION_GLOBAL_ACTIVE" => "Y",
		"ACTIVE" => "Y",
		"ACTIVE_DATE" => "Y",
		"CHECK_PERMISSIONS" => "Y",
		"MIN_PERMISSION" => "R",
	];
	$items = [];
	$elements = CIBlockElement::GetList(
		["SORT" => "ASC", "ID" => "ASC"], $filter, false, false,
		["ID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "CODE", "NAME", "DETAIL_PAGE_URL"]
	);
	$elements->SetUrlTemplates($arParams["DETAIL_URL"]);
	while ($item = $elements->GetNext()) {
		// Битрикс сохраняет поля элемента только при наличии контейнера PROPERTIES.
		$item["PROPERTIES"] = [];
		$items[(int)$item["ID"]] = $item;
	}

	if ($items) {
		CIBlockElement::GetPropertyValuesArray($items, $iblockId, ["ID" => array_keys($items)], ["CODE" => ["PRICE", "NOTE"]]);
		$memberships = [];
		$groups = CIBlockElement::GetElementGroups(array_keys($items), true, ["ID", "IBLOCK_ELEMENT_ID"]);
		while ($group = $groups->Fetch()) {
			$sectionId = (int)$group["ID"];
			if (isset($subsections[$sectionId])) {
				$memberships[(int)$group["IBLOCK_ELEMENT_ID"]][$sectionId] = true;
			}
		}

		$propertyText = static function (array $property): string {
			$value = $property["~VALUE"] ?? $property["VALUE"] ?? "";
			if (is_array($value)) {
				$value = $value["TEXT"] ?? reset($value);
			}
			return is_scalar($value)
				? trim(html_entity_decode(strip_tags((string)$value), ENT_QUOTES | ENT_HTML5, "UTF-8"))
				: "";
		};
		foreach ($items as $id => $item) {
			$price = $propertyText($item["PROPERTIES"]["PRICE"] ?? []);
			$numericPrice = str_replace(["\xc2\xa0", " ", ","], ["", "", "."], $price);
			if ($price !== "" && is_numeric($numericPrice)) {
				$decimals = (float)$numericPrice === floor((float)$numericPrice) ? 0 : 2;
				$price = number_format((float)$numericPrice, $decimals, ",", " ") . " ₽";
			}
			$buttons = CIBlock::GetPanelButtons($iblockId, $id, 0, ["SECTION_BUTTONS" => false, "SESSID" => false]);
			$priceItem = [
				"ID" => $id,
				"NAME" => (string)($item["~NAME"] ?? $item["NAME"]),
				"URL" => (string)($item["~DETAIL_PAGE_URL"] ?? $item["DETAIL_PAGE_URL"]),
				"PRICE" => $price !== "" ? $price : "индивидуально",
				"HAS_PRICE" => $price !== "",
				"NOTE" => $propertyText($item["PROPERTIES"]["NOTE"] ?? []),
				"EDIT_LINK" => $buttons["edit"]["edit_element"]["ACTION_URL"] ?? "",
				"DELETE_LINK" => $buttons["edit"]["delete_element"]["ACTION_URL"] ?? "",
			];
			foreach (array_keys($memberships[$id] ?? []) as $sectionId) {
				$subsections[$sectionId]["ITEMS"][] = $priceItem;
			}
		}
	}
}

foreach ($subsections as $group) {
	if ($group["ITEMS"] && isset($arResult["PRICE_CATEGORIES"][$group["PARENT_ID"]])) {
		$arResult["PRICE_CATEGORIES"][$group["PARENT_ID"]]["GROUPS"][] = $group;
	}
}
$arResult["PRICE_CATEGORIES"] = array_values(array_filter(
	$arResult["PRICE_CATEGORIES"], static fn(array $category): bool => !empty($category["GROUPS"])
));
