<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>

<section class="lw-section news-section news-section--last-news">
	<div class="lw-container-thin">
		<div class="section-header">
			<span class="lw-section-title">Последние новости:</span>
		</div>
		<? if ($arResult["ITEMS"]): ?>
			<ul class="news-list">
				<? foreach ($arResult["ITEMS"] as $arItem):
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				?>
					<li class="news-list__item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
						<div class="info-card">
							<div class="info-card__body">
								<a class="info-card__body-title" href="<?= $arItem["DETAIL_PAGE_URL"] ?>">
									<?= $arItem["NAME"] ?>
								</a>
								<? if ($arItem["PROPERTIES"]["TAG_LIST"]["VALUE"]): ?>
									<div class="info-card__tag-list">
										<? foreach ($arItem["PROPERTIES"]["TAG_LIST"]["VALUE"] as $tag): ?>
											<a href="/news/<?= '?tag=' . $tag ?>">
												<?= '#' . $tag ?>
											</a>
										<? endforeach; ?>
									</div>
								<? endif; ?>
								<span class="info-card__body-date">
									от <?= $arItem["DISPLAY_ACTIVE_FROM"] ?>
								</span>
								<p class="info-card__body-text"><?= $arItem["PREVIEW_TEXT"]; ?></p>
							</div>
							<a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="info-card__footer"><span>Читать далее</span></a>
						</div>
					</li>
				<? endforeach; ?>
			</ul>
		<? endif; ?>
	</div>
</section>