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
				<button class="main-btn">Получить консультацию</button>
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

<?
$relatedNewsTags = array_filter((array)($arResult["PROPERTIES"]["TAG_LIST"]["VALUE"] ?? []));

if (!empty($relatedNewsTags)):
	$relatedNewsFilterName = "arRelatedNewsFilter";
	$GLOBALS[$relatedNewsFilterName] = [
		"!ID" => (int)$arResult["ID"],
		"PROPERTY_TAG_LIST_VALUE" => $relatedNewsTags,
	];

	$APPLICATION->IncludeComponent(
		"bitrix:news.list",
		"news-slider",
		[
			"ACTIVE_DATE_FORMAT" => "j F Y",
			"ADD_SECTIONS_CHAIN" => "N",
			"AJAX_MODE" => "N",
			"CACHE_FILTER" => "Y",
			"CACHE_GROUPS" => "Y",
			"CACHE_TIME" => "36000000",
			"CACHE_TYPE" => "A",
			"CHECK_DATES" => "Y",
			"DETAIL_URL" => $arParams["DETAIL_URL"],
			"DISPLAY_BOTTOM_PAGER" => "N",
			"DISPLAY_DATE" => "N",
			"DISPLAY_NAME" => "Y",
			"DISPLAY_PICTURE" => "Y",
			"DISPLAY_PREVIEW_TEXT" => "Y",
			"DISPLAY_TOP_PAGER" => "N",
			"FIELD_CODE" => [
				"NAME",
				"PREVIEW_TEXT",
				"PREVIEW_PICTURE",
				"DETAIL_PICTURE",
				"DATE_ACTIVE_FROM",
			],
			"FILTER_NAME" => $relatedNewsFilterName,
			"HIDE_LINK_WHEN_NO_DETAIL" => "N",
			"IBLOCK_ID" => $arResult["IBLOCK_ID"],
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
			"INCLUDE_SUBSECTIONS" => "Y",
			"NEWS_COUNT" => "6",
			"PAGER_SHOW_ALWAYS" => "N",
			"PARENT_SECTION" => "",
			"PARENT_SECTION_CODE" => "",
			"PROPERTY_CODE" => ["TAG_LIST"],
			"SET_BROWSER_TITLE" => "N",
			"SET_LAST_MODIFIED" => "N",
			"SET_META_DESCRIPTION" => "N",
			"SET_META_KEYWORDS" => "N",
			"SET_STATUS_404" => "N",
			"SET_TITLE" => "N",
			"SHOW_404" => "N",
			"SORT_BY1" => "ACTIVE_FROM",
			"SORT_BY2" => "SORT",
			"SORT_ORDER1" => "DESC",
			"SORT_ORDER2" => "ASC",
			"STRICT_SECTION_CHECK" => "N",
			"EYEBROW_TEXT" => "похожие материалы",
			"USE_SWIPER_NAVIGATION" => "Y",
			"USE_SWIPER_PAGINATION" => "N",
			"SHOW_ACTIVE_FROM" => "Y",
			"SHOW_TAG_LIST" => "Y",
		],
		$component,
		["HIDE_ICONS" => "Y"]
	);

	unset($GLOBALS[$relatedNewsFilterName]);
endif;
?>

linked-services
