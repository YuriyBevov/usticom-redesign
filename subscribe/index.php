<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
?>
<?/*$APPLICATION->IncludeComponent(
	"bitrix:subscribe.index", 
	".default", 
	array(
		"SHOW_COUNT" => "Y",
		"SHOW_HIDDEN" => "Y",
		"PAGE" => "#SITE_DIR#subscribe/edit.php",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"SET_TITLE" => "Y",
		"COMPONENT_TEMPLATE" => ".default",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"ALLOW_ANONYMOUS" => "Y",
		"SHOW_AUTH_LINKS" => "N"
	),
	false
);*/ ?>

<? $APPLICATION->IncludeComponent(
	"bitrix:subscribe.edit",
	"lw-subscribe-edit",
	array(
		"AJAX_MODE" => "N",
		"SHOW_HIDDEN" => "Y",
		"ALLOW_ANONYMOUS" => "Y",
		"SHOW_AUTH_LINKS" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"SET_TITLE" => "Y",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"COMPONENT_TEMPLATE" => "lw-subscribe-edit",
		"AJAX_OPTION_ADDITIONAL" => ""
	),
	false
); ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>