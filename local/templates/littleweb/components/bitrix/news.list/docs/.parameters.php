<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$arTemplateParameters = [
	"EYEBROW_TEXT" => [
		"PARENT" => "BASE",
		"NAME" => "Текст над заголовком",
		"TYPE" => "STRING",
		"DEFAULT" => "документы",
	],
	"SECTION_TITLE" => [
		"PARENT" => "BASE",
		"NAME" => "Заголовок блока",
		"TYPE" => "STRING",
		"DEFAULT" => "Документы для клиентов",
	],
	"SECTION_TEXT" => [
		"PARENT" => "BASE",
		"NAME" => "Текст под заголовком",
		"TYPE" => "STRING",
		"DEFAULT" => "",
	],
	"FILE_PROPERTY_CODE" => [
		"PARENT" => "BASE",
		"NAME" => "Код свойства с файлом документа",
		"TYPE" => "STRING",
		"DEFAULT" => "FILE",
	],
];
