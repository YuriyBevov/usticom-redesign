<?php

/** Resolve a section relation using active, readable sections and canonical URLs. */
function getLinkedServicePriceSection(int $iblockId, int $sectionId): ?array
{
    if ($sectionId <= 0 || !in_array($iblockId, [5, 22], true) || !\Bitrix\Main\Loader::includeModule('iblock')) {
        return null;
    }

    // A cached price page also depends on the service name, URL and activity.
    // Register both tags even when the relation is empty so adding it clears cache.
    CIBlock::registerWithTagCache(5);
    CIBlock::registerWithTagCache(22);

    $field = $iblockId === 5 ? 'UF_PRICE_LINKED' : 'UF_SERVICE_LINKED';
    $targetIblockId = $iblockId === 5 ? 22 : 5;
    $readable = ['ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'CHECK_PERMISSIONS' => 'Y', 'MIN_PERMISSION' => 'R'];
    $source = CIBlockSection::GetList([], $readable + ['IBLOCK_ID' => $iblockId, 'ID' => $sectionId], false, [
        'ID', 'IBLOCK_ID', 'LEFT_MARGIN', 'RIGHT_MARGIN', 'DEPTH_LEVEL', $field,
    ])->Fetch();
    if (!$source) return null;

    $targetId = (int)($source[$field] ?? 0);
    // Nested service sections and individual services inherit their direction's price link.
    if ($targetId <= 0 && $iblockId === 5 && (int)$source['DEPTH_LEVEL'] > 1) {
        $parents = CIBlockSection::GetList(['DEPTH_LEVEL' => 'DESC'], $readable + [
            'IBLOCK_ID' => $iblockId,
            '<LEFT_MARGIN' => (int)$source['LEFT_MARGIN'],
            '>RIGHT_MARGIN' => (int)$source['RIGHT_MARGIN'],
        ], false, ['ID', 'IBLOCK_ID', $field]);
        while ($parent = $parents->Fetch()) {
            $targetId = (int)($parent[$field] ?? 0);
            if ($targetId > 0) break;
        }
    }
    if ($targetId <= 0) return null;

    $filter = $readable + ['IBLOCK_ID' => $targetIblockId, 'ID' => $targetId];
    // Only first-level price sections have a public page in the current catalog.
    if ($targetIblockId === 22) $filter['DEPTH_LEVEL'] = 1;
    $target = CIBlockSection::GetList([], $filter, false, ['ID', 'IBLOCK_ID', 'NAME', 'CODE', 'SECTION_PAGE_URL']);
    $siteDir = defined('SITE_DIR') ? SITE_DIR : '/';
    $target->SetUrlTemplates('', $targetIblockId === 22
        ? $siteDir . 'prices/#SECTION_CODE#/'
        : (string)CIBlock::GetArrayByID($targetIblockId, 'SECTION_PAGE_URL'));
    $section = $target->GetNext();
    if (!$section) return null;

    $url = (string)($section['~SECTION_PAGE_URL'] ?? $section['SECTION_PAGE_URL'] ?? '');
    if ($url === '' || $url[0] !== '/' || str_starts_with($url, '//')) return null;
    return [
        'ID' => (int)$section['ID'],
        'IBLOCK_ID' => $targetIblockId,
        'NAME' => (string)($section['~NAME'] ?? $section['NAME']),
        'URL' => $url,
    ];
}
