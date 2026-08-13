<?
$tagParam = $_GET['tag'] ?? '';
$arResult["CURRENT_TAG"] = $tagParam;

// Получаем все значения свойства TAG_LIST
$dbItems = CIBlockElement::GetList(
  array(),
  array(
    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
    "ACTIVE" => "Y",
    "!PROPERTY_TAG_LIST" => false
  ),
  array("PROPERTY_TAG_LIST"),
  false,
  array("PROPERTY_TAG_LIST")
);

$usedTags = array();
while ($arItem = $dbItems->Fetch()) {
  if (!empty($arItem['PROPERTY_TAG_LIST_VALUE'])) {
    $usedTags[] = $arItem['PROPERTY_TAG_LIST_VALUE'];
  }
}

// Убираем дубликаты
$usedTags = array_unique($usedTags);

// Сортируем значения
sort($usedTags);

if (!empty($usedTags)) {
  $arResult["TAG_LIST"] = $usedTags;
}

switch ($arParams["IBLOCK_ID"]) {
  case '6':
    $arResult["LIST_PAGE_TITLE"] = 'Все новости';
    break;
  case '7':
    $arResult["LIST_PAGE_TITLE"] = 'Все мероприятия';
    break;
  case '8':
    $arResult["LIST_PAGE_TITLE"] = 'Все публикации';
    break;

  default:
    $arResult["LIST_PAGE_TITLE"] = 'Все теги';
    break;
}
