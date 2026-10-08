<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$this->setFrameMode(true);
$sections = $arResult["SECTIONS"] ?? [];
?>

<section class="section inner-hero">
	<div class="container">
		<div class="inner-hero__grid">
			<div class="inner-hero__grid-item inner-hero__grid-item--content">
				<div class="eyebrow">Наши услуги</div>
				<h1 class="inner-hero__title">Услуги для бизнеса</h1>
				<p class="inner-hero__text">Аудит, бухгалтерия, налоги, право, кадры, ВЭД и сопровождение сделок — в одном окне. Аудиторы, бухгалтеры, налоговые консультанты и юристы работают вместе, поэтому задачи на стыке направлений решаются без поиска разных подрядчиков.</p>
				<div class="btn-row">
					<a class="main-btn main-btn--size-lg" href="#services-directions">Подобрать услугу</a>
					<a class="main-btn main-btn--outlined main-btn--size-lg" href="/contacts/">Получить консультацию</a>
				</div>
			</div>
			<div class="inner-hero__grid-item inner-hero__grid-item--picture" aria-hidden="true">
				<img src="<?= SITE_TEMPLATE_PATH ?>/components/bitrix/catalog/servises/_src/images/hero-team.png" width="510" height="681" alt="">
			</div>
		</div>
		<?php
		$heroTizzersSectionId = getHeroTizzersSectionId((int)($arParams["TIZZERS_SECTION_ID"] ?? 0), 5158);
		if ($heroTizzersSectionId > 0) {
			$APPLICATION->IncludeComponent(
				"bitrix:news.list",
				"tizzers",
				[
					"IBLOCK_TYPE" => "usticom_site_content",
					"IBLOCK_ID" => "29",
					"PARENT_SECTION" => $heroTizzersSectionId,
					"INCLUDE_SUBSECTIONS" => "N",
					"STRICT_SECTION_CHECK" => "Y",
					"NEWS_COUNT" => "4",
					"SORT_BY1" => "SORT",
					"SORT_ORDER1" => "ASC",
					"SORT_BY2" => "ID",
					"SORT_ORDER2" => "ASC",
					"FIELD_CODE" => ["NAME", "PREVIEW_TEXT"],
					"CHECK_DATES" => "Y",
					"CACHE_TYPE" => "A",
					"CACHE_TIME" => "36000000",
					"CACHE_GROUPS" => "Y",
					"SET_TITLE" => "N",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"ADD_SECTIONS_CHAIN" => "N",
				],
				$component,
				["HIDE_ICONS" => "Y"]
			);
		}
		?>
	</div>
</section>

<section class="section section--bg-initial services-list" id="services-directions">
	<div class="container">
		<div class="section-header">
			<div class="eyebrow">направления</div>
			<h2 class="section-title">Все направления</h2>
			<p class="services-list__lead">Выберите направление — внутри полный список услуг с ценами. Сложная задача на стыке направлений? Соберём команду под неё.</p>
		</div>
		<div class="services-list__grid">
			<?php foreach ($sections as $index => $section):
				$services = $arResult["SERVICES_BY_SECTION"][(int)$section["ID"]] ?? [];
				$count = count($services);
				$countMod100 = $count % 100;
				$countMod10 = $count % 10;
				$countWord = ($countMod100 >= 11 && $countMod100 <= 14) ? "услуг" : ($countMod10 === 1 ? "услуга" : (($countMod10 >= 2 && $countMod10 <= 4) ? "услуги" : "услуг"));
				$sectionUrl = (string)($section["~SECTION_PAGE_URL"] ?? $section["SECTION_PAGE_URL"]);
				$this->AddEditAction($section["ID"], $section["EDIT_LINK"] ?? "", CIBlock::GetArrayByID($section["IBLOCK_ID"], "SECTION_EDIT"));
				$this->AddDeleteAction($section["ID"], $section["DELETE_LINK"] ?? "", CIBlock::GetArrayByID($section["IBLOCK_ID"], "SECTION_DELETE"), ["CONFIRM" => "Удалить направление?"]);
			?>
				<article class="services-list__card" id="<?= $this->GetEditAreaId($section["ID"]) ?>">
					<div class="services-list__card-header">
						<span class="services-list__number"><?= str_pad((string)($index + 1), 2, "0", STR_PAD_LEFT) ?></span>
						<span class="services-list__count"><?= $count ?> <?= $countWord ?></span>
					</div>
					<h3 class="services-list__card-title"><a href="<?= htmlspecialcharsbx($sectionUrl) ?>"><?= htmlspecialcharsbx($section["~NAME"] ?? $section["NAME"]) ?></a></h3>
					<?php if ($services): ?>
						<ul>
							<?php foreach (array_slice($services, 0, 4) as $service): ?>
								<li><a href="<?= htmlspecialcharsbx($service["~DETAIL_PAGE_URL"] ?? $service["DETAIL_PAGE_URL"]) ?>"><?= htmlspecialcharsbx($service["~NAME"] ?? $service["NAME"]) ?></a></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<a class="services-list__card-more" href="<?= htmlspecialcharsbx($sectionUrl) ?>">
						Все услуги направления
						<svg width="14" height="14" aria-hidden="true">
							<use xlink:href="<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-arrow"></use>
						</svg>
					</a>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php $APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"features",
	[
		"IBLOCK_TYPE" => "usticom_site_content",
		"IBLOCK_ID" => "9",
		"SECTION_TITLE" => "Почему компании выбирают Юстиком",
		"NEWS_COUNT" => "20",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"FIELD_CODE" => ["NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE"],
		"CHECK_DATES" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"SET_TITLE" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
	],
	$component,
	["HIDE_ICONS" => "Y"]
); ?>

<section class="section section--bg-initial services-landing__company">
	<div class="container">
		<div class="section-header">
			<div class="eyebrow">о компании</div>
			<h2 class="section-title">Консалтинговая группа «Юстиком»</h2>
		</div>
		<p>«Юстиком» с 2003 года помогает компаниям вести учёт, проходить проверки и решать правовые задачи. Мы работаем по принципу «одного окна»: аудиторы, бухгалтеры, налоговые консультанты и юристы сопровождают клиента вместе. Профессиональная ответственность застрахована, деятельность контролируется СРО «Российская коллегия аудиторов».</p>
	</div>
</section>
