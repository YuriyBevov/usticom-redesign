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

<?/* $APPLICATION->IncludeComponent(
	"bitrix:breadcrumb",
	"breadcrumbs",
	array(
		"PATH" => "",
		"SITE_ID" => "s1",
		"START_FROM" => "0",
		"COMPONENT_TEMPLATE" => "breadcrumbs"
	),
	false
); */ ?>

<section class="section news-section">
	<div class="container">

		<? $APPLICATION->IncludeFile(
			SITE_TEMPLATE_PATH . '/include/section-header.php',
			array(
				"EYEBROW_TEXT" => $arParams["EYEBROW_TEXT"] ?? '',
				"TITLE" => $arResult["NAME"],
				"TEXT" => 'Передали бухгалтерию на аутсорсинг, когда внутренний специалист перестал справляться с объёмом задач. Команда быстро разобралась в учёте, навела порядок в документах и закрыла накопившиеся вопросы по отчётности».',
				"USE_SWIPER_NAVIGATION" => $arParams["USE_SWIPER_NAVIGATION"] ??  "N"
			),
			array('MODE' => 'html', 'NAME' => 'шапку раздела', 'SHOW_BORDER' => true)
		); ?>
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
				<? foreach ($arResult["ITEMS"] as $index => $arItem):
					// $resImage = CFile::ResizeImageGet(
					// 	$arItem["PREVIEW_PICTURE"],
					// 	array("width" => 521, "height" => 289),
					// 	BX_RESIZE_IMAGE_EXACT
					// );

					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

					$image = $arItem["PREVIEW_PICTURE"] ? $arItem["PREVIEW_PICTURE"] : $arItem["DETAIL_PICTURE"];
					$alt = $image["ALT"] ? $image["ALT"] : $image["NAME"];
				?>

					<li class="news-list__item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
						<?/*<div class="info-card">
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
						</div>*/ ?>
						<div class="news-card">
							<a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="news-card__header">
								<img src="<?= $image["SRC"] ?>" alt="<?= $alt ?>" width="120" height="80">
								<? if ($arParams["SHOW_ACTIVE_FROM"] === "Y"): ?>
									<div class="label"><?= $arItem["DISPLAY_ACTIVE_FROM"] ?></div>
								<? endif; ?>
							</a>
							<div class="news-card__content">
								<? if ($arParams["SHOW_TAG_LIST"] === "Y" && !empty($arItem["PROPERTIES"]["TAG_LIST"]["VALUE"])): ?>
									<ul class="tag-list">
										<? foreach ($arItem["PROPERTIES"]["TAG_LIST"]["VALUE"] as $tag): ?>
											<li>
												<a href="<?= '/news/?tag=' . $tag ?>" class="tag">
													#<?= $tag ?>
												</a>
											</li>
										<? endforeach; ?>
									</ul>
								<? endif; ?>
								<a class="news-card__title" href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><?= $arItem["NAME"] ?></a>
								<p class="news-card__text"><?= $arItem["PREVIEW_TEXT"] ?></p>
							</div>
							<div class="news-card__footer">
								<a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="news-card__link">
									<span>Читать полностью</span>
									<svg width='12' height='12' role='img' aria-hidden='true' focusable='false'>
										<use xlink:href='<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-arrow'></use>
									</svg>
								</a>
							</div>
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