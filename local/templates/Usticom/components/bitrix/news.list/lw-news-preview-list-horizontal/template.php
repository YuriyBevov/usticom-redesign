<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(true);
?>

<section class="lw-section news-section">
	<div class="section-header">
		<span class="lw-accent-title js-content-menu-item lw-section-title">
			<?= $arResult["NAME"] ?>
		</span>
		<a href="<?= $arResult["LIST_PAGE_URL"] ?>">Все</a>
	</div>

	<? if ($arResult["ITEMS"]): ?>
		<ul class="news-section__list">
			<? foreach ($arResult["ITEMS"] as $arItem):
				$resImage = CFile::ResizeImageGet(
					$arItem["PREVIEW_PICTURE"],
					array("width" => 480, "height" => 240),
					BX_RESIZE_IMAGE_EXACT
				);
			?>
				<li>
					<div class="news-card">
						<div class="news-card__container" aria-label="<?= $arItem["NAME"] ?>">
							<div class="news-card__image-wrapper">
								<img src="<?= $resImage['src'] ?>" alt="<?= $arItem["NAME"] ?>" title="<?= $arItem["NAME"] ?>" width="300" height="150">
							</div>
							<div class="news-card__content">
								<span class="news-card__pre"><?= $arItem["DISPLAY_ACTIVE_FROM"] ?></span>
								<a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="news-card__title" title="<?= $arItem["NAME"] ?>"><?= $arItem["NAME"] ?></a>
								<? if ($arItem["PROPERTIES"]["TAG_LIST"]["VALUE"]): ?>
									<div class="info-card__tag-list">
										<? foreach ($arItem["PROPERTIES"]["TAG_LIST"]["VALUE"] as $tag): ?>
											<a href="<?= $arResult["LIST_PAGE_URL"] . '?tag=' . $tag ?>">
												<?= '#' . $tag ?>
											</a>
										<? endforeach; ?>
									</div>
								<? endif; ?>
								<span class="news-card__desc"><?= $arItem["PREVIEW_TEXT"] ?></span>
							</div>
						</div>
					</div>
				</li>
			<? endforeach; ?>
		</ul>
	<? else: ?>
		<p>Раздел обновляется...</p>
	<? endif; ?>
</section>