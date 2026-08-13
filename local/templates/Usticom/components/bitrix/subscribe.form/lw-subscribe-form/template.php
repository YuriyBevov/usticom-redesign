<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
?>

<div class="subscribe" id="subscribe-form">
	<div class="subscribe__container">
		<div class="subscribe__info">
			<span class="subscribe__title">Подпишитесь на наши новости</span>
			<span class="subscribe__text">Получайте уведомления о новостях первыми!</span> 
		</div>
		<?
			$frame = $this->createFrame("subscribe-form", false)->begin();
		?>
		<form action="<?=$arResult["FORM_ACTION"]?>">

		<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
			<input type="hidden" name="sf_RUB_ID[]" id="sf_RUB_ID_<?=$itemValue["ID"]?>" value="<?=$itemValue["ID"]?>" checked />
		<?endforeach;?>
			<input class="lw-main-btn subscribe__submit-btn" type="submit" name="OK" value="<?=GetMessage("subscr_form_button")?>" />
		</form>
		<?
			$frame->beginStub();
		?>
		<form action="<?=$arResult["FORM_ACTION"]?>">

			<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
				<input type="hidden" name="sf_RUB_ID[]" id="sf_RUB_ID_<?=$itemValue["ID"]?>" value="<?=$itemValue["ID"]?>" /> <?=$itemValue["NAME"]?>
			<?endforeach;?>
			<input class="lw-main-btn subscribe__submit-btn" type="submit" name="OK" value="<?=GetMessage("subscr_form_button")?>" />
		</form>
		<?
			$frame->end();
		?>
	</div>
</div>
