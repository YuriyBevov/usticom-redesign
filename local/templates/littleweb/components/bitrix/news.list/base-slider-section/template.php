<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>

<section class="section base-slider-section">
	<div class="container">

		<div class="section-header">
			<? if (!empty($arParams["EYEBROW_TEXT"] && strlen($arParams["EYEBROW_TEXT"] > 0))): ?>
				<div class="eyebrow"><?= $arParams["EYEBROW_TEXT"] ?></div>
			<? endif; ?>

			<h2 class="section-title"><?= $arResult["NAME"] ?></h2>

			<? if ($arParams["USE_SWIPER_NAVIGATION"] && $arParams["USE_SWIPER_NAVIGATION"] === "Y"): ?>
				<div class="swiper-navigation">
					<button type="button" class="swiper-button swiper-button--prev">
						<svg width='12' height='6' role='img' aria-hidden='true' focusable='false'>
							<use xlink:href='<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-arrow'></use>
						</svg>
					</button>
					<button type="button" class="swiper-button swiper-button--next">
						<svg width='12' height='6' role='img' aria-hidden='true' focusable='false'>
							<use xlink:href='<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-arrow'></use>
						</svg>
					</button>
				</div>
			<? endif; ?>
		</div>

		<div class="swiper base-slider">
			<div class="swiper-wrapper">
				<? foreach ($arResult["ITEMS"] as $arItem):
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				?>
					<div class="swiper-slide" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
						1
					</div>
				<? endforeach; ?>
			</div>
		</div>

		<? if ($arParams["USE_SWIPER_PAGINATION"] && $arParams["USE_SWIPER_PAGINATION"] === "Y"): ?>
			<div class="swiper-pagination"></div>
		<? endif; ?>
	</div>
</section>