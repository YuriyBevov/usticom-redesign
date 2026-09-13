<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "События, новости, мероприятия. ✅ Аудит, бухгалтерское и юридическое сопровождение от компании Юстиком. Звоните: ☎ +7 (495) 287-92-68.");
$APPLICATION->SetPageProperty("title", "События — Услуги аутсорсинга в Москве");
$APPLICATION->SetTitle("События");
?>

<?
$APPLICATION->IncludeFile(
	SITE_TEMPLATE_PATH . '/include/experiences/hero-section.php',
	array(
		"EYEBROW_PATH" => "/include/experiences/eyebrow.php",
		"TITLE_PATH" => "/include/experiences/title.php",
		"TEXT_PATH" => "/include/experiences/text.php",
		"BUTTONS_PATH" => "/include/experiences/buttons.php",
		"IMG_PATH" => "/include/experiences/img.php",
	),
	array('MODE' => 'html', 'NAME' => 'Внутренний баннер', 'SHOW_BORDER' => false)
);
?>

<?
$innerNewsSliders = [
	[
		"IBLOCK_ID" => "6",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"EYEBROW_TEXT" => "новости",
		"SHOW_ACTIVE_FROM" => "Y",
		"SHOW_ALL_BUTTON_URL" => "/news/",
	],
	[
		"IBLOCK_ID" => "8",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"EYEBROW_TEXT" => "публикации",
		"SHOW_ACTIVE_FROM" => "N",
		"SHOW_ALL_BUTTON_URL" => "/publications/",
	],
	[
		"IBLOCK_ID" => "7",
		"ACTIVE_DATE_FORMAT" => "j F Y",
		"EYEBROW_TEXT" => "мероприятия",
		"SHOW_ACTIVE_FROM" => "Y",
		"SHOW_ALL_BUTTON_URL" => "/events/",
	],
];

$innerNewsSliderParams = [
	"ADD_SECTIONS_CHAIN" => "N",
	"AJAX_MODE" => "N",
	"CACHE_FILTER" => "N",
	"CACHE_GROUPS" => "Y",
	"CACHE_TIME" => "36000000",
	"CACHE_TYPE" => "A",
	"CHECK_DATES" => "Y",
	"DETAIL_URL" => "",
	"DISPLAY_BOTTOM_PAGER" => "N",
	"DISPLAY_DATE" => "N",
	"DISPLAY_NAME" => "Y",
	"DISPLAY_PICTURE" => "Y",
	"DISPLAY_PREVIEW_TEXT" => "Y",
	"DISPLAY_TOP_PAGER" => "N",
	"FIELD_CODE" => ["NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE", "DETAIL_PICTURE", "DATE_ACTIVE_FROM", "DATE_CREATE"],
	"FILTER_NAME" => "",
	"HIDE_LINK_WHEN_NO_DETAIL" => "N",
	"IBLOCK_TYPE" => "usticom_site_content",
	"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
	"INCLUDE_SUBSECTIONS" => "N",
	"NEWS_COUNT" => "6",
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
	"USE_SWIPER_NAVIGATION" => "Y",
	"USE_SWIPER_PAGINATION" => "N",
	"SHOW_TAG_LIST" => "Y",
	"SHOW_ALL_BUTTON" => "Y",
	"SHOW_ALL_BUTTON_TEXT" => "Показать все",
	"SECTION_CLASS" => "section--bg-initial"
];

foreach ($innerNewsSliders as $innerNewsSlider) {
	$APPLICATION->IncludeComponent(
		"bitrix:news.list",
		"news-slider",
		array_merge($innerNewsSliderParams, $innerNewsSlider),
		false,
		["HIDE_ICONS" => "Y"]
	);
}

unset($innerNewsSliders, $innerNewsSliderParams, $innerNewsSlider);
?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
