<?php

function getHeroTizzersSectionId(int $sectionId, int $fallbackSectionId = 0, int $iblockId = 29): int
{
	if (!\Bitrix\Main\Loader::includeModule("iblock")) {
		return 0;
	}

	foreach (array_unique([$sectionId, $fallbackSectionId]) as $candidateSectionId) {
		if ($candidateSectionId <= 0) {
			continue;
		}

		$elements = CIBlockElement::GetList(
			[],
			[
				"IBLOCK_ID" => $iblockId,
				"SECTION_ID" => $candidateSectionId,
				"INCLUDE_SUBSECTIONS" => "N",
				"SECTION_GLOBAL_ACTIVE" => "Y",
				"ACTIVE" => "Y",
				"ACTIVE_DATE" => "Y",
			],
			false,
			["nTopCount" => 1],
			["ID"]
		);

		if ($elements->Fetch()) {
			return $candidateSectionId;
		}
	}

	return 0;
}
