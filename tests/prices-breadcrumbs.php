<?php
namespace Bitrix\Iblock\Component {
    class Tools {
        public static bool $notFound = false;
        public static function process404(...$args): void { self::$notFound = true; }
    }
}
namespace {
    if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
    define('B_PROLOG_INCLUDED', true);
    function check(bool $condition, string $message): void { if (!$condition) throw new \RuntimeException($message); }
    class BreadcrumbApplication {
        public array $chain = [];
        public string $title = '';
        public function SetTitle($title): void { $this->title = $title; }
        public function AddChainItem($title, $url): void { $this->chain[] = [$title, $url]; }
    }
    function renderEpilog(array $arResult, array $arParams): BreadcrumbApplication {
        global $APPLICATION;
        $APPLICATION = new BreadcrumbApplication();
        include __DIR__ . '/../local/templates/littleweb/components/bitrix/news/prices/bitrix/catalog.section.list/prices/component_epilog.php';
        return $APPLICATION;
    }
    $result = [
        'SECTION' => ['ID' => 5043, 'DEPTH_LEVEL' => 1, 'NAME' => 'Аудиторские услуги'],
        'SERVICE_SECTION_LINK' => ['ID' => 585, 'NAME' => 'Аудиторские услуги', 'URL' => '/audit/'],
        'PRICE_CATEGORIES' => [['NAME' => 'Категория']],
    ];
    $params = ['IS_SECTION' => 'Y'];
    $fresh = renderEpilog($result, $params);
    check($fresh->chain === [['Аудиторские услуги', '/audit/'], ['Стоимость', '']], 'Нужны ссылка на услуги и текущий пункт Стоимость');
    check($fresh->title === 'Аудиторские услуги', 'Заголовок страницы не должен заменяться на Стоимость');
    $cached = renderEpilog(array_intersect_key($result, array_flip(['SECTION', 'SERVICE_SECTION_LINK'])), $params);
    check($cached->chain === $fresh->chain, 'Хлебные крошки не должны зависеть от остальных данных шаблона при попадании в кеш');
    unset($result['SERVICE_SECTION_LINK']);
    check(renderEpilog($result, $params)->chain === [['Аудиторские услуги', '']], 'Без связи должна сохраняться обычная цепочка раздела прайса');
    check(renderEpilog($result, ['IS_SECTION' => 'N'])->chain === [], 'На общей странице цен не нужно добавлять раздел и Стоимость');
    $result['SECTION']['DEPTH_LEVEL'] = 2;
    check(renderEpilog($result, $params)->chain === [] && \Bitrix\Iblock\Component\Tools::$notFound, 'Неизвестный публичный раздел не должен создавать крошки вместо 404');
    echo "OK: цепочка цен, ссылка на услуги, текущий пункт, кеш, отсутствие привязки, заголовок и 404\n";
}
