<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

global $APPLICATION;

if (defined("ERROR_404") && ERROR_404 === "Y") {
	return "";
}

$currentPath = rtrim((string)parse_url($APPLICATION->GetCurPage(false), PHP_URL_PATH), "/");
$items = [];
foreach ($arResult as $item) {
	$title = trim((string)($item["TITLE"] ?? ""));
	if ($title !== "") {
		$items[] = ["TITLE" => $title, "LINK" => (string)($item["LINK"] ?? "")];
	}
}

if (!$items || rtrim((string)parse_url($items[0]["LINK"], PHP_URL_PATH), "/") !== "") {
	array_unshift($items, ["TITLE" => "Главная", "LINK" => "/"]);
}

$last = end($items);
$lastPath = rtrim((string)parse_url($last["LINK"], PHP_URL_PATH), "/");
if ($last["LINK"] !== "" && $lastPath !== $currentPath) {
	$title = trim(strip_tags((string)$APPLICATION->GetTitle()));
	if ($title !== "" && $title !== $last["TITLE"]) {
		$items[] = ["TITLE" => $title, "LINK" => ""];
	}
}

$html = '<nav class="breadcrumbs" aria-label="Навигационная цепочка" itemscope itemtype="https://schema.org/BreadcrumbList"><div class="container"><ol class="breadcrumbs__list">';
$lastIndex = count($items) - 1;
foreach ($items as $index => $item) {
	$title = htmlspecialcharsbx($item["TITLE"]);
	$html .= '<li class="breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
	if ($index < $lastIndex && $item["LINK"] !== "") {
		$html .= '<a itemprop="item" href="' . htmlspecialcharsbx($item["LINK"]) . '"><span itemprop="name">' . $title . '</span></a>';
	} else {
		$html .= '<span itemprop="name" aria-current="page">' . $title . '</span>';
	}
	$html .= '<meta itemprop="position" content="' . ($index + 1) . '">';
	$html .= '</li>';
}
$html .= '</ol></div></nav>';

return $html;
