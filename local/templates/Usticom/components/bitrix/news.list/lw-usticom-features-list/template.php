<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);
?>

<?if($arResult["ITEMS"]):?>
	<span class="subtitle"><?=$arResult["NAME"]?></span>

	<div class="swiper feature-card-slider">
		<div class="swiper-wrapper">
			<?foreach($arResult["ITEMS"] as $arItem):?>
			<div class="swiper-slide swiper-slide-active" style="margin-right: 10px;">
				<div class="feature-card"><span class="feature-card__title"><?=$arItem["NAME"]?></span>
					<?if(!empty($arItem["PROPERTIES"]["DESCRIPTION_BLOCK"]["VALUE"])):?>
						<?foreach($arItem["PROPERTIES"]["DESCRIPTION_BLOCK"]["VALUE"] as $arDesc):?>
							<p class="feature-card__desc"><?=$arDesc["TEXT"]?></p>
						<?endforeach;?>
					<?endif;?>
				</div>
			</div>
			<?endforeach;?>
		</div>
	</div>
<?endif;?>
