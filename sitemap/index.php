<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Карта сайта. Юридические услуги от компании Юстиком. Звоните: ☎ +7 (495) 287-92-68.");
$APPLICATION->SetTitle("Карта сайта компании Юстиком");
?><?$APPLICATION->IncludeComponent(
	"bitrix:breadcrumb",
	"lw-breadcrumbs",
	Array(
		"COMPONENT_TEMPLATE" => "",
		"PATH" => "",
		"SITE_ID" => "s1",
		"START_FROM" => "0"
	)
);?>
<div class="lw-container-thin info-block info-block--wide">
	<div class="info-block__content">
		<section class="content-section lw-section-top-offset">
			<div class="section-header">
				<h1 class="lw-accent-title lw-section-title">Карта сайта</h1>
			</div>
			<?$APPLICATION->IncludeComponent(
				"bitrix:main.map", 
				"lw-site-map", 
				array(
					"CACHE_TIME" => "3600",
					"CACHE_TYPE" => "A",
					"COL_NUM" => "1",
					"LEVEL" => "3",
					"SET_TITLE" => "N",
					"SHOW_DESCRIPTION" => "N",
					"COMPONENT_TEMPLATE" => "lw-site-map"
				),
				false
			);?>
		</section>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>