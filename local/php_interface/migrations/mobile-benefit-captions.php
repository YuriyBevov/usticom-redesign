<?php
// php -d short_open_tag=1 local/php_interface/migrations/mobile-benefit-captions.php [--apply]
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__, 3);
$_SERVER['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('NO_KEEP_STATISTIC', true);
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
if (!\Bitrix\Main\Loader::includeModule('iblock')) throw new RuntimeException('Модуль инфоблоков недоступен');
$iblock = CIBlock::GetByID(25)->Fetch();
if (!$iblock || $iblock['NAME'] !== 'Наши достижения') throw new RuntimeException('Проверьте инфоблок «Наши достижения» #25');
$captions = [
    'Входим в СРО Содружество' => 'Член СРО «Содружество»',
    'Ежегодно входим в ТОП рейтинга по всем направлениям работы' => 'Ежегодно в ТОП рейтингов',
    'В тройке лучших аудиторских компаний России' => 'ТОП-3 аудиторских компаний России',
    'Лауреаты юридического рейтинга Право 300' => 'Лауреаты рейтинга «Право-300»',
];
$apply = in_array('--apply', $argv, true);
$editor = new CIBlockElement();
foreach ($captions as $name => $caption) {
    $elements = CIBlockElement::GetList([], ['IBLOCK_ID' => 25, '=NAME' => $name], false, false, ['ID', 'NAME', 'PREVIEW_TEXT', 'PREVIEW_TEXT_TYPE']);
    $item = $elements->Fetch();
    if ($elements->Fetch()) throw new RuntimeException('Найдено несколько карточек: ' . $name);
    if (!$item) { echo 'Карточка не найдена, пропущена: ' . $name . PHP_EOL; continue; }
    $current = trim((string)$item['PREVIEW_TEXT']);
    if ($current === $caption) { echo 'Подпись уже задана: ' . $caption . PHP_EOL; continue; }
    if ($current !== '' && $current !== $name) {
        echo 'Сохранена собственная подпись редактора: #' . $item['ID'] . PHP_EOL;
        continue;
    }
    echo 'Подпись #' . $item['ID'] . ': ' . $caption . PHP_EOL;
    if ($apply && !$editor->Update((int)$item['ID'], ['PREVIEW_TEXT' => $caption, 'PREVIEW_TEXT_TYPE' => 'text'])) throw new RuntimeException($editor->LAST_ERROR);
}
if ($apply) CIBlock::clearIblockTagCache(25);
echo $apply ? "Готово. Названия, изображения и собственные подписи сохранены.\n" : "Предпросмотр. Для сохранения добавьте --apply.\n";
