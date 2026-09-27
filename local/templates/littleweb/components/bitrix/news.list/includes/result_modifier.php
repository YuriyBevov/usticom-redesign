<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$source = $arResult["ITEMS"][0]["PROPERTIES"] ?? [];
$title = $source["INCLUDES_MAIN_TITLE"]["~VALUE"] ?? $source["INCLUDES_MAIN_TITLE"]["VALUE"] ?? "";
if (is_array($title)) {
	$title = $title["TEXT"] ?? "";
}
$arResult["INCLUDES_MAIN_TITLE"] = is_scalar($title) ? trim((string)$title) : "";
$parseCompound = require dirname(__DIR__, 4) . "/include/simai-compound.php";
$arResult["INCLUDES"] = $parseCompound($source["INCLUDES"]["VALUE"] ?? []);
