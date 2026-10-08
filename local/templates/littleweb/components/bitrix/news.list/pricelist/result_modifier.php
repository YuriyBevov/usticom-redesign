<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$arResult["PRICES"] = [];
$pricePropertyCode = (string)($arParams["PRICE_PROPERTY_CODE"] ?? "PRICE");
foreach ($arResult["ITEMS"] as $item) {
	$value = $item["PROPERTIES"][$pricePropertyCode]["~VALUE"] ?? $item["PROPERTIES"][$pricePropertyCode]["VALUE"] ?? "";
	if (is_array($value)) {
		$value = $value["TEXT"] ?? reset($value);
	}
	$price = is_scalar($value) ? trim((string)$value) : "";
	$numericPrice = str_replace(["\xc2\xa0", " ", ","], ["", "", "."], $price);
	if ($price !== "" && is_numeric($numericPrice)) {
		$decimals = (float)$numericPrice === floor((float)$numericPrice) ? 0 : 2;
		$price = number_format((float)$numericPrice, $decimals, ",", " ");
	}
	$note = $item["PROPERTIES"]["NOTE"]["~VALUE"] ?? $item["PROPERTIES"]["NOTE"]["VALUE"] ?? "";
	if (is_array($note)) {
		$note = $note["TEXT"] ?? reset($note);
	}
	$note = is_scalar($note)
		? trim(strip_tags(html_entity_decode((string)$note, ENT_QUOTES | ENT_HTML5, "UTF-8")))
		: "";
	$arResult["PRICES"][] = [
		"ID" => $item["ID"],
		"IBLOCK_ID" => $item["IBLOCK_ID"],
		"EDIT_LINK" => $item["EDIT_LINK"] ?? "",
		"DELETE_LINK" => $item["DELETE_LINK"] ?? "",
		"TITLE" => (string)($item["~NAME"] ?? $item["NAME"] ?? ""),
		"PRICE" => $price,
		"NOTE" => $note,
	];
}
