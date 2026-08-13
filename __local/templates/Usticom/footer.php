<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<section class="callback">
    <div class="lw-container test">
        <!-- <script data-b24-form="inline/10/p10azd" data-skip-moving="true"></script> -->
        <script data-b24-form="inline/10/p10azd" data-skip-moving="true">
            (function(w, d, u) {
                var s = d.createElement('script');
                s.async = true;
                s.src = u + '?' + (Date.now() / 180000 | 0);
                var h = d.getElementsByTagName('script')[0];
                h.parentNode.insertBefore(s, h);
            })(window, document, 'https://usticom24.ru/upload/crm/form/loader_10_p10azd.js');
        </script>
        <?/*$APPLICATION->IncludeComponent(
                    "bitrix:form.result.new", 
                    "lw-form-result", 
                    array(
                        "AJAX_MODE" => "Y",
                        "AJAX_OPTION_ADDITIONAL" => "",
                        "AJAX_OPTION_HISTORY" => "Y",
                        "AJAX_OPTION_JUMP" => "N",
                        "AJAX_OPTION_STYLE" => "Y",
                        "CACHE_TIME" => "3600",
                        "CACHE_TYPE" => "N",
                        "CHAIN_ITEM_LINK" => "",
                        "CHAIN_ITEM_TEXT" => "",
                        "COMPONENT_TEMPLATE" => "lw-form-result",
                        "EDIT_URL" => "",
                        "IGNORE_CUSTOM_TEMPLATE" => "N",
                        "LIST_URL" => "",
                        "SEF_MODE" => "N",
                        "SUCCESS_URL" => "",
                        "USE_EXTENDED_ERRORS" => "Y",
                        "WEB_FORM_ID" => "1",
                        "VARIABLE_ALIASES" => array(
                            "WEB_FORM_ID" => "WEB_FORM_ID",
                            "RESULT_ID" => "RESULT_ID",
                        )
                    ),
                    false
                );*/ ?>
    </div>
</section>
</main>

<footer class="main-footer">
    <div class="lw-container-thin">
        <div class="main-footer__section main-footer__section--top">
            <div class="main-footer__section-field">
                <a class="main-logo" href="/" aria-label="https://www.usticom.ru">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_TEMPLATE_PATH . "/include/company-info/main-logo.php"
                        ),
                        false,
                        array(
                            "ACTIVE_COMPONENT" => "Y" // отключение компонента
                        )
                    ); ?>
                </a>

                <div class="footer-contacts" itemscope itemtype="https://schema.org/Organization">
                    <span style="display:none;" itemprop="name">Юстиком</span>

                    <div class="footer-contacts__field">
                        <img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/icon-phone.svg" alt="Номер телефона" width="26" height="26">
                        <span>Номер телефона</span>

                        <span style="margin:0;" itemprop="telephone">
                            <? $APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                array(
                                    "AREA_FILE_SHOW" => "file",
                                    "PATH" => SITE_TEMPLATE_PATH . "/include/company-info/main-phone.php"
                                ),
                                false,
                                array(
                                    "ACTIVE_COMPONENT" => "Y" // отключение компонента
                                )
                            ); ?>
                        </span>
                    </div>

                    <div class="footer-contacts__field">
                        <img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/icon-mail.svg" alt="Почта" width="26" height="20">
                        <span>Почта</span>
                        <span style="margin:0;" itemprop="email">
                            <? $APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                array(
                                    "AREA_FILE_SHOW" => "file",
                                    "PATH" => SITE_TEMPLATE_PATH . "/include/company-info/mail.php"
                                ),
                                false,
                                array(
                                    "ACTIVE_COMPONENT" => "Y" // отключение компонента
                                )
                            ); ?>
                        </span>
                    </div>
                    <div class="footer-contacts__field" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                        <img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/icon-pin.svg" alt="Почта" width="20" height="30">
                        <span>Расположение</span>
                        <address>
                            <? $APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                array(
                                    "AREA_FILE_SHOW" => "file",
                                    "PATH" => SITE_TEMPLATE_PATH . "/include/company-info/address.php"
                                ),
                                false,
                                array(
                                    "ACTIVE_COMPONENT" => "Y" // отключение компонента
                                )
                            ); ?>
                        </address>
                    </div>

                    <div style="display:none;">
                        <link itemprop="url" href="https://usticom.ru/">
                        <a itemprop="sameAs" href="https://t.me/Usticom">Telegram</a>
                        <a itemprop="sameAs" href="https://vk.com/usticom">Вконтакте</a>
                        <a itemprop="sameAs" href="https://www.youtube.com/channel/UCtkD3dhgy3SRy1g3eKw7zOg">Youtube</a>
                    </div>
                </div>
            </div>
            <div class="main-footer__section-field">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:menu",
                    "main-footer-nav",
                    array(
                        "ROOT_MENU_TYPE" => "bottom",
                        "MAX_LEVEL" => "1",
                        "CHILD_MENU_TYPE" => "left",
                        "USE_EXT" => "Y",
                        "MENU_CACHE_TYPE" => "A",
                        "MENU_CACHE_TIME" => "36000000",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "MENU_CACHE_GET_VARS" => array(),
                        "COMPONENT_TEMPLATE" => "usticom_multilevel_menu",
                        "DELAY" => "N",
                        "ALLOW_MULTI_SELECT" => "N"
                    ),
                    false,
                    array(
                        "ACTIVE_COMPONENT" => "Y"
                    )
                ); ?>
            </div>
            <div class="main-footer__section-field">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:menu",
                    "service-footer-nav",
                    array(
                        "ROOT_MENU_TYPE" => "bottom-service",
                        "MAX_LEVEL" => "1",
                        "CHILD_MENU_TYPE" => "left",
                        "USE_EXT" => "Y",
                        "MENU_CACHE_TYPE" => "A",
                        "MENU_CACHE_TIME" => "36000000",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "MENU_CACHE_GET_VARS" => array(),
                        "COMPONENT_TEMPLATE" => "usticom_multilevel_menu",
                        "DELAY" => "N",
                        "ALLOW_MULTI_SELECT" => "N"
                    ),
                    false,
                    array(
                        "ACTIVE_COMPONENT" => "Y"
                    )
                ); ?>
            </div>
        </div>
        <div class="main-footer__section">
            <? $APPLICATION->IncludeComponent(
                "bitrix:subscribe.form",
                "lw-subscribe-form",
                array(
                    "CACHE_TIME" => "3600",
                    "CACHE_TYPE" => "A",
                    "PAGE" => " #SITE_DIR#subscribe/edit.php",
                    "SHOW_HIDDEN" => "N",
                    "USE_PERSONALIZATION" => "Y",
                    "COMPONENT_TEMPLATE" => "lw-subscribe-form"
                ),
                false
            ); ?>
        </div>
        <div class="main-footer__section main-footer__section--bottom">
            <? $APPLICATION->IncludeComponent(
                "bitrix:news.list",
                "lw-social-list",
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
                    "DISPLAY_NAME" => "N",
                    "DISPLAY_PICTURE" => "N",
                    "DISPLAY_PREVIEW_TEXT" => "N",
                    "DISPLAY_TOP_PAGER" => "N",
                    "FIELD_CODE" => array(
                        0 => "",
                        1 => "",
                    ),
                    "FILTER_NAME" => "",
                    "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                    "IBLOCK_ID" => "15",
                    "IBLOCK_TYPE" => "usticom_site_content",
                    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                    "INCLUDE_SUBSECTIONS" => "N",
                    "MESSAGE_404" => "",
                    "NEWS_COUNT" => "10",
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
                        0 => "SOCIAL_LINK",
                        1 => "SOCIAL_TEXT",
                        2 => "",
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
                    "COMPONENT_TEMPLATE" => "lw-social-list"
                ),
                false
            ); ?>

            <span class="rights">
                © 2003—<?= date("Y") ?> Юстиком. Все&nbsp;права защищены. <a href="/privacy-policy/" target="_blank" class="lw-accent-color">Политика&nbsp;конфиденциальности</a>.
            </span>
        </div>
    </div>
