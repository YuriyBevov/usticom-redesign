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
			$APPLICATION->IncludeComponent("bitrix:form.result.new", "service-request", [
				"WEB_FORM_ID" => 1,
				"CACHE_TYPE" => "N",
				"CACHE_TIME" => 0,
				"AJAX_MODE" => "N",
				"USE_EXTENDED_ERRORS" => "Y",
				"SEF_MODE" => "N",
				"COMPACT_LAYOUT" => "Y",
			], false, ["HIDE_ICONS" => "Y"]);
			?>
		</div>
	</div>
</section>
