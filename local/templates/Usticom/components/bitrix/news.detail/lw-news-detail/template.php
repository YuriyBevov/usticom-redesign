<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$APPLICATION->AddHeadString('<meta property="og:image" content="https://' .  $_SERVER["SERVER_NAME"] . $arResult["DETAIL_PICTURE"]["SRC"] . '"/>', true);

$resImage = CFile::ResizeImageGet(
	$arResult["DETAIL_PICTURE"],
	array("width" => 600, "height" => 400),
	BX_RESIZE_IMAGE_EXACT
);
?>

<? $APPLICATION->IncludeComponent(
	"bitrix:breadcrumb",
	"lw-breadcrumbs",
	array(
		"PATH" => "",
		"SITE_ID" => "s1",
		"START_FROM" => "0",
		"COMPONENT_TEMPLATE" => "lw-breadcrumbs"
	),
	false
); ?>

<section class="lw-section">
	<div class="lw-container-thin">
		<div class="content-wrapper">
			<div class="news-header">
				<div class="news-header__side">
					<h1 class="news-title"><?= $arResult["NAME"] ?></h1>
					<button class="main-header__request lw-main-btn" type="button" data-modal-anchor="request-modal">Получить консультацию</button>
					<? if (!empty($arResult["DISPLAY_PROPERTIES"]["NEWS_AUTHOR"]["VALUE"])): ?>
						<p><span>Автор: </span><?= $arResult["DISPLAY_PROPERTIES"]["NEWS_AUTHOR"]["VALUE"] ?></p>
					<? endif; ?>

					<? if ($arResult["DISPLAY_PROPERTIES"]["NEWS_NOTES"]["VALUE"]): ?>
						<? foreach ($arResult["DISPLAY_PROPERTIES"]["NEWS_NOTES"]["VALUE"] as $arNote): ?>
							<small>*<?= $arNote ?></small>
						<? endforeach; ?>
					<? endif; ?>
				</div>
				<div class="news-header__side">
					<img src="<?= $resImage['src'] ?>" width="600" height="400">
				</div>
			</div>
			<?= $arResult["DETAIL_TEXT"] ?>

			<? if (!empty($arResult["PROPERTIES"]["IMAGE_GALLERY"]["VALUE"])): ?>
				<div class="swiper main-slider base-slider" data-fancybox-gallery="news" style="margin-top: 40px;">
					<div class="swiper-wrapper">
						<?
						foreach ($arResult["PROPERTIES"]["IMAGE_GALLERY"]["VALUE"] as $galItem):
							$img = CFile::GetFileArray($galItem);
						?>
							<div class="swiper-slide">
								<img src="<?= $img["SRC"] ?>" alt="<?= $img["FILE_NAME"] ?>" width="280" height="200" data-fancybox-item>
							</div>
						<? endforeach; ?>
					</div>
					<div class="swiper-pagination"></div>
				</div>
			<? endif; ?>

			<? if ($arResult["IBLOCK_ID"] == 6): ?>
				<p>Больше новостей вы найдете на нашей страничке в <a class="lw-accent-color" href="https://t.me/Usticom" rel="norefferer nofollow noopener">телеграмм</a>, подписывайтесь! </p>
			<? endif; ?>
		</div>
	</div>
</section>

<? if (!empty($arResult['PROPERTIES']['LINKED_SERVICES']['VALUE'])) {
	// debug($arResult["PROPERTIES"]["LINKED_SERVICES"]["VALUE"]);

	$GLOBALS['arLinkedServicesFilter'] = array('ID' => $arResult['PROPERTIES']['LINKED_SERVICES']['VALUE']);

	$APPLICATION->IncludeComponent(
		"bitrix:news.list",
		"lw-news-rowne",
		array(
			"ACTIVE_DATE_FORMAT" => "d.m.Y",
			"ADD_SECTIONS_CHAIN" => "N",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_ADDITIONAL" => "",
			"AJAX_OPTION_HISTORY" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "Y",
			"CACHE_TIME" => "36000000",
			"CACHE_TYPE" => "N",
			"CHECK_DATES" => "Y",
			"DETAIL_URL" => "",
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"DISPLAY_DATE" => "Y",
			"DISPLAY_NAME" => "Y",
			"DISPLAY_PICTURE" => "Y",
			"DISPLAY_PREVIEW_TEXT" => "Y",
			"DISPLAY_TOP_PAGER" => "N",
			"FIELD_CODE" => array(
				0 => "",
				1 => "",
			),
			"FILTER_NAME" => "arLinkedServicesFilter",
			"HIDE_LINK_WHEN_NO_DETAIL" => "N",
			"IBLOCK_ID" => "5",
			"IBLOCK_TYPE" => "usticom_site_content",
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
			"INCLUDE_SUBSECTIONS" => "Y",
			"MESSAGE_404" => "",
			"NEWS_COUNT" => "6",
			"PAGER_BASE_LINK_ENABLE" => "N",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "N",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => ".default",
			"PAGER_TITLE" => "Новости",
			"PARENT_SECTION" => "",
			"PARENT_SECTION_CODE" => "",
			"PREVIEW_TRUNCATE_LEN" => "",
			"PROPERTY_CODE" => array(
				0 => "",
				1 => "",
			),
			"SET_BROWSER_TITLE" => "N",
			"SET_LAST_MODIFIED" => "N",
			"SET_META_DESCRIPTION" => "Y",
			"SET_META_KEYWORDS" => "Y",
			"SET_STATUS_404" => "N",
			"SET_TITLE" => "N",
			"SHOW_404" => "N",
			"SORT_BY1" => "ACTIVE_FROM",
			"SORT_BY2" => "SORT",
			"SORT_ORDER1" => "DESC",
			"SORT_ORDER2" => "ASC",
			"STRICT_SECTION_CHECK" => "N",
			"COMPONENT_TEMPLATE" => "lw-news-rowne"
		),
		$component
	);

	unset($GLOBALS['arLinkedServicesFilter']);
}
?>