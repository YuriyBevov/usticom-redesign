<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
$APPLICATION->AddHeadString('<meta property="og:image" content="https://' .  $_SERVER["SERVER_NAME"] . $arResult["DETAIL_PICTURE"]["SRC"] . '"/>', true);
?>

<section class="section news-detail">
	<div class="container">
		<div class="news-detail__header">
			<div class="news-detail__header-section">

				<? if (!empty($arResult["PROPERTIES"]["TAG_LIST"]["VALUE"])): ?>
					<div class="swiper tag-slider news-detail__tag-slider">
						<ul class="swiper-wrapper tag-list tag-list--slider">
							<? foreach ((array)$arResult["PROPERTIES"]["TAG_LIST"]["VALUE"] as $arTag): ?>
								<li class="swiper-slide tag-list__item">
									<a href="<?= htmlspecialcharsbx($arResult["LIST_PAGE_URL"] . '?tag=' . rawurlencode($arTag)) ?>">#<?= htmlspecialcharsbx($arTag) ?></a>
								</li>
							<? endforeach; ?>
						</ul>
					</div>
				<? endif; ?>
				<h1><?= $arResult["NAME"] ?></h1>
				<button class="main-btn">Получить консультацию</button>
			</div>
			<div class="news-detail__header-section">
				<img src="<?= $arResult["DETAIL_PICTURE"]["SRC"] ?>" alt="<?= $arResult["NAME"] ?>" width="680" height="340">
			</div>
		</div>
		<div class="news-detail__content content-block">
			<?= $arResult["DETAIL_TEXT"] ?>
		</div>
	</div>
</section>

linked-news

linked-services