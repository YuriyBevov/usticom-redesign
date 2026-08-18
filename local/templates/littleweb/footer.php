<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
</main>

<footer class="footer">
	<div class="footer__top">
		<div class="container">
			<div class="footer__top-section">
				<? $APPLICATION->IncludeFile(
					SITE_TEMPLATE_PATH . '/include/logo.php',
					array(),
					array('MODE' => 'html', 'NAME' => 'логотип', 'SHOW_BORDER' => false)
				); ?>

				<small>© 2003—<?= Date("Y") ?> Юстиком.</small>
				<small>Все права защищены.</small>
			</div>
			<div class="footer__top-section">

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
						"DIRECTION" => "row"
					],
					false
				); ?>

				<div class="footer__top-section-column">
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
				</div>
				<div class="footer__top-section-column">
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
				</div>
				<div class="footer__top-section-column">
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
			</div>
			<div class="footer__top-section">
				<button class="main-btn">Оставить заявку</button>
				Контакты
				Соцсети
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