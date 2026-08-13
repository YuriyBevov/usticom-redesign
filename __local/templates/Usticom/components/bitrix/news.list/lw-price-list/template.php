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

<? $APPLICATION->IncludeComponent(
	"bitrix:breadcrumb",
	"lw-breadcrumbs",
	array(
		"PATH" => "",
		"SITE_ID" => "s1",
		"START_FROM" => "0",
		"COMPONENT_TEMPLATE" => "lw-breadcrumbs"
	),
	false
); ?>

<section class="lw-section news-section">
	<div class="lw-container-thin">
		<div class="content-wrapper">
			<h1><?= $arResult["NAME"] ?></h1>
			<? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
				<?= $arResult["NAV_STRING"] ?><br />
			<? endif; ?>
			<? if ($arResult["ITEMS"]): ?>
				<ul>
					<? foreach ($arResult["ITEMS"] as $arItem): ?>
						<?
						$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
						$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
						?>

						<li id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
							<a href="<?= $arItem["DETAIL_PAGE_URL"] ?>">
								<span title="<?= $arItem["NAME"] ?>"><?= $arItem["NAME"] ?></span>
							</a>
						</li>
					<? endforeach; ?>
				</ul>
			<? else: ?>
				<p>Раздел обновляется...</p>
			<? endif; ?>

			<? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
				<?= $arResult["NAV_STRING"] ?>
			<? endif; ?>
		</div>
	</div>
</section>