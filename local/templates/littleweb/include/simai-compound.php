<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

return static function ($values): array {
	if (isset($values["SUB_VALUES"])) {
		$values = [$values];
	}

	$items = [];
	foreach ((array)$values as $value) {
		if (!is_array($value) || empty($value["SUB_VALUES"]) || !is_array($value["SUB_VALUES"])) {
			continue;
		}

		$item = ["TITLE" => "", "TEXT" => ""];
		$otherValues = [];
		foreach ($value["SUB_VALUES"] as $subProperty) {
			if (!is_array($subProperty) || ($subProperty["PROPERTY_TYPE"] ?? "") === "F") {
				continue;
			}

			$raw = $subProperty["~VALUE"] ?? $subProperty["VALUE"] ?? null;
			if (is_array($raw)) {
				$raw = $raw["TEXT"] ?? null;
			}
			if (!is_scalar($raw)) {
				continue;
			}
			$text = trim((string)$raw);
			if ($text === "") {
				continue;
			}

			$code = strtoupper((string)($subProperty["CODE"] ?? ""));
			$name = (string)($subProperty["NAME"] ?? "");
			if ($item["TITLE"] === "" && (preg_match('/(^|_)(TITLE|NAME|HEADING)(_|$)/', $code) || preg_match('/заголов|назван/iu', $name))) {
				$item["TITLE"] = $text;
			} elseif ($item["TEXT"] === "" && (preg_match('/(^|_)(TEXT|DESCRIPTION)(_|$)/', $code) || preg_match('/текст|описан/iu', $name))) {
				$item["TEXT"] = $text;
			} else {
				$otherValues[] = $text;
			}
		}

		if ($item["TITLE"] === "" && $item["TEXT"] === "") {
			$item["TITLE"] = (string)array_shift($otherValues);
		}
		if ($item["TEXT"] === "") {
			$item["TEXT"] = (string)array_shift($otherValues);
		}
		if ($item["TITLE"] !== "" || $item["TEXT"] !== "") {
			$items[] = $item;
		}
	}

	return $items;
};
