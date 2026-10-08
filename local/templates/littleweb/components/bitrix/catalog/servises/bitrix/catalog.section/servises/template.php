<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$this->setFrameMode(true);
$heroTitle = trim((string)($arResult["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] ?? "")) ?: (string)($arResult["~NAME"] ?? $arResult["NAME"]);
$heroEyebrow = (string)($arParams["EYEBROW_TEXT"] ?? "наши услуги");
$heroDescription = $arResult["HERO_PREVIEW_TEXT"];
$heroDescriptionType = $arResult["HERO_PREVIEW_TEXT_TYPE"];
$heroPicture = $arResult["HERO_PICTURE"] ?: [
	"SRC" => SITE_TEMPLATE_PATH . "/components/bitrix/catalog/servises/_src/images/hero-team.png",
	"WIDTH" => 1086,
	"HEIGHT" => 1448,
];
include dirname(__DIR__, 3) . "/partials/hero.php";
?>

<section class="section section--bg-initial service-section">
	<div class="container">
		<div class="service-section__grid">
			<?php foreach ($arResult["ITEMS"] as $item):
				$this->AddEditAction($item["ID"], $item["EDIT_LINK"] ?? "", CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($item["ID"], $item["DELETE_LINK"] ?? "", CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), ["CONFIRM" => "Удалить услугу?"]);
				$url = (string)($item["~DETAIL_PAGE_URL"] ?? $item["DETAIL_PAGE_URL"]);
				$preview = trim((string)($item["~PREVIEW_TEXT"] ?? $item["PREVIEW_TEXT"] ?? ""));
			?>
				<article class="service-card" id="<?= $this->GetEditAreaId($item["ID"]) ?>">
					<span class="service-card__title"><a href="<?= htmlspecialcharsbx($url) ?>"><?= htmlspecialcharsbx($item["~NAME"] ?? $item["NAME"]) ?></a></span>
					<?php if ($preview !== ""): ?>
						<div class="service-card__preview-text"><?= ($item["PREVIEW_TEXT_TYPE"] ?? "text") === "html" ? $preview : nl2br(htmlspecialcharsbx($preview)) ?></div>
					<?php endif; ?>
					<div class="service-card__footer">
						<span class="service-card__price<?= $item["HAS_PRICE"] ? "" : " service-card__price--empty" ?>"><?= htmlspecialcharsbx($item["PRICE_TEXT"]) ?></span>
						<a class="service-card__more" href="<?= htmlspecialcharsbx($url) ?>">
							Подробнее
							<svg width="14" height="14" aria-hidden="true"><use xlink:href="<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-arrow"></use></svg>
						</a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section section--bg-initial consultation">
	<div class="container">
		<div class="consultation__inner">
			<div class="consultation__wrapper">
				<h3>Не знаете, какую услугу выбрать?</h3>
				<p>Специалист бесплатно разберёт задачу и подберёт формат работы — ответим в течение рабочего дня.</p>
			</div>
			<a class="main-btn main-btn--size-lg" href="/contacts/">Получить консультацию</a>
		</div>
	</div>
</section>

<?php if ($arResult["DOCS_IBLOCK_ID"] > 0 && $arResult["DOCS_FILTER"] && $arResult["DOCS_FILE_PROPERTY_CODE"] !== ""):
	$docsFilterName = "serviceSectionDocsFilter_" . (int)$arResult["ID"];
	$GLOBALS[$docsFilterName] = $arResult["DOCS_FILTER"];
	$APPLICATION->IncludeComponent("bitrix:news.list", "docs", [
		"IBLOCK_ID" => $arResult["DOCS_IBLOCK_ID"],
		"NEWS_COUNT" => 1000,
		"FILTER_NAME" => $docsFilterName,
		"CACHE_FILTER" => "Y",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ID",
		"SORT_ORDER2" => "ASC",
		"FIELD_CODE" => ["NAME", "PREVIEW_TEXT"],
		"PROPERTY_CODE" => [$arResult["DOCS_FILE_PROPERTY_CODE"]],
		"FILE_PROPERTY_CODE" => $arResult["DOCS_FILE_PROPERTY_CODE"],
		"CHECK_DATES" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => 36000000,
		"CACHE_GROUPS" => "Y",
		"SET_TITLE" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"EYEBROW_TEXT" => "документы",
		"SECTION_TITLE" => "Документы для клиентов",
		"SECTION_TEXT" => "Заполните анкету — рассчитаем стоимость проверки за 1 рабочий день.",
	], $component, ["HIDE_ICONS" => "Y"]);
	unset($GLOBALS[$docsFilterName]);
endif; ?>

<?php $APPLICATION->IncludeComponent("bitrix:news.list", "features", [
	"IBLOCK_TYPE" => "usticom_site_content",
	"IBLOCK_ID" => 9,
	"SECTION_TITLE" => "Почему компании выбирают Юстиком",
	"NEWS_COUNT" => 20,
	"SORT_BY1" => "ACTIVE_FROM",
	"SORT_ORDER1" => "DESC",
	"SORT_BY2" => "SORT",
	"SORT_ORDER2" => "ASC",
	"FIELD_CODE" => ["NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE"],
	"CHECK_DATES" => "Y",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => 36000000,
	"CACHE_GROUPS" => "Y",
	"SET_TITLE" => "N",
	"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
	"ADD_SECTIONS_CHAIN" => "N",
], $component, ["HIDE_ICONS" => "Y"]); ?>

<?php
$description = trim((string)($arResult["~DESCRIPTION"] ?? $arResult["DESCRIPTION"] ?? ""));
if ($description !== ""):
?>
	<section class="section section--bg-initial service-section-description">
		<div class="container">
			<div class="content-block"><?= strtolower((string)($arResult["DESCRIPTION_TYPE"] ?? "text")) === "html" ? $description : nl2br(htmlspecialcharsbx($description)) ?></div>
		</div>
	</section>
<?php endif; ?>

<?php foreach ($arResult["FAQ_GROUPS"] as $group):
	$faqFilterName = "serviceSectionFaqFilter_" . (int)$arResult["ID"] . "_" . $group["IBLOCK_ID"];
	$GLOBALS[$faqFilterName] = [
		"SECTION_ID" => $group["SECTION_IDS"],
		"INCLUDE_SUBSECTIONS" => "Y",
		"SECTION_GLOBAL_ACTIVE" => "Y",
	];
	$APPLICATION->IncludeComponent("bitrix:news.list", "faq", [
		"IBLOCK_ID" => $group["IBLOCK_ID"],
		"NEWS_COUNT" => 1000,
		"FILTER_NAME" => $faqFilterName,
		"CACHE_FILTER" => "Y",
		"INCLUDE_SUBSECTIONS" => "Y",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ID",
		"SORT_ORDER2" => "ASC",
		"FIELD_CODE" => ["NAME", "PREVIEW_TEXT", "DETAIL_TEXT"],
		"CHECK_DATES" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => 36000000,
		"CACHE_GROUPS" => "Y",
		"SET_TITLE" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"FAQ_DESCRIPTION" => $arResult["FAQ_DESCRIPTION"],
		"FAQ_DESCRIPTION_TYPE" => $arResult["FAQ_DESCRIPTION_TYPE"],
	], $component, ["HIDE_ICONS" => "Y"]);
	unset($GLOBALS[$faqFilterName]);
endforeach; ?>
