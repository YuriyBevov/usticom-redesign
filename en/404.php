<?php
if (!defined('ERROR_404')) define('ERROR_404', 'Y');
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';
CHTTP::SetStatus('404 Not Found');
$APPLICATION->SetTitle('Page Not Found');
$APPLICATION->SetPageProperty('title', 'Page Not Found — Usticom');
$APPLICATION->SetPageProperty('description', '');
?><section class="section"><div class="container"><h1>Page Not Found</h1><a href="/en/">Home</a></div></section><?php
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';
