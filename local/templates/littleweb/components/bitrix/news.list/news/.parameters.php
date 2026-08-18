<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arTemplateParameters = array(
	"EYEBROW_TEXT" => array(
		"PARENT" => "BASE",
		"NAME" => "Текст над заголовком",
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
	"USE_SWIPER_NAVIGATION" => array(
		"PARENT" => "BASE",
		"NAME" => "Использовать навигацию в слайдере",
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"USE_SWIPER_PAGINATION" => array(
		"PARENT" => "BASE",
		"NAME" => "Использовать пагинацию в слайдере",
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
	"SHOW_ACTIVE_FROM" => array(
		"PARENT" => "BASE",
		"NAME" => "Показывать дату создания",
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
	"SHOW_TAG_LIST" => array(
		"PARENT" => "BASE",
		"NAME" => "Показывать теги",
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
);
