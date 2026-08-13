<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
$APPLICATION->AddHeadString('<meta property="og:image" content="https://'.  $_SERVER["SERVER_NAME"] . CFile::GetPath(CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "PICTURE")) .'"/>',true);
$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));
?>

<!-- <section class="lw-section inner-page-top">
	<div class="lw-container-fluid">
		<div class="lw-container inner-page-top__container">
			<h1 class="lw-page-title">
				<?=CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "NAME")?>
			</h1>
			<img 
				src="<?=CFile::GetPath(CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "PICTURE"))?>" 
				width="1180" height="470"
			>
		</div>
	</div>
</section> -->

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

<section class="services-main">
	<div class="lw-container-thin">
		<h1 class="lw-page-title">
			<?=CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "NAME")?>
		</h1>
		<ul class="services-main__list">
			<?foreach($arResult["SECTIONS"] as $arSection):?>
				<li class="services-main__list-item">
					<a href="<?=$arSection["SECTION_PAGE_URL"]?>">
						<img src="<?=$arSection["PICTURE"]["SRC"]?>" alt="">
						<span><?=$arSection["NAME"]?></span>
					</a>
				</li>
			<?endforeach;?>
		</ul>
	</div>
</section>

