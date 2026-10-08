<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$filePropertyCode = trim((string)($arParams["FILE_PROPERTY_CODE"] ?? "FILE"));
$arResult["DOCUMENTS"] = [];

foreach ($arResult["ITEMS"] as $item) {
	$fileId = $item["PROPERTIES"][$filePropertyCode]["VALUE"] ?? 0;
	if (is_array($fileId)) {
		$fileId = reset($fileId);
	}
	$file = (int)$fileId > 0 ? CFile::GetFileArray((int)$fileId) : false;
	if (!$file || empty($file["SRC"])) {
		continue;
	}

	$item["DOCUMENT_FILE"] = $file;
	$item["DOCUMENT_EXTENSION"] = strtoupper(pathinfo((string)($file["ORIGINAL_NAME"] ?: $file["FILE_NAME"]), PATHINFO_EXTENSION));
	$arResult["DOCUMENTS"][] = $item;
}
