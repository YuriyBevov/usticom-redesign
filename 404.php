<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/urlrewrite.php';

CHTTP::SetStatus('404 Not Found');
if (!defined('ERROR_404')) {
    define('ERROR_404', 'Y');
}

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

$APPLICATION->SetPageProperty('not_show_nav_chain', 'Y');
$APPLICATION->SetTitle('Страница не найдена');
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/404.css');
?>

<section class="error-page" aria-labelledby="error-page-title">
    <div class="container error-page__content">
        <h1 class="error-page__title" id="error-page-title">404</h1>
        <p class="error-page__text">Такой страницы не существует.</p>
        <a class="main-btn main-btn--size-lg error-page__button" href="/">Вернуться на главную</a>
    </div>
</section>

<?php require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
