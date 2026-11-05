<section class="section inner-hero">
  <div class="container">

    <div class="inner-hero__grid">

      <div class="inner-hero__grid-item inner-hero__grid-item--content">
        <div class="eyebrow">
          <? $APPLICATION->IncludeFile(
            SITE_DIR . ltrim($arParams["EYEBROW_PATH"], "/"),
            array(),
            array('MODE' => 'html', 'NAME' => 'Надпись над заголовком', 'SHOW_BORDER' => true)
          ); ?>
        </div>
        <h1 class="inner-hero__title">
          <? $APPLICATION->IncludeFile(
            SITE_DIR . ltrim($arParams["TITLE_PATH"], "/"),
            array(),
            array('MODE' => 'html', 'NAME' => 'Заголовок', 'SHOW_BORDER' => true)
          ); ?>
        </h1>
        <p class="inner-hero__text">
          <? $APPLICATION->IncludeFile(
            SITE_DIR . ltrim($arParams["TEXT_PATH"], "/"),
            array(),
            array('MODE' => 'html', 'NAME' => 'Текст', 'SHOW_BORDER' => true)
          ); ?>
        </p>

        <div class="btn-row">
          <? $APPLICATION->IncludeFile(
            SITE_DIR . ltrim($arParams["BUTTONS_PATH"], "/"),
            array(),
            array('MODE' => 'html', 'NAME' => 'Кнопки', 'SHOW_BORDER' => true)
          ); ?>
        </div>
      </div>

      <div class="inner-hero__grid-item inner-hero__grid-item--picture" aria-hidden="true">
        <?
        $APPLICATION->IncludeFile(
          SITE_DIR . ltrim($arParams["IMG_PATH"], "/"),
          array(),
          array('MODE' => 'html', 'NAME' => 'Изображение', 'SHOW_BORDER' => true)
        ); ?>
      </div>
    </div>



    <?
    includeHeroTizzers((array)($arParams["HERO_TIZZERS"] ?? []));
    ?>

  </div>
</section>
