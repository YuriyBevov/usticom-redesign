<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

if(!$arResult["NavShowAlways"])
{
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
		return;
}

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
?>

<div class="pagination">
	<div class="pagination__field">
		<span class="pagination__item pagination__item--count">
			Показано:
			<?=$arResult["NavFirstRecordShow"]?>
			<?=GetMessage("nav_to")?>
			<?=$arResult["NavLastRecordShow"]?>
			<?=GetMessage("nav_of")?>
			<?=$arResult["NavRecordCount"]?>
		</span>
	</div>
	
	<div class="pagination__field">
		
		<?if ($arResult["NavPageNomer"] > 1):?>

			<?if($arResult["bSavePage"]):?>
				<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1" class="pagination__item pagination__item--side">Начало</a>
				<div class="pagination__main">
				<a 	
					href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>" 
					class="pagination__item pagination__item--prev"
				>
					<svg width="16" height="16">
						<use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/sprite.svg#icon-arrow"></use>
					</svg>
				</a>
			<?else:?>
				<a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" class="pagination__item pagination__item--side">Начало</a>
				
				<?if ($arResult["NavPageNomer"] > 2):?>
					<div class="pagination__main">
					<a 	
						href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>" 
						class="pagination__item pagination__item--prev"
					>
						<svg width="16" height="16">
							<use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/sprite.svg#icon-arrow"></use>
						</svg>
					</a>
				<?else:?>
					<div class="pagination__main">
					<a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" class="pagination__item pagination__item--prev">
						<svg width="16" height="16">
							<use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/sprite.svg#icon-arrow"></use>
						</svg>
					</a>
				<?endif?>
				
			<?endif?>

		<?else:?>
			<span class="pagination__item pagination__item--disabled pagination__item--side">Начало</span>
			<div class="pagination__main">
			<span class="pagination__item pagination__item--prev pagination__item--disabled ">
				<svg width="16" height="16">
					<use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/sprite.svg#icon-arrow"></use>
				</svg>
			</span>
		<?endif?>

		<?while($arResult["nStartPage"] <= $arResult["nEndPage"]):?>
			
				<?if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
					<span class="pagination__item pagination__item--active pagination__item--num"><?=$arResult["nStartPage"]?></span>
				<?elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):?>
					<a class="pagination__item pagination__item--num" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$arResult["nStartPage"]?></a>
				<?else:?>
					<a class="pagination__item pagination__item--num" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a>
				<?endif?>
				<?$arResult["nStartPage"]++?>
			
		<?endwhile?>
		

		<?if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
			<a 
				href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"
				class="pagination__item pagination__item--next"
			>
				<svg width="16" height="16">
					<use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/sprite.svg#icon-arrow"></use>
				</svg>
			</a>
			</div>
			<a class="pagination__item pagination__item--side" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>">Конец</a>
		<?else:?>

			<span class="pagination__item pagination__item--next pagination__item--disabled ">
				<svg width="16" height="16">
					<use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/sprite.svg#icon-arrow"></use>
				</svg>
			</span>
			</div>
			<span class="pagination__item pagination__item--disabled pagination__item--side">Конец</span> 

		<?endif?>
	</div>
	
	<?if ($arResult["bShowAll"]):?>
		<div class="pagination__field pagination__field--checker">
			<noindex>
				<?if ($arResult["NavShowAll"]):?>
					<a class="pagination__item pagination__item--checker" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=0" rel="nofollow"><?=GetMessage("nav_paged")?></a>
				<?else:?>
					<a class="pagination__item pagination__item--checker" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=1" rel="nofollow"><?=GetMessage("nav_all")?></a>
				<?endif?>
			</noindex>
		</div>
	<?endif?>
</div>
