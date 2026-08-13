<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(true);
?>

<? if ($arResult["ITEMS"]): ?>
	<section class="lw-section licenses">
		<div class="lw-container<?= ($arParams["CONTAINER_THIN"] == "Y" ? '-thin' : '') ?>">
			<div class="section-header">
				<span class="lw-section-title"><?= $arResult["NAME"] ?></span>
			</div>
			<div class="swiper main-slider base-slider" data-fancybox-gallery="licenses">
				<div class="swiper-wrapper">
					<? foreach ($arResult["ITEMS"] as $arItem):
						$resImage = CFile::ResizeImageGet(
							$arItem["DETAIL_PICTURE"],
							array("width" => 800, "height" => 600),
							BX_RESIZE_IMAGE_PROPORTIONAL
						);
					?>
						<div class="swiper-slide">
							<img loading="lazy" src="<?= $resImage["src"] ?>" alt="<?= $arItem["NAME"] ?>" width="400" height="300" data-fancybox-item>
						</div>
					<? endforeach; ?>
				</div>
				<div class="swiper-pagination"></div>
			</div>
		</div>
	</section>
<? endif; ?>