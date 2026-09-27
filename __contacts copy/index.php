<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Контактные данные ООО «ЮСТИКОМ» 125055 г. Москва, БЦ «Бейкер Плаза», ул. Бутырский Вал, д. 68/70, стр. 1, офис 21 Ближайшие станции метро: м. Савёловская, м. Менделевская");
$APPLICATION->SetPageProperty("title", "Контакты ООО «ЮСТИКОМ»");
$APPLICATION->SetTitle("Контакты");
?>

<?/*$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
	"AREA_FILE_SHOW" => "file",
		"PATH" => SITE_TEMPLATE_PATH."/include/contacts-page-header.php"
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "Y"
	)
);*/?>

<?$APPLICATION->IncludeComponent(
	"bitrix:breadcrumb", 
	"lw-breadcrumbs", 
	array(
		"PATH" => "",
		"SITE_ID" => "s1",
		"START_FROM" => "0",
		"COMPONENT_TEMPLATE" => "lw-breadcrumbs"
	),
	false
);?>

<section class="lw-section contacts">
	<div class="lw-container-thin"><h1 class="lw-page-title">Контакты </h1></div>
	<div class="lw-container-thin contacts__container">
			<div class="contacts__item">
				<?$APPLICATION->IncludeComponent(
					"bitrix:map.yandex.view", 
					".default", 
					array(
						"API_KEY" => "da2d4ab1-2c85-4275-8fc9-603be858bd7f",
						"COMPONENT_TEMPLATE" => ".default",
						"CONTROLS" => array(
							0 => "ZOOM",
						),
						"INIT_MAP_TYPE" => "MAP",
						"MAP_DATA" => "a:4:{s:10:\"yandex_lat\";d:55.80501085102666;s:10:\"yandex_lon\";d:37.617813271163996;s:12:\"yandex_scale\";i:17;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:3:\"LON\";d:37.617813271164;s:3:\"LAT\";d:55.80501085104;s:4:\"TEXT\";s:25:\"ООО «Юстиком»\";}}}",
						"MAP_HEIGHT" => "500",
						"MAP_ID" => "usticom-map",
						"MAP_WIDTH" => "100%",
						"OPTIONS" => array(
							0 => "ENABLE_SCROLL_ZOOM",
							1 => "ENABLE_DBLCLICK_ZOOM",
						)
					),
					false
				);?>
			</div>
			<div class="contacts__item">
				<div class="contacts__info" itemscope itemtype="https://schema.org/Organization">
					<span style="display:none;" itemprop="name">Юстиком</span> 
					<div class="contacts__info-section" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
						<span class="lw-subtitle lw-accent-title">Адрес:</span>

						<address>
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
								"AREA_FILE_SHOW" => "file",
									"PATH" => SITE_TEMPLATE_PATH."/include/company-info/address.php"
								),
								false,
								array(
								"ACTIVE_COMPONENT" => "Y"
								)
							);?>
						</address>

						<span class="contacts__info-section-value">
							<span class="lw-accent-color">Метро:</span> 
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
								"AREA_FILE_SHOW" => "file",
									"PATH" => SITE_TEMPLATE_PATH."/include/company-info/metro.php"
								),
								false,
								array(
								"ACTIVE_COMPONENT" => "Y"
								)
							);?>
						</span>
					</div>

					<div class="contacts__info-section">
						<span class="lw-subtitle lw-accent-title">Режим работы</span>

						<span class="contacts__info-section-value">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
								"AREA_FILE_SHOW" => "file",
									"PATH" => SITE_TEMPLATE_PATH."/include/company-info/worktime.php"
								),
								false,
								array(
								"ACTIVE_COMPONENT" => "Y"
								)
							);?>
						</span>
					</div>

					<div class="contacts__info-section contacts__info-section--row">
						<div class="contacts__info-section">
							<span class="lw-subtitle lw-accent-title">Телефон</span>

							<span class="contacts__info-section-value" itemprop="telephone">
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
									"AREA_FILE_SHOW" => "file",
										"PATH" => SITE_TEMPLATE_PATH."/include/company-info/main-phone.php"
									),
									false,
									array(
									"ACTIVE_COMPONENT" => "Y"
									)
								);?>
							</span>
						</div>

						<div class="contacts__info-section">
							<span class="lw-subtitle lw-accent-title">E-mail</span>
							<span class="contacts__info-section-value" itemprop="email">
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
									"AREA_FILE_SHOW" => "file",
										"PATH" => SITE_TEMPLATE_PATH."/include/company-info/mail.php"
									),
									false,
									array(
									"ACTIVE_COMPONENT" => "Y"
									)
								);?>
							</span>
						</div>
					</div>
				</div>
			</div>
	</div>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>