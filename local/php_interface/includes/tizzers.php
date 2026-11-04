<?php

/** Explicit element links take precedence; unavailable links fall back to the default section. */
function getHeroTizzersFilter(array $linkedIds = []): array
{
	if (!\Bitrix\Main\Loader::includeModule("iblock")) {
		return [];
	}

	// The outer service component must depend on the teasers' cache tag too.
	CIBlock::registerWithTagCache(29);
	$linkedIds = array_values(array_unique(array_filter(array_map("intval", $linkedIds), static fn($id) => $id > 0)));
	$baseFilter = ["IBLOCK_ID" => 29, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "CHECK_PERMISSIONS" => "Y"];
	if ($linkedIds) {
		$filter = array_merge($baseFilter, ["ID" => $linkedIds]);
		if (CIBlockElement::GetList([], $filter, false, ["nTopCount" => 1], ["ID"])->Fetch()) {
			return $filter;
		}
	}

	$section = CIBlockSection::GetList([], ["IBLOCK_ID" => 29, "ID" => 5158, "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y"], false, ["ID"])->Fetch();
	return $section ? array_merge($baseFilter, ["SECTION_ID" => (int)$section["ID"], "INCLUDE_SUBSECTIONS" => "N"]) : [];
}

function includeHeroTizzers(array $linkedIds = [], $parentComponent = false): void
{
	global $APPLICATION;
	$filter = getHeroTizzersFilter($linkedIds);
	if (!$filter) {
		return;
	}

	$filterName = "heroTizzersFilter_" . md5(serialize($filter));
	$hadPreviousFilter = array_key_exists($filterName, $GLOBALS);
	$previousFilter = $GLOBALS[$filterName] ?? null;
	$GLOBALS[$filterName] = $filter;
	try {
		$APPLICATION->IncludeComponent("bitrix:news.list", "tizzers", [
			"IBLOCK_TYPE" => "usticom_site_content",
			"IBLOCK_ID" => 29,
			"FILTER_NAME" => $filterName,
			"CACHE_FILTER" => "Y",
			"PARENT_SECTION" => (int)($filter["SECTION_ID"] ?? 0),
			"INCLUDE_SUBSECTIONS" => isset($filter["SECTION_ID"]) ? "N" : "Y",
			"STRICT_SECTION_CHECK" => "Y",
			"NEWS_COUNT" => 4,
			"SORT_BY1" => "SORT",
			"SORT_ORDER1" => "ASC",
			"SORT_BY2" => "ID",
			"SORT_ORDER2" => "ASC",
			"FIELD_CODE" => ["NAME", "PREVIEW_TEXT"],
			"PROPERTY_CODE" => [],
			"CHECK_DATES" => "Y",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => 36000000,
			"CACHE_GROUPS" => "Y",
			"SET_TITLE" => "N",
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
			"ADD_SECTIONS_CHAIN" => "N",
		], $parentComponent, ["HIDE_ICONS" => "Y"]);
	} finally {
		if ($hadPreviousFilter) {
			$GLOBALS[$filterName] = $previousFilter;
		} else {
			unset($GLOBALS[$filterName]);
		}
	}
}
