<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)): ?>
	<nav class="main-nav">
		<div class="main-nav__header">
			<a class="nav-logo" href="/" aria-label="https://www.usticom.ru">
				<img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/nav-logo.svg" alt="Юстиком" title="Юстиком" width="24" height="28">
			</a>

			<button class="main-nav__closer" type="button" aria-label="Закрыть меню">
				<svg width="24" height="24">
					<use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/sprite.svg#icon-burger"></use>
				</svg>
			</button>
		</div>

		<ul class="main-nav__list">

			<?
			$previousLevel = 0;
			$index = 0;
			$max_menu_elements_count = 7; // Максимальное кол-во элементов в первом выпадающем меню
			foreach ($arResult as $arItem): ?>
				<? if ($USER->isAdmin()) {
					// debug($arItem);
				} ?>

				<? if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel): ?>
					<?= str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"])); ?>
				<? endif ?>

				<? if ($arItem["IS_PARENT"]): ?>
					<? if ($arItem["DEPTH_LEVEL"] == 1): ?>
						<li class="main-nav__list-item has-inner <?= $arItem["PARAMS"]["class"] ?>">
							<a href="<?= $arItem["LINK"] ?>">
								<?= $arItem["TEXT"] ?>

								<svg width="24" height="24">
									<use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/sprite.svg#icon-small-down"></use>
								</svg>
							</a>
							<div class="main-nav__inner-list-wrapper">
								<div class="lw-container">
									<ul class="main-nav__inner-list">
										<li class="main-nav__inner-list-item main-nav__inner-list-item--mobile-only main-nav__inner-list-item--all">
											<a href="<?= $arItem["LINK"] ?>">Весь раздел</a>
										</li>
									<? else:
									$index += 1;
									?>
										<li class="
							main-nav__inner-list-item
							has-inner
							<?= ($index == $max_menu_elements_count ? 'main-nav__inner-list-item--laptop-hide' : null) ?>
							<?= ($index > $max_menu_elements_count ? 'main-nav__inner-list-item--mobile-only' : null) ?>
						">
											<a href="<?= $arItem["LINK"] ?>">
												<?= str_replace('услуги', '', $arItem["TEXT"]) ?>
												<svg width="24" height="24">
													<use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/sprite.svg#icon-small-down"></use>
												</svg>
											</a>
											<div class="main-nav__inner-list-wrapper">
												<div class="lw-container">
													<ul class="main-nav__inner-list">
														<li class="main-nav__inner-list-item main-nav__inner-list-item--mobile-only main-nav__inner-list-item--all">
															<a href="<?= $arItem["LINK"] ?>">Весь раздел</a>
														</li>
													<? endif ?>

												<? else: ?>
													<? if ($arItem["DEPTH_LEVEL"] == 1): ?>

														<li class="main-nav__list-item <?= $arItem["PARAMS"]["class"] ?>">
															<a href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?></a>
														</li>
													<? else: ?>
														<li class="main-nav__inner-list-item"><a href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?></a></li>
													<? endif ?>
												<? endif ?>

												<? $previousLevel = $arItem["DEPTH_LEVEL"]; ?>
											<? endforeach ?>

											<? if ($previousLevel > 1): //close last item tags
											?>
												<?= str_repeat("</ul></div></div></li>", ($previousLevel - 1)); ?>
											<? endif ?>
													</ul>
													<div class="main-header__info">
														<div class="lw-iconed-link">
															<svg width="18" height="18">
																<use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/sprite.svg#icon-phone"></use>
															</svg>
															<? $APPLICATION->IncludeComponent(
																"bitrix:main.include",
																"",
																array(
																	"AREA_FILE_SHOW" => "file",
																	"PATH" => SITE_TEMPLATE_PATH . "/include/company-info/main-phone.php"
																),
																false,
																array(
																	"ACTIVE_COMPONENT" => "Y"
																)
															); ?>
														</div>

														<div class="lw-iconed-link">
															<svg width="18" height="18">
																<use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/sprite.svg#icon-mail"></use>
															</svg>
															<? $APPLICATION->IncludeComponent(
																"bitrix:main.include",
																"",
																array(
																	"AREA_FILE_SHOW" => "file",
																	"PATH" => SITE_TEMPLATE_PATH . "/include/company-info/mail.php"
																),
																false,
																array(
																	"ACTIVE_COMPONENT" => "Y"
																)
															); ?>
														</div>
													</div>
													<? $APPLICATION->IncludeComponent(
														"bitrix:news.list",
														"lw-social-list-mobile",
														array(
															"ACTIVE_DATE_FORMAT" => "d.m.Y",
															"ADD_SECTIONS_CHAIN" => "N",
															"AJAX_MODE" => "N",
															"AJAX_OPTION_ADDITIONAL" => "",
															"AJAX_OPTION_HISTORY" => "N",
															"AJAX_OPTION_JUMP" => "N",
															"AJAX_OPTION_STYLE" => "Y",
															"CACHE_FILTER" => "N",
															"CACHE_GROUPS" => "Y",
															"CACHE_TIME" => "36000000",
															"CACHE_TYPE" => "A",
															"CHECK_DATES" => "Y",
															"DETAIL_URL" => "",
															"DISPLAY_BOTTOM_PAGER" => "N",
															"DISPLAY_DATE" => "N",
															"DISPLAY_NAME" => "N",
															"DISPLAY_PICTURE" => "N",
															"DISPLAY_PREVIEW_TEXT" => "N",
															"DISPLAY_TOP_PAGER" => "N",
															"FIELD_CODE" => array(
																0 => "",
																1 => "",
															),
															"FILTER_NAME" => "",
															"HIDE_LINK_WHEN_NO_DETAIL" => "N",
															"IBLOCK_ID" => "15",
															"IBLOCK_TYPE" => "usticom_site_content",
															"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
															"INCLUDE_SUBSECTIONS" => "N",
															"MESSAGE_404" => "",
															"NEWS_COUNT" => "6",
															"PAGER_BASE_LINK_ENABLE" => "N",
															"PAGER_DESC_NUMBERING" => "N",
															"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
															"PAGER_SHOW_ALL" => "N",
															"PAGER_SHOW_ALWAYS" => "N",
															"PAGER_TEMPLATE" => ".default",
															"PAGER_TITLE" => "Новости",
															"PARENT_SECTION" => "",
															"PARENT_SECTION_CODE" => "",
															"PREVIEW_TRUNCATE_LEN" => "",
															"PROPERTY_CODE" => array(
																0 => "SOCIAL_LINK",
																1 => "SOCIAL_TEXT",
																2 => "",
															),
															"SET_BROWSER_TITLE" => "N",
															"SET_LAST_MODIFIED" => "N",
															"SET_META_DESCRIPTION" => "N",
															"SET_META_KEYWORDS" => "N",
															"SET_STATUS_404" => "N",
															"SET_TITLE" => "N",
															"SHOW_404" => "N",
															"SORT_BY1" => "ACTIVE_FROM",
															"SORT_BY2" => "SORT",
															"SORT_ORDER1" => "DESC",
															"SORT_ORDER2" => "ASC",
															"STRICT_SECTION_CHECK" => "N",
															"COMPONENT_TEMPLATE" => "lw-social-list-mobile"
														),
														false
													); ?>
	</nav>
<? endif; ?>

<style>
	@media(min-width: 1025px) {
		.main-nav__inner-list-item--mobile-only {
			display: none !important;
		}
	}

	@media(max-width: 1360px) {
		.main-nav__inner-list-item--laptop-hide {
			display: none !important;
		}
	}
</style>