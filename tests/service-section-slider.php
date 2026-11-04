<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
define('B_PROLOG_INCLUDED', true);
define('SITE_TEMPLATE_PATH', '/local/templates/littleweb');
set_error_handler(static function ($severity, $message, $file, $line) { throw new ErrorException($message, 0, $severity, $file, $line); });
function check(bool $condition, string $message): void {
    if (!$condition) throw new RuntimeException($message);
}
function getLinkedServicePriceSection(...$args) { return null; }
function includeHeroTizzers(...$args): void {}
function htmlspecialcharsbx($value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
class CIBlock { public static function GetArrayByID(...$args): string { return ''; } }
class SliderApplication {
    public array $calls = [];
    public function IncludeComponent($name, $template, $params, ...$args): void {
        if ($template !== 'related-slider') return;
        $this->calls[] = ['params' => $params, 'filter' => $GLOBALS[$params['FILTER_NAME']]];
        echo '<section data-section-slider></section>';
    }
}
class SectionTemplate {
    public function setFrameMode(...$args): void {}
    public function AddEditAction(...$args): void {}
    public function AddDeleteAction(...$args): void {}
    public function GetEditAreaId($id): string { return 'item-' . $id; }
    public function render(array $items, string $description = '<p>Подробное описание</p>'): array {
        global $APPLICATION;
        $arResult = ['ID' => 585, 'NAME' => 'Раздел', 'ITEMS' => $items, '~DESCRIPTION' => $description, 'DESCRIPTION_TYPE' => 'html'];
        $arParams = ['IBLOCK_ID' => 5, 'IBLOCK_TYPE' => 'usticom_site_content', 'CACHE_TYPE' => 'A', 'CACHE_TIME' => 36000000, 'CACHE_GROUPS' => 'Y', 'DETAIL_URL' => '/#SECTION_CODE#/#ELEMENT_CODE#/'];
        $component = null;
        $base = __DIR__ . '/../local/templates/littleweb/components/bitrix/catalog/servises/bitrix/catalog.section/servises/';
        include $base . 'result_modifier.php';
        ob_start(); include $base . 'template.php'; $html = ob_get_clean();
        return [$arResult, $html];
    }
}
function item(int $id, array $flag = []): array {
    return ['ID' => $id, 'IBLOCK_ID' => 5, 'NAME' => 'Услуга ' . $id, 'DETAIL_PAGE_URL' => '/service-' . $id . '/', 'PROPERTIES' => ['SHOW_IN_SLIDER_ON_LISTING_PAGE' => $flag, 'SERVICE_PRICE_FROM' => ['VALUE' => '55000']]];
}
$APPLICATION = new SliderApplication();
$renderer = new SectionTemplate();
[$result, $html] = $renderer->render([item(1), item(2, ['VALUE_XML_ID' => 'Y', 'VALUE' => 'Да']), item(3, ['VALUE_XML_ID' => 'N', 'VALUE' => 'Y']), item(4, ['VALUE_XML_ID' => '']), item(5, ['VALUE_XML_ID' => ['N', 'Y']])]);
check(array_column($result['GRID_ITEMS'], 'ID') === [1, 3, 4] && $result['SLIDER_SERVICE_IDS'] === [2, 5], 'Услуги нужно делить по XML_ID значения списка, сохраняя порядок');
check($result['GRID_ITEMS'][0]['PRICE_TEXT'] === 'от 55 000 ₽', 'Цены основной плитки должны сохраняться');
foreach ([1, 3, 4] as $id) check(str_contains($html, 'id="item-' . $id . '"'), 'Неотмеченная услуга должна остаться в плитке');
foreach ([2, 5] as $id) check(!str_contains($html, 'id="item-' . $id . '"'), 'Услуга слайдера не должна дублироваться в плитке');
check(count($APPLICATION->calls) === 1, 'Нужен один слайдер');
$call = $APPLICATION->calls[0];
check($call['filter'] === ['ID' => [2, 5]] && $call['params']['NEWS_COUNT'] === 2, 'В слайдер можно передать только выбранные услуги текущего раздела');
check($call['params']['EYEBROW_TEXT'] === 'Больше услуг' && $call['params']['SECTION_TITLE'] === 'Другие услуги раздела', 'Нужны заданные бровь и заголовок');
check($call['params']['CACHE_FILTER'] === 'Y' && $call['params']['CACHE_GROUPS'] === 'Y' && $call['params']['CHECK_DATES'] === 'Y', 'Нужно сохранить корректный кеш, права и даты активности');
check(strpos($html, 'service-section-description') < strpos($html, 'data-section-slider'), 'Слайдер должен находиться под подробным описанием');
check(!isset($GLOBALS[$call['params']['FILTER_NAME']]), 'Фильтр слайдера должен очищаться');
$APPLICATION->calls = [];
[$result, $html] = $renderer->render([item(1)]);
check(!$APPLICATION->calls && !str_contains($html, 'data-section-slider'), 'При пустом выборе не должно быть пустого слайдера');
[$result, $html] = $renderer->render([item(2, ['VALUE_XML_ID' => 'Y'])], '');
check(!str_contains($html, 'service-section__grid') && str_contains($html, 'data-section-slider'), 'При всех отмеченных услугах плитка скрывается, а слайдер работает и без описания');
echo "OK: разделение плитки и слайдера по XML_ID=Y, цены, заголовки, положение блока, кеш и пустые состояния\n";
