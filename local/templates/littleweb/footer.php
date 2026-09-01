<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
</main>

<footer class="footer">
	<div class="footer__top">
		<div class="container">
			<div class="footer__top-section footer__top-section--logo-block">
				<? $APPLICATION->IncludeFile(
					SITE_TEMPLATE_PATH . '/include/logo.php',
					array(),
					array('MODE' => 'html', 'NAME' => 'логотип', 'SHOW_BORDER' => false)
				); ?>

				<small>© 2003—<?= Date("Y") ?> Юстиком.<br>Все права защищены.</small>
			</div>
			<div class="footer__top-section footer__top-section--menu-block">
				<? $APPLICATION->IncludeComponent(
					"bitrix:menu",
					"bottom",
					[
						"ROOT_MENU_TYPE" => "bottom_row",
						"MAX_LEVEL" => "1",
						"CHILD_MENU_TYPE" => "left",
						"USE_EXT" => "Y",
						"MENU_CACHE_TYPE" => "A",
						"MENU_CACHE_TIME" => "36000000",
						"MENU_CACHE_USE_GROUPS" => "Y",
						"MENU_CACHE_GET_VARS" => [],
						"COMPONENT_TEMPLATE" => "bottom",
						"DELAY" => "N",
						"ALLOW_MULTI_SELECT" => "N",
					],
					false
				); ?>

				<? $APPLICATION->IncludeComponent(
					"bitrix:menu",
					"bottom",
					[
						"ROOT_MENU_TYPE" => "bottom",
						"MAX_LEVEL" => "1",
						"CHILD_MENU_TYPE" => "left",
						"USE_EXT" => "Y",
						"MENU_CACHE_TYPE" => "A",
						"MENU_CACHE_TIME" => "36000000",
						"MENU_CACHE_USE_GROUPS" => "Y",
						"MENU_CACHE_GET_VARS" => [],
						"COMPONENT_TEMPLATE" => "bottom",
						"DELAY" => "N",
						"ALLOW_MULTI_SELECT" => "N"
					],
					false
				); ?>

				<? $APPLICATION->IncludeComponent(
					"bitrix:menu",
					"bottom",
					[
						"ROOT_MENU_TYPE" => "bottom_left",
						"MAX_LEVEL" => "1",
						"CHILD_MENU_TYPE" => "left",
						"USE_EXT" => "Y",
						"MENU_CACHE_TYPE" => "A",
						"MENU_CACHE_TIME" => "36000000",
						"MENU_CACHE_USE_GROUPS" => "Y",
						"MENU_CACHE_GET_VARS" => [],
						"COMPONENT_TEMPLATE" => "bottom",
						"DELAY" => "N",
						"ALLOW_MULTI_SELECT" => "N"
					],
					false
				); ?>

				<? $APPLICATION->IncludeComponent(
					"bitrix:menu",
					"bottom",
					[
						"ROOT_MENU_TYPE" => "bottom_right",
						"MAX_LEVEL" => "1",
						"CHILD_MENU_TYPE" => "left",
						"USE_EXT" => "Y",
						"MENU_CACHE_TYPE" => "A",
						"MENU_CACHE_TIME" => "36000000",
						"MENU_CACHE_USE_GROUPS" => "Y",
						"MENU_CACHE_GET_VARS" => [],
						"COMPONENT_TEMPLATE" => "bottom",
						"DELAY" => "N",
						"ALLOW_MULTI_SELECT" => "N"
					],
					false
				); ?>
			</div>
			<div class="footer__top-section footer__top-section--contacts-block">
				<button class="main-btn">Оставить заявку</button>
				<div class="footer__contacts">
					<div class="footer__contacts-item">
						<span class="footer__contacts-item-title">
							Номер телефона
						</span>

						<?
						$APPLICATION->IncludeFile(
							SITE_DIR . 'include/phone.php',
							array(),
							array('MODE' => 'html', 'NAME' => 'телефон', 'SHOW_BORDER' => true)
						);
						?>
					</div>

					<div class="footer__contacts-item">
						<span class="footer__contacts-item-title">
							Почта
						</span>

						<?
						$APPLICATION->IncludeFile(
							SITE_DIR . 'include/mail.php',
							array(),
							array('MODE' => 'html', 'NAME' => 'эл. почту', 'SHOW_BORDER' => true)
						);
						?>
					</div>

					<div class="footer__contacts-item">
						<span class="footer__contacts-item-title">
							Расположение
						</span>
						<address>
							<?
							$APPLICATION->IncludeFile(
								SITE_DIR . 'include/address.php',
								array(),
								array('MODE' => 'html', 'NAME' => 'адрес', 'SHOW_BORDER' => true)
							);
							?>
						</address>
					</div>


					<? $APPLICATION->IncludeComponent(
						"bitrix:news.list",
						"social-list",
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
							"NEWS_COUNT" => "10",
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
							"COMPONENT_TEMPLATE" => "social-list"
						),
						false
					); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="footer__bottom">
		<div class="container">
			<a href="/policy-privacy/">Политика конфиденциальности</a>
		</div>

	</div>
</footer>

</body>

</html>