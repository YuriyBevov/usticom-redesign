<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<section class="lw-section news-section">
	<div class="section-header">
		<span class="lw-accent-title js-content-menu-item lw-section-title"><?=$arResult["NAME"]?></span>
	</div>

	<?if($arResult["DESCRIPTION"]):?>
		<p><?=$arResult["DESCRIPTION"]?></p>
	<?endif;?>

	<?if($arResult["ITEMS"]):?>
		<div class="icons-grid">
			<?foreach($arResult["ITEMS"] as $arItem):?>
			<div class="icons-grid__item">
				<div class="icons-grid__item-icon">
					<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>" width="50" height="50">
				</div>
				<span><?=$arItem["NAME"]?></span>
			</div>
			<?endforeach;?>
		</div>
	<?endif;?>
</section>
