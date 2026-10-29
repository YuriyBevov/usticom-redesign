<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

if (($arParams["IS_SECTION"] ?? "N") === "Y") {
	if (empty($arResult["SECTION"]["ID"]) || (int)$arResult["SECTION"]["DEPTH_LEVEL"] !== 1) {
		\Bitrix\Iblock\Component\Tools::process404("Раздел не найден", true, true, true);
		return;
	}
	$APPLICATION->SetTitle($arResult["SECTION"]["~NAME"] ?? $arResult["SECTION"]["NAME"]);
	$service = $arResult['SERVICE_SECTION_LINK'] ?? null;
	if ($service) {
		$APPLICATION->AddChainItem($service['NAME'], $service['URL']);
		$APPLICATION->AddChainItem('Стоимость', '');
	} else {
		$APPLICATION->AddChainItem($arResult['SECTION']['~NAME'] ?? $arResult['SECTION']['NAME'], '');
	}
}