</footer>



<div class="modal" id="offer-modal">
    <div class="modal__overlay">
        <div class="modal__content">
            <button class="modal-closer">
                <svg width="24" height="24">
                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/sprite.svg#icon-close"></use>
                </svg>
            </button>
            <div class="modal__content-body">
                <!-- <script data-b24-form="inline/10/p10azd" data-skip-moving="true"></script> -->

                <script data-b24-form="inline/10/p10azd" data-skip-moving="true">
                    (function(w, d, u) {
                        var s = d.createElement('script');
                        s.async = true;
                        s.src = u + '?' + (Date.now() / 180000 | 0);
                        var h = d.getElementsByTagName('script')[0];
                        h.parentNode.insertBefore(s, h);
                    })(window, document, 'https://usticom24.ru/upload/crm/form/loader_10_p10azd.js');
                </script>

                <?/*$APPLICATION->IncludeComponent(
                            "bitrix:form.result.new", 
                            "lw-form-modal", 
                            array(
                                "AJAX_MODE" => "Y",
                                "AJAX_OPTION_ADDITIONAL" => "",
                                "AJAX_OPTION_HISTORY" => "Y",
                                "AJAX_OPTION_JUMP" => "N",
                                "AJAX_OPTION_STYLE" => "Y",
                                "CACHE_TIME" => "3600",
                                "CACHE_TYPE" => "N",
                                "CHAIN_ITEM_LINK" => "",
                                "CHAIN_ITEM_TEXT" => "",
                                "COMPONENT_TEMPLATE" => "",
                                "EDIT_URL" => "",
                                "IGNORE_CUSTOM_TEMPLATE" => "Y",
                                "LIST_URL" => "",
                                "SEF_MODE" => "N",
                                "SUCCESS_URL" => "",
                                "USE_EXTENDED_ERRORS" => "Y",
                                "WEB_FORM_ID" => "2",
                                "VARIABLE_ALIASES" => array(
                                    "WEB_FORM_ID" => "WEB_FORM_ID",
                                    "RESULT_ID" => "RESULT_ID",
                                )
                            ),
                            false
                        );*/ ?>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="request-modal">
    <div class="modal__overlay">
        <div class="modal__content">
            <button class="modal-closer">
                <svg width="24" height="24">
                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/sprite.svg#icon-close"></use>
                </svg>
            </button>
            <div class="modal__content-body">
                <!-- <script data-b24-form="inline/10/p10azd" data-skip-moving="true"></script> -->

                <script data-b24-form="inline/10/p10azd" data-skip-moving="true">
                    (function(w, d, u) {
                        var s = d.createElement('script');
                        s.async = true;
                        s.src = u + '?' + (Date.now() / 180000 | 0);
                        var h = d.getElementsByTagName('script')[0];
                        h.parentNode.insertBefore(s, h);
                    })(window, document, 'https://usticom24.ru/upload/crm/form/loader_10_p10azd.js');
                </script>

                <?/*$APPLICATION->IncludeComponent(
                            "bitrix:form.result.new", 
                            "lw-form-modal", 
                            array(
                                "AJAX_MODE" => "Y",
                                "AJAX_OPTION_ADDITIONAL" => "",
                                "AJAX_OPTION_HISTORY" => "Y",
                                "AJAX_OPTION_JUMP" => "N",
                                "AJAX_OPTION_STYLE" => "Y",
                                "CACHE_TIME" => "3600",
                                "CACHE_TYPE" => "N",
                                "CHAIN_ITEM_LINK" => "",
                                "CHAIN_ITEM_TEXT" => "",
                                "COMPONENT_TEMPLATE" => "",
                                "EDIT_URL" => "",
                                "IGNORE_CUSTOM_TEMPLATE" => "Y",
                                "LIST_URL" => "",
                                "SEF_MODE" => "N",
                                "SUCCESS_URL" => "",
                                "USE_EXTENDED_ERRORS" => "Y",
                                "WEB_FORM_ID" => "3",
                                "VARIABLE_ALIASES" => array(
                                    "WEB_FORM_ID" => "WEB_FORM_ID",
                                    "RESULT_ID" => "RESULT_ID",
                                )
                            ),
                            false
                        );*/ ?>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="cost-request-modal">
    <div class="modal__overlay">
        <div class="modal__content">
            <button class="modal-closer">
                <svg width="24" height="24">
                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/sprite.svg#icon-close"></use>
                </svg>
            </button>
            <div class="modal__content-body">
                <script data-b24-form="inline/1/fax4od" data-skip-moving="true"></script>
                <?/*$APPLICATION->IncludeComponent(
                            "bitrix:form.result.new", 
                            "lw-form-modal", 
                            array(
                                "AJAX_MODE" => "Y",
                                "AJAX_OPTION_ADDITIONAL" => "",
                                "AJAX_OPTION_HISTORY" => "Y",
                                "AJAX_OPTION_JUMP" => "N",
                                "AJAX_OPTION_STYLE" => "Y",
                                "CACHE_TIME" => "3600",
                                "CACHE_TYPE" => "N",
                                "CHAIN_ITEM_LINK" => "",
                                "CHAIN_ITEM_TEXT" => "",
                                "COMPONENT_TEMPLATE" => "",
                                "EDIT_URL" => "",
                                "IGNORE_CUSTOM_TEMPLATE" => "Y",
                                "LIST_URL" => "",
                                "SEF_MODE" => "N",
                                "SUCCESS_URL" => "",
                                "USE_EXTENDED_ERRORS" => "Y",
                                "WEB_FORM_ID" => "4",
                                "VARIABLE_ALIASES" => array(
                                    "WEB_FORM_ID" => "WEB_FORM_ID",
                                    "RESULT_ID" => "RESULT_ID",
                                )
                            ),
                            false
                        );*/ ?>
            </div>
        </div>
    </div>
</div>

<? $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH . "/include/preloader.php", array(), array("MODE" => "html")); ?>

<script>
    window.roistatVisitCallback = function(visitId) {
        window.addEventListener('b24:form:init', (event) => {
            let form = event.detail.object;
            form.setProperty("roistatID", visitId);
        });

        (function(w, d, u) {
            var s = d.createElement('script');
            s.async = true;
            s.src = u + '?' + (Date.now() / 180000 | 0);
            var h = d.getElementsByTagName('script')[0];
            h.parentNode.insertBefore(s, h);
        })
        (window, document, 'https://usticom24.ru/upload/crm/form/loader_10_p10azd.js');
        (function(w, d, u) {
            var s = d.createElement('script');
            s.async = true;
            s.src = u + '?' + (Date.now() / 180000 | 0);
            var h = d.getElementsByTagName('script')[0];
            h.parentNode.insertBefore(s, h);
        })
        (window, document, 'https://usticom24.ru/upload/crm/form/loader_1_fax4od.js');
    }
</script>

<script src="//cdn.callibri.ru/callibri.js" type="text/javascript" charset="utf-8" defer></script>
<? include_once($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/include/cookie/template.php"); ?>
</body>

</html>