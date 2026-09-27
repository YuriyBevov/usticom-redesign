<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}
?>
<div class="service-detail__request" id="<?= htmlspecialcharsbx($serviceFormId) ?>">
	<div class="service-detail__request-intro"><div class="eyebrow">заявка</div><h2 class="section-title">Получите консультацию эксперта</h2><p>Расскажите о вашей задаче — мы предложим лучшее решение.</p></div>
	<?php include __DIR__ . "/request-form.php"; ?>
	<?php if ($serviceFormId === "service-prices-request"): ?><img class="service-detail__request-image" src="<?= SITE_TEMPLATE_PATH ?>/components/bitrix/catalog/services/_src/images/detail/consultation.png" alt="" loading="lazy"><?php endif; ?>
</div>
