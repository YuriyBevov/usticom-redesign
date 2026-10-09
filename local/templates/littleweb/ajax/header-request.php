<?php
// Load the existing Bitrix form only when the request dialog is opened. Keeping
// it in a separate document also prevents handling another page form's POST.
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

ob_start();
$APPLICATION->IncludeComponent('bitrix:form.result.new', 'service-request', [
    'WEB_FORM_ID' => 1,
    'CACHE_TYPE' => 'N',
    'CACHE_TIME' => 0,
    'AJAX_MODE' => 'N',
    'USE_EXTENDED_ERRORS' => 'Y',
    'SEF_MODE' => 'N',
], false, ['HIDE_ICONS' => 'Y']);
$requestForm = ob_get_clean();
includeGlobalAssets();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Оставить заявку — Юстиком</title>
    <?php $APPLICATION->ShowHead(); ?>
</head>
<body class="header-request-page">
    <main class="header-request">
        <h1>Оставить заявку</h1>
        <?= $requestForm ?>
    </main>
</body>
</html>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php'; ?>
