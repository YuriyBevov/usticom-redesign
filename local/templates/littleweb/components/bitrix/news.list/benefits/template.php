<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>

<section class="section section--bg-initial benefits">
	<div class="container">
		<h2 class="visually-hidden"><?= $arResult["NAME"] ?></h2>
		<ul class="benefits-list">
			<? foreach ($arResult["ITEMS"] as $arItem):
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
				<li id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
					<div class="benefits-list__img-wrapper">
						<img src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>" alt="<?= $arItem["NAME"] ?>" width="32" height="32">
					</div>
					<span><?= $arItem["NAME"] ?></span>
				</li>
			<? endforeach; ?>
		</ul>
	</div>
</section>