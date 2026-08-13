<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
$APPLICATION->AddHeadString('<meta property="og:image" content="https://' .  $_SERVER["SERVER_NAME"] . CFile::GetPath($arResult["SECTION"]["DETAIL_PICTURE"]) . '"/>', true);
?>

<? $APPLICATION->IncludeComponent(
	"bitrix:breadcrumb",
	"lw-breadcrumbs",
	array(
		"PATH" => "",
		"SITE_ID" => "s1",
		"START_FROM" => "0",
		"COMPONENT_TEMPLATE" => ""
	),
	false
); ?>



<div class="lw-container-thin info-block">
	<div class="info-block__sidebar"><span class="info-block__sidebar-title">Дополнительно</span>
		<?
		$arSelect = array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "NAME", "DETAIL_PAGE_URL", "CODE");
		$arFilter = array("IBLOCK_ID" => $arResult["IBLOCK_ID"], "IBLOCK_SECTION_ID" => $arResult["IBLOCK_SECTION_ID"], "ACTIVE" => "Y");
		$res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
		?>
		<div class="left-menu">
			<ul class="left-menu__list">
				<?
				while ($ob = $res->GetNextElement()):
					$arFields = $ob->GetFields();
					$arProps = $ob->GetProperties();
				?>
					<? if ($arFields["NAME"] != $arResult["NAME"]): ?>
						<li class="left-menu__list-item">
							<a href="<?= $arFields["DETAIL_PAGE_URL"] ?>">
								<?= $arProps["TOP_MENU_TITLE"]["VALUE"] !== '' ? $arProps["TOP_MENU_TITLE"]["VALUE"] : $arFields["NAME"] ?>
							</a>
						</li>
					<? else: ?>
						<li class="left-menu__list-item"><span><?= $arProps["TOP_MENU_TITLE"]["VALUE"] !== '' ? $arProps["TOP_MENU_TITLE"]["VALUE"] : $arFields["NAME"] ?></span></li>
					<? endif; ?>
				<? endwhile; ?>
			</ul>
		</div>
	</div>
	<div class="info-block__content">
		<section class="lw-section content-section">
			<h1 class="title"><?= $arResult["NAME"] ?></h1>

			<? if ($arResult["DETAIL_TEXT"]): ?>
				<?= $arResult["DETAIL_TEXT"] ?>
			<? endif; ?>

			<? foreach ($arResult["PROPERTIES"]["SECTION_BLOCK_CONTENTS"]["~VALUE"] as $arItem): ?>
				<div class="content-block">
					<?= $arItem["TEXT"] ?>
				</div>
			<? endforeach; ?>

			<button style="background-color: var(--accent); margin:24px auto;min-width:240px;" class="lw-main-btn" type="button" data-modal-anchor="request-modal">Оставить заявку</button>

			<? if (!empty($arResult["SERVICE_PRICES"])): ?>

				<span class="title">Стоимость услуг</span>
				<div class="price-block">
					<? if ($arResult['UF_PRICE_SECTION_TEXT_BEFORE']): ?>
						<p><?= $arResult['UF_PRICE_SECTION_TEXT_BEFORE'] ?></p>
					<? endif; ?>
					<ul>
						<? foreach ($arResult["SERVICE_PRICES"] as $price): ?>
							<li>
								<? if (!empty($price["service_link"])): ?>
									<a href="<?= $price["service_link"] ?>" class="price-block__title">
										<span>
											<b><?= $price["service_name"] ?></b>
										</span>
									</a>
								<? else: ?>
									<span class="price-block__title">
										<b><?= $price["service_name"] ?></b>
									</span>
								<? endif; ?>

								<span class="price-block__value">
									<b><?= $price["service_price_value"] ?></b>
								</span>

								<? if ($price["service_price_note"]): ?>
									<span><?= $price["service_price_note"] ?></span>
								<? endif; ?>

							</li>
						<? endforeach; ?>
					</ul>

					<? if ($arResult['UF_PRICE_SECTION_TEXT_AFTER']): ?>
						<p><?= $arResult['UF_PRICE_SECTION_TEXT_AFTER'] ?></p>
					<? endif; ?>
				</div>
				<button style="background-color: var(--accent); margin:24px auto;min-width:240px;" class="lw-main-btn" type="button" data-modal-anchor="request-modal">Оставить заявку</button>
			<? endif; ?>

		</section>

		<? if (!empty($arResult['PROPERTIES']['FAQ_LINKED']['VALUE'])) { ?>

			<?
			$GLOBALS['arFaqFilter'] = array('ID' => $arResult['PROPERTIES']['FAQ_LINKED']['VALUE']);

			$APPLICATION->IncludeComponent(
				"bitrix:news.list",
				"lw-faq-list",
				array(
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
					"DISPLAY_BOTTOM_PAGER" => "Y",
					"DISPLAY_DATE" => "Y",
					"DISPLAY_NAME" => "Y",
					"DISPLAY_PICTURE" => "Y",
					"DISPLAY_PREVIEW_TEXT" => "Y",
					"DISPLAY_TOP_PAGER" => "N",
					"FIELD_CODE" => array(
						0 => "NAME",
						1 => "PREVIEW_TEXT",
						2 => "",
					),
					"FILTER_NAME" => "arFaqFilter",
					"HIDE_LINK_WHEN_NO_DETAIL" => "N",
					"IBLOCK_ID" => "26",
					"IBLOCK_TYPE" => "usticom_site_content",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"INCLUDE_SUBSECTIONS" => "N",
					"MESSAGE_404" => "",
					"NEWS_COUNT" => "20",
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
					"PROPERTY_CODE" => array(
						0 => "",
						1 => "",
					),
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
					"COMPONENT_TEMPLATE" => "lw-faq-list"
				),
				false
			);
			unset($GLOBALS['arFaqFilter']);

			?>
			<button style="background-color: var(--accent);margin:24px auto;min-width:240px;" class="lw-main-btn" type="button" data-modal-anchor="request-modal">Оставить заявку</button>
		<?		}
		?>


		<!--Преимущества-->
		<? $APPLICATION->IncludeComponent(
			"bitrix:news.list",
			"lw-news-features-inner-list",
			array(
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
				"DISPLAY_NAME" => "Y",
				"DISPLAY_PICTURE" => "Y",
				"DISPLAY_PREVIEW_TEXT" => "Y",
				"DISPLAY_TOP_PAGER" => "N",
				"FIELD_CODE" => array("", ""),
				"FILTER_NAME" => "",
				"HIDE_LINK_WHEN_NO_DETAIL" => "N",
				"IBLOCK_ID" => "9",
				"IBLOCK_TYPE" => "usticom_site_content",
				"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
				"INCLUDE_SUBSECTIONS" => "N",
				"MESSAGE_404" => "",
				"NEWS_COUNT" => "5",
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
				"PROPERTY_CODE" => array("", ""),
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
				"STRICT_SECTION_CHECK" => "N"
			)
		); ?>

		<!--Клиенты-->
		<? $APPLICATION->IncludeComponent(
			"bitrix:news.list",
			"lw-news-clients-inner-list",
			array(
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
				"DISPLAY_NAME" => "Y",
				"DISPLAY_PICTURE" => "Y",
				"DISPLAY_PREVIEW_TEXT" => "Y",
				"DISPLAY_TOP_PAGER" => "N",
				"FIELD_CODE" => array(
					0 => "",
					1 => "",
				),
				"FILTER_NAME" => "",
				"HIDE_LINK_WHEN_NO_DETAIL" => "N",
				"IBLOCK_ID" => "10",
				"IBLOCK_TYPE" => "usticom_site_content",
				"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
				"INCLUDE_SUBSECTIONS" => "N",
				"MESSAGE_404" => "",
				"NEWS_COUNT" => "1000",
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
				"PROPERTY_CODE" => array(
					0 => "",
					1 => "",
				),
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
				"COMPONENT_TEMPLATE" => "lw-news-clients-inner-list"
			),
			false
		); ?>

		<!--Сертификаты и гарантии-->
		<? $APPLICATION->IncludeComponent(
			"bitrix:news.list",
			"lw-news-licenses-inner-list",
			array(
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
				"DISPLAY_NAME" => "Y",
				"DISPLAY_PICTURE" => "Y",
				"DISPLAY_PREVIEW_TEXT" => "Y",
				"DISPLAY_TOP_PAGER" => "N",
				"FIELD_CODE" => array(
					0 => "DETAIL_PICTURE",
					1 => "",
				),
				"FILTER_NAME" => "",
				"HIDE_LINK_WHEN_NO_DETAIL" => "N",
				"IBLOCK_ID" => "17",
				"IBLOCK_TYPE" => "usticom_site_content",
				"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
				"INCLUDE_SUBSECTIONS" => "N",
				"MESSAGE_404" => "",
				"NEWS_COUNT" => "1000",
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
				"PROPERTY_CODE" => array(
					0 => "",
					1 => "",
				),
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
				"COMPONENT_TEMPLATE" => "lw-news-licenses-inner-list"
			),
			false
		); ?>

		<? if ($arResult["PROPERTIES"]["RELATED_SECTIONS"]["VALUE"]): ?>
			<section class="more">
				<span class="more__title">Вам также могут быть интересны:</span>
				<ul class="more__list">
					<?
					if (!CModule::IncludeModule("iblock"))
						return;

					foreach ($arResult["PROPERTIES"]["RELATED_SECTIONS"]["VALUE"] as $relatedSection):
						$item = CIBlockElement::GetByID($relatedSection);
						if ($res = $item->GetNext()):
					?>
							<? if ($res): ?>
								<li class="more__list-item"><a href="<?= $res["DETAIL_PAGE_URL"] ?>"><?= $res["NAME"] ?></a></li>
							<? endif; ?>
					<?
						endif;
					endforeach;
					?>
				</ul>
			</section>
		<? endif; ?>
	</div>
</div>