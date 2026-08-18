<div class="section-header">

  <? if (!empty($arParams["EYEBROW_TEXT"])): ?>
    <div class="eyebrow">
      <?= htmlspecialcharsbx($arParams["EYEBROW_TEXT"]) ?>
    </div>
  <? endif; ?>

  <h2 class="section-title">
    <?= $arParams["TITLE"] ?>
  </h2>


  <? if ($arParams["USE_SWIPER_NAVIGATION"] === "Y"): ?>
    <div class="swiper-navigation">
      <button
        type="button"
        class="swiper-button swiper-button--prev"
        aria-label="Предыдущий слайд">
        <svg width="12" height="6" aria-hidden="true">
          <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-arrow"></use>
        </svg>
      </button>

      <button
        type="button"
        class="swiper-button swiper-button--next"
        aria-label="Следующий слайд">
        <svg width="12" height="6" aria-hidden="true">
          <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/_dist/sprite.svg#icon-arrow"></use>
        </svg>
      </button>
    </div>
  <? endif; ?>
</div>