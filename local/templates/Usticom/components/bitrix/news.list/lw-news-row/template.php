<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

?>

<section class="lw-section news-section">
	<div class="lw-container-thin">
		<div class="section-header">
			<span class="lw-section-title">Последние новости:</span>
		</div>
		<? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
			<?= $arResult["NAV_STRING"] ?><br />
		<? endif; ?>
		<? if ($arResult["ITEMS"]): ?>
			<ul class="news-list">
				<? foreach ($arResult["ITEMS"] as $arItem): ?>
					<?
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					?>

					<li class="news-list__item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
						<div class="info-card" href="<?= $arItem["DETAIL_PAGE_URL"] ?>">
							<div class="info-card__body">
								<a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="info-card__body-title" title="<?= $arItem["NAME"] ?>"><?= $arItem["NAME"] ?></a>
								<p class="info-card__body-text"><?= $arItem["PREVIEW_TEXT"]; ?></p>
							</div>
						</div>
					</li>
				<? endforeach; ?>
			</ul>
		<? else: ?>
			<p>Раздел обновляется...</p>
		<? endif; ?>


	</div>
</section>