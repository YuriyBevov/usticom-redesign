<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$this->setFrameMode(true);
$sectionTitle = trim((string)($arParams["SECTION_TITLE"] ?? "")) ?: $arResult["NAME"];
$sectionText = trim((string)($arParams["SECTION_TEXT"] ?? ""));
?>

<? if (!empty($arResult["ITEMS"])): ?>
	<section class="section section--bg-initial directions-section">
		<div class="container">
			<div class="section-header directions-section__header">
				<h2 class="section-title"><?= htmlspecialcharsbx($sectionTitle) ?></h2>
				<? if ($sectionText !== ""): ?>
					<p class="section-description"><?= htmlspecialcharsbx($sectionText) ?></p>
				<? endif; ?>
			</div>

			<ul class="direction-list">
				<? foreach ($arResult["ITEMS"] as $arItem):
					$this->AddEditAction($arItem["ID"], $arItem["EDIT_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem["ID"], $arItem["DELETE_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), ["CONFIRM" => GetMessage("CT_BNL_ELEMENT_DELETE_CONFIRM")]);
				?>
					<li class="direction-list__item" id="<?= $this->GetEditAreaId($arItem["ID"]) ?>">
						<img class="direction-list__icon" src="<?= SITE_TEMPLATE_PATH ?>/_src/images/content-image/direction-icon.svg" alt="" width="24" height="24">
						<span class="direction-list__title"><?= htmlspecialcharsbx($arItem["NAME"]) ?></span>
					</li>
				<? endforeach; ?>
			</ul>
		</div>
	</section>
<? endif; ?>
