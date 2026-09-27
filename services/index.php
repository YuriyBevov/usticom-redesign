<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>

<?if(CSite::InDir('/services/index.php')):?>
	<?
		$APPLICATION->SetPageProperty("description", "Услуги компании Юстиком. ✅ Аудит, бухгалтерское и юридическое сопровождение от компании Юстиком. Звоните: ☎ +7 (495) 287-92-68.");
		$APPLICATION->SetPageProperty("title", "Наши услуги — Услуги аутсорсинга в Москве");
		$APPLICATION->IncludeComponent(
	"bitrix:catalog",
	"services",
	[
		"IBLOCK_TYPE" => "usticom_site_content",
		"IBLOCK_ID" => "5",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "/services/",
		"SEF_URL_TEMPLATES" => [
			"sections" => "",
			"section" => "#SECTION_CODE#/",
			"element" => "#SECTION_CODE#/#ELEMENT_CODE#/",
		],
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"EYEBROW_TEXT" => "наши услуги",
		"SET_TITLE" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
	],
	false
);
	?>
<?else:?>
	<?
	$catalogTemplate = "lw-catalog";
	$serviceSectionId = 0;
	$currentPath = parse_url($APPLICATION->GetCurPage(false), PHP_URL_PATH);
	$pathParts = array_values(array_filter(explode("/", trim((string)$currentPath, "/"))));
	$sectionCode = end($pathParts);
	if ($sectionCode && \Bitrix\Main\Loader::includeModule("iblock")) {
		$sections = CIBlockSection::GetList(
			[],
			["IBLOCK_ID" => 5, "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y", "=CODE" => $sectionCode],
			false,
			["ID", "CODE", "IBLOCK_ID", "IBLOCK_SECTION_ID", "SECTION_PAGE_URL"]
		);
		$sections->SetUrlTemplates("", (string)CIBlock::GetArrayByID(5, "SECTION_PAGE_URL"));
		while ($section = $sections->GetNext()) {
			if (rtrim((string)parse_url($section["~SECTION_PAGE_URL"], PHP_URL_PATH), "/") === rtrim((string)$currentPath, "/")) {
				$catalogTemplate = "services";
				$serviceSectionId = (int)$section["ID"];
				break;
			}
		}
		if ($catalogTemplate === "lw-catalog") {
			$elements = CIBlockElement::GetList(
				[],
				["IBLOCK_ID" => 5, "ACTIVE" => "Y", "=CODE" => $sectionCode],
				false,
				false,
				["ID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "DETAIL_PAGE_URL"]
			);
			$elements->SetUrlTemplates((string)CIBlock::GetArrayByID(5, "DETAIL_PAGE_URL"));
			while ($element = $elements->GetNext()) {
				if (rtrim((string)parse_url($element["~DETAIL_PAGE_URL"], PHP_URL_PATH), "/") === rtrim((string)$currentPath, "/")) {
					$catalogTemplate = "services";
					$serviceSectionId = (int)$element["IBLOCK_SECTION_ID"];
					break;
				}
			}
		}
	}
	if ($catalogTemplate === "lw-catalog") {
		$APPLICATION->SetPageProperty("description", "Услуги компании Юстиком. ✅ Аудит, бухгалтерское и юридическое сопровождение от компании Юстиком. Звоните: ☎ +7 (495) 287-92-68.");
		$APPLICATION->SetPageProperty("title", "Наши услуги — Услуги аутсорсинга в Москве");
	}
	$APPLICATION->IncludeComponent(
	"bitrix:catalog", 
	$catalogTemplate,
	array(
		"IBLOCK_TYPE" => "usticom_site_content",
		"IBLOCK_ID" => "5",
		"CURRENT_SECTION_ID" => $serviceSectionId,
		"BASKET_URL" => "",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "/",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_SHADOW" => "Y",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"DISPLAY_PANEL" => "N",
		"SECTION_SHOW_PARENT_NAME" => "N",
		"SET_TITLE" => "Y",
		"SET_STATUS_404" => "Y",
		"USE_FILTER" => "N",
		"USE_COMPARE" => "N",
		"PRICE_CODE" => array(
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "N",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"SHOW_TOP_ELEMENTS" => "N",
		"PAGE_ELEMENT_COUNT" => "10",
		"LINE_ELEMENT_COUNT" => "1",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_ORDER" => "asc",
		"LIST_PROPERTY_CODE" => array(
			0 => "PRICECURRENCY",
			1 => "",
		),
		"INCLUDE_SUBSECTIONS" => "Y",
		"LIST_META_KEYWORDS" => "-",
		"LIST_META_DESCRIPTION" => "-",
		"LIST_BROWSER_TITLE" => "-",
		"DETAIL_PROPERTY_CODE" => array(
			0 => "SIZE",
			1 => "S_SIZE",
			2 => "ARTNUMBER",
			3 => "MATERIAL",
			4 => "MANUFACTURER",
			5 => "",
		),
		"DETAIL_META_KEYWORDS" => "-",
		"DETAIL_META_DESCRIPTION" => "-",
		"DETAIL_BROWSER_TITLE" => "-",
		"DETAIL_SHOW_PICTURE" => "Y",
		"LINK_IBLOCK_TYPE" => "",
		"LINK_IBLOCK_ID" => "",
		"LINK_PROPERTY_SID" => "",
		"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Продукция",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "arrows",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000000",
		"PAGER_SHOW_ALL" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"COMPONENT_TEMPLATE" => "lw-catalog",
		"TEMPLATE_THEME" => "blue",
		"ADD_PICT_PROP" => "-",
		"LABEL_PROP" => "",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_COMPARE" => "Сравнение",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"MESS_NOT_AVAILABLE_SERVICE" => "Недоступно",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"SIDEBAR_SECTION_SHOW" => "Y",
		"SIDEBAR_DETAIL_SHOW" => "N",
		"SIDEBAR_PATH" => "",
		"USER_CONSENT" => "N",
		"USER_CONSENT_ID" => "0",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"DETAIL_STRICT_SECTION_CHECK" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"ADD_SECTIONS_CHAIN" => "Y",
		"ADD_ELEMENT_CHAIN" => "Y",
		"FILTER_VIEW_MODE" => "VERTICAL",
		"FILTER_HIDE_ON_MOBILE" => "N",
		"INSTANT_RELOAD" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"SEARCH_PAGE_RESULT_COUNT" => "50",
		"SEARCH_RESTART" => "N",
		"SEARCH_NO_WORD_LOGIC" => "Y",
		"SEARCH_USE_LANGUAGE_GUESS" => "Y",
		"SEARCH_CHECK_DATES" => "Y",
		"SEARCH_USE_SEARCH_RESULT_ORDER" => "N",
		"SECTION_COUNT_ELEMENTS" => "Y",
		"SECTION_TOP_DEPTH" => "2",
		"SECTIONS_VIEW_MODE" => "LIST",
		"SECTIONS_SHOW_PARENT_NAME" => "Y",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER2" => "desc",
		"SECTION_BACKGROUND_IMAGE" => "-",
		"LIST_PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
		"LIST_PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false}]",
		"LIST_ENLARGE_PRODUCT" => "STRICT",
		"LIST_SHOW_SLIDER" => "Y",
		"LIST_SLIDER_INTERVAL" => "3000",
		"LIST_SLIDER_PROGRESS" => "N",
		"DETAIL_SET_CANONICAL_URL" => "N",
		"DETAIL_CHECK_SECTION_ID_VARIABLE" => "N",
		"DETAIL_BACKGROUND_IMAGE" => "-",
		"SHOW_DEACTIVATED" => "N",
		"SHOW_SKU_DESCRIPTION" => "N",
		"DETAIL_USE_VOTE_RATING" => "N",
		"DETAIL_USE_COMMENTS" => "N",
		"DETAIL_BRAND_USE" => "N",
		"DETAIL_DISPLAY_NAME" => "Y",
		"DETAIL_IMAGE_RESOLUTION" => "16by9",
		"DETAIL_PRODUCT_INFO_BLOCK_ORDER" => "sku,props",
		"DETAIL_PRODUCT_PAY_BLOCK_ORDER" => "rating,price,priceRanges,quantityLimit,quantity,buttons",
		"DETAIL_SHOW_SLIDER" => "N",
		"DETAIL_DETAIL_PICTURE_MODE" => array(
			0 => "POPUP",
			1 => "MAGNIFIER",
		),
		"DETAIL_ADD_DETAIL_TO_SLIDER" => "N",
		"DETAIL_DISPLAY_PREVIEW_TEXT_MODE" => "E",
		"DETAIL_SHOW_POPULAR" => "Y",
		"DETAIL_SHOW_VIEWED" => "Y",
		"USE_STORE" => "N",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"LAZY_LOAD" => "N",
		"MESS_BTN_LAZY_LOAD" => "Показать ещё",
		"LOAD_ON_SCROLL" => "N",
		"SHOW_404" => "Y",
		"MESSAGE_404" => "",
		"COMPATIBLE_MODE" => "N",
		"USE_ELEMENT_COUNTER" => "Y",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"FILE_404" => "",
		"SEF_URL_TEMPLATES" => array(
			"sections" => "/",
			"section" => $catalogTemplate === "services" ? "#SECTION_CODE_PATH#/" : "#SECTION_CODE#/",
			"element" => "#SECTION_CODE#/#ELEMENT_CODE#/",
			"compare" => "",
			"smart_filter" => "#SECTION_CODE#/filter/#SMART_FILTER_PATH#/apply/",
		)
	),
	false
);?>
<?endif;?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
