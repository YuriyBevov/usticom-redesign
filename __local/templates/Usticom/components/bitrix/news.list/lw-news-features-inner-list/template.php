<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);
?>

<?if($arResult["ITEMS"]):?>
	<section class="lw-section">
		<span class="lw-subtitle lw-accent-title">Преимущества</span>
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
	</section>
<?endif;?>