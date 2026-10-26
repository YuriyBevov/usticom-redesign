<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Партнерская программа Юстиком: рекомендуйте бухгалтерские, аудиторские, кадровые и юридические услуги и получайте вознаграждение. Выберите программу «Агент» или «Партнер».");
$APPLICATION->SetPageProperty("title", "Партнерская программа от компании Юстиком");
$APPLICATION->SetTitle("Партнерская программа");
includeComponentAssets("news.list/workflow");

$partnershipPrograms = [
    "agent" => [
        "title" => "Агент",
        "description" => "Для тех, у кого есть своя клиентская база и кто хочет самостоятельно вести переговоры, сохраняя конфиденциальность данных клиента до заключения договора.",
        "summary" => "Вы работаете с потенциальным клиентом самостоятельно при поддержке нашего менеджера.",
        "heading" => "Что делаете вы",
        "steps" => [
            "Выявляете потребность потенциального клиента в услуге.",
            "Консультируетесь с нашим менеджером по услугам компании.",
            "Передаете информацию для подготовки коммерческого предложения.",
            "Получаете готовое предложение и проводите переговоры с клиентом.",
            "Сопровождаете клиента до решения заключить договор и передаете его контакты менеджеру.",
        ],
        "result" => "Наш менеджер заключает договор. Клиент оплачивает счет — вы получаете вознаграждение.",
    ],
    "partner" => [
        "title" => "Партнер",
        "description" => "Для тех, у кого есть клиенты, друзья или деловые партнеры, заинтересованные в аудиторских, бухгалтерских, кадровых или юридических услугах.",
        "summary" => "Вы передаете нам контакты заинтересованного клиента. Переговоры, подготовку предложения и заключение договора мы берем на себя.",
        "heading" => "Что делает наш менеджер",
        "steps" => [
            "Связывается с клиентом, проводит переговоры и подбирает подходящую услугу.",
            "Готовит коммерческое предложение.",
            "Согласовывает условия и заключает договор.",
        ],
        "result" => "Клиент оплачивает счет — вы получаете свой доход. Сопровождение клиента остается за нашей командой.",
    ],
];
$partnershipSteps = [
    ["Оставьте заявку", "Заполните форму на сайте, чтобы присоединиться к партнерской программе."],
    ["Обсудите условия", "Дождитесь звонка нашего специалиста и выберите подходящий формат сотрудничества."],
    ["Заключите соглашение", "Оформите партнерское соглашение с компанией Юстиком."],
    ["Получите поддержку", "Ваш менеджер расскажет о компании и услугах, предоставит рекламные материалы."],
    ["Начните зарабатывать", "Привлекайте клиентов и получайте вознаграждение от сделок."],
];
?>

<section class="section inner-hero">
    <div class="container">
        <div class="inner-hero__grid">
            <div class="inner-hero__grid-item inner-hero__grid-item--content">
                <div class="eyebrow">расти вместе с юстиком</div>
                <h1 class="inner-hero__title">Партнерская программа «Сарафанное радио»</h1>
                <p class="inner-hero__text">Рекомендуйте наши услуги друзьям, клиентам и партнерам и получайте вознаграждение. Приглашаем к сотрудничеству физических лиц, ИП, самозанятых и представителей компаний.</p>
                <div class="btn-row">
                    <a class="main-btn main-btn--size-lg" href="#partnership-programs">Выбрать программу</a>
                    <button class="main-btn main-btn--outlined main-btn--size-lg" type="button" data-form-id="2" data-form-theme="Стать партнером">Стать партнером</button>
                </div>
            </div>
            <div class="inner-hero__grid-item inner-hero__grid-item--picture" aria-hidden="true">
                <img src="<?= SITE_TEMPLATE_PATH ?>/_src/images/inner-hero.png" alt="" width="1536" height="1024" fetchpriority="high">
            </div>
        </div>
    </div>
</section>

