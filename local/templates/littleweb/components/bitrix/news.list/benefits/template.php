<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>

<section class="section section--bg-initial benefits <?= htmlspecialcharsbx($arParams['SECTION_CLASS'] ?? '') ?>">
	<div class="container">
		<h2 class="visually-hidden"><?= $arResult["NAME"] ?></h2>
		<ul class="benefits-list">
			<? foreach ($arResult["ITEMS"] as $arItem):
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				$isMetric = preg_match('/^\d[\d\s\x{00A0}]*\s+\D/u', trim((string)($arItem["~NAME"] ?? $arItem["NAME"]))) === 1;
				$mobileCaption = trim((string)($arItem["~PREVIEW_TEXT"] ?? $arItem["PREVIEW_TEXT"] ?? ""));
			?>
				<li class="benefits-list__item<?= $isMetric ? ' benefits-list__item--metric' : '' ?>" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
					<div class="benefits-list__img-wrapper">
						<img src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>" alt="<?= $arItem["NAME"] ?>" width="32" height="32">
					</div>
					<span class="benefits-list__name"><?= $arItem["NAME"] ?></span>
					<?php if ($mobileCaption !== ""): ?>
						<span class="benefits-list__mobile-caption"><?= htmlspecialcharsbx(strip_tags($mobileCaption)) ?></span>
					<?php endif; ?>
				</li>
			<? endforeach; ?>
		</ul>
	</div>
</section>
