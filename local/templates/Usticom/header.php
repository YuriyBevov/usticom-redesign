<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<!DOCTYPE html>
<html xmlns="https://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">

<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?/*$APPLICATION->ShowHead();*/ ?>
  <!--<link href="<?/*=SITE_TEMPLATE_PATH*/ ?>/common.css" type="text/css" rel="stylesheet" />-->

  <title><? $APPLICATION->ShowTitle() ?></title>

  <? $APPLICATION->ShowMeta("robots"); ?>
  <? $APPLICATION->ShowMeta("description"); ?>
  <?/*$APPLICATION->ShowLink("canonical");*/ ?>
  <? $APPLICATION->ShowCSS(); ?>
  <? $APPLICATION->ShowHeadStrings(); ?>
  <? $APPLICATION->ShowHeadScripts(); ?>

  <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
  <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
  <link rel="shortcut icon" href="/favicon.ico" />
  <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
  <link rel="manifest" href="/site.webmanifest" />


  <link href="<?= SITE_TEMPLATE_PATH ?>/assets/main.css" type="text/css" rel="stylesheet" />
  <link href="<?= SITE_TEMPLATE_PATH ?>/assets/custom.css?v=<?= md5_file($_SERVER['DOCUMENT_ROOT'] . '/' . SITE_TEMPLATE_PATH . '/assets/custom.css') ?>" type="text/css" rel="stylesheet" />
  <script defer src="<?= SITE_TEMPLATE_PATH ?>/assets/bundle.js"></script>

  <!-- Yandex.Metrika counter -->
  <script type="text/javascript">
    (function(m, e, t, r, i, k, a) {
      m[i] = m[i] || function() {
        (m[i].a = m[i].a || []).push(arguments)
      };
      m[i].l = 1 * new Date();
      for (var j = 0; j < document.scripts.length; j++) {
        if (document.scripts[j].src === r) {
          return;
        }
      }
      k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
    })
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

    ym(32182839, "init", {
      clickmap: true,
      trackLinks: true,
      accurateTrackBounce: true,
      webvisor: true
    });
  </script>
  <noscript>
    <div><img src="https://mc.yandex.ru/watch/32182839" style="position:absolute; left:-9999px;" alt="" /></div>
  </noscript>
  <!-- /Yandex.Metrika counter -->

  <!-- Yandex.Metrika counter -->
  <script type="text/javascript">
    (function(m, e, t, r, i, k, a) {
      m[i] = m[i] || function() {
        (m[i].a = m[i].a || []).push(arguments)
      };
      m[i].l = 1 * new Date();
      for (var j = 0; j < document.scripts.length; j++) {
        if (document.scripts[j].src === r) {
          return;
        }
      }
      k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
    })(window, document, 'script', 'https://mc.yandex.ru/metrika/tag.js?id=106890300', 'ym');

    ym(106890300, 'init', {
      ssr: true,
      webvisor: true,
      clickmap: true,
      ecommerce: "dataLayer",
      referrer: document.referrer,
      url: location.href,
      accurateTrackBounce: true,
      trackLinks: true
    });
  </script>
  <noscript>
    <div><img src="https://mc.yandex.ru/watch/106890300" style="position:absolute; left:-9999px;" alt="" /></div>
  </noscript>
  <!-- /Yandex.Metrika counter -->

  <?
  $url = $APPLICATION->GetCurPage();
  ?>

  <meta property="og:site_name" content="Юстиком" />
  <meta property="og:title" content="<?= $APPLICATION->ShowTitle(); ?>" />
  <meta property="og:description" content="<? $APPLICATION->ShowProperty('description'); ?>" />
  <? if ($url == '/') : ?>
    <meta property="og:image" content="<?= SITE_TEMPLATE_PATH ?>/assets/img/logo.svg">
  <? endif; ?>
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://usticom.ru<?= $url ?>" />

  <!-- <script src="https://www.google.com/recaptcha/api.js?render=6LdeZMsoAAAAAJL9C73NIc9uGZ0uB3LivVJP2Cj3"></script> -->

  <script>
    (function(w, d, s, h, id) {
      w.roistatProjectId = id;
      w.roistatHost = h;
      var p = d.location.protocol == "https:" ? "https://" : "http://";
      var u = /^.*roistat_visit=[^;]+(.*)?$/.test(d.cookie) ? "/dist/module.js" : "/api/site/1.0/" + id + "/init?referrer=" + encodeURIComponent(d.location.href);
      var js = d.createElement(s);
      js.charset = "UTF-8";
      js.async = 1;
      js.src = p + h + u;
      var js2 = d.getElementsByTagName(s)[0];
      js2.parentNode.insertBefore(js, js2);
    })(window, document, 'script', 'cloud.roistat.com', '3cbcad3714a45583dae6711193878373');
  </script>

  <? include_once $_SERVER['DOCUMENT_ROOT'] . '/local/templates/Usticom/include/canonical.php'; ?>

  <script type="text/javascript">
    window._ab_id_ = 168088
  </script>
  <script src="https://cdn.botfaqtor.ru/one.js"></script>
</head>

