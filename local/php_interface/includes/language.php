<?php

function usticomIsEnglish(): bool
{
    return (defined('SITE_ID') && SITE_ID === 'en')
        || str_starts_with((string)($_SERVER['REQUEST_URI'] ?? ''), '/en/');
}

function usticomText(string $russian): string
{
    if (!usticomIsEnglish()) return $russian;
    static $messages;
    $messages ??= require __DIR__.'/../lang/en/interface.php';
    return $messages[$russian] ?? $russian;
}

function usticomIblock(string $role): int
{
    $ru = ['services' => 5, 'prices' => 22, 'faq' => 26, 'tizzers' => 29];
    if (!usticomIsEnglish()) return $ru[$role] ?? 0;
    static $ids = [];
    if (!isset($ids[$role]) && \Bitrix\Main\Loader::includeModule('iblock')) {
        $block = CIBlock::GetList([], ['TYPE' => 'usticom_site_content_en', '=CODE' => 'usticom_en_'.$role])->Fetch();
        $ids[$role] = (int)($block['ID'] ?? 0);
    }
    return $ids[$role] ?? 0;
}

function usticomFormId(int $russianId): int
{
    if (!usticomIsEnglish()) return $russianId;
    static $id;
    if ($id === null && \Bitrix\Main\Loader::includeModule('form')) {
        $by = 's_id'; $order = 'asc'; $filtered = false;
        $form = CForm::GetList($by, $order, ['SID' => 'USTICOM_EN_REQUEST', 'SID_EXACT_MATCH' => 'Y'], $filtered, 'N')->Fetch();
        $id = (int)($form['ID'] ?? 0);
    }
    return $id ?? 0;
}

function usticomPopupUrl(int $form): string
{
    return (usticomIsEnglish() ? '/en' : '').'/local/ajax/popup_form.php?form_id='.usticomFormId($form);
}

/** Use native SEO fields for ordinary EN pages as well as service cards. */
function usticomSetPageMeta(string $code, string $fallback): void
{
    global $APPLICATION;
    $APPLICATION->SetTitle($fallback);
    if (!\Bitrix\Main\Loader::includeModule('iblock')) return;
    $block = CIBlock::GetList([], ['TYPE' => 'usticom_site_content_en', '=CODE' => 'usticom_en_pages'])->Fetch();
    if (!$block) return;
    $element = CIBlockElement::GetList([], ['IBLOCK_ID' => $block['ID'], '=CODE' => $code, 'ACTIVE' => 'Y'], false, false, ['ID', 'IBLOCK_ID', 'NAME'])->Fetch();
    if (!$element) return;
    $seo = (new \Bitrix\Iblock\InheritedProperty\ElementValues($block['ID'], $element['ID']))->getValues();
    $APPLICATION->SetTitle(($seo['ELEMENT_PAGE_TITLE'] ?? '') ?: $element['NAME']);
    $APPLICATION->SetPageProperty('title', ($seo['ELEMENT_META_TITLE'] ?? '') ?: $element['NAME']);
    $APPLICATION->SetPageProperty('description', $seo['ELEMENT_META_DESCRIPTION'] ?? '');
}

/** Resolve the actual counterpart; untranslated Russian pages lead to the EN catalogue. */
function usticomLanguageUrl(): string
{
    if (!\Bitrix\Main\Loader::includeModule('iblock')) return usticomIsEnglish() ? '/' : '/en/';
    $path = (string)parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $commonPages = ['/en/' => '/', '/en/services/' => '/services/', '/en/contacts/' => '/contacts/'];
    $commonPages += array_flip($commonPages);
    if (isset($commonPages[$path])) return $commonPages[$path];
    $code = basename(rtrim($path, '/'));
    $english = CIBlock::GetList([], ['TYPE' => 'usticom_site_content_en', '=CODE' => 'usticom_en_services'])->Fetch();
    if (!$english) return usticomIsEnglish() ? '/' : '/en/';
    $current = CIBlockElement::GetList([], ['IBLOCK_ID' => usticomIsEnglish() ? $english['ID'] : 5,
        '=CODE' => $code, 'ACTIVE' => 'Y'], false, false, usticomIsEnglish() ? ['ID', 'IBLOCK_ID', 'PROPERTY_RU_ORIGINAL'] : ['ID', 'IBLOCK_ID'])->Fetch();
    if ($current) {
        $filter = usticomIsEnglish()
            ? ['IBLOCK_ID' => 5, 'ID' => (int)$current['PROPERTY_RU_ORIGINAL_VALUE']]
            : ['IBLOCK_ID' => $english['ID'], 'PROPERTY_RU_ORIGINAL' => $current['ID']];
        // Never interpret a missing relation as an unfiltered query.
        if (!usticomIsEnglish() || !empty($filter['ID'])) {
            $query = CIBlockElement::GetList([], $filter + ['ACTIVE' => 'Y'], false, false, ['ID', 'IBLOCK_ID', 'IBLOCK_SECTION_ID', 'DETAIL_PAGE_URL']);
            $urlTemplate = usticomIsEnglish() ? '/#SECTION_CODE#/#ELEMENT_CODE#/' : '/en/services/#SECTION_CODE#/#ELEMENT_CODE#/';
            $query->SetUrlTemplates($urlTemplate);
            if ($other = $query->GetNext()) return (string)$other['~DETAIL_PAGE_URL'];
        }
    }
    return usticomIsEnglish() ? '/' : '/en/';
}
