<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$arResult["REASONS"] = [];
$sectionTitleValue = $arResult["ITEMS"][0]["PROPERTIES"]["REASONS_SECTION_TITLE"]["~VALUE"]
	?? $arResult["ITEMS"][0]["PROPERTIES"]["REASONS_SECTION_TITLE"]["VALUE"]
	?? "";
if (is_array($sectionTitleValue)) {
	$sectionTitleValue = $sectionTitleValue["TEXT"] ?? "";
}
$arResult["REASONS_SECTION_TITLE"] = is_scalar($sectionTitleValue) ? trim((string)$sectionTitleValue) : "";
$values = $arResult["ITEMS"][0]["PROPERTIES"]["REASONS"]["VALUE"] ?? [];

if (isset($values["SUB_VALUES"])) {
	$values = [$values];
}

foreach ((array)$values as $value) {
	if (!is_array($value) || empty($value["SUB_VALUES"]) || !is_array($value["SUB_VALUES"])) {
		continue;
	}

	$reason = ["TITLE" => "", "TEXT" => "", "PICTURE" => null];
	$textValues = [];

	foreach ($value["SUB_VALUES"] as $subProperty) {
		if (!is_array($subProperty)) {
			continue;
		}

		$rawValue = $subProperty["~VALUE"] ?? $subProperty["VALUE"] ?? null;
		if (($subProperty["PROPERTY_TYPE"] ?? "") === "F") {
			$fileId = (int)$rawValue;
			if ($fileId > 0) {
				$reason["PICTURE"] = CFile::GetFileArray($fileId) ?: null;
			}
			continue;
		}

		if (is_array($rawValue)) {
			$rawValue = $rawValue["TEXT"] ?? null;
		}
		if (!is_scalar($rawValue)) {
			continue;
		}

		$text = trim((string)$rawValue);
		if ($text === "") {
			continue;
		}

		$code = strtoupper((string)($subProperty["CODE"] ?? ""));
		$name = (string)($subProperty["NAME"] ?? "");
		if ($reason["TITLE"] === "" && (preg_match('/(^|_)(TITLE|NAME|HEADING)(_|$)/', $code) || preg_match('/заголов|назван/iu', $name))) {
			$reason["TITLE"] = $text;
		} elseif ($reason["TEXT"] === "" && (preg_match('/(^|_)(TEXT|DESCRIPTION)(_|$)/', $code) || preg_match('/текст|описан/iu', $name))) {
			$reason["TEXT"] = $text;
		} else {
			$textValues[] = $text;
		}
	}

	if ($reason["TITLE"] === "") {
		$reason["TITLE"] = (string)array_shift($textValues);
	}
	if ($reason["TEXT"] === "") {
		$reason["TEXT"] = (string)array_shift($textValues);
	}
	if ($reason["TITLE"] !== "" || $reason["TEXT"] !== "" || $reason["PICTURE"]) {
		$arResult["REASONS"][] = $reason;
	}
}
