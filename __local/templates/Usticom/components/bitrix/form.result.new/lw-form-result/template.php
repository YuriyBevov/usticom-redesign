<?
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<div class="main-form">
	<span class="main-form__title"><?=$arResult["FORM_TITLE"]?></span>
	<?if($arResult["FORM_DESCRIPTION"]):?>
		<p><?=$arResult["FORM_DESCRIPTION"]?></p>
	<?endif;?>
	<?=$arResult["FORM_HEADER"]?>

	<?foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion):?>
		<?if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden'):?>
			<?=$arQuestion["HTML_CODE"];?>
		<?else:?>
			<fieldset class="control-wrapper <?=(is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])) ? 'invalid-field' : null;?>">
				<label for="<?=$arQuestion["STRUCTURE"][0]["FIELD_ID"]?>">
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

		<input class="lw-main-btn" <?=(intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : "");?> type="submit" name="web_form_submit" value="<?=htmlspecialcharsbx(trim($arResult["arForm"]["BUTTON"]) == '' ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>" />

		<small>Отправляя форму, вы соглашаетесь с <a href="/privacy-policy/" target="_blank">условиями обработки и использования персональных данных</a></small>
		
	<?=$arResult["FORM_FOOTER"]?>
</div>

<?if($arResult["FORM_NOTE"] && !$arResult["FORM_ERRORS"]):?>
	<script>
		dataLayer.push({'event': 'ZayavkaMain'});		
	</script>
	<div class="modal-overlay active">
		<div class="modal modal--success">
			<button onclick="modalCloser();" class="js-modal-close-btn" aria-label="Закрыть">
				<svg width="40" height="40">
					<use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/sprite.svg#icon-close"></use>
				</svg>
			</button>
			<div class="modal-content">
				<span class="modal-form-answer"><?=$arResult["FORM_NOTE"]?></span>
			</div>
		</div>
	</div>
<?endif;?>

<style>
	.main-form.disabled {
		position: relative;
	}

	.main-form.disabled::before {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		content: '';
		background-color: rgba(0,0,0,.5);
	}

	.modal-overlay {
		position: fixed;
		top: 0;
		left: 0;
		
		display: none;
		align-items: center;
		justify-content: center;

		width: 100vw;
		height: 100vh;
		background-color: rgba(0,0,0,.5);
		z-index: 101;
		opacity: 0;
	}

	.modal-overlay.active {
		display: flex;
		animation: modal-overlay-animation .4s ease-in forwards;
	}

	.modal.modal--success {
		position: relative;
		display: none;
		flex-direction: column;
		width: 90vw;
		height: fit-content;
		max-width: fit-content;
		max-height: fit-content;
		background-color: var(--white);
		padding: 100px 120px;
		transform: translateY(50px);
	}

	@media(max-width: 1024px) {
		.modal.modal--success {
			padding: 100px 60px;
		}
	}

	@media(max-width: 768px) {
		.modal.modal--success {
			padding: 80px 40px 50px;
		}
	}

	@media(max-width: 534px) {
		.modal.modal--success {
			padding: 50px 20px 30px;
		}
	}

	.modal-form-answer {
		font-size: 30px;
		font-weight: 700;
		color: var(--primary);
	}

	@media(max-width: 1024px) {
		.modal-form-answer {
			font-size: 26px;
		}
	}

	@media(max-width: 768px) {
		.modal-form-answer {
			font-size: 22px;
		}
	}

	@media(max-width: 534px) {
		.modal-form-answer {
			font-size: 18px;
		}
	}

	.js-modal-close-btn {
		position: absolute;
		top: 20px;
		right: 20px;
		width: 40px;
		height: 40px;
		display: flex;
		align-items: center;
		justify-content: center;
		padding: 0;
		border: none;
		background-color: transparent;

		cursor: pointer;
	}

	@media(max-width: 534px) {
		.js-modal-close-btn {
			top: 5px;
			right: 5px;
		}
	}

	.js-modal-close-btn svg {
		fill: var(--primary);
	}

	@media(max-width: 534px) {
		.js-modal-close-btn svg {
			width: 20px;
			height: 20px;
		}
	}

	.modal-overlay.active > .modal {
		display: flex;
		animation: modal 0.4s ease-in forwards;
	}

	@keyframes modal {
		0% {
			opacity: 0;
			transform: translateY(50px);
		}

		100% {
			opacity: 1;
			transform: translateY(0px);
		}
	}

	@keyframes modal-overlay-animation {
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

<script>
	function modalCloser(evt) {
		var overlay = document.querySelector('.modal-overlay.active');
		if(overlay) {
			overlay.classList.remove('active');
		}
	}

	var labels = document.querySelectorAll('.main-form form label');
	labels.forEach(label => {

		let input = label.parentNode.querySelector('input');
		if(input) {
			input.setAttribute("id", label.getAttribute("for"));
		}
		let textarea = label.parentNode.querySelector('textarea');
		if(textarea) {
			textarea.setAttribute("id", label.getAttribute("for"));
		}
		
	});
</script>