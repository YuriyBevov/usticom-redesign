<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<?if($arResult["ITEMS"]):?>
  <section class="rate">
    <div class="lw-container">
      <ul class="rate-list">
        <?foreach($arResult["ITEMS"] as $arItem):?>
        <li class="rate-list__item">
          <div class="rate-list__item-header">
            <?if($arItem["PREVIEW_PICTURE"]):?>
              <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>" width="120" height="80">
            <?else:?>
              <?if($arItem["PROPERTIES"]["COUNT"]["VALUE"]):?>
                <span class="rate-list__item-header-count"><?=$arItem["PROPERTIES"]["COUNT"]["VALUE"]?></span>
                <?if($arItem["PROPERTIES"]["DESCRIPTION"]["VALUE"]):?>
                  <span class="rate-list__item-header-description"><?=$arItem["PROPERTIES"]["DESCRIPTION"]["VALUE"]?></span>
                <?endif;?>
              <?endif;?>
            <?endif;?>
          </div>
          <div class="rate-list__item-content">
            <?=$arItem["PREVIEW_TEXT"]?>
          </div>
        </li>
        <?endforeach;?>
      </ul>
    </div>
  </section>
<?endif;?> 