<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
define('B_PROLOG_INCLUDED', true);
define('SITE_TEMPLATE_PATH', '/local/templates/littleweb');
define('SITE_DIR', '/');
if (in_array('--404', $argv, true)) define('ERROR_404', 'Y');
class FooterApplication {
	public array $forms = [];
	public function IncludeFile(...$args): void {}
	public function IncludeComponent($name, $template, $params, ...$args): void {
		if ($name === 'bitrix:form.result.new') {
			if ($template !== 'form-inline' || $params['WEB_FORM_ID'] !== 5 || $params['IGNORE_CUSTOM_TEMPLATE'] !== 'Y' || $params['AJAX_MODE'] !== 'Y' || $params['AJAX_OPTION_JUMP'] !== 'N' || $params['AJAX_OPTION_HISTORY'] !== 'N') throw new RuntimeException('Неверный компонент сквозной формы');
			$this->forms[] = $params;
			echo '<section data-test-inline-form></section>';
		}
	}
}
$APPLICATION = new FooterApplication();
ob_start();
include __DIR__ . '/../local/templates/littleweb/footer.php';
$html = ob_get_clean();
$is404 = defined('ERROR_404') && ERROR_404 === 'Y';
if (count($APPLICATION->forms) !== ($is404 ? 0 : 1)) throw new RuntimeException('Форма должна выводиться один раз, кроме 404');
if (!$is404 && strpos($html, 'data-test-inline-form') > strpos($html, '<footer')) throw new RuntimeException('Форма должна быть перед футером');
echo $is404 ? "OK: на 404 форма отсутствует\n" : "OK: перед футером одна форма №5 form-inline\n";
