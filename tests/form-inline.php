<?php
// Изолированная проверка шаблона и обработчиков согласия без базы Битрикс.
namespace Bitrix\Main {
	class EventManager {
		public static array $handlers = [];
		public static function getInstance(): self { return new self(); }
		public function addEventHandlerCompatible($module, $event, $handler): void { self::$handlers[$event] = $handler; }
	}
}
namespace Bitrix\Main\UserConsent {
	class Agreement {
		public static bool $active = true;
		public function __construct($id) { \check($id === 1, 'Неверное соглашение'); }
		public function isExist(): bool { return true; }
		public function isActive(): bool { return self::$active; }
	}
	class Consent {
		public static array $records = [];
		public static function addByContext(...$args): int { self::$records[] = $args; return count(self::$records); }
	}
}
namespace {
	if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
	define('B_PROLOG_INCLUDED', true);
	define('SITE_TEMPLATE_PATH', '/local/templates/littleweb');
	function check(bool $condition, string $message): void { if (!$condition) throw new \RuntimeException($message); }
	function htmlspecialcharsbx($value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
	function includeComponentAssets($name): void {}
	class CForm {
		public static function GetHiddenField($id, $value = '', $params = ''): string {
			return '<input type="hidden" ' . $params . ' name="form_hidden_' . $id . '" value="' . htmlspecialcharsbx($value) . '" />';
		}
	}
	class InlineApplication {
		public array $errors = [];
		public function ThrowException($message): void { $this->errors[] = $message; }
		public function IncludeComponent($name, $template, $params, ...$args): void {
			check($name === 'bitrix:main.userconsent.request' && $template === 'inline', 'Нужен штатный компонент согласия');
			check($params['ID'] === 1 && $params['AUTO_SAVE'] === 'N', 'Согласие должно записываться на сервере');
			$arParams = $params;
			$arParams['IS_CHECKED'] = $params['IS_CHECKED'] === 'Y';
			$arResult = ['INPUT_LABEL' => 'Нажимая на кнопку, я принимаю условия соглашения.'];
			include __DIR__ . '/../local/templates/littleweb/components/bitrix/form.result.new/form-inline/bitrix/main.userconsent.request/inline/template.php';
		}
	}
	class InlineTemplate {
		public function render(array $arResult, array $params = []): string {
			global $APPLICATION;
			$arParams = $params + ['WEB_FORM_ID' => 5];
			$component = null;
			include __DIR__ . '/../local/templates/littleweb/components/bitrix/form.result.new/form-inline/result_modifier.php';
			ob_start();
			include __DIR__ . '/../local/templates/littleweb/components/bitrix/form.result.new/form-inline/template.php';
			return ob_get_clean();
		}
	}
	$APPLICATION = new InlineApplication();
	require __DIR__ . '/../local/php_interface/includes/form-inline-consent.php';
	check(count(\Bitrix\Main\EventManager::$handlers) === 2, 'Не зарегистрированы события веб-формы');
	$fields = [];
	$values = [];
	UsticomInlineFormConsent::beforeResultAdd(5, $fields, $values);
	check(count($APPLICATION->errors) === 1, 'Отправка без согласия должна отклоняться сервером');
	UsticomInlineFormConsent::afterResultAdd(5, 100);
	check(\Bitrix\Main\UserConsent\Consent::$records === [], 'Без согласия не должно быть записи');
	$values['form_inline_consent'] = 'Y';
	UsticomInlineFormConsent::beforeResultAdd(5, $fields, $values);
	check(\Bitrix\Main\UserConsent\Consent::$records === [], 'Согласие нельзя сохранять до создания заявки');
	UsticomInlineFormConsent::afterResultAdd(5, 101);
	check(\Bitrix\Main\UserConsent\Consent::$records === [[1, 'form', '101']], 'Согласие должно сохраняться штатным API и связываться с заявкой');
	UsticomInlineFormConsent::afterResultAdd(5, 101);
	check(count(\Bitrix\Main\UserConsent\Consent::$records) === 1, 'Согласие не должно дублироваться');
	\Bitrix\Main\UserConsent\Agreement::$active = false;
	UsticomInlineFormConsent::beforeResultAdd(5, $fields, $values);
	UsticomInlineFormConsent::afterResultAdd(5, 102);
	check(count($APPLICATION->errors) === 2 && count(\Bitrix\Main\UserConsent\Consent::$records) === 1, 'Неактивное соглашение не должно приниматься');
	$errorCount = count($APPLICATION->errors);
	$values = [];
	UsticomInlineFormConsent::beforeResultAdd(1, $fields, $values);
	check(count($APPLICATION->errors) === $errorCount, 'Другие веб-формы не должны затрагиваться');
	\Bitrix\Main\UserConsent\Agreement::$active = true;

	$fixture = [
		'FORM_HEADER' => '<form method="post" action=""><input type="hidden" name="WEB_FORM_ID" value="5"><input type="hidden" name="sessid" value="test-session">',
		'FORM_TITLE' => 'Получите консультацию эксперта', 'FORM_DESCRIPTION' => 'Расскажите о вашей задаче - мы предложим лучшее решение.',
		'FORM_FOOTER' => '</form>', 'FORM_NOTE' => '', 'FORM_ERRORS' => [], 'FORM_ERRORS_TEXT' => '', 'F_RIGHT' => 10, 'isUseCaptcha' => false,
		'QUESTIONS' => [
			'NAME' => ['CAPTION' => 'Имя', 'REQUIRED' => 'Y', 'STRUCTURE' => [['FIELD_TYPE' => 'text']], 'HTML_CODE' => '<input type="text" name="form_text_11" value="">'],
			'PHONE' => ['CAPTION' => 'Телефон', 'REQUIRED' => 'Y', 'STRUCTURE' => [['FIELD_TYPE' => 'text']], 'HTML_CODE' => '<input type="text" name="form_text_12" value="">'],
			'SERVICE' => ['CAPTION' => 'Сфера услуг', 'REQUIRED' => 'Y', 'STRUCTURE' => [['FIELD_TYPE' => 'dropdown']], 'HTML_CODE' => '<select name="form_dropdown_SERVICE"><option value="21">Аудиторские услуги</option><option value="22">Юридические услуги</option><option value="23">Бухгалтерские услуги</option></select>'],
			'QUESTION' => ['CAPTION' => 'Ваш вопрос', 'REQUIRED' => 'N', 'STRUCTURE' => [['FIELD_TYPE' => 'textarea']], 'HTML_CODE' => '<textarea name="form_textarea_13"></textarea>'],
		],
	];
	$template = new InlineTemplate();
	$html = $template->render($fixture);
	check(str_contains($html, 'novalidate') && str_contains($html, 'data-inline-form'), 'Нужна кастомная валидация');
	check(str_contains($html, 'type="tel"') && str_contains($html, 'data-kind="phone"'), 'Не определено поле телефона');
	check(str_contains($html, '<option value="">Сфера услуг</option>'), 'Селекту нужна подсказка без изменения ID ответов');
	check(str_contains($html, 'value="21"') && str_contains($html, 'name="form_text_11"') && str_contains($html, 'test-session'), 'Нативные имена полей, значения и сессия должны сохраняться');
	check(!preg_match('/data-bx-user-consent\s*=/', $html), 'Модальное окно согласия не должно инициализироваться');
	check(!str_contains($html, ' checked'), 'Согласие должно быть не отмечено');
	$errorFixture = $fixture;
	$errorFixture['FORM_ERRORS'] = ['NAME' => 'Введите имя'];
	$errorFixture['FORM_ERRORS_TEXT'] = 'Введите имя';
	$errorFixture['arrVALUES']['form_inline_consent'] = 'Y';
	$errorHtml = $template->render($errorFixture);
	check(str_contains($errorHtml, 'invalid-field') && str_contains($errorHtml, ' checked'), 'Ошибки сервера должны отмечаться классом, согласие должно сохраняться');
	check(str_contains($errorHtml, '<template data-inline-feedback="error">Введите имя</template>'), 'Сообщение сервера должно передаваться в модальное окно');
	$visibleErrorHtml = preg_replace('/<template\b[^>]*>.*?<\/template>/s', '', $errorHtml);
	check(!str_contains($visibleErrorHtml, 'Введите имя'), 'Текст ошибки не должен выводиться под полем');
	check(!str_contains($html, 'data-inline-error') && !str_contains($html, 'aria-describedby'), 'Не должно быть текстов ошибок и ссылок на их ID');
	check(!preg_match('/<(?:section|h2)[^>]+\bid=/', $html), 'Секции и заголовку не нужны ID');
	$adminFixture = $fixture;
	$adminFixture['FORM_TITLE'] = 'Заголовок из админки';
	$adminFixture['FORM_DESCRIPTION'] = '<p>Описание <strong>из админки</strong>.</p>';
	$adminHtml = $template->render($adminFixture);
	check(str_contains($adminHtml, $adminFixture['FORM_TITLE']) && str_contains($adminHtml, $adminFixture['FORM_DESCRIPTION']), 'Заголовок и описание должны приходить из данных формы');
	$success = $fixture;
	$success['FORM_NOTE'] = 'Спасибо! Ваша заявка принята.';
	$successHtml = $template->render($success);
	check(str_contains($successHtml, 'role="status"') && str_contains($successHtml, 'data-inline-feedback="success"') && !str_contains($successHtml, '<form '), 'После успеха должно выводиться подтверждение и открываться модальное окно');
	$modalHtml = $template->render($fixture, ['MODAL_MODE' => 'Y']);
	check(str_contains($modalHtml, 'form-inline--modal') && !str_contains($modalHtml, 'callback section'), 'В модальном окне не нужен сквозной блок с фоном');
	check(str_contains($modalHtml, 'id="form-popup-5-NAME"') && !str_contains($modalHtml, 'id="form-inline-5-NAME"'), 'ID полей модальной и сквозной формы не должны совпадать');
	$themeFixture = $fixture;
	$themeFixture['QUESTIONS']['THEME'] = ['CAPTION' => 'Тема', 'REQUIRED' => 'N', 'STRUCTURE' => [['ID' => 907, 'FIELD_TYPE' => 'hidden']], 'HTML_CODE' => '<input type="hidden" name="form_hidden_907" value="">'];
	$theme = 'Присоединиться к программе «Партнер» & "тест" <script>';
	$themeHtml = $template->render($themeFixture, ['MODAL_MODE' => 'Y', 'FORM_THEME' => htmlspecialcharsbx($theme), '~FORM_THEME' => $theme]);
	check(str_contains($themeHtml, 'name="form_hidden_907" value="' . htmlspecialcharsbx($theme) . '"'), 'Тема должна передаваться в штатное поле из структуры вопроса');
	check(!str_contains($themeHtml, '<script>'), 'Тема должна безопасно экранироваться');
	$themeFixture['arrVALUES']['form_hidden_907'] = 'Отправленная тема';
	$themeFixture['QUESTIONS']['THEME']['HTML_CODE'] = '<input type="hidden" name="form_hidden_907" value="Отправленная тема">';
	$postedThemeHtml = $template->render($themeFixture, ['MODAL_MODE' => 'Y', 'FORM_THEME' => 'Другая тема']);
	check(str_contains($postedThemeHtml, 'value="Отправленная тема"') && !str_contains($postedThemeHtml, 'Другая тема'), 'После ошибки должна сохраняться отправленная тема');
	$withoutThemeHtml = $template->render($fixture, ['MODAL_MODE' => 'Y', 'FORM_THEME' => $theme]);
	check(!str_contains($withoutThemeHtml, 'form_hidden_907'), 'Нельзя создавать поле, которого нет в структуре формы');
	define('USTICOM_MODAL_FORM_ID', 2);
	$values = [];
	$errorCount = count($APPLICATION->errors);
	UsticomInlineFormConsent::beforeResultAdd(2, $fields, $values);
	UsticomInlineFormConsent::afterResultAdd(2, 201);
	check(count($APPLICATION->errors) === $errorCount + 1 && count(\Bitrix\Main\UserConsent\Consent::$records) === 1, 'Модальная форма должна отклоняться без согласия');
	$values['form_inline_consent'] = 'Y';
	UsticomInlineFormConsent::beforeResultAdd(2, $fields, $values);
	UsticomInlineFormConsent::afterResultAdd(2, 202);
	check(\Bitrix\Main\UserConsent\Consent::$records[1] === [1, 'form', '202'], 'Согласие модальной формы должно быть связано с её результатом');
	$embeddedHtml = $template->render($fixture, ['WEB_FORM_ID' => 5, 'INSTANCE_ID' => 'reasons', 'EMBEDDED_MODE' => 'Y', 'SHOW_HEADER' => 'N']);
	check(str_contains($embeddedHtml, 'form-inline--embedded') && !str_contains($embeddedHtml, 'callback section'), 'Встроенная форма должна выводиться без фоновой секции');
	check(!str_contains($embeddedHtml, '<h2') && !str_contains($embeddedHtml, 'class="container"'), 'Встроенной форме не нужны повторный заголовок и контейнер');
	check(str_contains($embeddedHtml, 'form-inline-reasons-5-NAME') && !str_contains($embeddedHtml, 'id="form-inline-5-NAME"'), 'ID встроенной и сквозной форм должны отличаться');
	check(str_contains($embeddedHtml, 'name="form_inline_instance" value="reasons"'), 'POST должен содержать идентификатор экземпляра формы');
	$existingIdFixture = $fixture;
	$existingIdFixture['QUESTIONS']['SERVICE']['HTML_CODE'] = str_replace('<select ', '<select id="form_dropdown_SERVICE" ', $fixture['QUESTIONS']['SERVICE']['HTML_CODE']);
	$existingIdHtml = $template->render($existingIdFixture, ['INSTANCE_ID' => 'reasons', 'EMBEDDED_MODE' => 'Y']);
	check(str_contains($existingIdHtml, 'id="form-inline-reasons-5-SERVICE"') && str_contains($existingIdHtml, 'for="form-inline-reasons-5-SERVICE"') && !str_contains($existingIdHtml, 'id="form_dropdown_SERVICE"'), 'Штатный ID селекта должен заменяться уникальным ID экземпляра вместе с подписью');
	$savedApplication = $APPLICATION;
	$savedPost = $_POST;
	$savedMethod = $_SERVER['REQUEST_METHOD'] ?? null;
	$APPLICATION = new class {
		public array $forms = [];
		public function IncludeComponent($name, $template, $params, ...$args): void { $this->forms[] = $params; }
	};
	$reasonsTemplate = new class {
		public function setFrameMode($mode): void {}
		public function render(): void {
			global $APPLICATION;
			$arResult = ['REASONS_SECTION_TITLE' => 'Помощь', 'REASONS' => [['TITLE' => 'Задача', 'TEXT' => 'Описание', 'PICTURE' => null]]];
			ob_start();
			include __DIR__ . '/../local/templates/littleweb/components/bitrix/news.list/reasons/template.php';
			ob_end_clean();
		}
	};
	foreach ([['GET', [], 1], ['POST', ['WEB_FORM_ID' => 5], 0], ['POST', ['WEB_FORM_ID' => 5, 'form_inline_instance' => 'reasons'], 1]] as [$method, $post, $expected]) {
		$_SERVER['REQUEST_METHOD'] = $method;
		$_POST = $post;
		$APPLICATION->forms = [];
		$reasonsTemplate->render();
		check(count($APPLICATION->forms) === $expected, 'Встроенная форма не должна обрабатывать отправку сквозной формы');
		if ($expected) check($APPLICATION->forms[0]['WEB_FORM_ID'] === 5 && $APPLICATION->forms[0]['AJAX_MODE'] === 'Y', 'Встроенный блок должен использовать AJAX-форму №5');
	}
	$APPLICATION = $savedApplication;
	$_POST = $savedPost;
	if ($savedMethod === null) unset($_SERVER['REQUEST_METHOD']);
	else $_SERVER['REQUEST_METHOD'] = $savedMethod;
	if (in_array('--render', $argv, true)) echo $html;
	else echo "OK: поля формы, ошибки, подтверждение, серверная проверка и сохранение согласия №1 после заявки №5\n";
}
