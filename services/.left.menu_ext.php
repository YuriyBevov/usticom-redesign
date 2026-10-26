<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

// main.map uses the left menu for child pages. Reuse the current service
// navigation, including active elements, URLs and permission checks.
include $_SERVER['DOCUMENT_ROOT'] . '/.header_services.menu_ext.php';
