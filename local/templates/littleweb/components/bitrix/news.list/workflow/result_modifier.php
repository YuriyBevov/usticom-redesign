<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$source = $arResult["ITEMS"][0]["PROPERTIES"] ?? [];
$title = $source["WORKFLOW_MAIN_TITLE"]["~VALUE"] ?? $source["WORKFLOW_MAIN_TITLE"]["VALUE"] ?? "";
if (is_array($title)) {
	$title = $title["TEXT"] ?? "";
}
$arResult["WORKFLOW_MAIN_TITLE"] = is_scalar($title) ? trim((string)$title) : "";
$parseCompound = require dirname(__DIR__, 4) . "/include/simai-compound.php";
$arResult["WORKFLOW"] = $parseCompound($source["WORKFLOW"]["VALUE"] ?? []);
