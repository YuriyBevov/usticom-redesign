<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

$formId = filter_var($_GET['form_id'] ?? null, FILTER_VALIDATE_INT);
if (!$formId || $formId < 1) {
	http_response_code(400);
	echo 'Форма не найдена.';
	require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';
	return;
}

// Контекст задаёт сервер: удаление полей из POST не отключает проверку согласия.
define('USTICOM_MODAL_FORM_ID', $formId);
$APPLICATION->ShowAjaxHead();
$APPLICATION->IncludeComponent('bitrix:form.result.new', 'form-inline', [
	'WEB_FORM_ID' => $formId,
	'FORM_THEME' => is_string($_GET['form_theme'] ?? null) ? mb_substr(trim($_GET['form_theme']), 0, 1000) : '',
	'MODAL_MODE' => 'Y',
	'IGNORE_CUSTOM_TEMPLATE' => 'Y',
	'USE_EXTENDED_ERRORS' => 'Y',
	'CACHE_TYPE' => 'N',
	'CACHE_TIME' => 0,
	'AJAX_MODE' => 'Y',
	'AJAX_OPTION_JUMP' => 'N',
	'AJAX_OPTION_STYLE' => 'Y',
	'AJAX_OPTION_HISTORY' => 'N',
	'AJAX_OPTION_ADDITIONAL' => 'popup-form-' . $formId,
	'SEF_MODE' => 'N',
	'LIST_URL' => '',
	'EDIT_URL' => '',
	'SUCCESS_URL' => '',
], false, ['HIDE_ICONS' => 'Y']);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';
