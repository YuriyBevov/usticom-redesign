<?php
namespace Bitrix\Main {
    class Loader { public static function includeModule($module): bool { return true; } }
}
namespace {
    if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
    define('B_PROLOG_INCLUDED', true);
    define('SITE_TEMPLATE_PATH', '/local/templates/littleweb');
    function check(bool $condition, string $message): void {
        if (!$condition) throw new RuntimeException($message);
    }
    function htmlspecialcharsbx($value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
    class Rows {
        private array $rows;
        public function __construct(array $rows) { $this->rows = $rows; }
        public function Fetch() { return array_shift($this->rows) ?: false; }
        public function GetNext() { return $this->Fetch(); }
        public function SetUrlTemplates(...$args): void {}
    }
    class CIBlock { public static function GetArrayByID(...$args): string { return ''; } }
    class CUserTypeEntity {
        public static string $type = 'boolean';
        public static function GetList(...$args): Rows {
            return new Rows([['ID' => 32, 'USER_TYPE_ID' => self::$type]]);
        }
    }
    class CUserFieldEnum {
        public static function GetList(...$args): Rows {
            return new Rows([['ID' => 20, 'XML_ID' => 'Y'], ['ID' => 21, 'XML_ID' => 'N']]);
        }
    }
    class CIBlockSection {
        public static array $flags = [];
        public static function GetList($sort, $filter, $count, $fields): Rows {
            check(in_array('UF_SHOW_IN_DESKTOP_MENU', $fields, true), 'Нужно загружать поле видимости');
            check($filter['CHECK_PERMISSIONS'] === 'Y' && $filter['DEPTH_LEVEL'] === 1, 'Нужно сохранить права и верхний уровень');
            $rows = [];
            foreach (self::$flags as $index => $flag) {
                $id = $index + 1;
                $rows[] = ['ID' => $id, '~NAME' => 'Раздел ' . $id, '~SECTION_PAGE_URL' => '/section-' . $id . '/', 'UF_SHOW_IN_DESKTOP_MENU' => $flag];
            }
            return new Rows($rows);
        }
    }
    class CIBlockElement {
        public static function GetList($sort, $filter, ...$args): Rows {
            $id = $filter['SECTION_ID'];
            return new Rows([['ID' => $id, '~NAME' => 'Услуга ' . $id, '~DETAIL_PAGE_URL' => '/service-' . $id . '/']]);
        }
    }
    function loadMenu(array $flags, string $type): array {
        CIBlockSection::$flags = $flags;
        CUserTypeEntity::$type = $type;
        $aMenuLinks = [];
        include __DIR__ . '/../.header_services.menu_ext.php';
        $result = [];
        foreach ($aMenuLinks as $link) $result[] = ['TEXT' => $link[0], 'LINK' => $link[1], 'PARAMS' => $link[3], 'DEPTH_LEVEL' => $link[3]['DEPTH_LEVEL'], 'SELECTED' => false];
        return $result;
    }
    function renderMenu(array $arResult, string $mode): string {
        $arParams = ['VIEW_MODE' => $mode];
        include __DIR__ . '/../local/templates/littleweb/components/bitrix/menu/header-services/result_modifier.php';
        ob_start();
        include __DIR__ . '/../local/templates/littleweb/components/bitrix/menu/header-services/template.php';
        return ob_get_clean();
    }
    foreach ([['boolean', [1, null, '1', 0], [1, 3]], ['enumeration', [20, 21, null, '20'], [1, 4]], ['string', ['Y', 'N', '', 'Y'], [1, 4]]] as [$type, $flags, $expected]) {
        $menu = loadMenu($flags, $type);
        check(count($menu) === 8, 'Общий источник меню должен сохранять все разделы и услуги');
        $desktop = renderMenu($menu, 'desktop');
        $mobile = renderMenu($menu, 'mobile');
        foreach (range(1, 4) as $id) {
            check(str_contains($desktop, 'href="/section-' . $id . '/"') === in_array($id, $expected, true), 'Неверный отбор раздела для ПК: ' . $type);
            check(str_contains($desktop, 'href="/service-' . $id . '/"') === in_array($id, $expected, true), 'Услуги скрытого раздела не должны попадать в меню ПК');
            check(str_contains($mobile, 'href="/section-' . $id . '/"') && str_contains($mobile, 'href="/service-' . $id . '/"'), 'В бургер-меню нужны все разделы и услуги');
        }
    }
    $menu = loadMenu([1, 0, 1, 1, 1, 1, 1, 0, 1], 'boolean');
    $desktop = renderMenu($menu, 'desktop');
    check(!str_contains($desktop, 'desktop-services-more'), 'Семь выбранных разделов должны помещаться без списка «Ещё»');
    check(substr_count($desktop, 'class="nav__link"') === 7, 'В основной строке должны выводиться семь выбранных разделов');
    check(!str_contains($desktop, 'href="/section-2/"') && !str_contains($desktop, 'href="/section-8/"'), 'Неотмеченные разделы нельзя показывать в «Ещё»');
    $desktop = renderMenu(loadMenu(array_fill(0, 9, 1), 'boolean'), 'desktop');
    check(substr_count($desktop, 'class="nav__link"') === 7, 'При переполнении в основной строке остаётся семь разделов');
    check(str_contains($desktop, 'desktop-services-more'), 'Для восьмого и последующих разделов нужен список «Ещё»');
    $more = substr($desktop, strpos($desktop, 'id="desktop-services-more"'));
    check(str_contains($more, 'href="/section-8/"') && str_contains($more, 'href="/section-9/"') && !str_contains($more, 'href="/section-7/"'), 'В «Ещё» должны попадать только разделы после первых семи');
    $menu = loadMenu([null, 0], 'boolean');
    check(!str_contains(renderMenu($menu, 'desktop'), 'class="nav__link"'), 'При пустом выборе на ПК разделы не выводятся');
    check(substr_count(renderMenu($menu, 'mobile'), 'class="nav__link"') === 2, 'Пустой выбор не должен менять бургер-меню');
    echo "OK: меню ПК по флажку и XML_ID=Y, список «Ещё», полное мобильное меню и общий источник ссылок\n";
}
