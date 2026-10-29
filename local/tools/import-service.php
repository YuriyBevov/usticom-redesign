<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$options = getopt('', ['root:', 'payload:', 'apply']);
if (empty($options['root']) || empty($options['payload'])) {
    fwrite(STDERR, "Usage: php import-service.php --root=/site/root --payload=/private/service.json [--apply]\n"); exit(2);
}
$_SERVER['DOCUMENT_ROOT'] = rtrim($options['root'], '/');
$_SERVER['HTTP_HOST'] = 'localhost';
define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_CHECK', true);
define('BX_CRONTAB', true);
define('DisableEventsCheck', true);
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';
set_exception_handler(static function (Throwable $error): void {
    fwrite(STDERR, get_class($error).': '.$error->getMessage()."\n".$error->getTraceAsString()."\n");
    exit(1);
});
foreach (['iblock','simai.complexprop'] as $module) {
    if (!\Bitrix\Main\Loader::includeModule($module)) throw new RuntimeException('Missing module: '.$module);
}
require dirname(__DIR__).'/migrations/lib/service-importer.php';
$payload = json_decode(file_get_contents($options['payload']), true, 512, JSON_THROW_ON_ERROR);
$DB->StartTransaction();
try {
    $result = usticomImportService($payload, isset($options['apply']));
    $DB->Commit();
} catch (Throwable $e) { $DB->Rollback(); throw $e; }
echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)."\n";
