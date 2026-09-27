<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$this->setFrameMode(true);

$iblock = $arResult["IBLOCK_INFO"] ?? [];
$heroTitle = (string)($iblock["NAME"] ?? "");
$heroDescription = (string)($iblock["DESCRIPTION"] ?? "");
$heroDescriptionType = (string)($iblock["DESCRIPTION_TYPE"] ?? "text");
$heroPicture = $arResult["IBLOCK_PICTURE"] ?? false;
$heroEyebrow = (string)($arParams["EYEBROW_TEXT"] ?? "наши услуги");
include dirname(__DIR__, 3) . "/partials/hero.php";
?>
<section class="section section--bg-initial services-list">
	<div class="container">
		<?php foreach ($arResult["SECTIONS"] as $section):
			$services = $arResult["SERVICES_BY_SECTION"][(int)$section["ID"]] ?? [];
		?>
			<div class="services__section">
				<h2 class="services__title"><a href="<?= htmlspecialcharsbx($section["~SECTION_PAGE_URL"] ?? $section["SECTION_PAGE_URL"]) ?>"><?= htmlspecialcharsbx($section["~NAME"] ?? $section["NAME"]) ?></a></h2>
				<?php if ($services): ?>
					<ul class="services__list">
						<?php foreach ($services as $service): ?>
							<li class="services__item">
								<a href="<?= htmlspecialcharsbx($service["~DETAIL_PAGE_URL"] ?? $service["DETAIL_PAGE_URL"]) ?>"><?= htmlspecialcharsbx($service["~NAME"] ?? $service["NAME"]) ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</section>
