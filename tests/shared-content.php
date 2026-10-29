<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
define('SITE_DIR', '/');
define('SITE_TEMPLATE_PATH', '/local/templates/littleweb');
function getHeroTizzersSectionId(...$args): int { return 0; }
class SharedContentApplication {
    public array $paths = [];
    public function IncludeFile(string $path, ...$args): void {
        $this->paths[] = $path;
        echo 'Содержимое редактора';
    }
}
$arParams = [
    'EYEBROW_PATH' => '/include/about/eyebrow.php',
    'TITLE_PATH' => '/include/about/title.php',
    'TEXT_PATH' => '/include/about/text.php',
    'BUTTONS_PATH' => '/include/about/buttons.php',
    'IMG_PATH' => '/include/about/img.php',
];
foreach (['about/hero-section.php', 'experiences/hero-section.php', 'about/company-experience/section.php'] as $template) {
    $APPLICATION = new SharedContentApplication();
    ob_start();
    include __DIR__ . '/../local/templates/littleweb/include/' . $template;
    $html = ob_get_clean();
    $expected = str_ends_with($template, '/section.php') ? 4 : 5;
    if (count($APPLICATION->paths) !== $expected || substr_count($html, 'Содержимое редактора') !== $expected) {
        throw new RuntimeException('Не выведены редактируемые фрагменты: ' . $template);
    }
    foreach ($APPLICATION->paths as $path) {
        if (!str_starts_with($path, '/include/')) throw new RuntimeException('Контент должен загружаться из /include/: ' . $path);
    }
}
echo "OK: редактируемые фрагменты загружаются из общих файлов контента\n";
