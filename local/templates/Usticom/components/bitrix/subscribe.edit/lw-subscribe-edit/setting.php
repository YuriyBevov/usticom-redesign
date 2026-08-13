<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
//***********************************
//setting section
//***********************************
?>

<form class="main-form main-form--subscribe" action="<?= $arResult["FORM_ACTION"] ?>" method="post">
	<? echo bitrix_sessid_post(); ?>
	<fieldset class="control-wrapper">
		<label for="subscribe-mail">
			Ваш E-mail*:
		</label>
		<input type="text" id="subscribe-mail" name="EMAIL" value="<?= $arResult["SUBSCRIPTION"]["EMAIL"] != "" ? $arResult["SUBSCRIPTION"]["EMAIL"] : $arResult["REQUEST"]["EMAIL"]; ?>" size="30" maxlength="255" />
	</fieldset>

	<? foreach ($arResult["RUBRICS"] as $itemID => $itemValue): ?>
		<input type="hidden" name="RUB_ID[]" value="<?= $itemValue["ID"] ?>" checked />
	<? endforeach; ?>
	<input type="submit" class="lw-main-btn main-form__submit" name="Save" value="<? echo GetMessage("subscr_activate") ?>" />

	<? $APPLICATION->IncludeComponent(
		"bitrix:main.userconsent.request",
		"lw",
		array(
			"AUTO_SAVE" => "Y",
			"ID" => "1",
			"IS_CHECKED" => "N",
			"IS_LOADED" => "N"
		)
	); ?>

	<input type="hidden" name="FORMAT" value="text" checked />
	<input type="hidden" name="PostAction" value="<? echo ($arResult["ID"] > 0 ? "Update" : "Add") ?>" />
	<input type="hidden" name="ID" value="<? echo $arResult["SUBSCRIPTION"]["ID"]; ?>" />

	<? if ($_REQUEST["register"] == "YES"): ?>
		<input type="hidden" name="register" value="YES" />
	<? endif; ?>
	<? if ($_REQUEST["authorize"] == "YES"): ?>
		<input type="hidden" name="authorize" value="YES" />
	<? endif; ?>

</form>