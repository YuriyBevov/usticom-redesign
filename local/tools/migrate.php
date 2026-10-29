<?php
// CLI only. Code releases add content; they never restore a database dump.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$options = getopt('', ['root:', 'apply']);
$_SERVER['DOCUMENT_ROOT'] = rtrim($options['root'] ?? dirname(__DIR__, 2), '/');
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
$connection = \Bitrix\Main\Application::getConnection();
$lock = $connection->query("SELECT GET_LOCK('usticom_content_migrations', 0) AS L")->fetch();
if ((int)$lock['L'] !== 1) throw new RuntimeException('Another content migration is running');
try {
    foreach (glob(dirname(__DIR__).'/migrations/[0-9]*.php') as $path) {
        $key = 'migration_'.basename($path, '.php');
        $checksum = hash_file('sha256', $path);
        $done = COption::GetOptionString('usticom', $key, '');
        if ($done !== '') {
            if ($done !== $checksum) throw new RuntimeException('Applied migration was edited: '.basename($path));
            echo basename($path).": already applied\n"; continue;
        }
        $migration = require $path;
        $DB->StartTransaction();
        try {
            $result = $migration(isset($options['apply']));
            if (isset($options['apply'])) COption::SetOptionString('usticom', $key, $checksum);
            $DB->Commit();
        } catch (Throwable $error) { $DB->Rollback(); throw $error; }
        echo basename($path).': '.json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)."\n";
    }
    if (isset($options['apply'])) {
        // Add only missing routes; retain an editor's existing routing decisions.
        foreach ([['CONDITION' => '#^/en/services/#', 'RULE' => '', 'ID' => 'bitrix:catalog',
                    'PATH' => '/en/services/index.php', 'SORT' => 50],
                  ['CONDITION' => '#^/en/#', 'RULE' => '', 'ID' => '', 'PATH' => '/en/404.php', 'SORT' => 90]] as $rule) {
            $existing = CUrlRewriter::GetList(['CONDITION' => $rule['CONDITION']]);
            if (!$existing) CUrlRewriter::Add($rule);
            $saved = CUrlRewriter::GetList(['CONDITION' => $rule['CONDITION']]);
            if (count($saved) !== 1 || reset($saved)['PATH'] !== $rule['PATH']) {
                throw new RuntimeException('The EN route is unavailable or has a different destination: '.$rule['CONDITION']);
            }
        }
        \Bitrix\Main\Application::getInstance()->getManagedCache()->cleanAll();
    }
} finally {
    $connection->query("SELECT RELEASE_LOCK('usticom_content_migrations')");
}
