<?php
// php local/tools/bind-service-price-sections.php [--apply]
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__, 2);
$_SERVER['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('NO_KEEP_STATISTIC', true);
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
if (!\Bitrix\Main\Loader::includeModule('iblock')) throw new RuntimeException('Модуль инфоблоков недоступен');

$fields = [5 => 'UF_PRICE_LINKED', 22 => 'UF_SERVICE_LINKED'];
$sections = [];
foreach ($fields as $iblockId => $fieldName) {
    $field = CUserTypeEntity::GetList([], ['ENTITY_ID' => 'IBLOCK_' . $iblockId . '_SECTION', 'FIELD_NAME' => $fieldName])->Fetch();
    $targetIblockId = $iblockId === 5 ? 22 : 5;
    if (!$field || $field['USER_TYPE_ID'] !== 'iblock_section' || $field['MULTIPLE'] !== 'N' || (int)$field['SETTINGS']['IBLOCK_ID'] !== $targetIblockId) {
        throw new RuntimeException('Проверьте тип и целевой инфоблок поля ' . $fieldName);
    }
    $rows = CIBlockSection::GetList(['SORT' => 'ASC', 'ID' => 'ASC'], [
        'IBLOCK_ID' => $iblockId, 'DEPTH_LEVEL' => 1, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y',
    ], false, ['ID', 'IBLOCK_ID', 'NAME', $fieldName]);
    while ($section = $rows->Fetch()) $sections[$iblockId][] = $section;
}

$normalize = static fn(string $name): string => mb_strtolower(trim(preg_replace('/\s+/u', ' ', $name)));
$prices = [];
$services = [];
foreach ($sections[22] ?? [] as $section) $prices[$normalize($section['NAME'])][] = $section;
foreach ($sections[5] ?? [] as $section) $services[$normalize($section['NAME'])][] = $section;
$pairs = [];
foreach ($services as $name => $matches) {
    if (count($matches) !== 1 || count($prices[$name] ?? []) !== 1) {
        echo 'Без однозначной пары: ' . $matches[0]['NAME'] . PHP_EOL;
        continue;
    }
    $service = $matches[0];
    $price = $prices[$name][0];
    if (((int)$service['UF_PRICE_LINKED'] > 0 && (int)$service['UF_PRICE_LINKED'] !== (int)$price['ID'])
        || ((int)$price['UF_SERVICE_LINKED'] > 0 && (int)$price['UF_SERVICE_LINKED'] !== (int)$service['ID'])) {
        throw new RuntimeException('Существующая связь отличается: ' . $service['NAME'] . '. Автоматическая замена отменена.');
    }
    $pairs[] = [$service, $price];
    echo $service['NAME'] . ': услуги #' . $service['ID'] . ' ↔ прайс #' . $price['ID'] . PHP_EOL;
}
if (!in_array('--apply', $argv, true)) {
    echo 'Предпросмотр: ' . count($pairs) . ' пар. Для сохранения добавьте --apply.' . PHP_EOL;
    exit;
}

$updated = 0;
$DB->StartTransaction();
try {
    $editor = new CIBlockSection();
    foreach ($pairs as [$service, $price]) {
        foreach ([[$service, 'UF_PRICE_LINKED', (int)$price['ID']], [$price, 'UF_SERVICE_LINKED', (int)$service['ID']]] as [$section, $fieldName, $targetId]) {
            if ((int)$section[$fieldName] === $targetId) continue;
            if (!$editor->Update((int)$section['ID'], [$fieldName => $targetId])) throw new RuntimeException($editor->LAST_ERROR);
            $updated++;
        }
    }
    $DB->Commit();
} catch (Throwable $error) {
    $DB->Rollback();
    throw $error;
}
echo 'Сохранено связей: ' . $updated . PHP_EOL;
