<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$this->setFrameMode(true);

if (!$arResult["PROFIT"] && !$arResult["UNPROFIT"]) {
	return;
}

$assetPath = SITE_TEMPLATE_PATH . "/components/bitrix/news.list/profit/_src/images/";
$image = $arResult["PROFIT_SECTION_IMG"];
?>
<section class="section section--bg-initial profit">
	<div class="container">
		<div class="section-header">
			<div class="eyebrow">плюсы и минусы</div>
			<?php if ($arResult["PROFIT_SECTION_TITLE"] !== ""): ?>
				<h2 class="section-title"><?= htmlspecialcharsbx($arResult["PROFIT_SECTION_TITLE"]) ?></h2>
			<?php endif; ?>
		</div>
		<div class="profit__layout<?= $image ? " profit__layout--with-image" : "" ?>">
			<div class="profit__comparison">
				<div class="profit__column profit__column--positive">
					<?php if ($arResult["PROFIT_TITLE"] !== ""): ?><h3><?= htmlspecialcharsbx($arResult["PROFIT_TITLE"]) ?></h3><?php endif; ?>
					<ul>
						<?php foreach ($arResult["PROFIT"] as $item):
							$text = $item["TEXT"] !== "" ? $item["TEXT"] : $item["TITLE"];
						?>
							<li><img src="<?= $assetPath ?>check.svg" width="24" height="24" alt="" aria-hidden="true"><span><?= htmlspecialcharsbx($text) ?></span></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="profit__column profit__column--negative">
					<?php if ($arResult["UNPROFIT_TITLE"] !== ""): ?><h3><?= htmlspecialcharsbx($arResult["UNPROFIT_TITLE"]) ?></h3><?php endif; ?>
					<ul>
						<?php foreach ($arResult["UNPROFIT"] as $item):
							$text = $item["TEXT"] !== "" ? $item["TEXT"] : $item["TITLE"];
						?>
							<li><img src="<?= $assetPath ?>close.svg" width="24" height="24" alt="" aria-hidden="true"><span><?= htmlspecialcharsbx($text) ?></span></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<img class="profit__divider" src="<?= $assetPath ?>divider.svg" width="1" height="276" alt="" aria-hidden="true">
				<span class="profit__vs" aria-hidden="true">VS</span>
			</div>
			<?php if ($image): ?>
				<div class="profit__image">
					<img src="<?= htmlspecialcharsbx($image["SRC"]) ?>" width="<?= (int)$image["WIDTH"] ?>" height="<?= (int)$image["HEIGHT"] ?>" alt="<?= htmlspecialcharsbx($image["DESCRIPTION"] ?: $arResult["PROFIT_SECTION_TITLE"]) ?>" loading="lazy">
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
