<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$this->setFrameMode(true);
?>

<section class="section news-slider-section">
	<div class="container">

		<? $APPLICATION->IncludeFile(
			SITE_TEMPLATE_PATH . '/include/section-header.php',
			array(
				"EYEBROW_TEXT" => $arParams["EYEBROW_TEXT"] ?? '',
				"TITLE" => $arResult["NAME"],
				"USE_SWIPER_NAVIGATION" => $arParams["USE_SWIPER_NAVIGATION"] ??  "N"
			),
			array('MODE' => 'html', 'NAME' => 'шапку раздела', 'SHOW_BORDER' => true)
		); ?>

		<div class="swiper base-slider">
			<div class="swiper-wrapper">
				<? foreach ($arResult["ITEMS"] as $index => $arItem):
					$this->AddEditAction($arItem["ID"], $arItem["EDIT_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem["ID"], $arItem["DELETE_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage("CT_BNL_ELEMENT_DELETE_CONFIRM"),));

					$image = $arItem["PREVIEW_PICTURE"] ? $arItem["PREVIEW_PICTURE"] : $arItem["DETAIL_PICTURE"];
					$alt = $image["ALT"] ? $image["ALT"] : $image["NAME"];
				?>
					<div class="swiper-slide" id="<?= $this->GetEditAreaId($arItem["ID"]) ?>">
						<div class="news-card">
							<a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="news-card__header">
								<img src="<?= $image["SRC"] ?>" alt="<?= $alt ?>" width="120" height="80">
								<? if ($arParams["SHOW_ACTIVE_FROM"] === "Y"): ?>
									<div class="label"><?= $arItem["DISPLAY_ACTIVE_FROM"] ?></div>
								<? endif; ?>
							</a>
							<div class="news-card__content">
								<? if ($arParams["SHOW_TAG_LIST"] === "Y" && !empty($arItem["PROPERTIES"]["TAG_LIST"]["VALUE"])): ?>
									<ul class="tag-list">
										<? foreach ($arItem["PROPERTIES"]["TAG_LIST"]["VALUE"] as $tag): ?>
											<li>
												<a href="<?= '/news/?tag=' . $tag ?>" class="tag">
													#<?= $tag ?>
												</a>
											</li>
										<? endforeach; ?>
									</ul>
								<? endif; ?>
								<a class="news-card__title" href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><?= $arItem["NAME"] ?></a>
								<p class="news-card__text"><?= $arItem["PREVIEW_TEXT"] ?></p>
							</div>
							<div class="news-card__footer">
								<a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="news-card__link">
									<span>Читать полностью</span>
									<svg width='12' height='12' role='img' aria-hidden='true' focusable='false'>
										<use xlink:href='<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-arrow'></use>
									</svg>
								</a>
							</div>
						</div>
					</div>
				<? endforeach; ?>
			</div>

			<? if ($arParams["USE_SWIPER_PAGINATION"] === "Y"): ?>
				<div class="swiper-pagination"></div>
			<? endif; ?>
		</div>

	</div>
</section>