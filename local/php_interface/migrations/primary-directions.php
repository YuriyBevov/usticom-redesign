<?php
// php -d short_open_tag=1 local/php_interface/migrations/primary-directions.php [--apply]
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__, 3);
$_SERVER['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('NO_KEEP_STATISTIC', true);
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
if (!\Bitrix\Main\Loader::includeModule('iblock')) throw new RuntimeException('Модуль инфоблоков недоступен');

$iblock = CIBlock::GetByID(30)->Fetch();
if (!$iblock || $iblock['NAME'] !== 'Основные направления') throw new RuntimeException('Проверьте инфоблок «Основные направления» #30');
$elements = CIBlockElement::GetList([], [
    'IBLOCK_ID' => 30,
    ['LOGIC' => 'OR', '=CODE' => 'kadrovyj-audit', '=NAME' => 'Кадровый аудит'],
], false, false, ['ID', 'NAME', 'ACTIVE']);
$existing = $elements->Fetch();
if ($elements->Fetch()) throw new RuntimeException('Найдено несколько элементов кадрового аудита; автоматическое добавление отменено');
if ($existing) {
    echo 'Направление уже существует: #' . $existing['ID'] . ', активность ' . $existing['ACTIVE'] . '. Правки редакторов сохранены.' . PHP_EOL;
    exit;
}

echo "Будет добавлено направление «Кадровый аудит» в инфоблок 30.\n";
if (!in_array('--apply', $argv, true)) { echo "Предпросмотр. Для сохранения добавьте --apply.\n"; exit; }
$editor = new CIBlockElement();
$id = $editor->Add(['IBLOCK_ID' => 30, 'NAME' => 'Кадровый аудит', 'CODE' => 'kadrovyj-audit', 'ACTIVE' => 'Y', 'SORT' => 500]);
if (!$id) throw new RuntimeException($editor->LAST_ERROR);
CIBlock::clearIblockTagCache(30);
echo 'Добавлено направление #' . $id . PHP_EOL;
