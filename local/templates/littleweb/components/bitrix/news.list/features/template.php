<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
$sectionTitle = trim((string)($arParams["SECTION_TITLE"] ?? ""));
?>

<section class="section section--bg-initial features">
	<div class="container">

		<ul class="features-list">
			<li class="features-list__heading">
				<? $APPLICATION->IncludeFile(
					SITE_TEMPLATE_PATH . '/include/section-header.php',
					array(
						"EYEBROW_TEXT" => 'какие есть',
						"TITLE" => $sectionTitle !== "" ? htmlspecialcharsbx($sectionTitle) : $arResult["NAME"],
						"USE_SWIPER_NAVIGATION" => $arParams["USE_SWIPER_NAVIGATION"] ??  "N"
					),
					array('MODE' => 'html', 'NAME' => 'шапку раздела', 'SHOW_BORDER' => false)
				); ?>
			</li>
			<? foreach ($arResult["ITEMS"] as $arItem):
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
				<li class="features-list__item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
					<details class="features-list__details" open>
						<summary class="features-list__summary">
							<div class="features-list__icon-wrapper">
								<img src="<?= htmlspecialcharsbx($arItem["PREVIEW_PICTURE"]["SRC"]) ?>" alt="" width="32" height="32">
							</div>
							<span class="features-list__title"><?= htmlspecialcharsbx($arItem["~NAME"] ?? $arItem["NAME"]) ?></span>
							<img class="features-list__toggle" src="<?= SITE_TEMPLATE_PATH ?>/components/bitrix/news.list/features/_src/images/feature-chevron.svg" width="24" height="24" alt="">
						</summary>
						<div class="features-list__description"><?= $arItem["PREVIEW_TEXT"] ?></div>
					</details>
				</li>
			<? endforeach; ?>
		</ul>
	</div>
</section>
