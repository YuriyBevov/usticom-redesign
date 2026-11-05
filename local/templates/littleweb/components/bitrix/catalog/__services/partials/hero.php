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

		<?php includeHeroTizzers((array)($arResult["PROPERTIES"]["HERO_TIZZERS"]["VALUE"] ?? $arResult["UF_HERO_TIZZERS"] ?? []), $component); ?>
	</div>
</section>
