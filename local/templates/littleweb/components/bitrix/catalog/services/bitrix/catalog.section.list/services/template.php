<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$this->setFrameMode(true);

$iblock = $arResult["IBLOCK_INFO"] ?? [];
$picture = $arResult["IBLOCK_PICTURE"] ?? false;
$description = trim((string)($iblock["DESCRIPTION"] ?? ""));
?>
<section class="section inner-hero">
	<div class="container">
		<div class="inner-hero__grid">
			<div class="inner-hero__grid-item inner-hero__grid-item--content">
				<div class="eyebrow"><?= htmlspecialcharsbx($arParams["EYEBROW_TEXT"] ?? "наши услуги") ?></div>
				<h1 class="inner-hero__title"><?= htmlspecialcharsbx($iblock["NAME"] ?? "") ?></h1>
				<?php if ($description !== ""): ?>
					<div class="inner-hero__text">
						<?= ($iblock["DESCRIPTION_TYPE"] ?? "text") === "html" ? $description : nl2br(htmlspecialcharsbx($description)) ?>
					</div>
				<?php endif; ?>

				<div class="btn-row">
					<button class="main-btn main-btn--size-lg" type="button">Оставить заявку</button>
					<a class="main-btn main-btn--outlined main-btn--size-lg" href="/partnership/">Партнерская программа</a>
				</div>
			</div>
			<?php if ($picture): ?>
				<div class="inner-hero__grid-item inner-hero__grid-item--picture" aria-hidden="true">
					<img src="<?= htmlspecialcharsbx($picture["SRC"]) ?>" width="<?= (int)$picture["WIDTH"] ?>" height="<?= (int)$picture["HEIGHT"] ?>" alt="">
				</div>
			<?php endif; ?>
		</div>

		<? $APPLICATION->IncludeComponent(
			"bitrix:news.list",
			"tizzers",
			[
				"IBLOCK_TYPE" => "usticom_site_content",
				"IBLOCK_ID" => "29",
				"NEWS_COUNT" => "4",
				"SORT_BY1" => "SORT",
				"SORT_ORDER1" => "ASC",
				"SORT_BY2" => "ID",
				"SORT_ORDER2" => "ASC",
				"FIELD_CODE" => ["NAME", "PREVIEW_TEXT"],
				"PROPERTY_CODE" => [],
				"DISPLAY_NAME" => "Y",
				"DISPLAY_PREVIEW_TEXT" => "Y",
				"DISPLAY_PICTURE" => "N",
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
	</div>
</section>

<section class="section section--bg-initial services-list">
	<div class="container">
		<?php foreach ($arResult["SECTIONS"] as $section):
			$services = $arResult["SERVICES_BY_SECTION"][(int)$section["ID"]] ?? [];
		?>
			<div class="services__section">
				<h2 class="services__title"><?= htmlspecialcharsbx($section["~NAME"] ?? $section["NAME"]) ?></h2>
				<?php if ($services): ?>
					<ul class="services__list">
						<?php foreach ($services as $service): ?>
							<li class="services__item">
								<a href="<?= htmlspecialcharsbx($service["~DETAIL_PAGE_URL"] ?? $service["DETAIL_PAGE_URL"]) ?>"><?= htmlspecialcharsbx($service["~NAME"] ?? $service["NAME"]) ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</section>