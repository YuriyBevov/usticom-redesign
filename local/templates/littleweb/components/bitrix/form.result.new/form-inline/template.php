<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}
includeComponentAssets('form.result.new/form-inline');
$modalMode = ($arParams['MODAL_MODE'] ?? 'N') === 'Y';
$embeddedMode = ($arParams['EMBEDDED_MODE'] ?? 'N') === 'Y';
?>
<section class="form-inline<?= $modalMode ? ' form-inline--modal' : ($embeddedMode ? ' form-inline--embedded' : ' callback section') ?>">
	<div<?= $modalMode || $embeddedMode ? '' : ' class="container"' ?>>
		<div class="form-inline__inner">
			<?php if (($arParams['SHOW_HEADER'] ?? 'Y') !== 'N' && (!empty($arResult['FORM_TITLE']) || !empty($arResult['FORM_DESCRIPTION']))): ?>
				<div class="section-header">
					<?php if (!$modalMode): ?><div class="eyebrow"><?= usticomText('заявка') ?></div><?php endif; ?>
					<?php if (!empty($arResult['FORM_TITLE'])): ?><h2 class="section-title"><?= $arResult['FORM_TITLE'] ?></h2><?php endif; ?>
					<?php if (!empty($arResult['FORM_DESCRIPTION'])): ?><div class="form-inline__description"><?= $arResult['FORM_DESCRIPTION'] ?></div><?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if (!empty($arResult['FORM_NOTE']) && empty($arResult['FORM_ERRORS'])): ?>
				<div class="form-inline__message" role="status" data-inline-feedback="success"><?= usticomText('В ближайшее время с Вами свяжется менеджер для уточнения деталей') ?></div>
			<?php elseif (!empty($arResult['ERROR'])): ?>
				<p class="form-inline__message" role="alert"><?= usticomText('Форма временно недоступна. Попробуйте обратиться к нам позже.') ?></p>
			<?php else: ?>
				<?php if (!empty($arResult['FORM_ERRORS'])):
					$errors = is_array($arResult['FORM_ERRORS']) ? implode("\n", $arResult['FORM_ERRORS']) : $arResult['FORM_ERRORS'];
					$errors = trim(html_entity_decode(strip_tags($errors), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
				?>
					<template data-inline-feedback="error"><?= htmlspecialcharsbx($errors ?: usticomText('Не удалось отправить заявку. Попробуйте ещё раз.')) ?></template>
				<?php endif; ?>
				<?= $arResult['INLINE_FORM_HEADER'] ?>
				<?php if (!empty($arParams['INSTANCE_ID'])): ?>
					<input type="hidden" name="form_inline_instance" value="<?= htmlspecialcharsbx($arParams['INSTANCE_ID']) ?>">
				<?php endif; ?>
				<div class="form-inline__fields">
					<?php foreach ($arResult['INLINE_FIELDS'] as $field): ?>
						<?php if ($field['TYPE'] === 'hidden'): ?>
							<?= $field['HTML'] ?>
						<?php else: ?>
							<div class="form-inline__field<?= $field['TYPE'] === 'textarea' ? ' form-inline__field--wide' : '' ?><?= $field['INVALID'] ? ' invalid-field' : '' ?>" data-inline-field data-kind="<?= htmlspecialcharsbx($field['KIND']) ?>" data-required="<?= $field['REQUIRED'] ? 'true' : 'false' ?>">
								<label class="form-inline__label" for="<?= htmlspecialcharsbx($field['ID']) ?>"><?= htmlspecialcharsbx($field['CAPTION']) ?></label>
								<?= $field['HTML'] ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
				<?php if (!empty($arResult['isUseCaptcha']) && $arResult['isUseCaptcha'] !== 'N'): ?>
					<div class="form-inline__captcha" data-inline-field data-required="true" data-kind="text">
						<input type="hidden" name="captcha_sid" value="<?= htmlspecialcharsbx($arResult['CAPTCHACode']) ?>">
						<img src="/bitrix/tools/captcha.php?captcha_sid=<?= htmlspecialcharsbx($arResult['CAPTCHACode']) ?>" width="180" height="40" alt="Код проверки">
						<label for="form-inline-captcha"><?= usticomText('Введите код с картинки') ?></label>
						<input type="text" id="form-inline-captcha" name="captcha_word" aria-required="true">
					</div>
				<?php endif; ?>
				<div class="form-inline__agreement<?= !empty($arResult['FORM_ERRORS']) && ($arResult['arrVALUES']['form_inline_consent'] ?? '') !== 'Y' ? ' invalid-field' : '' ?>" data-inline-field data-kind="consent" data-required="true">
					<?php $APPLICATION->IncludeComponent('bitrix:main.userconsent.request', 'inline', [
						'ID' => 1,
						'IS_CHECKED' => !empty($arResult['FORM_ERRORS']) && ($arResult['arrVALUES']['form_inline_consent'] ?? '') === 'Y' ? 'Y' : 'N',
						'AUTO_SAVE' => 'N',
						'IS_LOADED' => 'N',
						'REQUIRED' => 'Y',
						'INPUT_NAME' => 'form_inline_consent',
						'IS_INVALID' => !empty($arResult['FORM_ERRORS']) && ($arResult['arrVALUES']['form_inline_consent'] ?? '') !== 'Y',
						'REPLACE' => ['button_caption' => trim((string)($arResult['arForm']['BUTTON'] ?? '')) ?: usticomText('Оставить заявку'), 'fields' => array_column($arResult['INLINE_FIELDS'], 'CAPTION')],
					], $component, ['HIDE_ICONS' => 'Y']); ?>
				</div>
				<button class="main-btn main-btn--size-lg form-inline__submit" type="submit" name="web_form_submit" value="Y"<?= (int)$arResult['F_RIGHT'] < 10 ? ' disabled' : '' ?>><?= htmlspecialcharsbx(trim((string)($arResult['arForm']['BUTTON'] ?? '')) ?: usticomText('Оставить заявку')) ?></button>
				<?= $arResult['FORM_FOOTER'] ?>
			<?php endif; ?>
		</div>
	</div>
</section>
