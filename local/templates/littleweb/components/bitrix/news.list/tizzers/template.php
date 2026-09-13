<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$this->setFrameMode(true);
?>

<? if (!empty($arResult["ITEMS"])): ?>
	<ul class="tizzers">
		<? foreach ($arResult["ITEMS"] as $arItem):
			$this->AddEditAction($arItem["ID"], $arItem["EDIT_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem["ID"], $arItem["DELETE_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), ["CONFIRM" => GetMessage("CT_BNL_ELEMENT_DELETE_CONFIRM")]);
			$text = trim((string)$arItem["PREVIEW_TEXT"]) !== "" ? $arItem["PREVIEW_TEXT"] : $arItem["NAME"];
		?>
			<li class="tizzers__item" id="<?= $this->GetEditAreaId($arItem["ID"]) ?>">
				<svg width="40" height="40" role="img" aria-hidden="true" focusable="false">
					<use xlink:href="<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-star"></use>
				</svg>
				<div class="tizzers__text"><?= $text ?></div>
			</li>
		<? endforeach; ?>
	</ul>
<? endif; ?>
