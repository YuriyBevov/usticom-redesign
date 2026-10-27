<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

$arResult['INLINE_FIELDS'] = [];
$instanceId = preg_replace('/[^a-z0-9_-]/i', '-', (string)($arParams['INSTANCE_ID'] ?? ''));
foreach ($arResult['QUESTIONS'] ?? [] as $sid => $question) {
	$type = (string)($question['STRUCTURE'][0]['FIELD_TYPE'] ?? 'text');
	$caption = trim(html_entity_decode(strip_tags((string)$question['CAPTION']), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
	$html = (string)$question['HTML_CODE'];
	$id = (($arParams['MODAL_MODE'] ?? 'N') === 'Y' ? 'form-popup-' : 'form-inline-') . ($instanceId !== '' ? $instanceId . '-' : '') . (int)$arParams['WEB_FORM_ID'] . '-' . preg_replace('/[^a-z0-9_-]/i', '-', (string)$sid);
	$comments = (string)($arResult['arQuestions'][$sid]['COMMENTS'] ?? '');
	$isPhone = $type === 'tel' || preg_match('/телефон/iu', $caption) || preg_match('/phone|tel/i', (string)$sid) || strpos($comments, 'data-type="tel"') !== false;
	$kind = $isPhone ? 'phone' : ($type === 'email' || preg_match('/e-?mail|почт/iu', $caption) ? 'email' : $type);
	$required = ($question['REQUIRED'] ?? 'N') === 'Y';
	$error = is_array($arResult['FORM_ERRORS'] ?? null) ? (string)($arResult['FORM_ERRORS'][$sid] ?? '') : '';
	if ($sid === 'THEME' && $type === 'hidden' && isset($arParams['FORM_THEME'])) {
		$answer = $question['STRUCTURE'][0];
		$answerId = (int)$answer['ID'];
		// При ошибке сохраняем отправленное штатное значение, при открытии — тему кнопки.
		if ($answerId > 0 && !array_key_exists('form_hidden_' . $answerId, $arResult['arrVALUES'] ?? [])) {
			$html = CForm::GetHiddenField($answerId, $arParams['~FORM_THEME'] ?? $arParams['FORM_THEME'], (string)($answer['FIELD_PARAM'] ?? ''));
		}
	}

	if ($type !== 'hidden') {
		if (preg_match('/<(?:input|textarea|select)\b[^>]*\bid\s*=\s*(["\'])(.*?)\1/is', $html, $matches)) {
			if ($instanceId !== '') {
				$html = preg_replace_callback('/(<(?:input|textarea|select)\b[^>]*\bid\s*=\s*)(["\'])(.*?)\2/is', static fn($match) => $match[1] . $match[2] . htmlspecialcharsbx($id) . $match[2], $html, 1);
			} else {
				$id = html_entity_decode($matches[2], ENT_QUOTES | ENT_HTML5, 'UTF-8');
			}
		}
		if ($isPhone) {
			$html = preg_replace('/\btype\s*=\s*(["\'])text\1/i', 'type="tel"', $html, 1);
		}
		$html = preg_replace_callback('/<(input|textarea|select)\b([^>]*)>/i', static function ($matches) use ($id, $caption, $required, $isPhone, $error) {
			$attributes = ['id' => $id];
			if (strtolower($matches[1]) !== 'select') {
				$attributes['placeholder'] = $caption;
			}
			if ($required) {
				$attributes['aria-required'] = 'true';
			}
			if ($error !== '') {
				$attributes['aria-invalid'] = 'true';
			}
			if ($isPhone) {
				$attributes['inputmode'] = 'tel';
				$attributes['autocomplete'] = 'tel';
			}
			$tag = $matches[0];
			foreach ($attributes as $name => $value) {
				if (!preg_match('/\b' . preg_quote($name, '/') . '\s*=/i', $tag)) {
					$tag = preg_replace('/\s*\/?>$/', ' ' . $name . '="' . htmlspecialcharsbx($value) . '">', $tag);
				}
			}
			return $tag;
		}, $html, 1);
		if ($type === 'dropdown' && !preg_match('/<option\b[^>]*\bvalue\s*=\s*(["\'])\1/i', $html)) {
			$html = preg_replace('/(<select\b[^>]*>)/i', '$1<option value="">' . htmlspecialcharsbx($caption) . '</option>', $html, 1);
		}
	}

	$arResult['INLINE_FIELDS'][] = [
		'ID' => $id, 'CAPTION' => $caption,
		'TYPE' => $type, 'KIND' => $kind, 'REQUIRED' => $required,
		'HTML' => $html, 'INVALID' => $error !== '',
	];
}
$arResult['INLINE_FORM_HEADER'] = preg_replace('/<form\b/i', '<form data-inline-form novalidate', (string)($arResult['FORM_HEADER'] ?? ''), 1);
