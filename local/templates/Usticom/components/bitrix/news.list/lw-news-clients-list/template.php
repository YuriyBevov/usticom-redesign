<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(true);
?>
<? if ($arResult["ITEMS"]): ?>
	<section class="lw-section clients">
		<div class="lw-container<?= ($arParams["CONTAINER_THIN"] == "Y" ? '-thin' : '') ?>">
			<div class="section-header">
				<span class="lw-section-title"><?= $arResult["NAME"] ?></span>
			</div>
			<div class="swiper main-slider clients-slider" data-autoplay="true">
				<div class="swiper-wrapper">
					<? foreach ($arResult["ITEMS"] as $arItem): ?>
						<div class="swiper-slide">
							<img loading="lazy" src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>" alt="<?= $arItem["NAME"] ?>" width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"] ?>" height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>">
						</div>
					<? endforeach; ?>
				</div>
				<div class="swiper-pagination"></div>
			</div>
		</div>
	</section>
<? endif; ?>