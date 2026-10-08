<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$arTemplateParameters = [
	"PRICE_PROPERTY_CODE" => [
		"PARENT" => "BASE", "NAME" => "Код свойства со стоимостью тарифа", "TYPE" => "STRING", "DEFAULT" => "PRICE",
	],
	"EYEBROW_TEXT" => [
		"PARENT" => "BASE", "NAME" => "Текст над заголовком", "TYPE" => "STRING", "DEFAULT" => "тарифы на услуги",
	],
	"SECTION_TITLE" => [
		"PARENT" => "BASE", "NAME" => "Заголовок блока", "TYPE" => "STRING", "DEFAULT" => "Стоимость",
	],
	"FEATURED_INDEX" => [
		"PARENT" => "BASE", "NAME" => "Номер выделенного тарифа (0 — не выделять)", "TYPE" => "STRING", "DEFAULT" => "2",
	],
	"BUTTON_TEXT" => [
		"PARENT" => "BASE", "NAME" => "Текст кнопки", "TYPE" => "STRING", "DEFAULT" => "Оставить заявку",
	],
	"BUTTON_URL" => [
		"PARENT" => "BASE", "NAME" => "Ссылка кнопки", "TYPE" => "STRING", "DEFAULT" => "/contacts/",
	],
];
