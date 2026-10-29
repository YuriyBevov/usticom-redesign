<?php
// Read credentials without starting Bitrix, executing agents or writing to the DB.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$root = $argv[1];
$_SERVER['DOCUMENT_ROOT'] = $root;
$settings = [];
foreach (['.settings.php', '.settings_extra.php'] as $name) {
    $path = is_file($root . '/local/' . $name) ? $root . '/local/' . $name : $root . '/bitrix/' . $name;
    if (is_file($path)) {
        $value = require $path;
        if (is_array($value)) $settings = array_replace($settings, $value);
    }
}
$connection = $settings['connections']['value']['default'] ?? null;
if (!is_array($connection) || !isset($connection['database'], $connection['login'])) {
    fwrite(STDERR, "MySQL connection is missing from Bitrix settings\n");
    exit(1);
}
echo json_encode($connection, JSON_THROW_ON_ERROR);
