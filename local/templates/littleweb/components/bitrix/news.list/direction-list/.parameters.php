<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$arTemplateParameters = [
	"SECTION_TITLE" => [
		"PARENT" => "BASE",
		"NAME" => "Заголовок раздела",
		"TYPE" => "STRING",
		"DEFAULT" => "Основные направления:",
	],
	"SECTION_TEXT" => [
		"PARENT" => "BASE",
		"NAME" => "Описание раздела",
		"TYPE" => "STRING",
		"DEFAULT" => "",
	],
];
