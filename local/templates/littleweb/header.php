<!DOCTYPE html>
<html lang="ru">

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
  <main class="workarea">
    <?php
    $currentPagePath = (string)parse_url($APPLICATION->GetCurPage(false), PHP_URL_PATH);
    if (!in_array($currentPagePath, ["/", "/index.php", "/404.php"], true) && !(defined("ERROR_404") && ERROR_404 === "Y")) {
      // bitrix:breadcrumb renders through GetNavChain and does not run component_epilog.php.
      includeComponentAssets("breadcrumb/littleweb");
      $APPLICATION->IncludeComponent("bitrix:breadcrumb", "littleweb", [
        "START_FROM" => 0,
        "PATH" => "",
        "SITE_ID" => SITE_ID,
      ], false, ["HIDE_ICONS" => "Y"]);
    }
    ?>
