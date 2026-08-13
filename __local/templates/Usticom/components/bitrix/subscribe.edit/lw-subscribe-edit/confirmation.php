<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//*************************************
//show confirmation form
//*************************************
?>

<form class="main-form main-form--subscribe" action="<?=$arResult["FORM_ACTION"]?>" method="get">
	<small>Отправить письмо <a href="<?echo $arResult["FORM_ACTION"]?>?ID=<?echo $arResult["ID"]?>&amp;action=sendcode&amp;<?echo bitrix_sessid_get()?>">повторно</a>.</small>
	<input type="hidden" name="ID" value="<?echo $arResult["ID"];?>" />
	<?echo bitrix_sessid_post();?>
</form>

