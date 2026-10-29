<?php
namespace Bitrix\Main {
    class Loader { public static function includeModule($name): bool { return true; } }
}
namespace {
    if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
    define('SITE_DIR', '/');
    function check(bool $condition, string $message): void { if (!$condition) throw new \RuntimeException($message); }
    class CIBlock {
        public static array $tags = [];
        public static function registerWithTagCache($id): void { self::$tags[] = $id; }
        public static function GetArrayByID($id, $field): string { return '/#SECTION_CODE#/'; }
    }
    class SectionRows {
        private int $index = 0;
        private string $url = '';
        public function __construct(private array $rows) {}
        public function Fetch() { return $this->rows[$this->index++] ?? false; }
        public function SetUrlTemplates($detail, $section): void { $this->url = $section; }
        public function GetNext() {
            $row = $this->Fetch();
            if ($row) $row['~SECTION_PAGE_URL'] = str_replace('#SECTION_CODE#', $row['CODE'], $this->url);
            return $row;
        }
    }
    class CIBlockSection {
        public static array $rows = [];
        public static function GetList($sort, $filter, $group, $select): SectionRows {
            check($filter['ACTIVE'] === 'Y' && $filter['GLOBAL_ACTIVE'] === 'Y', 'Нужно проверять активность раздела и его родителей');
            check($filter['CHECK_PERMISSIONS'] === 'Y' && $filter['MIN_PERMISSION'] === 'R', 'Нужно учитывать права посетителя');
            $rows = array_filter(self::$rows, static function ($row) use ($filter) {
                if (!$row['READABLE']) return false;
                foreach ($filter as $field => $value) {
                    if (in_array($field, ['CHECK_PERMISSIONS', 'MIN_PERMISSION'], true)) continue;
                    if ($field === '<LEFT_MARGIN') { if ($row['LEFT_MARGIN'] >= $value) return false; }
                    elseif ($field === '>RIGHT_MARGIN') { if ($row['RIGHT_MARGIN'] <= $value) return false; }
                    elseif (($row[$field] ?? null) != $value) return false;
                }
                return true;
            });
            if (isset($sort['DEPTH_LEVEL'])) usort($rows, static fn($a, $b) => $b['DEPTH_LEVEL'] <=> $a['DEPTH_LEVEL']);
            return new SectionRows(array_values($rows));
        }
    }
    require_once __DIR__.'/../local/php_interface/includes/language.php';
    require __DIR__ . '/../local/php_interface/includes/service-price-links.php';
    $base = ['ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'READABLE' => true, 'DEPTH_LEVEL' => 1, 'LEFT_MARGIN' => 1, 'RIGHT_MARGIN' => 10];
    CIBlockSection::$rows = [
        ['ID' => 585, 'IBLOCK_ID' => 5, 'NAME' => 'Аудит', 'CODE' => 'audit', 'UF_PRICE_LINKED' => 5043] + $base,
        ['ID' => 586, 'IBLOCK_ID' => 5, 'NAME' => 'Подраздел', 'CODE' => 'audit-child', 'DEPTH_LEVEL' => 2, 'LEFT_MARGIN' => 2, 'RIGHT_MARGIN' => 3] + $base,
        ['ID' => 5043, 'IBLOCK_ID' => 22, 'NAME' => 'Аудиторские услуги', 'CODE' => 'auditorskie', 'UF_SERVICE_LINKED' => 585] + $base,
        ['ID' => 709, 'IBLOCK_ID' => 5, 'NAME' => 'Тендеры', 'CODE' => 'tender'] + $base,
    ];
    check(getLinkedServicePriceSection(5, 585)['URL'] === '/prices/auditorskie/', 'Прайс должен иметь канонический префикс /prices/');
    check(getLinkedServicePriceSection(22, 5043)['URL'] === '/audit/', 'Обратная ссылка должна вести в услуги');
    check(getLinkedServicePriceSection(5, 586)['URL'] === '/prices/auditorskie/', 'Вложенный раздел должен наследовать привязку направления');
    CIBlock::$tags = [];
    check(getLinkedServicePriceSection(5, 709) === null, 'Без привязки ссылки быть не должно');
    check(CIBlock::$tags === [5, 22], 'Даже пустая связь должна зависеть от обоих инфоблоков в кеше');
    check(getLinkedServicePriceSection(5, 5043) === null && getLinkedServicePriceSection(6, 585) === null && getLinkedServicePriceSection(5, 0) === null, 'Неверный источник должен отклоняться');
    CIBlockSection::$rows[2]['ACTIVE'] = 'N';
    check(getLinkedServicePriceSection(5, 585) === null, 'Неактивный прайс не должен выводиться');
    CIBlockSection::$rows[2]['ACTIVE'] = 'Y';
    CIBlockSection::$rows[2]['READABLE'] = false;
    check(getLinkedServicePriceSection(5, 585) === null, 'Недоступный посетителю раздел должен скрываться');
    CIBlockSection::$rows[2]['READABLE'] = true;
    CIBlockSection::$rows[2]['DEPTH_LEVEL'] = 2;
    check(getLinkedServicePriceSection(5, 585) === null, 'Подраздел прайса без публичной страницы не должен создавать битую ссылку');
    echo "OK: взаимные ссылки, канонические URL, наследование, пустые привязки, права, активность и теги кеша\n";
}
