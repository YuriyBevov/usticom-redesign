<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);
?>

<?if($arResult["ITEMS"]):?>
<section class="lw-section clients">
	<span class="lw-subtitle lw-accent-title">Клиенты</span>
	<div class="swiper main-slider clients-slider" data-autoplay="true">
		<div class="swiper-wrapper">
			<?foreach($arResult["ITEMS"] as $arItem):?>
			<div class="swiper-slide">
				<img loading="lazy" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>" width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>" height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>">
			</div>
			<?endforeach;?>
		</div>
		<div class="swiper-pagination"></div>
	</div>
</section>

<?endif;?>
