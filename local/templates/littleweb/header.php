<?php if (usticomIsEnglish()) $APPLICATION->SetPageProperty('keywords', ''); ?>
<!DOCTYPE html>
<html lang="<?= usticomIsEnglish() ? 'en' : 'ru' ?>">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />

  <link rel="shortcut icon" type="image/x-icon" href="<?= SITE_TEMPLATE_PATH ?>/favicon.ico" />

  <? $APPLICATION->ShowHead(); ?>
  <title><? $APPLICATION->ShowTitle() ?></title>

  <?
  includeGlobalAssets();
  initBitrixCore('popup');
  ?>
</head>

<body>
  <div id="panel"><? $APPLICATION->ShowPanel(); ?></div>
  <?php include __DIR__ . '/include/header-top.php'; ?>
  <header class="header" data-site-header>
    <div class="container header__inner">
      <a class="main-logo header__logo" href="<?= SITE_DIR ?>" aria-label="<?= usticomText('Юстиком — главная') ?>">
        <? $APPLICATION->IncludeFile(SITE_DIR . 'include/company-info/main-logo.php', [], ['MODE' => 'html', 'NAME' => 'Логотип', 'SHOW_BORDER' => false]); ?>
      </a>
      <?php $headerMenuView = 'desktop';
      include __DIR__ . '/include/header-services.php'; ?>
      <div class="header__actions">
        <a class="header__language" href="<?= htmlspecialcharsbx(usticomLanguageUrl()) ?>" lang="<?= usticomIsEnglish() ? 'ru' : 'en' ?>" aria-label="<?= usticomIsEnglish() ? 'Русская версия' : 'English version' ?>"><?= usticomIsEnglish() ? 'RU' : 'EN' ?></a>
        <a class="main-btn header__request" data-form-id="<?= usticomFormId(2) ?>" data-form-theme="<?= usticomText('Оставить заявку') ?>" data-header-request href="<?= usticomPopupUrl(2) ?>"><?= usticomText('Оставить заявку') ?></a>
        <a class="header__phone" href="tel:+74952879268" aria-label="<?= usticomText('Позвонить в Юстиком') ?>">
          <svg width="24" height="24" aria-hidden="true">
            <use href="<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-header-phone"></use>
          </svg>
        </a>
        <?php $headerSocialFilter = ['tg', 'max'];
        include __DIR__ . '/include/header-socials.php'; ?>
        <button class="header__toggle" type="button" aria-label="<?= usticomText('Открыть меню') ?>" aria-controls="mobile-menu" aria-expanded="false">
          <svg class="header__menu-icon" width="32" height="32" aria-hidden="true">
            <use href="<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-menu"></use>
          </svg>
        </button>
      </div>
    </div>
    <button class="header__overlay" type="button" aria-label="<?= usticomText('Закрыть меню услуг') ?>" tabindex="-1" hidden></button>
  </header>

  <div class="mobile-menu" id="mobile-menu" role="dialog" aria-modal="true" aria-label="<?= usticomText('Меню сайта') ?>" aria-hidden="true" inert>
    <button class="mobile-menu__overlay" type="button" aria-label="<?= usticomText('Закрыть меню') ?>" tabindex="-1"></button>
    <div class="mobile-menu__panel">
      <div class="mobile-menu__header">
        <a class="main-logo mobile-menu__logo" href="<?= SITE_DIR ?>" aria-label="<?= usticomText('Юстиком — главная') ?>">
          <? $APPLICATION->IncludeFile(SITE_DIR . 'include/company-info/main-logo.php', [], ['MODE' => 'html', 'NAME' => 'Логотип', 'SHOW_BORDER' => false]); ?>
        </a>
        <div class="mobile-menu__actions">
          <?php $headerSocialFilter = ['tg', 'max'];
          include __DIR__ . '/include/header-socials.php'; ?>
          <button class="mobile-menu__close" type="button" aria-label="<?= usticomText('Закрыть меню') ?>">
            <svg width="32" height="32" aria-hidden="true">
              <use href="<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-close"></use>
            </svg>
          </button>
        </div>
      </div>
      <div class="mobile-menu__body">
        <?php $headerMenuView = 'mobile';
        include __DIR__ . '/include/header-services.php'; ?>
        <? $APPLICATION->IncludeComponent('bitrix:menu', 'header-pages', [
          'ROOT_MENU_TYPE' => 'header_pages',
          'MAX_LEVEL' => 1,
          'USE_EXT' => 'N',
          'MENU_CACHE_TYPE' => 'A',
          'MENU_CACHE_TIME' => 3600,
          'MENU_CACHE_USE_GROUPS' => 'Y',
          'MENU_CACHE_GET_VARS' => [],
          'ALLOW_MULTI_SELECT' => 'N',
        ], false, ['HIDE_ICONS' => 'Y']); ?>
        <div class="mobile-menu__contacts">
          <div class="mobile-menu__contact"><strong><?= usticomText('Номер телефона') ?></strong><? $APPLICATION->IncludeFile(SITE_DIR . 'include/phone.php', [], ['MODE' => 'html', 'NAME' => 'Телефон', 'SHOW_BORDER' => false]); ?></div>
          <div class="mobile-menu__contact"><strong><?= usticomText('Почта') ?></strong><? $APPLICATION->IncludeFile(SITE_DIR . 'include/mail.php', [], ['MODE' => 'html', 'NAME' => usticomText('Почта'), 'SHOW_BORDER' => false]); ?></div>
          <div class="mobile-menu__contact"><strong><?= usticomText('Расположение') ?></strong>
            <address><? $APPLICATION->IncludeFile(SITE_DIR . 'include/address.php', [], ['MODE' => 'html', 'NAME' => 'Адрес', 'SHOW_BORDER' => false]); ?></address>
          </div>
          <?php $headerSocialFilter = [];
          include __DIR__ . '/include/header-socials.php'; ?>
          <a class="main-btn mobile-menu__request" data-form-id="<?= usticomFormId(2) ?>" data-form-theme="<?= usticomText('Оставить заявку') ?>" data-header-request href="<?= usticomPopupUrl(2) ?>"><?= usticomText('Оставить заявку') ?></a>
        </div>
      </div>
    </div>
  </div>

  <main class="workarea">
    <?php
    $currentPagePath = (string)parse_url($APPLICATION->GetCurPage(false), PHP_URL_PATH);
    if (!in_array($currentPagePath, ["/", "/index.php", "/404.php", "/en/", "/en/index.php"], true) && !(defined("ERROR_404") && ERROR_404 === "Y")) {
      // bitrix:breadcrumb renders through GetNavChain and does not run component_epilog.php.
      includeComponentAssets("breadcrumb/littleweb");
      $APPLICATION->IncludeComponent("bitrix:breadcrumb", "littleweb", [
        "START_FROM" => 0,
        "PATH" => "",
        "SITE_ID" => SITE_ID,
      ], false, ["HIDE_ICONS" => "Y"]);
    }
    ?>
