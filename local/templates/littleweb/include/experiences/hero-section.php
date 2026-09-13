<section class="section inner-hero">
  <div class="container">

    <div class="inner-hero__grid">

      <div class="inner-hero__grid-item inner-hero__grid-item--content">
        <div class="eyebrow">
          <? $APPLICATION->IncludeFile(
            SITE_TEMPLATE_PATH . $arParams["EYEBROW_PATH"],
            array(),
            array('MODE' => 'html', 'NAME' => 'Надпись над заголовком', 'SHOW_BORDER' => true)
          ); ?>
        </div>
        <h1 class="inner-hero__title">
          <? $APPLICATION->IncludeFile(
            SITE_TEMPLATE_PATH . $arParams["TITLE_PATH"],
            array(),
            array('MODE' => 'html', 'NAME' => 'Заголовок', 'SHOW_BORDER' => true)
          ); ?>
        </h1>
        <p class="inner-hero__text">
          <? $APPLICATION->IncludeFile(
            SITE_TEMPLATE_PATH . $arParams["TEXT_PATH"],
            array(),
            array('MODE' => 'html', 'NAME' => 'Текст', 'SHOW_BORDER' => true)
          ); ?>
        </p>

        <div class="btn-row">
          <? $APPLICATION->IncludeFile(
            SITE_TEMPLATE_PATH . $arParams["BUTTONS_PATH"],
            array(),
            array('MODE' => 'html', 'NAME' => 'Кнопки', 'SHOW_BORDER' => true)
          ); ?>
        </div>
      </div>

      <div class="inner-hero__grid-item inner-hero__grid-item--picture" aria-hidden="true">
        <?
        $APPLICATION->IncludeFile(
          SITE_TEMPLATE_PATH . $arParams["IMG_PATH"],
          array(),
          array('MODE' => 'html', 'NAME' => 'Изображение', 'SHOW_BORDER' => true)
        ); ?>
      </div>
    </div>



    <? $APPLICATION->IncludeComponent(
      "bitrix:news.list",
      "tizzers",
      [
        "IBLOCK_TYPE" => "usticom_site_content",
        "IBLOCK_ID" => "29",
        "NEWS_COUNT" => "4",
        "SORT_BY1" => "SORT",
        "SORT_ORDER1" => "ASC",
        "SORT_BY2" => "ID",
        "SORT_ORDER2" => "ASC",
        "FIELD_CODE" => ["NAME", "PREVIEW_TEXT"],
        "PROPERTY_CODE" => [],
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "DISPLAY_PICTURE" => "N",
        "CHECK_DATES" => "Y",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
        "CACHE_GROUPS" => "Y",
        "SET_TITLE" => "N",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "ADD_SECTIONS_CHAIN" => "N",
      ],
      false,
      ["HIDE_ICONS" => "Y"]
    ); ?>

  </div>
</section>
