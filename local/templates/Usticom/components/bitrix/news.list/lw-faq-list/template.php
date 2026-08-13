<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
// $APPLICATION->AddHeadString('<script src="/script.js"></script>', true);
?>


<? if ($arResult["ITEMS"]): ?>
	<section class="lw-section faq-preview">

		<div class="section-header">
			<span class="lw-section-title"><?= $arResult["NAME"] ?></span>
		</div>

		<div class="accordeon" itemscope itemtype="https://schema.org/FAQPage">
			<? foreach ($arResult["ITEMS"] as $arItem):
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
				<div class="accordeon-item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
					<div class="accordeon-header">
						<strong class="base-subtitle" itemprop="name"><?= $arItem["NAME"] ?></strong>
						<div class="accordeon-opener">

							<svg width="24" height="24">
								<use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/sprite.svg#icon-small-down"></use>
							</svg>
						</div>
					</div>
					<div class="accordeon-body" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
						<div class="content-wrapper" itemprop="text">
							<?= $arItem["PREVIEW_TEXT"] ?>
						</div>
					</div>
				</div>
			<? endforeach; ?>
		</div>

	</section>
<? endif; ?>