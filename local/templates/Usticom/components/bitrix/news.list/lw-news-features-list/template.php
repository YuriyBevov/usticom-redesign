<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);
?>

<?if($arResult["ITEMS"]):?>
	<section class="lw-section features">
		<div class="lw-container">
			<div class="section-header">
				<span class="lw-section-title"><?=$arResult["NAME"]?></span>
			</div>
		</div>

		<div class="features__container">
			<div class="lw-container">
				<ul class="features-list">
					<?foreach($arResult["ITEMS"] as $arItem):?>
					<li class="features-list__item">
						<div class="features-list__item-header">
							<div class="features-list__item-icon">
								<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>" width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>" height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>">
							</div>
						</div>
						
						<div class="features-list__item-content">
							<span class="features-list__item-title"><?=$arItem["NAME"]?></span>
							<div class="features-list__item-text"><?=$arItem["PREVIEW_TEXT"]?></div>
						</div>
					</li>
					<?endforeach;?>
				</ul>
			</div>
		</div>
	</section>
<?endif;?>