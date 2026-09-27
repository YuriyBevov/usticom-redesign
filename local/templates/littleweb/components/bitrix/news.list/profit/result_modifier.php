<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$properties = $arResult["ITEMS"][0]["PROPERTIES"] ?? [];
$readText = static function (string $code) use ($properties): string {
	$value = $properties[$code]["~VALUE"] ?? $properties[$code]["VALUE"] ?? "";
	if (is_array($value)) {
		$value = $value["TEXT"] ?? "";
	}
	return is_scalar($value) ? trim((string)$value) : "";
};

$arResult["PROFIT_SECTION_TITLE"] = $readText("PROFIT_SECTION_TITLE");
$arResult["PROFIT_TITLE"] = $readText("PROFIT_TITLE");
$arResult["UNPROFIT_TITLE"] = $readText("UNPROFIT_TITLE");

$imageId = (int)($properties["PROFIT_SECTION_IMG"]["VALUE"] ?? 0);
$arResult["PROFIT_SECTION_IMG"] = $imageId > 0 ? (CFile::GetFileArray($imageId) ?: null) : null;

$parseCompound = require dirname(__DIR__, 4) . "/include/simai-compound.php";
$arResult["PROFIT"] = $parseCompound($properties["PROFIT"]["VALUE"] ?? []);
$arResult["UNPROFIT"] = $parseCompound($properties["UNPROFIT"]["VALUE"] ?? []);
