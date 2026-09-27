<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Контакты компании Юстиком: телефон, почта, адрес и схема проезда.");
$APPLICATION->SetPageProperty("title", "Контакты ООО «ЮСТИКОМ»");
$APPLICATION->SetTitle("Контакты");
?>

<section class="section section--bg-initial contacts">
	<div class="container">
		<div class="contacts__layout" itemscope itemtype="https://schema.org/Organization">
			<meta itemprop="name" content="Юстиком">
			<div class="contacts__content">
				<? $APPLICATION->IncludeFile(
					SITE_TEMPLATE_PATH . '/include/section-header.php',
					[
						'EYEBROW_TEXT' => 'наши контакты',
						'TITLE' => 'Наши контакты',
						'TEXT' => 'Мы всегда на связи и готовы ответить на ваши вопросы. Выберите удобный способ связи — наши специалисты проконсультируют и помогут найти оптимальное решение.',
						'HEADING_TAG' => 'h1',
					],
					['MODE' => 'html', 'NAME' => 'Шапка раздела', 'SHOW_BORDER' => false]
				); ?>

				<div class="contacts__cards">
					<div class="contacts__card">
						<div class="contacts__card-heading">
							<svg width="24" height="24" aria-hidden="true">
								<use href="<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-phone"></use>
							</svg>
							<span>Телефон</span>
						</div>
						<div class="contacts__card-value" itemprop="telephone">
							<? $APPLICATION->IncludeFile(SITE_DIR . 'include/phone.php', [], ['MODE' => 'html', 'NAME' => 'Телефон', 'SHOW_BORDER' => true]); ?>
						</div>
						<span class="contacts__card-note">
							<? $APPLICATION->IncludeFile(SITE_DIR . 'include/worktime.php', [], ['MODE' => 'html', 'NAME' => 'Время работы', 'SHOW_BORDER' => true]); ?>
						</span>
						<a class="contacts__card-action" href="tel:+74952879268">
							<span>Заказать звонок</span>
							<svg width="24" height="24" aria-hidden="true">
								<use href="<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-arrow"></use>
							</svg>
						</a>
					</div>

					<div class="contacts__card">
						<div class="contacts__card-heading">
							<svg width="24" height="24" viewBox="0 0 24 24" aria-hidden="true">
								<use href="<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-mail"></use>
							</svg>
							<span>Почта</span>
						</div>
						<div class="contacts__card-value" itemprop="email">
							<? $APPLICATION->IncludeFile(SITE_DIR . 'include/mail.php', [], ['MODE' => 'html', 'NAME' => 'Почта', 'SHOW_BORDER' => true]); ?>
						</div>
						<span class="contacts__card-note">Ответим в течение 1 часа</span>
						<a class="contacts__card-action" href="tel:+74952879268">
							<span>Заказать звонок</span>
							<svg width="24" height="24" aria-hidden="true">
								<use href="<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-arrow"></use>
							</svg>
						</a>
					</div>

					<div class="contacts__card" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
						<div class="contacts__card-heading">
							<svg width="24" height="24" viewBox="0 0 24 24" aria-hidden="true">
								<use href="<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-pin"></use>
							</svg>
							<span>Адрес</span>
						</div>
						<address class="contacts__card-value" itemprop="streetAddress">
							<? $APPLICATION->IncludeFile(SITE_DIR . 'include/address.php', [], ['MODE' => 'html', 'NAME' => 'Адрес', 'SHOW_BORDER' => true]); ?>
						</address>
						<a class="contacts__card-action" href="#contacts-map">
							<span>Показать на карте</span>
							<svg width="24" height="24" aria-hidden="true">
								<use href="<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-arrow"></use>
							</svg>
						</a>
					</div>

					<div class="contacts__card">
						<div class="contacts__card-heading">
							<svg width="24" height="24" viewBox="0 0 24 24" aria-hidden="true">
								<use href="<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-chat"></use>
							</svg>
							<span>Мессенджеры</span>
						</div>
						<? $APPLICATION->IncludeComponent(
							'bitrix:news.list',
							'social-list',
							[
								'IBLOCK_TYPE' => 'usticom_site_content',
								'IBLOCK_ID' => '15',
								'NEWS_COUNT' => '10',
								'SORT_BY1' => 'SORT',
								'SORT_ORDER1' => 'ASC',
								'PROPERTY_CODE' => ['SOCIAL_LINK', 'SOCIAL_ICON'],
								'CACHE_TYPE' => 'A',
								'CACHE_TIME' => '36000000',
								'SET_TITLE' => 'N',
							],
							false,
							['HIDE_ICONS' => 'Y']
						); ?>
						<a class="contacts__card-action" href="mailto:call@usticom.ru">
							<span>Получить консультацию</span>
							<svg width="24" height="24" aria-hidden="true">
								<use href="<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-arrow"></use>
							</svg>
						</a>
					</div>
				</div>
			</div>

			<div class="contacts__map" id="contacts-map" aria-label="Карта проезда к офису Юстиком">
				<? $APPLICATION->IncludeComponent(
					'bitrix:map.yandex.view',
					'.default',
					[
						'API_KEY' => 'da2d4ab1-2c85-4275-8fc9-603be858bd7f',
						'CONTROLS' => ['ZOOM'],
						'INIT_MAP_TYPE' => 'MAP',
						'MAP_DATA' => 'a:4:{s:10:"yandex_lat";d:55.80501085102666;s:10:"yandex_lon";d:37.617813271163996;s:12:"yandex_scale";i:17;s:10:"PLACEMARKS";a:1:{i:0;a:3:{s:3:"LON";d:37.617813271164;s:3:"LAT";d:55.80501085104;s:4:"TEXT";s:25:"ООО «Юстиком»";}}}',
						'MAP_HEIGHT' => '568',
						'MAP_ID' => 'usticom-map',
						'MAP_WIDTH' => '100%',
						'OPTIONS' => ['ENABLE_SCROLL_ZOOM', 'ENABLE_DBLCLICK_ZOOM'],
					],
					false
				); ?>
			</div>
		</div>
	</div>
</section>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>