<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Реальные отзывы клиентов, доверивших нам бухгалтерское, юридическое и налоговое сопровождение бизнеса. Оценка качества услуг от тех, кто уже с нами работал.");
$APPLICATION->SetPageProperty("title", "Отзывы о ЮСТИКОМ – реальные мнения клиентов о нашей работе");
$APPLICATION->SetTitle("Отзывы и клиенты");
?><?$APPLICATION->IncludeComponent(
	"bitrix:breadcrumb",
	"lw-breadcrumbs",
	Array(
		"COMPONENT_TEMPLATE" => "",
		"PATH" => "",
		"SITE_ID" => "s1",
		"START_FROM" => "0"
	)
);?>
<div class="lw-container-thin info-block info-block--wide">
	<div class="info-block__content">
 <section class="content-section lw-section-top-offset">
<div class="section-header">
			<h1 class="lw-accent-title lw-section-title">Конфиденциальность — базовый принцип «ЮСТИКОМ»</h1>
		</div>
		<p>
			 За более чем 20 лет успешной деятельности компания «ЮСТИКОМ» зарекомендовала себя как надёжный и профессиональный партнёр в сфере аудита, бухгалтерского и налогового консультирования, а также в области юридических, финансовых и кадровых услуг.<br>
			 Мы гордимся тем, что заслужили доверие десятков клиентов — от малых предприятий до крупных организаций. Для всех них принципиально важны такие ценности, как конфиденциальность, безопасность информации и этика делового взаимодействия.
		</p>
		<p>
		</p>
		 Конфиденциальность — один из базовых принципов нашей работы. Мы строго соблюдаем требования законодательства и профессиональных стандартов. Вся информация, полученная нами в ходе аудиторских проверок, финансового анализа, кадрового сопровождения, налогового или юридического консультирования, остаётся строго конфиденциальной. Ни одна деталь не передаётся третьим лицам без согласия клиента — ни при каких обстоятельствах.
		<p>
		</p>
		 Каждый сотрудник компании «ЮСТИКОМ» несёт персональную ответственность за соблюдение режима конфиденциальности. Мы регулярно проводим внутреннее обучение по защите данных и деловой этике, чтобы наши клиенты могли быть уверены: с их информацией обращаются бережно и ответственно. Такой подход позволяет нам не только качественно выполнять свои обязательства, но и выстраивать долгосрочные партнёрские отношения, основанные на доверии, прозрачности и взаимном уважении.
		<p>
		</p>
		 Мы готовы подтвердить свою надёжность и профессионализм результатами реальных проектов. Обращаясь в «ЮСТИКОМ», вы выбираете опыт, ответственность и безопасность.
		<p>
		</p>
 </section>
	</div>
</div>
<!--Отзывы-->
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"lw-news-licenses-list",
	Array(
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
		"COMPONENT_TEMPLATE" => "lw-news-licenses-list",
		"CONTAINER_THIN" => "Y",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(0=>"DETAIL_PICTURE",1=>"",),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "24",
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
		"PROPERTY_CODE" => array(0=>"",1=>"",),
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
);?>
<!--Клиенты-->
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"lw-news-clients-list",
	Array(
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
		"COMPONENT_TEMPLATE" => "lw-news-clients-list",
		"CONTAINER_THIN" => "Y",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(0=>"",1=>"",),
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
		"PROPERTY_CODE" => array(0=>"",1=>"",),
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
);?><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>