<section class="section partnership-programs" id="partnership-programs">
    <div class="container">
        <div class="section-header">
            <div class="eyebrow">два формата сотрудничества</div>
            <h2 class="section-title">Выберите свою партнерскую программу</h2>
        </div>
        <div data-partnership-tabs>
            <div class="partnership-programs__tabs" role="tablist" aria-label="Партнерские программы">
                <?php foreach ($partnershipPrograms as $key => $program): ?>
                    <button class="partnership-programs__tab" id="program-tab-<?= $key ?>" type="button" role="tab" aria-selected="<?= $key === 'agent' ? 'true' : 'false' ?>" aria-controls="program-panel-<?= $key ?>" tabindex="<?= $key === 'agent' ? '0' : '-1' ?>"><?= $program["title"] ?></button>
                <?php endforeach; ?>
            </div>
            <?php foreach ($partnershipPrograms as $key => $program): ?>
                <div class="partnership-programs__panel" id="program-panel-<?= $key ?>" role="tabpanel" aria-labelledby="program-tab-<?= $key ?>" tabindex="0"<?= $key === 'agent' ? '' : ' hidden' ?>>
                    <div class="partnership-programs__intro">
                        <span class="partnership-programs__title">Программа «<?= $program["title"] ?>»</span>
                        <p><?= $program["description"] ?></p>
                        <p><?= $program["summary"] ?></p>
                        <button class="main-btn main-btn--size-lg" type="button" data-form-id="2" data-form-theme="<?= htmlspecialcharsbx('Присоединиться к программе, как ' . mb_strtolower($program['title'])) ?>">Присоединиться к программе</button>
                    </div>
                    <div class="partnership-programs__details">
                        <span class="partnership-programs__subtitle"><?= $program["heading"] ?></span>
                        <ol class="partnership-programs__steps" role="list">
                            <?php foreach ($program["steps"] as $step): ?><li><?= $step ?></li><?php endforeach; ?>
                        </ol>
                        <p class="partnership-programs__result"><?= $program["result"] ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section--bg-alternative partnership-info">
    <div class="container">
        <div class="section-header">
            <div class="eyebrow">условия партнерства</div>
            <h2 class="section-title">Сотрудничество, в котором выигрывают все</h2>
        </div>
        <div class="partnership-info__grid">
            <article class="partnership-info__card">
                <span class="partnership-info__title">Кто может стать партнером</span>
                <ul>
                    <li>Менеджеры по продажам</li>
                    <li>Генеральные, финансовые и исполнительные директора</li>
                    <li>Главные бухгалтеры</li>
                    <li>Специалисты тендерных отделов</li>
                    <li>Люди с широким кругом знакомств</li>
                </ul>
            </article>
            <article class="partnership-info__card">
                <span class="partnership-info__title">Форматы сотрудничества</span>
                <ul>
                    <li>Партнеры — поставщики услуг</li>
                    <li>Партнеры — конкуренты</li>
                    <li>Партнеры — субподрядчики</li>
                    <li>Стратегические партнеры</li>
                </ul>
            </article>
            <article class="partnership-info__card">
                <span class="partnership-info__title">Ваше вознаграждение</span>
                <strong class="partnership-info__rate">5–20%</strong>
                <ul>
                    <li>Разовая услуга — от суммы договора.</li>
                    <li>Абонентское обслуживание — от суммы ежемесячного платежа в течение 6 месяцев.</li>
                </ul>
            </article>
        </div>
        <p class="partnership-info__note">Аудит, бухгалтерское сопровождение, налоговый консалтинг, кадровое делопроизводство и юридические услуги — помогите клиенту найти нужное решение. <a href="/services/">Посмотреть все услуги</a></p>
        <div class="btn-row">
            <button class="main-btn main-btn--outlined main-btn--size-lg" type="button" data-form-id="2" data-form-theme="Обсудить условия партнерской программы">Обсудить условия</button>
        </div>
    </div>
</section>

<? $APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"benefits",
	[
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
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "N",
		"DISPLAY_PREVIEW_TEXT" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => [
			0 => "",
			1 => "",
		],
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "25",
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
		"PROPERTY_CODE" => [
			0 => "",
			1 => "",
		],
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
		"COMPONENT_TEMPLATE" => "benefits",
	],
	false
); ?>

<section class="section partnership-workflow workflow">
    <div class="container">
        <ul class="workflow-list">
            <li class="workflow-list__heading">
                <div class="section-header">
                    <div class="eyebrow">пять простых шагов</div>
                    <h2 class="section-title">Как стать нашим партнером</h2>
                </div>
            </li>
            <?php foreach ($partnershipSteps as $index => $step): ?>
                <li class="workflow-list__item">
                    <span class="workflow-list__number" aria-hidden="true"><?= sprintf("%02d", $index + 1) ?></span>
                    <div class="workflow-list__content">
                        <span class="workflow-list__title"><?= $step[0] ?></span>
                        <p><?= $step[1] ?></p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="btn-row">
            <button class="main-btn main-btn--size-lg" type="button" data-form-id="2" data-form-theme="Стать партнером Юстиком">Стать партнером Юстиком</button>
        </div>
    </div>
</section>

<? $APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"features",
	[
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
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "N",
		"DISPLAY_PREVIEW_TEXT" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => [
			0 => "",
			1 => "",
		],
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "9",
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
		"PROPERTY_CODE" => [
			0 => "",
			1 => "",
		],
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
		"COMPONENT_TEMPLATE" => "features"
	],
	false
); ?>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
