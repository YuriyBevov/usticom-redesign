<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

return static function (bool $apply): array {
    if (!$apply) return ['plan' => 'Attach native email notifications to the new English request form.'];
    \Bitrix\Main\Loader::includeModule('form');
    $form = CForm::GetBySID('USTICOM_EN_REQUEST')->Fetch();
    if (!$form) throw new RuntimeException('English request form is missing');
    // Retain any notification choices already made by an administrator.
    if (CForm::GetMailTemplateArray($form['ID'])) return ['status' => 'already_configured'];
    $templates = [];
    $query = CEventMessage::GetList('id', 'asc', ['EVENT_NAME' => $form['MAIL_EVENT_TYPE'],
        'EVENT_NAME_EXACT_MATCH' => 'Y', 'SITE_ID' => 'en', 'ACTIVE' => 'Y']);
    while ($template = $query->Fetch()) $templates[] = (int)$template['ID'];
    if (!$templates) $templates = CForm::SetMailTemplate($form['ID']);
    if (!$templates || !CForm::Set(['arMAIL_TEMPLATE' => $templates], $form['ID'], 'N')) {
        throw new RuntimeException('Cannot attach the English form notification templates');
    }
    return ['status' => 'configured', 'templates' => $templates];
};
