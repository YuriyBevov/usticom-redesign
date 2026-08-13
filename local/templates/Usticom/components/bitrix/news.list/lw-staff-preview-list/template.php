<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>

<?
$staffPreviewItems = array_filter($arResult["ITEMS"], function ($arItem) {
	return $arItem["PROPERTIES"]["IS_STAFF_PREVIEW_INCLUDED"]["VALUE"] == "Y";
});
?>

<? if ($staffPreviewItems): ?>
	<section class="lw-section">
		<div class="section-header">
			<span class="lw-accent-title js-content-menu-item lw-section-title">
				<?= $arResult["NAME"] ?>
			</span>
			<a href="/staff/">Все</a>
		</div>

		<div class="team-preview-grid">
			<? foreach ($staffPreviewItems as $arItem):
				$resImage = CFile::ResizeImageGet(
					$arItem["PREVIEW_PICTURE"],
					array("width" => 260, "height" => 350),
					BX_RESIZE_IMAGE_EXACT
				);

			?>

				<a class="staff-preview-card" href="/staff/">
					<div class="lw-image-hovered-wrapper">
						<img src="<?= $resImage['src'] ?>" alt="<?= $arItem["NAME"] ?>" width="260" height="350">
					</div>
					<div class="staff-preview-card__content">
						<span class="staff-preview-card__title"><?= $arItem["NAME"] ?></span>
						<span class="staff-preview-card__desc"><?= $arItem["PREVIEW_TEXT"] ?></span>
					</div>
				</a>

			<? endforeach; ?>
		</div>

		<? if (count($staffPreviewItems) > 6): ?>
			<button class="lw-main-btn team-preview-grid__btn hidden" type="button">Еще</button>
		<? endif; ?>
	</section>
<? endif; ?>