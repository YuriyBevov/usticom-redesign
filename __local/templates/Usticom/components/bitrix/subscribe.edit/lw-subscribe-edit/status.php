<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//***********************************
//status and unsubscription/activation section
//***********************************
?>

<form class="main-form main-form--subscribe" action="<?=$arResult["FORM_ACTION"]?>" method="get">

<?if($arResult["SUBSCRIPTION"]["CONFIRMED"] == "Y"):?>

	<?if($arResult["SUBSCRIPTION"]["ACTIVE"] == "Y"):?>
		<input type="submit" class="lw-main-btn main-form__submit" name="unsubscribe" value="<?= GetMessage("subscr_unsubscr")?>" />
		<input type="hidden" name="action" value="unsubscribe" />
	<?else:?>
		<?=GetMessage($MESS ['subscr_status_note5'])?>
		<input type="submit" class="lw-main-btn main-form__submit" name="activate" value="<?= GetMessage("subscr_activate")?>" />
		<input type="hidden" name="action" value="activate" />
	<?endif;?>

<?endif;?>
<input type="hidden" name="ID" value="<?echo $arResult["SUBSCRIPTION"]["ID"];?>" />
<?echo bitrix_sessid_post();?>
</form>
<br />