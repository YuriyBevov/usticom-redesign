<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$heroDescription = trim((string)$heroDescription);
$heroPrimaryLabel = (string)($heroPrimaryLabel ?? "Оставить заявку");
$heroSecondaryLabel = (string)($heroSecondaryLabel ?? "Партнерская программа");
$heroPrimaryUrl = (string)($heroPrimaryUrl ?? "");
$heroSecondaryUrl = (string)($heroSecondaryUrl ?? "/partnership/");
?>
<section class="section inner-hero">
	<div class="container">
		<div class="inner-hero__grid">
			<div class="inner-hero__grid-item inner-hero__grid-item--content">
				<div class="eyebrow"><?= htmlspecialcharsbx($heroEyebrow) ?></div>
				<h1 class="inner-hero__title"><?= htmlspecialcharsbx($heroTitle) ?></h1>
				<?php if ($heroDescription !== ""): ?>
					<div class="inner-hero__text">
						<?= $heroDescriptionType === "html" ? $heroDescription : nl2br(htmlspecialcharsbx($heroDescription)) ?>
					</div>
				<?php endif; ?>

				<div class="btn-row">
					<?php if ($heroPrimaryUrl !== ""): ?>
						<a class="main-btn main-btn--size-lg" href="<?= htmlspecialcharsbx($heroPrimaryUrl) ?>"><?= htmlspecialcharsbx($heroPrimaryLabel) ?></a>
					<?php else: ?>
						<button class="main-btn main-btn--size-lg" type="button"><?= htmlspecialcharsbx($heroPrimaryLabel) ?></button>
					<?php endif; ?>
					<a class="main-btn main-btn--outlined main-btn--size-lg" href="<?= htmlspecialcharsbx($heroSecondaryUrl) ?>"><?= htmlspecialcharsbx($heroSecondaryLabel) ?></a>
				</div>
			</div>
			<?php if ($heroPicture): ?>
				<div class="inner-hero__grid-item inner-hero__grid-item--picture" aria-hidden="true">
					<img src="<?= htmlspecialcharsbx($heroPicture["SRC"]) ?>" width="<?= (int)$heroPicture["WIDTH"] ?>" height="<?= (int)$heroPicture["HEIGHT"] ?>" alt="">
				</div>
			<?php endif; ?>
		</div>

		<?php $APPLICATION->IncludeComponent(
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
