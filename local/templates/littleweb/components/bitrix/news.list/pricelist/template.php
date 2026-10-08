<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$this->setFrameMode(true);
if (empty($arResult["PRICES"])) {
	return;
}
$featuredIndex = (int)($arParams["FEATURED_INDEX"] ?? 2);
$buttonText = (string)($arParams["BUTTON_TEXT"] ?? "Оставить заявку");
$buttonUrl = (string)($arParams["BUTTON_URL"] ?? "/contacts/");
?>

<section class="section section--bg-alternative pricelist">
	<div class="container">
		<?php $APPLICATION->IncludeFile(
			SITE_TEMPLATE_PATH . "/include/section-header.php",
			[
				"EYEBROW_TEXT" => $arParams["EYEBROW_TEXT"] ?? "тарифы на услуги",
				"TITLE" => htmlspecialcharsbx($arParams["SECTION_TITLE"] ?? "Стоимость"),
				"USE_SWIPER_NAVIGATION" => "N",
			],
			["MODE" => "html", "NAME" => "Шапка тарифов", "SHOW_BORDER" => true]
		); ?>
		<div class="pricelist__grid">
			<?php foreach ($arResult["PRICES"] as $index => $price):
				$this->AddEditAction($price["ID"], $price["EDIT_LINK"], CIBlock::GetArrayByID($price["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($price["ID"], $price["DELETE_LINK"], CIBlock::GetArrayByID($price["IBLOCK_ID"], "ELEMENT_DELETE"), ["CONFIRM" => "Удалить тариф?"]);
			?>
				<article class="price-card<?= $index + 1 === $featuredIndex ? " price-card--featured" : "" ?>" id="<?= $this->GetEditAreaId($price["ID"]) ?>">
					<?php if ($price["TITLE"] !== ""): ?>
						<h3 class="price-card__title"><?= htmlspecialcharsbx($price["TITLE"]) ?></h3>
					<?php endif; ?>
					<?php if ($price["NOTE"] !== ""): ?>
						<div class="price-card__preview-text"><?= htmlspecialcharsbx($price["NOTE"]) ?></div>
					<?php endif; ?>
					<?php if ($price["PRICE"] !== ""): ?>
						<div class="price-card__price"><?= htmlspecialcharsbx($price["PRICE"]) ?></div>
					<?php endif; ?>
					<?php if ($buttonText !== "" && $buttonUrl !== ""): ?>
						<a class="main-btn price-card__button" href="<?= htmlspecialcharsbx($buttonUrl) ?>"><?= htmlspecialcharsbx($buttonText) ?></a>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
