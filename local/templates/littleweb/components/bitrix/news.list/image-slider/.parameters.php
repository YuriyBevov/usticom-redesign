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
	"USE_FANCY" => array(
		"PARENT" => "BASE",
		"NAME" => "Показывать увеличенную копию изображения при клике",
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
	"USE_AUTO_FILL_MODE" => array(
		"PARENT" => "BASE",
		"NAME" => "Заполнять слайды по ширине изображения",
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
);
