<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if ($arResult["PROPERTIES"]["PRICES"]["VALUE"]) {

    $sectionId = $arResult["PROPERTIES"]["PRICES"]["VALUE"];
    $iblockId = 22;

    $rsSection = CIBlockSection::GetList(
        [],
        [
            'ID' => $sectionId,
            'IBLOCK_ID' => $iblockId
        ],
        false,
        ['ID', 'IBLOCK_ID', 'NAME', 'UF_*']
    );

    $section = $rsSection->Fetch();

    if (!$section) {
        $UFBeforeValue = '';
        $UFAfterValue = '';
    } else {
        $UFBeforeValue = $section['UF_PRICE_SECTION_TEXT_BEFORE'] ?? '';
        $UFAfterValue = $section['UF_PRICE_SECTION_TEXT_AFTER'] ?? '';
    }

    $arResult['UF_PRICE_SECTION_TEXT_BEFORE'] = $UFBeforeValue;
    $arResult['UF_PRICE_SECTION_TEXT_AFTER'] = $UFAfterValue;

    $arResult["SERVICE_PRICES"] = [];
    $arFilter = array(
        "IBLOCK_ID" => 22,
        "IBLOCK_SECTION_ID" => $arResult["PROPERTIES"]["PRICES"]["VALUE"],
        "ACTIVE" => "Y"
    );
    $res = CIBlockElement::GetList(array(), $arFilter, false, false, array());

    $i = 0;
    while ($ob = $res->GetNextElement()) {
        $arProps = $ob->GetProperties();
        $arFields = $ob->GetFields();

        $arResult["SERVICE_PRICES"][$i] = array(
            "service_name" => $arFields["NAME"],
            "service_price_value" => $arProps["PRICE"]["VALUE"],
            "service_price_note" => $arProps["NOTE"]["~VALUE"],
            "service_link" => $arProps["LINK"]["VALUE"],
        );
        $i++;
    }
}
