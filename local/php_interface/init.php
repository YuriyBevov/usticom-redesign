<?php

// use Bitrix\Main\Page\Asset;
use Bitrix\Main\EventManager;

// Загружаем манифест
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/classes/ViteManifest.php';

// Создаём глобальный объект Vite
global $vite;
$vite = new ViteManifest('littleweb');

// Подключаем все вспомогательные файлы
$includesPath = $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/includes/';
require_once $includesPath . 'assets.php';
require_once $includesPath . 'core.php';
require_once $includesPath . 'debug.php';
