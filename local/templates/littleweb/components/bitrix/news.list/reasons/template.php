<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$this->setFrameMode(true);

if (!$arResult["REASONS"]) {
	return;
}
?>
<section class="section section--bg-initial reasons">
	<div class="container">
		<div class="section-header">
			<div class="eyebrow">помощь</div>
			<?php if ($arResult["REASONS_SECTION_TITLE"] !== ""): ?>
				<h2 class="section-title"><?= htmlspecialcharsbx($arResult["REASONS_SECTION_TITLE"]) ?></h2>
			<?php endif; ?>
		</div>
		<ul class="reasons__list">
			<?php foreach ($arResult["REASONS"] as $reason): ?>
				<li class="reasons__item">
					<?php if ($reason["PICTURE"]): ?>
						<div class="reasons__media">
							<img class="reasons__picture" src="<?= htmlspecialcharsbx($reason["PICTURE"]["SRC"]) ?>" width="<?= (int)$reason["PICTURE"]["WIDTH"] ?>" height="<?= (int)$reason["PICTURE"]["HEIGHT"] ?>" alt="" loading="lazy">
						</div>
					<?php endif; ?>
					<div class="reasons__content">
						<?php if ($reason["TITLE"] !== ""): ?><h3 class="reasons__title"><?= htmlspecialcharsbx($reason["TITLE"]) ?></h3><?php endif; ?>
						<?php if ($reason["TEXT"] !== ""): ?><p class="reasons__text"><?= nl2br(htmlspecialcharsbx($reason["TEXT"])) ?></p><?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
		<div class="reasons__request">
			<div class="reasons__request-intro">
				<div class="eyebrow">заявка</div>
				<h2 class="section-title">Получите консультацию эксперта</h2>
				<p>Расскажите о вашей задаче — мы предложим лучшее решение.</p>
			</div>
			<?php
			global $APPLICATION;
			// The footer also uses form 5. Only the submitted instance may process POST.
			$otherFormSubmit = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST'
				&& (int)($_POST['WEB_FORM_ID'] ?? 0) === 5
				&& ($_POST['form_inline_instance'] ?? '') !== 'reasons';
			if (!$otherFormSubmit):
			$APPLICATION->IncludeComponent("bitrix:form.result.new", "form-inline", [
				"WEB_FORM_ID" => 5,
				"INSTANCE_ID" => "reasons",
				"EMBEDDED_MODE" => "Y",
				"SHOW_HEADER" => "N",
				"IGNORE_CUSTOM_TEMPLATE" => "Y",
				"CACHE_TYPE" => "N",
				"CACHE_TIME" => 0,
				"AJAX_MODE" => "Y",
				"AJAX_OPTION_JUMP" => "N",
				"AJAX_OPTION_STYLE" => "Y",
				"AJAX_OPTION_HISTORY" => "N",
				"AJAX_OPTION_ADDITIONAL" => "reasons-request",
				"USE_EXTENDED_ERRORS" => "Y",
				"SEF_MODE" => "N",
				"LIST_URL" => "",
				"EDIT_URL" => "",
				"SUCCESS_URL" => "",
			], false, ["HIDE_ICONS" => "Y"]);
			endif;
			?>
		</div>
	</div>
</section>
