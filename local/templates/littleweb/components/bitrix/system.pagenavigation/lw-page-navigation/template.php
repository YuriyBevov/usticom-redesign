<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

if (!$arResult["NavShowAlways"]) {
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
		return;
}

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"] . "&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?" . $arResult["NavQueryString"] : "");

$currentPage = (int) $arResult["NavPageNomer"];
$pageCount = (int) $arResult["NavPageCount"];
$paginationPages = [];

if ($pageCount <= 5) {
	$paginationPages = range(1, $pageCount);
} elseif ($currentPage <= 2) {
	$paginationPages = [1, 2, 3, 'dots-end', $pageCount];
} elseif ($currentPage >= $pageCount - 1) {
	$paginationPages = [1, 'dots-start', $pageCount - 2, $pageCount - 1, $pageCount];
} else {
	$paginationPages = [1];

	if ($currentPage - 1 > 2) {
		$paginationPages[] = 'dots-start';
	}

	$paginationPages[] = $currentPage - 1;
	$paginationPages[] = $currentPage;
	$paginationPages[] = $currentPage + 1;

	if ($currentPage + 1 < $pageCount - 1) {
		$paginationPages[] = 'dots-end';
	}

	$paginationPages[] = $pageCount;
}
?>
<? if (!$arResult["NavShowAll"]): ?>
	<div class="pagination-container">
		<div class="pagination">

			<? if ($arResult["NavPageNomer"] > 1): ?>
				<? if ($arResult["bSavePage"]): ?>
					<a
						href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] - 1) ?>"
						class="pagination__btn pagination__btn--prev">

						<svg width="72" height="24" role="img" aria-hidden="true" focusable="false">
							<use xlink:href="<?= SITE_TEMPLATE_PATH . '/_dist/sprite.svg#icon-arrow' ?>"></use>
						</svg>
					</a>
				<? else: ?>
					<? if ($arResult["NavPageNomer"] > 2): ?>
						<a
							href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] - 1) ?>"
							class="pagination__btn pagination__btn--prev">
							<svg width="72" height="24" role="img" aria-hidden="true" focusable="false">
								<use xlink:href="<?= SITE_TEMPLATE_PATH . '/_dist/sprite.svg#icon-arrow' ?>"></use>
							</svg>
						</a>
					<? else: ?>
						<a href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>" class="pagination__btn pagination__btn--prev">
							<svg width="72" height="24" role="img" aria-hidden="true" focusable="false">
								<use xlink:href="<?= SITE_TEMPLATE_PATH . '/_dist/sprite.svg#icon-arrow' ?>"></use>
							</svg>
						</a>
					<? endif ?>
				<? endif ?>
			<? else: ?>
				<span class="pagination__btn pagination__btn--prev pagination__btn--disabled">
					<svg width="72" height="24" role="img" aria-hidden="true" focusable="false">
						<use xlink:href="<?= SITE_TEMPLATE_PATH . '/_dist/sprite.svg#icon-arrow' ?>"></use>
					</svg>
				</span>
			<? endif ?>

			<? foreach ($paginationPages as $paginationPage): ?>
				<? if (!is_numeric($paginationPage)): ?>
					<span class="pagination__btn pagination__btn--dots">...</span>
				<? elseif ((int) $paginationPage === $currentPage): ?>
					<span class="pagination__btn pagination__btn--selected"><?= $paginationPage ?></span>
				<? elseif ((int) $paginationPage === 1 && $arResult["bSavePage"] == false): ?>
					<a class="pagination__btn" href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>"><?= $paginationPage ?></a>
				<? else: ?>
					<a class="pagination__btn" href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $paginationPage ?>"><?= $paginationPage ?></a>
				<? endif ?>
			<? endforeach ?>

			<? if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]): ?>
				<a
					href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] + 1) ?>"
					class="pagination__btn pagination__btn--next">
					<svg width="72" height="24" role="img" aria-hidden="true" focusable="false">
						<use xlink:href="<?= SITE_TEMPLATE_PATH . '/_dist/sprite.svg#icon-arrow' ?>"></use>
					</svg>
				</a>
			<? else: ?>
				<span class="pagination__btn pagination__btn--next pagination__btn--disabled">
					<svg width="72" height="24" role="img" aria-hidden="true" focusable="false">
						<use xlink:href="<?= SITE_TEMPLATE_PATH . '/_dist/sprite.svg#icon-arrow' ?>"></use>
					</svg>
				</span>
			<? endif ?>

			<? if ($arResult["bShowAll"]): ?>
				<noindex>
					<? if (!$arResult["NavShowAll"]): ?>
						<a class="pagination-show-all-btn" href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>SHOWALL_<?= $arResult["NavNum"] ?>=1" rel="nofollow">
							Показать все
						</a>
					<? endif ?>
				</noindex>
			<? endif ?>
		</div>
	</div>
<? endif ?>