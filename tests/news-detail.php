<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
define('B_PROLOG_INCLUDED', true);
define('SITE_TEMPLATE_PATH', '/local/templates/littleweb');
$_SERVER['SERVER_NAME'] = 'localhost';

function check(bool $condition, string $message): void {
    if (!$condition) throw new RuntimeException($message);
}
function htmlspecialcharsbx($value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function GetMessage($code): string { return 'Удалить элемент?'; }
class CIBlock {
    public static function GetArrayByID($id, $field): string {
        return $field === 'IBLOCK_TYPE_ID' ? 'usticom_site_content' : '/#SECTION_CODE#/#ELEMENT_CODE#/';
    }
}
class MaterialApplication {
    public array $calls = [];
    public function AddHeadString(...$args): void {}
    public function GetCurDir(): string { return '/news/'; }
    public function IncludeComponent($name, $template, $params, ...$args): void {
        $this->calls[] = ['template' => $template, 'params' => $params, 'filter' => $GLOBALS[$params['FILTER_NAME']]];
    }
    public function IncludeFile($file, $arParams, ...$args): void {
        include __DIR__ . '/../local/templates/littleweb/include/section-header.php';
    }
}
class MaterialTemplate {
    public function setFrameMode($value): void {}
    public function AddEditAction(...$args): void {}
    public function AddDeleteAction(...$args): void {}
    public function GetEditAreaId($id): string { return 'item-' . $id; }
    public function render(string $template, array $arResult, array $arParams): string {
        global $APPLICATION;
        $component = null;
        ob_start();
        include __DIR__ . '/../local/templates/littleweb/components/bitrix/' . $template . '/template.php';
        return ob_get_clean();
    }
}
$APPLICATION = new MaterialApplication();
$template = new MaterialTemplate();
$fixture = ['ID' => 11, 'IBLOCK_ID' => 6, 'NAME' => 'Материал', 'DETAIL_PICTURE' => ['SRC' => '/photo.jpg'], 'DETAIL_TEXT' => '<p>Текст</p>', 'LIST_PAGE_URL' => '/news/', 'PROPERTIES' => []];
$params = ['IBLOCK_TYPE' => 'usticom_site_content', 'DETAIL_URL' => '/news/#ELEMENT_CODE#/', 'IBLOCK_URL' => '/news/'];

foreach ([6 => 'Другие новости', 7 => 'Другие мероприятия', 8 => 'Другие публикации', 28 => 'Другие кейсы', 99 => 'Другие события'] as $iblock => $title) {
    $APPLICATION->calls = [];
    $result = $fixture;
    $result['IBLOCK_ID'] = $iblock;
    $html = $template->render('news.detail/news-detail', $result, $params);
    check(!str_contains($html, 'linked-services'), 'Служебная надпись не должна выводиться');
    check(count($APPLICATION->calls) === 1, 'При отсутствии связей нужен только слайдер других материалов');
    $call = $APPLICATION->calls[0];
    check($call['template'] === 'news-slider' && $call['params']['SECTION_TITLE'] === $title, 'Нужен слайдер с заголовком текущего раздела');
    check($call['params']['IBLOCK_ID'] === $iblock && $call['filter'] === ['!ID' => 11], 'Выборка должна исключать текущий материал и не зависеть от тегов');
    check($call['params']['SORT_BY1'] === 'ACTIVE_FROM' && $call['params']['SORT_ORDER1'] === 'DESC' && $call['params']['SORT_BY2'] === 'ID' && $call['params']['SORT_ORDER2'] === 'DESC', 'Нужен стабильный порядок от новых к старым');
    check($call['params']['CHECK_DATES'] === 'Y' && $call['params']['CACHE_FILTER'] === 'Y', 'Нужно учитывать срок активности и фильтр в кеше');
    check(!isset($GLOBALS[$call['params']['FILTER_NAME']]), 'Глобальный фильтр должен очищаться после компонента');
}

$fixture['PROPERTIES']['LINKED_SERVICES'] = ['LINK_IBLOCK_ID' => 5, 'VALUE' => [0, -1, 10, 10, 20]];
$APPLICATION->calls = [];
$template->render('news.detail/news-detail', $fixture, $params);
check(array_column($APPLICATION->calls, 'template') === ['related-slider', 'news-slider'], 'Связанные услуги должны предшествовать другим материалам');
check($APPLICATION->calls[0]['filter'] === ['ID' => [10, 20]], 'Связи должны содержать только уникальные положительные ID');
check($APPLICATION->calls[0]['params']['IBLOCK_ID'] === 5 && $APPLICATION->calls[0]['params']['NEWS_COUNT'] === 2, 'Нужно вывести выбранные услуги из связанного инфоблока');
$fixture['PROPERTIES']['LINKED_SERVICES']['VALUE'] = [];
$APPLICATION->calls = [];
$template->render('news.detail/news-detail', $fixture, $params);
check(count($APPLICATION->calls) === 1, 'Пустое свойство не должно создавать блок услуг');

check(trim($template->render('news.list/news-slider', ['ITEMS' => []], [])) === '', 'При отсутствии других материалов слайдер должен быть скрыт');
$slider = ['NAME' => 'Исходный заголовок', 'LIST_PAGE_URL' => '/news/', 'ITEMS' => [['ID' => 12, 'IBLOCK_ID' => 6, 'EDIT_LINK' => '', 'DELETE_LINK' => '', 'PREVIEW_PICTURE' => false, 'DETAIL_PICTURE' => false, 'NAME' => 'Новость', 'DETAIL_PAGE_URL' => '/news/other/', 'PREVIEW_TEXT' => 'Описание']]];
$html = $template->render('news.list/news-slider', $slider, ['SECTION_TITLE' => 'Другие новости', 'USE_SWIPER_NAVIGATION' => 'Y', 'USE_SWIPER_PAGINATION' => 'Y', 'SHOW_ACTIVE_FROM' => 'N', 'SHOW_TAG_LIST' => 'N']);
check(str_contains($html, 'Другие новости') && !str_contains($html, 'Исходный заголовок'), 'Заголовок должен браться из параметра слайдера');
check(str_contains($html, 'class="news-card"') && str_contains($html, 'class="swiper base-slider"'), 'Карточки должны использовать шаблон главной страницы');
check(!str_contains($html, '<img'), 'Отсутствующее изображение не должно создавать сломанный img');
echo "OK: связанные услуги, отсутствие свойства, пустые списки, заголовки разделов, фильтрация, порядок и карточки слайдера\n";
