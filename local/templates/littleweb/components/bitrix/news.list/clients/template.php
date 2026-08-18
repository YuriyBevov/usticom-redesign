<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$this->setFrameMode(true);
?>

<section class="section clients-slider-section">
	<div class="container">

		<? $APPLICATION->IncludeFile(
			SITE_TEMPLATE_PATH . '/include/section-header.php',
			array(
				"EYEBROW_TEXT" => $arParams["EYEBROW_TEXT"] ?? '',
				"TITLE" => $arResult["NAME"],
				"USE_SWIPER_NAVIGATION" => $arParams["USE_SWIPER_NAVIGATION"] ??  "N"
			),
			array('MODE' => 'html', 'NAME' => 'шапку раздела', 'SHOW_BORDER' => true)
		); ?>

		<div class="swiper base-slider">
			<div class="swiper-wrapper">
				<? foreach ($arResult["ITEMS"] as $index => $arItem):
					$this->AddEditAction($arItem["ID"], $arItem["EDIT_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem["ID"], $arItem["DELETE_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage("CT_BNL_ELEMENT_DELETE_CONFIRM"),));

					$image = $arItem["PREVIEW_PICTURE"] ? $arItem["PREVIEW_PICTURE"] : $arItem["DETAIL_PICTURE"];
					$alt = $image["ALT"] ? $image["ALT"] : $image["NAME"];
				?>
					<div class="swiper-slide" id="<?= $this->GetEditAreaId($arItem["ID"]) ?>">
						<img src="<?= $image["SRC"] ?>" alt="<?= $alt ?>" width="120" height="80">
					</div>
				<? endforeach; ?>
			</div>

			<? if ($arParams["USE_SWIPER_PAGINATION"] === "Y"): ?>
				<div class="swiper-pagination"></div>
			<? endif; ?>
		</div>

	</div>
</section>