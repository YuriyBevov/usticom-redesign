<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!function_exists('createMenuArray')) {
    function createMenuArray(&$res, &$menuItems, $arParent, $depthLevel)
    {
        foreach ($arParent as $item) {

            $isParent = ($item['IS_SECTION'] && isset($menuItems[$item['ID']]));

            // Формируем название для меню
            $menuTitle = $item['NAME'];
            if (!empty($item['TOP_MENU_TITLE'])) {
                $menuTitle = $item['TOP_MENU_TITLE'];
            }

            $res[] = array(
                htmlspecialchars($menuTitle), // Используем альтернативный заголовок если есть
                $item['LINK'],
                array(), //массив доп ссылок
                array(
                    'FROM_IBLOCK' => true,
                    'IS_PARENT' => $isParent,
                    'DEPTH_LEVEL' => $depthLevel,
                    'ORIGINAL_NAME' => $item['NAME'], // оригинальное название (для отладки)
                    'TOP_MENU_TITLE' => $item['TOP_MENU_TITLE'] ?? '', // альтернативный заголовок
                ),
            );
            if ($isParent) {
                createMenuArray($res, $menuItems, $menuItems[$item['ID']], $depthLevel + 1);
            }
        }
    }
}

if (!isset($arParams['CACHE_TIME']))
    $arParams['CACHE_TIME'] = 36000000;

$arParams['IBLOCK_ID'] = intval($arParams['IBLOCK_ID']);

$arParams['DEPTH_LEVEL'] = intval($arParams['DEPTH_LEVEL']);
if ($arParams['DEPTH_LEVEL'] <= 0)
    $arParams['DEPTH_LEVEL'] = 1;

if ($this->StartResultCache()) {
    CModule::IncludeModule('iblock');
    $arSectionId = array();
    $arFilter = array(
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'GLOBAL_ACTIVE' => 'Y',
        'ACTIVE' => 'Y',
        '<=DEPTH_LEVEL' => $arParams['DEPTH_LEVEL'],
    );
    $arOrder = array(
        'SORT' => 'ASC',
    );
    $rsSections = CIBlockSection::GetList($arOrder, $arFilter, false, array(
        'ID',
        'DEPTH_LEVEL',
        'NAME',
        'SECTION_PAGE_URL',
        'IBLOCK_SECTION_ID',
    ));
    $menuItems = array();
    while ($arSection = $rsSections->GetNext()) {
        $arSection['IS_SECTION'] = 1;
        $arSection['LINK'] = $arSection['SECTION_PAGE_URL'];

        // Для разделов тоже можно добавить альтернативный заголовок
        // если есть пользовательское поле UF_TOP_MENU_TITLE
        // $arSection['TOP_MENU_TITLE'] = $arSection['UF_TOP_MENU_TITLE'] ?? '';

        if ($arSection['IBLOCK_SECTION_ID']) {
            $menuItems[$arSection['IBLOCK_SECTION_ID']][] = $arSection;
        } else {
            $menuItems['ROOT'][] = $arSection;
        }
        $arSectionId[] = $arSection['ID'];
    }

    //Получим элементы
    $arSelect = array('ID', 'NAME', 'DETAIL_PAGE_URL', 'IBLOCK_SECTION_ID');
    $arFilter = array(
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'ACTIVE' => 'Y',
        array(
            'LOGIC' => 'OR',
            array('SECTION_ID' => $arSectionId),
            array('SECTION_ID' => false),
        ),
    );
    $arOrder = array('SORT' => 'ASC');
    $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        $arProps = $ob->GetProperties();

        // Фильтрация: пропускаем элементы, у которых HIDE_ON_TOP_MENU = 'Y'
        $hideOnTopMenu = $arProps['HIDE_ON_TOP_MENU']['VALUE_XML_ID'] ?? '';

        if ($hideOnTopMenu == 'Y') {
            continue; // Пропускаем этот элемент
        }

        $arFields['IS_SECTION'] = 0;
        $arFields['LINK'] = $arFields['DETAIL_PAGE_URL'];

        // Добавляем альтернативный заголовок если есть
        if (!empty($arProps['TOP_MENU_TITLE']['VALUE'])) {
            $arFields['TOP_MENU_TITLE'] = $arProps['TOP_MENU_TITLE']['VALUE'];
        }

        if ($arFields['IBLOCK_SECTION_ID']) {
            $menuItems[$arFields['IBLOCK_SECTION_ID']][] = $arFields;
        } else {
            $menuItems['ROOT'][] = $arFields;
        }
    }

    //Рекурсивно сформируем итоговый массив для меню
    $arResult = array();
    createMenuArray($arResult, $menuItems, $menuItems['ROOT'], 1);
    $this->EndResultCache();
}
return $arResult;
