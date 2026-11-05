<?php
namespace Bitrix\Main {
    class Loader {
        public static bool $available = true;
        public static function includeModule($module): bool { return self::$available; }
    }
}
namespace {
    if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
    function check(bool $condition, string $message): void {
        if (!$condition) throw new RuntimeException($message);
    }
    class Rows {
        private array $rows;
        public function __construct(array $rows) { $this->rows = $rows; }
        public function Fetch() { return array_shift($this->rows) ?: false; }
    }
    class CIBlock {
        public static array $tags = [];
        public static function registerWithTagCache($id): void { self::$tags[] = $id; }
    }
    class CIBlockSection {
        public static bool $defaultAvailable = true;
        public static function GetList($sort, $filter, ...$args): Rows {
            check($filter === ['IBLOCK_ID' => 29, 'ID' => 5158, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y'], 'Нужен активный раздел по умолчанию');
            return new Rows(self::$defaultAvailable ? [['ID' => 5158]] : []);
        }
    }
    class CIBlockElement {
        public static array $availableIds = [11, 12];
        public static function GetList($sort, $filter, ...$args): Rows {
            check($filter['IBLOCK_ID'] === 29 && $filter['ACTIVE'] === 'Y' && $filter['ACTIVE_DATE'] === 'Y' && $filter['CHECK_PERMISSIONS'] === 'Y', 'Связи должны учитывать инфоблок, активность, даты и права');
            check(!isset($filter['SECTION_ID']), 'Явные привязки не должны ограничиваться старым разделом тиззеров');
            return new Rows(array_intersect($filter['ID'], self::$availableIds) ? [['ID' => 11]] : []);
        }
    }
    class Application {
        public array $calls = [];
        public bool $fail = false;
        public function IncludeComponent($name, $template, $params, $parent, $options): void {
            $this->calls[] = ['params' => $params, 'filter' => $GLOBALS[$params['FILTER_NAME']], 'parent' => $parent];
            if ($this->fail) throw new RuntimeException('test exception');
        }
    }
    require __DIR__ . '/../local/php_interface/includes/tizzers.php';
    $default = getHeroTizzersFilter();
    check($default['SECTION_ID'] === 5158 && $default['INCLUDE_SUBSECTIONS'] === 'N', 'Без привязок нужен только набор по умолчанию');
    $custom = getHeroTizzersFilter(['11', 11, 12, 0, -1, '']);
    check($custom['ID'] === [11, 12] && !isset($custom['SECTION_ID']), 'Нужны уникальные положительные ID выбранных элементов');
    check(getHeroTizzersFilter([999]) === $default, 'Удалённые, недоступные или неактивные связи должны давать набор по умолчанию');
    CIBlockSection::$defaultAvailable = false;
    check(getHeroTizzersFilter() === [], 'Без доступного набора по умолчанию нельзя выводить весь инфоблок');
    check(getHeroTizzersFilter([11])['ID'] === [11], 'Явные связи работают без раздела по умолчанию');
    CIBlockSection::$defaultAvailable = true;
    $APPLICATION = new Application();
    $parent = new stdClass();
    includeHeroTizzers([11], $parent);
    includeHeroTizzers([12], $parent);
    includeHeroTizzers([], $parent);
    foreach ($APPLICATION->calls as $index => $call) {
        $params = $call['params'];
        check($params['CACHE_FILTER'] === 'Y' && $params['CACHE_GROUPS'] === 'Y', 'Кеш должен учитывать привязки и права пользователя');
        check($params['NEWS_COUNT'] === 4 && $params['SORT_BY1'] === 'SORT' && $params['SORT_ORDER1'] === 'ASC' && $params['SORT_BY2'] === 'ID', 'Сохраняем лимит и стабильную сортировку');
        check($call['parent'] === $parent, 'Нужно передавать родительский компонент');
        check(!isset($GLOBALS[$params['FILTER_NAME']]), 'Глобальный фильтр должен очищаться');
        check($params['PARENT_SECTION'] === ($index === 2 ? 5158 : 0), 'Выбор по ID не должен ограничиваться разделом по умолчанию');
    }
    check(count(array_unique(array_column(array_column($APPLICATION->calls, 'params'), 'FILTER_NAME'))) === 3, 'Разные привязки должны иметь независимые фильтры');
    $APPLICATION->fail = true;
    try { includeHeroTizzers([11]); } catch (RuntimeException $exception) {}
    $last = end($APPLICATION->calls);
    check(!isset($GLOBALS[$last['params']['FILTER_NAME']]), 'Фильтр очищается и при исключении');
    check(count(CIBlock::$tags) >= 8 && array_unique(CIBlock::$tags) === [29], 'Родительский кеш должен зависеть от тиззеров даже при отсутствии результатов');
    \Bitrix\Main\Loader::$available = false;
    check(getHeroTizzersFilter([11]) === [], 'Без модуля инфоблоков не должно быть запроса всех тиззеров');
    echo "OK: явные связи, набор по умолчанию, недоступные элементы, изоляция фильтров и кеш тиззеров\n";
}
