<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$arResult["INDUSTRIES_BLOCK"] = [
	"NAME" => "",
	"DESCRIPTION" => "",
	"DESCRIPTION_TYPE" => "text",
	"PICTURE" => null,
];

$iblock = CIBlock::GetByID((int)$arParams["IBLOCK_ID"])->Fetch();
if ($iblock) {
	$arResult["INDUSTRIES_BLOCK"]["NAME"] = trim((string)$iblock["NAME"]);
	$arResult["INDUSTRIES_BLOCK"]["DESCRIPTION"] = trim((string)$iblock["DESCRIPTION"]);
	$arResult["INDUSTRIES_BLOCK"]["DESCRIPTION_TYPE"] = $iblock["DESCRIPTION_TYPE"] === "html" ? "html" : "text";
	if ((int)$iblock["PICTURE"] > 0) {
		$arResult["INDUSTRIES_BLOCK"]["PICTURE"] = CFile::GetFileArray((int)$iblock["PICTURE"]) ?: null;
	}
}
