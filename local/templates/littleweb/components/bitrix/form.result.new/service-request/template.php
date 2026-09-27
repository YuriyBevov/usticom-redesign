<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$compactLayout = ($arParams["COMPACT_LAYOUT"] ?? "N") === "Y";
?>
<div class="service-detail__form<?= $compactLayout ? " service-detail__form--compact" : "" ?>">
	<?php if ($arResult["FORM_NOTE"] && !$arResult["FORM_ERRORS"]): ?>
		<p role="status"><?= htmlspecialcharsbx($arResult["FORM_NOTE"]) ?></p>
	<?php else: ?>
		<?php if ($arResult["FORM_ERRORS"]): ?><div role="alert"><?= $arResult["FORM_ERRORS_TEXT"] ?></div><?php endif; ?>
		<?= $arResult["FORM_HEADER"] ?>
		<div class="service-detail__form-fields">
			<?php foreach ($arResult["QUESTIONS"] as $sid => $question): ?>
				<?php if (($question["STRUCTURE"][0]["FIELD_TYPE"] ?? "") === "hidden"): ?>
					<?= $question["HTML_CODE"] ?>
				<?php else: ?>
					<?php
					$fieldHtml = $question["HTML_CODE"];
					if ($compactLayout && in_array($question["STRUCTURE"][0]["FIELD_TYPE"] ?? "", ["text", "email", "tel", "textarea"], true)) {
						$fieldHtml = preg_replace_callback('/<(input|textarea)\b(?![^>]*\bplaceholder\s*=)/i', static function ($matches) use ($question) {
							return $matches[0] . ' placeholder="' . htmlspecialcharsbx($question["CAPTION"]) . '"';
						}, $fieldHtml, 1);
					}
					?>
					<label class="service-detail__form-field<?= ($question["STRUCTURE"][0]["FIELD_TYPE"] ?? "") === "textarea" ? " service-detail__form-field--textarea" : "" ?>"><span><?= htmlspecialcharsbx($question["CAPTION"]) ?><?= $question["REQUIRED"] === "Y" ? " *" : "" ?></span><?= $fieldHtml ?></label>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<?php if ($arResult["isUseCaptcha"] === "Y"): ?>
			<input type="hidden" name="captcha_sid" value="<?= htmlspecialcharsbx($arResult["CAPTCHACode"]) ?>">
			<img src="/bitrix/tools/captcha.php?captcha_sid=<?= htmlspecialcharsbx($arResult["CAPTCHACode"]) ?>" width="180" height="40" alt="Код проверки">
			<label>Код проверки <input type="text" name="captcha_word" required></label>
		<?php endif; ?>
		<?php if ($compactLayout): ?>
			<label class="service-detail__form-consent"><input type="checkbox" name="service_request_consent" required><span>Нажимая на кнопку, я принимаю <a href="/privacy-policy/">условия соглашения</a>.</span></label>
		<?php else: ?>
			<p class="service-detail__form-consent">Нажимая на кнопку, я принимаю <a href="/privacy-policy/">условия соглашения</a>.</p>
		<?php endif; ?>
		<button class="main-btn main-btn--size-lg" type="submit" name="web_form_submit" value="Y"<?= (int)$arResult["F_RIGHT"] < 10 ? " disabled" : "" ?>>Оставить заявку</button>
		<?= $arResult["FORM_FOOTER"] ?>
	<?php endif; ?>
</div>
