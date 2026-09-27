<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$this->setFrameMode(true);

$heroTitle = (string)($arResult["~NAME"] ?? $arResult["NAME"] ?? "");
$heroDescription = (string)($arResult["~DESCRIPTION"] ?? $arResult["DESCRIPTION"] ?? "");
$heroDescriptionType = (string)($arResult["DESCRIPTION_TYPE"] ?? "text");
$heroPicture = !empty($arResult["PICTURE"]) ? CFile::GetFileArray((int)$arResult["PICTURE"]) : false;
$heroEyebrow = (string)($arParams["EYEBROW_TEXT"] ?? "наши услуги");
include dirname(__DIR__, 3) . "/partials/hero.php";
?>

<section class="section section--bg-initial services-list">
	<div class="container">
		<div class="services__section">
			<h2 class="services__title"><?= htmlspecialcharsbx($heroTitle) ?></h2>
			<?php if ($arResult["ITEMS"]): ?>
				<ul class="services__list">
					<?php foreach ($arResult["ITEMS"] as $service): ?>
						<li class="services__item">
							<a href="<?= htmlspecialcharsbx($service["~DETAIL_PAGE_URL"] ?? $service["DETAIL_PAGE_URL"]) ?>"><?= htmlspecialcharsbx($service["~NAME"] ?? $service["NAME"]) ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</section>
