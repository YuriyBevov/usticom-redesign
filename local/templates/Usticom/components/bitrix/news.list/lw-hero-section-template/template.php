<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>

<section class="lw-section">
    <h1 class="visually-hidden">Услуги аутсорсинга</h1>
    <div class="swiper hero-section-swiper">
        <div class="swiper-wrapper">
            <? foreach ($arResult["ITEMS"] as $arItem):
                $resImage = CFile::ResizeImageGet(
                    $arItem["DETAIL_PICTURE"],
                    array("width" => 1920, "height" => 640),
                    BX_RESIZE_IMAGE_EXACT
                );

                $resImageTablet = CFile::ResizeImageGet(
                    $arItem["PROPERTIES"]["IMAGE_TABLET"]["VALUE"],
                    array("width" => 960, "height" => 480),
                    BX_RESIZE_IMAGE_EXACT
                );

                $resImageMobile = CFile::ResizeImageGet(
                    $arItem["PROPERTIES"]["IMAGE_MOBILE"]["VALUE"],
                    array("width" => 560, "height" => 420),
                    BX_RESIZE_IMAGE_EXACT
                );
            ?>
                <div class="swiper-slide">
                    <div class="lw-container-fluid">
                        <div class="swiper-slide-content">
                            <? if ($arItem["PROPERTIES"]["SLIDE_LINK"]["VALUE"]): ?>
                                <a href="<?= $arItem["PROPERTIES"]["SLIDE_LINK"]["VALUE"] ?>">
                                <? else: ?>
                                    <button aria-label="Заказать звонок" data-modal-anchor="request-modal" style="cursor:pointer;">
                                    <? endif ?>

                                    <picture>
                                        <source media="(max-width: 535px)" srcset="<?= $resImageMobile["src"] ?>" type="image/png">
                                        <source media="(max-width: 961px)" srcset="<?= $resImageTablet["src"] ?>" type="image/png">
                                        <img src="<?= $resImage["src"] ?>" alt="" width="1920" height="640" style="width:100%;height:100%;">
                                    </picture>

                                    <? if ($arItem["PROPERTIES"]["SLIDE_LINK"]["VALUE"]): ?>
                                </a>
                            <? else: ?>
                                </button>
                            <? endif ?>

                        </div>
                    </div>
                </div>
            <? endforeach; ?>
        </div>

        <div class="swiper-pagination"></div>
    </div>
</section>