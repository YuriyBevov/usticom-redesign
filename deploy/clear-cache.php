<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$_SERVER['DOCUMENT_ROOT'] = $argv[1];
$_SERVER['HTTP_HOST'] = $argv[2];
define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_CHECK', true);
define('NO_AGENT_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('BX_CRONTAB', true);
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
BXClearCache(true);
$GLOBALS['CACHE_MANAGER']->CleanAll();
$GLOBALS['stackCacheManager']->CleanAll();
if (class_exists('Bitrix\\Main\\Composite\\Page')) {
    \Bitrix\Main\Composite\Page::getInstance()->deleteAll();
} elseif (class_exists('Bitrix\\Main\\Data\\StaticHtmlCache')) {
    \Bitrix\Main\Data\StaticHtmlCache::getInstance()->deleteAll();
}
echo "Bitrix cache cleared\n";
