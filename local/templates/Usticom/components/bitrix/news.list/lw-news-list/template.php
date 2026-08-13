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
$APPLICATION->AddHeadString('<meta property="og:image" content="https://' .  $_SERVER["SERVER_NAME"] . CFile::GetPath(CIBlock::GetArrayByID($arResult["ID"], "PICTURE")) . '"/>', true);

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
		<h1 class="lw-page-title"><?= $arResult["NAME"] ?></h1>
		<? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
			<?= $arResult["NAV_STRING"] ?><br />
		<? endif; ?>
		<? if ($arResult["ITEMS"]): ?>
			<? if ($arResult["TAG_LIST"]): ?>
				<ul class="tag-list">
					<li class="tag-list__item">
						<a <?= (empty($arResult["CURRENT_TAG"]) ? 'class="active"' : '') ?> href="<?= $arResult["LIST_PAGE_URL"] ?>">#<?= $arResult["LIST_PAGE_TITLE"] ?></a>
					</li>
					<? foreach ($arResult["TAG_LIST"] as $tag): ?>
						<li class="tag-list__item">
							<a <?= ($arResult["CURRENT_TAG"] === $tag ? 'class="active"' : '') ?> href="<?= $arResult["LIST_PAGE_URL"] ?><?= '?tag=' . $tag ?>"><?= '#' . $tag ?></a>
						</li>
					<? endforeach; ?>
				</ul>
			<? endif; ?>
			<ul class="news-list">
				<? foreach ($arResult["ITEMS"] as $arItem):
					$resImage = CFile::ResizeImageGet(
						$arItem["PREVIEW_PICTURE"],
						array("width" => 521, "height" => 289),
						BX_RESIZE_IMAGE_EXACT
					);
				?>
					<?
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					?>

					<li class="news-list__item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
						<div class="info-card">
							<a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="info-card__header">
								<img
									src="<?= $resImage['src'] ?>"
									alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
									title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>"
									width="540" height="360"
									loading="lazy">
								<? if ($arParams["DISPLAY_DATE"] != "N" && $arItem["DISPLAY_ACTIVE_FROM"]): ?>
									<div class="info-card__header-date">
										<span><?= $arItem["DISPLAY_ACTIVE_FROM"] ?></span>
									</div>
								<? endif ?>

							</a>

							<div class="info-card__body">
								<a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="info-card__body-title" title="<?= $arItem["NAME"] ?>"><?= $arItem["NAME"] ?></a>
								<? if ($arItem["PROPERTIES"]["TAG_LIST"]["VALUE"]): ?>
									<div class="info-card__tag-list">
										<? foreach ($arItem["PROPERTIES"]["TAG_LIST"]["VALUE"] as $tag): ?>
											<a href="<?= $arResult["LIST_PAGE_URL"] . '?tag=' . $tag ?>">
												<?= '#' . $tag ?>
											</a>
										<? endforeach; ?>
									</div>
								<? endif; ?>
								<p class="info-card__body-text"><?= $arItem["PREVIEW_TEXT"]; ?></p>
							</div>

							<a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="info-card__footer"><span>Читать далее</span></a>
						</div>
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
</section>