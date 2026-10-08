<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$APPLICATION->IncludeComponent(
	"bitrix:form.result.new",
	"service-request",
	[
		"WEB_FORM_ID" => 1,
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => 0,
		"AJAX_MODE" => "N",
		"USE_EXTENDED_ERRORS" => "Y",
		"SEF_MODE" => "N",
	],
	false,
	["HIDE_ICONS" => "Y"]
);
