<?
//if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<?if(!$arResult["FORM_NOTE"]):?>
	<div class="main-form main-form--modal">
		<span class="main-form__title">
			<?=$arResult["FORM_TITLE"]?>
		</span>

		<?if($arResult["FORM_DESCRIPTION"]):?>
			<p><?=$arResult["FORM_DESCRIPTION"]?></p>
		<?endif;?>

		<?=$arResult["FORM_HEADER"]?>

		<?foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion):?>
			<?if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden'):?>
				<?=$arQuestion["HTML_CODE"];?>
			<?else:?>
				<fieldset class="control-wrapper <?=(is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])) ? 'invalid-field' : null;?>">
					<label>
						<?=$arQuestion["CAPTION"]?><?if ($arQuestion["REQUIRED"] == "Y"):?><span>*</span><?endif;?>
					</label>
					<?
						if($arResult["arQuestions"][$FIELD_SID]["COMMENTS"] == 'data-type="tel"'):
						/*Если поле с типом tel. data-type прописываем в админке в поле комментарий для поля формы*/
					?>
						<div class="phone-field">
				<?=$arQuestion["HTML_CODE"]?>
			</div>
					<?else:?>

						<?=$arQuestion["HTML_CODE"]?>
					<?endif;?>
				</fieldset>
			<?endif;?>
		<?endforeach;?>
		<?if($arResult["isUseCaptcha"] == "Y"):?>
			<input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" />
			<img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" width="180" height="40" />
			<input type="text" name="captcha_word" size="30" maxlength="50" value="" class="inputtext" />
		<?endif;?>

			<input class="lw-main-btn main-form__submit" <?=(intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : "");?> type="submit" name="web_form_submit" value="<?=htmlspecialcharsbx(trim($arResult["arForm"]["BUTTON"]) == '' ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>" />

			<small>Отправляя форму, вы соглашаетесь с <a href="/privacy-policy/" target="_blank">условиями обработки и использования персональных данных</a></small>
			
		<?=$arResult["FORM_FOOTER"]?>
	</div>
<?endif;?>

<?if($arResult["FORM_NOTE"] && !$arResult["FORM_ERRORS"]):?>
	<script>
		dataLayer.push({'event': 'ZayavkaMain'});
	</script>
	<span class="modal-form-answer animate-load"><?=$arResult["FORM_NOTE"]?></span>
<?endif;?>

<style>
	.modal-form-answer.animate-load {
		opacity: 0;
		animation: animate-load 0.4s .1s ease-in forwards;
	}

	@keyframes animate-load {
		0% {
			opacity: 0;
		}

		100% {
			opacity: 1;
		}
	}
</style>


<script src="https://unpkg.com/imask"></script>
<script>
  var fields = document.querySelectorAll('.phone-field input');
  
  var options = {
      mask: '+{7}(000)000-00-00'
  };

  fields.forEach(field => {
      IMask(field, options);
  });
</script>