<body>



  <div id="panel"><? $APPLICATION->ShowPanel(); ?></div>
  <header class="main-header">
    <div class="lw-container main-header__container">

      <div class="main-header__side">
        <a class="main-logo" href="/" aria-label="https://www.usticom.ru">
          <? $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            array(
              "AREA_FILE_SHOW" => "file",
              "PATH" => SITE_TEMPLATE_PATH . "/include/company-info/main-logo.php"
            ),
            false,
            array(
              "ACTIVE_COMPONENT" => "Y" // отключение компонента
            )
          ); ?>
        </a>
      </div>

      <div class="main-header__content">
        <div class="main-header__section main-header__section--top">
          <div class="main-header__info">
            <div class="lw-iconed-link">
              <svg width="18" height="18">
                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/sprite.svg#icon-address"></use>
              </svg>
              <span>

                <? $APPLICATION->IncludeComponent(
                  "bitrix:main.include",
                  "",
                  array(
                    "AREA_FILE_SHOW" => "file",
                    "PATH" => SITE_TEMPLATE_PATH . "/include/company-info/header-address.php"
                  ),
                  false,
                  array(
                    "ACTIVE_COMPONENT" => "Y"
                  )
                ); ?>
              </span>
            </div>

            <div class="lw-iconed-link">
              <svg width="18" height="18">
                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/sprite.svg#icon-phone"></use>
              </svg>
              <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                array(
                  "AREA_FILE_SHOW" => "file",
                  "PATH" => SITE_TEMPLATE_PATH . "/include/company-info/main-phone.php"
                ),
                false,
                array(
                  "ACTIVE_COMPONENT" => "Y"
                )
              ); ?>
            </div>

            <div class="lw-iconed-link">
              <svg width="18" height="18">
                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/sprite.svg#icon-mail"></use>
              </svg>
              <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                array(
                  "AREA_FILE_SHOW" => "file",
                  "PATH" => SITE_TEMPLATE_PATH . "/include/company-info/mail.php"
                ),
                false,
                array(
                  "ACTIVE_COMPONENT" => "Y"
                )
              ); ?>
            </div>
          </div>
          <? $APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "lw-social-list",
            [
              "ACTIVE_DATE_FORMAT" => "d.m.Y",
              "ADD_SECTIONS_CHAIN" => "N",
              "AJAX_MODE" => "N",
              "AJAX_OPTION_ADDITIONAL" => "",
              "AJAX_OPTION_HISTORY" => "N",
              "AJAX_OPTION_JUMP" => "N",
              "AJAX_OPTION_STYLE" => "Y",
              "CACHE_FILTER" => "N",
              "CACHE_GROUPS" => "Y",
              "CACHE_TIME" => "36000000",
              "CACHE_TYPE" => "A",
              "CHECK_DATES" => "Y",
              "DETAIL_URL" => "",
              "DISPLAY_BOTTOM_PAGER" => "N",
              "DISPLAY_DATE" => "N",
              "DISPLAY_NAME" => "N",
              "DISPLAY_PICTURE" => "N",
              "DISPLAY_PREVIEW_TEXT" => "N",
              "DISPLAY_TOP_PAGER" => "N",
              "FIELD_CODE" => [
                0 => "",
                1 => "",
              ],
              "FILTER_NAME" => "",
              "HIDE_LINK_WHEN_NO_DETAIL" => "N",
              "IBLOCK_ID" => "15",
              "IBLOCK_TYPE" => "usticom_site_content",
              "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
              "INCLUDE_SUBSECTIONS" => "N",
              "MESSAGE_404" => "",
              "NEWS_COUNT" => "6",
              "PAGER_BASE_LINK_ENABLE" => "N",
              "PAGER_DESC_NUMBERING" => "N",
              "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
              "PAGER_SHOW_ALL" => "N",
              "PAGER_SHOW_ALWAYS" => "N",
              "PAGER_TEMPLATE" => ".default",
              "PAGER_TITLE" => "Новости",
              "PARENT_SECTION" => "",
              "PARENT_SECTION_CODE" => "",
              "PREVIEW_TRUNCATE_LEN" => "",
              "PROPERTY_CODE" => [
                0 => "SOCIAL_LINK",
                1 => "SOCIAL_TEXT",
                2 => "",
              ],
              "SET_BROWSER_TITLE" => "N",
              "SET_LAST_MODIFIED" => "N",
              "SET_META_DESCRIPTION" => "N",
              "SET_META_KEYWORDS" => "N",
              "SET_STATUS_404" => "N",
              "SET_TITLE" => "N",
              "SHOW_404" => "N",
              "SORT_BY1" => "ACTIVE_FROM",
              "SORT_BY2" => "SORT",
              "SORT_ORDER1" => "DESC",
              "SORT_ORDER2" => "ASC",
              "STRICT_SECTION_CHECK" => "N",
              "COMPONENT_TEMPLATE" => "lw-social-list"
            ],
            false
          ); ?>
        </div>
        <div class="main-header__section main-header__section--bottom" style="justify-content: flex-end;">
          <? $APPLICATION->IncludeComponent(
            "bitrix:menu",
            "usticom_multilevel_menu",
            [
              "ROOT_MENU_TYPE" => "top",
              "MAX_LEVEL" => "2",
              "CHILD_MENU_TYPE" => "left",
              "USE_EXT" => "Y",
              "MENU_CACHE_TYPE" => "A",
              "MENU_CACHE_TIME" => "36000000",
              "MENU_CACHE_USE_GROUPS" => "Y",
              "MENU_CACHE_GET_VARS" => [],
              "COMPONENT_TEMPLATE" => "usticom_multilevel_menu",
              "DELAY" => "N",
              "ALLOW_MULTI_SELECT" => "N"
            ],
            false,
            [
              "ACTIVE_COMPONENT" => "Y"
            ]
          ); ?>
          <button class="main-header__request lw-main-btn" type="button" data-modal-anchor="request-modal">Оставить заявку</button>
          <div class="lw-iconed-link lw-iconed-link--header-mobile">
            <a href="tel:+74952879268">
              <svg width="22" height="22">
                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/sprite.svg#icon-phone"></use>
              </svg>
            </a>
          </div>
          <button class="main-nav__opener" type="button" aria-label="Открыть меню">
            <svg width="24" height="24">
              <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/sprite.svg#icon-burger"></use>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </header>

  <main>