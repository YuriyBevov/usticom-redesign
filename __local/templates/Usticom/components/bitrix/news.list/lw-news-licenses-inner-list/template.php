<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);
?>

<?if($arResult["ITEMS"]):?>
	<section class="lw-section licenses">
		<span class="lw-subtitle lw-accent-title"><?=$arResult["NAME"]?></span>
		<div class="swiper main-slider base-slider" data-fancybox-gallery="licenses">
			<div class="swiper-wrapper">
				<?foreach($arResult["ITEMS"] as $arItem):?> 
					<div class="swiper-slide">
						<img loading="lazy" src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>" width="<?=$arItem["DETAIL_PICTURE"]["WIDTH"]?>" height="<?=$arItem["DETAIL_PICTURE"]["HEIGHT"]?>" data-fancybox-item>
					</div>
				<?endforeach;?>
			</div>
			<div class="swiper-pagination"></div>
		</div>
	</section>

<?endif;?>
