<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(true);
?>

<section class="lw-section">
	<div class="lw-container">
		<div class="section-header">
			<span class="lw-section-title"><?= $arResult["NAME"] ?></span>
			<a href="<?= $arResult["LIST_PAGE_URL"] ?>">Все</a>
		</div>
		<? if ($arResult["ITEMS"]): ?>
			<ul class="news-list">
				<? foreach ($arResult["ITEMS"] as $arItem):
					$resImage = CFile::ResizeImageGet(
						$arItem["PREVIEW_PICTURE"],
						array("width" => 521, "height" => 289),
						BX_RESIZE_IMAGE_EXACT
					);
				?>
					<li class="news-list__item">
						<div class="info-card">
							<a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="info-card__header">
								<img
									src="<?= $resImage['src'] ?>"
									alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
									title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>"
									width="521" height="289"
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
											<a href="/news/<?= '?tag=' . $tag ?>">
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
	</div>
</section>