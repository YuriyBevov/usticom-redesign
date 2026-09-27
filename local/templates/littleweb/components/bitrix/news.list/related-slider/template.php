<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$this->setFrameMode(true);
if (!$arResult["ITEMS"]) {
	return;
}
?>
<section class="section related-slider-section <?= $arParams["SECTION_CLASS"] ?? '' ?>">
	<div class="container">
		<?php $APPLICATION->IncludeFile(
			SITE_TEMPLATE_PATH . "/include/section-header.php",
			["EYEBROW_TEXT" => "рекомендуем", "TITLE" => "Вам также могут быть интересны", "USE_SWIPER_NAVIGATION" => "Y"],
			["MODE" => "html", "NAME" => "шапку раздела", "SHOW_BORDER" => true]
		); ?>
		<div class="swiper base-slider">
			<div class="swiper-wrapper">
				<?php foreach ($arResult["ITEMS"] as $item):
					$this->AddEditAction($item["ID"], $item["EDIT_LINK"], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($item["ID"], $item["DELETE_LINK"], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"));
				?>
					<div class="swiper-slide" id="<?= $this->GetEditAreaId($item["ID"]) ?>">
						<a class="related-slider-card" href="<?= htmlspecialcharsbx($item["DETAIL_PAGE_URL"]) ?>">
							<span class="related-slider-card__title"><?= htmlspecialcharsbx($item["NAME"]) ?></span>
							<span class="related-slider-card__footer"><span>Подробнее</span><svg width="12" height="12" aria-hidden="true">
									<use xlink:href="<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-arrow"></use>
								</svg></span>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>