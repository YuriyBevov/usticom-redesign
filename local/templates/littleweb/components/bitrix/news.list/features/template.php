<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>

<section class="section section--bg-initial features">
	<div class="container">

		<ul class="features-list">
			<li>
				<? $APPLICATION->IncludeFile(
					SITE_TEMPLATE_PATH . '/include/section-header.php',
					array(
						"EYEBROW_TEXT" => 'какие есть',
						"TITLE" => $arResult["NAME"],
						"USE_SWIPER_NAVIGATION" => $arParams["USE_SWIPER_NAVIGATION"] ??  "N"
					),
					array('MODE' => 'html', 'NAME' => 'шапку раздела', 'SHOW_BORDER' => true)
				); ?>
			</li>
			<? foreach ($arResult["ITEMS"] as $arItem):
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
				<li id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
					<div class="features-list__icon-wrapper">
						<img src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>" alt="<?= $arItem["NAME"] ?>" width="32" height="32">
					</div>
					<span><?= $arItem["NAME"] ?></span>
					<p><?= $arItem["PREVIEW_TEXT"] ?></p>
				</li>
			<? endforeach; ?>
		</ul>
	</div>
</section>