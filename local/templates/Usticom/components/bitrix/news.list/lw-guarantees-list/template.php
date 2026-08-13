<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:breadcrumb", 
	"lw-breadcrumbs", 
	array(
		"PATH" => "",
		"SITE_ID" => "s1",
		"START_FROM" => "0",
		"COMPONENT_TEMPLATE" => ""
	),
	false
);?>

<div class="lw-container-thin info-block info-block--wide ">
    <div class="info-block__content">
        <section class="content-section lw-section-top-offset">
            <div class="section-header">
                <h1 class="lw-accent-title lw-section-title">Документы и гарантии</h1>
			</div>
			
			<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
				"AREA_FILE_SHOW" => "file",
					"PATH" => SITE_TEMPLATE_PATH."/include/guarantees-description.php"
				),
				false,
				array()
			);?>

			<div class="swiper main-slider base-slider" style="margin:40px 0;" data-fancybox-gallery="guarantees">
				<div class="swiper-wrapper">
					<?foreach($arResult["ITEMS"] as $arItem):?>

					<div class="swiper-slide">
						<img src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>" width="<?=$arItem["DETAIL_PICTURE"]["WIDTH"]?>" height="<?=$arItem["DETAIL_PICTURE"]["HEIGHT"]?>" data-fancybox-item>
					</div>
					<?endforeach;?>
				</div>
				<div class="swiper-pagination"></div>
			</div>
        </section>
    </div>
</div>

