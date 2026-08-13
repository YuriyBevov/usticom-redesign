<?php
namespace RedSlash\Translatorjs;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\ModuleManager;

class Main {

    protected static function getOptions($module_id)
    {
        static $options = null;

        if ($options === null) {
            $options = [
                'active'             => Option::get($module_id, 'active', 'N'),
                'widget'             => Option::get($module_id, 'widget', 'yt'),
                'lang_default'       => Option::get($module_id, 'lang_default', 'ru'),
                'langs_rtl'          => Option::get($module_id, 'langs_rtl', 'ar,fa,he,iw,sd,ur'),
                'switch_langs'       => Option::get($module_id, 'switch_langs', ''),
                'info'               => Option::get($module_id, 'info', 'N'),
                'index_translations' => Option::get($module_id, 'index_translations', 'N'),
                'bx_panel_translate' => Option::get($module_id, 'bx_panel_translate', 'N'),
            ];
        }

        return $options;
    }

    public static function translatorJsAddAssets() {
        $admin_section = (defined('ADMIN_SECTION') && ADMIN_SECTION === true);

        if (!$admin_section) {
            $module_id = pathinfo(dirname(__DIR__))['basename'];
            $module_version = ModuleManager::getVersion($module_id);

            $options = self::getOptions($module_id);

            if ($options['active'] !== 'Y') {
                return false;
            }

            Asset::getInstance()->addCss('/local/modules/' . $module_id . '/assets/css/styles.min.css?v=' . $module_version, true);
            Asset::getInstance()->addJs('/local/modules/' . $module_id . '/assets/js/js.cookie.min.js?v=' . $module_version, true);
            Asset::getInstance()->addJs('/local/modules/' . $module_id . '/assets/js/script.min.js?v=' . $module_version, true);

            $script_params = array_filter([
                'widget'            => $options['widget'],
                'langDefault'       => $options['lang_default'],
                'langsRtl'          => $options['langs_rtl'],
                'indexTranslations' => $options['index_translations'],
                'bxPanelTranslate'  => $options['bx_panel_translate'],
            ]);

            Asset::getInstance()->addString(
                '<script>window.translatorJsConfig = ' . \CUtil::PhpToJSObject($script_params) . ';</script>',
                true
            );

            return false;
        }
    }

    protected static function buildLangSwitcher($module_id, $options)
    {
        $switch_langs = array_filter(explode(',', $options['switch_langs']));
        if (empty($switch_langs)) {
            return '';
        }

        $widget = $options['widget'];
        $default = $options['lang_default'];

        $html = '<div class="translatorjs_langs">';

        // default язык
        if ($default) {
            $imgPath = "/local/modules/{$module_id}/assets/img/flags/{$default}.svg";

            $html .= '<img src="' . $imgPath . '" 
            data-translatorjs-lang="' . htmlspecialcharsbx($default) . '" 
            class="translatorjs_lang_item"
            onerror="this.outerHTML=\'<span data-translatorjs-lang=&quot;' . htmlspecialcharsbx($default) . '&quot; class=&quot;translatorjs_lang_item&quot;>' . htmlspecialcharsbx($default) . '</span>\'">';
        }

        foreach ($switch_langs as $lang) {
            $langCode = $lang;
            $langImg = $lang;

            if ($widget === 'gt' && $lang === 'cn') {
                $langCode = 'zh-CN';
                $langImg = 'cn';
            } elseif ($widget === 'yt' && $lang === 'cn') {
                $langCode = 'zh';
                $langImg = 'cn';
            }

            $imgPath = "/local/modules/{$module_id}/assets/img/flags/{$langImg}.svg";

            $html .= '<img src="' . $imgPath . '" 
            data-translatorjs-lang="' . htmlspecialcharsbx($langCode) . '" 
            class="translatorjs_lang_item"
            onerror="this.outerHTML=\'<span data-translatorjs-lang=&quot;' . htmlspecialcharsbx($langCode) . '&quot; class=&quot;translatorjs_lang_item&quot;>' . htmlspecialcharsbx($lang) . '</span>\'">';
        }

        $html .= '</div>';

        return $html;
    }

    public static function translatorJsAddHtml(&$content = null) {
        $admin_section = (defined('ADMIN_SECTION') && ADMIN_SECTION === true);

        if ($admin_section || $content === null) {
            return;
        }

        $module_id = pathinfo(dirname(__DIR__))['basename'];
        $options = self::getOptions($module_id);

        if ($options['active'] !== 'Y') {
            return false;
        }

        $injectHtml = '';

        if ($options['info'] === 'Y') {
            $injectHtml .= '<div class="translatorjs_info" data-translatorjs-off>Important! The site is translated automatically; there may be inaccuracies in the translation.</div>';
        }

        $injectHtml .= self::buildLangSwitcher($module_id, $options);

        if ($injectHtml) {
            $content = preg_replace('/<\/body>/i', $injectHtml . '</body>', $content, 1);
        }
    }

}
?>