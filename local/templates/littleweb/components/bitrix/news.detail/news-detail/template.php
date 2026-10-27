<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
$APPLICATION->AddHeadString('<meta property="og:image" content="https://' .  $_SERVER["SERVER_NAME"] . $arResult["DETAIL_PICTURE"]["SRC"] . '"/>', true);
?>

<section class="section news-detail">
	<div class="container">
		<div class="news-detail__header">
			<div class="news-detail__header-section">

				<? if (!empty($arResult["PROPERTIES"]["TAG_LIST"]["VALUE"])): ?>
					<div class="swiper tag-slider news-detail__tag-slider">
						<ul class="swiper-wrapper tag-list tag-list--slider">
							<? foreach ((array)$arResult["PROPERTIES"]["TAG_LIST"]["VALUE"] as $arTag): ?>
								<li class="swiper-slide tag-list__item">
									<a href="<?= htmlspecialcharsbx($arResult["LIST_PAGE_URL"] . '?tag=' . rawurlencode($arTag)) ?>">#<?= htmlspecialcharsbx($arTag) ?></a>
								</li>
							<? endforeach; ?>
						</ul>
					</div>
				<? endif; ?>
				<h1><?= $arResult["NAME"] ?></h1>
				<button class="main-btn" type="button" data-form-id="2" data-form-theme="<?= htmlspecialcharsbx('Получить консультацию: ' . html_entity_decode(strip_tags($arResult['NAME']), ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?>">Получить консультацию</button>
			</div>
			<div class="news-detail__header-section">
				<img src="<?= $arResult["DETAIL_PICTURE"]["SRC"] ?>" alt="<?= $arResult["NAME"] ?>" width="680" height="340">
			</div>
		</div>
		<div class="news-detail__content content-block">
			<?= $arResult["DETAIL_TEXT"] ?>
		</div>
	</div>
</section>

<?php
$linkedServices = $arResult['PROPERTIES']['LINKED_SERVICES'] ?? null;
$linkedServiceIds = array_values(array_unique(array_filter(
    array_map('intval', (array)($linkedServices['VALUE'] ?? [])),
    static fn($id) => $id > 0
)));
$servicesIblockId = (int)($linkedServices['LINK_IBLOCK_ID'] ?? 0);

if ($linkedServiceIds && $servicesIblockId > 0):
    $linkedServicesFilterName = 'linkedServicesForMaterial' . (int)$arResult['ID'];
    $GLOBALS[$linkedServicesFilterName] = ['ID' => $linkedServiceIds];
    $APPLICATION->IncludeComponent('bitrix:news.list', 'related-slider', [
        'IBLOCK_TYPE' => (string)CIBlock::GetArrayByID($servicesIblockId, 'IBLOCK_TYPE_ID'),
        'IBLOCK_ID' => $servicesIblockId,
        'NEWS_COUNT' => count($linkedServiceIds),
        'SORT_BY1' => 'SORT',
        'SORT_ORDER1' => 'ASC',
        'SORT_BY2' => 'ID',
        'SORT_ORDER2' => 'ASC',
        'FILTER_NAME' => $linkedServicesFilterName,
        'FIELD_CODE' => ['NAME'],
        'DETAIL_URL' => (string)CIBlock::GetArrayByID($servicesIblockId, 'DETAIL_PAGE_URL'),
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => 36000000,
        'CACHE_FILTER' => 'Y',
        'CACHE_GROUPS' => 'Y',
        'CHECK_DATES' => 'Y',
        'DISPLAY_TOP_PAGER' => 'N',
        'DISPLAY_BOTTOM_PAGER' => 'N',
        'SET_TITLE' => 'N',
        'SET_BROWSER_TITLE' => 'N',
        'SET_META_DESCRIPTION' => 'N',
        'SET_META_KEYWORDS' => 'N',
        'SET_LAST_MODIFIED' => 'N',
        'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
        'ADD_SECTIONS_CHAIN' => 'N',
        'SECTION_TITLE' => 'Услуги по теме',
        'EYEBROW_TEXT' => 'услуги',
    ], $component, ['HIDE_ICONS' => 'Y']);
    unset($GLOBALS[$linkedServicesFilterName]);
endif;

$otherMaterialTitles = [
    6 => 'Другие новости',
    7 => 'Другие мероприятия',
    8 => 'Другие публикации',
    28 => 'Другие кейсы',
];
$otherMaterialsFilterName = 'otherMaterialsFor' . (int)$arResult['IBLOCK_ID'] . '_' . (int)$arResult['ID'];
$GLOBALS[$otherMaterialsFilterName] = ['!ID' => (int)$arResult['ID']];
$APPLICATION->IncludeComponent('bitrix:news.list', 'news-slider', [
    'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
    'IBLOCK_ID' => (int)$arResult['IBLOCK_ID'],
    'NEWS_COUNT' => 12,
    'SORT_BY1' => 'ACTIVE_FROM',
    'SORT_ORDER1' => 'DESC',
    'SORT_BY2' => 'ID',
    'SORT_ORDER2' => 'DESC',
    'FILTER_NAME' => $otherMaterialsFilterName,
    'FIELD_CODE' => ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'DATE_ACTIVE_FROM'],
    'PROPERTY_CODE' => ['TAG_LIST'],
    'DETAIL_URL' => $arParams['DETAIL_URL'],
    'LIST_PAGE_URL' => $arParams['IBLOCK_URL'] ?? $arResult['LIST_PAGE_URL'],
    'ACTIVE_DATE_FORMAT' => 'd.m.Y',
    'CACHE_TYPE' => 'A',
    'CACHE_TIME' => 36000000,
    'CACHE_FILTER' => 'Y',
    'CACHE_GROUPS' => 'Y',
    'CHECK_DATES' => 'Y',
    'DISPLAY_TOP_PAGER' => 'N',
    'DISPLAY_BOTTOM_PAGER' => 'N',
    'SET_TITLE' => 'N',
    'SET_BROWSER_TITLE' => 'N',
    'SET_META_DESCRIPTION' => 'N',
    'SET_META_KEYWORDS' => 'N',
    'SET_LAST_MODIFIED' => 'N',
    'SET_STATUS_404' => 'N',
    'SHOW_404' => 'N',
    'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
    'ADD_SECTIONS_CHAIN' => 'N',
    'SECTION_TITLE' => $otherMaterialTitles[(int)$arResult['IBLOCK_ID']] ?? 'Другие события',
    'USE_SWIPER_NAVIGATION' => 'Y',
    'USE_SWIPER_PAGINATION' => 'Y',
    'SHOW_ACTIVE_FROM' => 'Y',
    'SHOW_TAG_LIST' => 'Y',
], $component, ['HIDE_ICONS' => 'Y']);
unset($GLOBALS[$otherMaterialsFilterName]);
?>
