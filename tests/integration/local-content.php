<?php
// Opt-in integration check against the existing local Bitrix, never a production database.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
if (!in_array('--local-mamp', $argv, true)) { fwrite(STDERR, "Use --local-mamp against the local MAMP checkout only.\n"); exit(2); }
$_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__, 2);
$_SERVER['HTTP_HOST'] = 'localhost:8888';
define('NO_AGENT_CHECK', true); define('BX_CRONTAB', true); define('DisableEventsCheck', true);
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';
set_exception_handler(static function (Throwable $e) { fwrite(STDERR, $e->getMessage()."\n"); exit(1); });
if (!defined('USTICOM_LOCAL_MAIL_DISABLED') || !USTICOM_LOCAL_MAIL_DISABLED) throw new RuntimeException('Local mail suppression is required');
foreach (['iblock', 'simai.complexprop', 'form'] as $module) \Bitrix\Main\Loader::includeModule($module);
require $_SERVER['DOCUMENT_ROOT'].'/local/migrations/lib/service-importer.php';
function verify($condition, string $message): void { if (!$condition) throw new RuntimeException($message); }
$blocks = [];
$q = CIBlock::GetList([], ['TYPE' => 'usticom_site_content_en']);
while ($r = $q->Fetch()) $blocks[$r['CODE']] = (int)$r['ID'];
verify(count($blocks) === 5, 'Expected five EN information blocks');
$form = CForm::GetBySID('USTICOM_EN_REQUEST')->Fetch();
verify($form && CForm::GetMailTemplateArray($form['ID']), 'English form notifications are not attached');
foreach (['ru-hr-audit' => 5, 'en-hr-outsourcing' => $blocks['usticom_en_services']] as $file => $block) {
    $payload = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/local/migrations/content/'.$file.'.json'), true);
    $query = CIBlockElement::GetList([], ['IBLOCK_ID' => $block, '=XML_ID' => $payload['xml_id']]);
    verify($query->SelectedRowsCount() === 1, 'Service was duplicated');
    $element = $query->GetNextElement(); $fields = $element->GetFields(); $props = $element->GetProperties();
    verify($fields['CODE'] === $payload['code'], 'Unexpected service address');
    foreach (['REASONS' => 'reasons', 'INCLUDES' => 'includes', 'WORKFLOW' => 'workflow'] as $property => $source) {
        verify(count(array_filter($props[$property]['VALUE'], static fn($v) => is_array($v) && !empty($v['SUB_VALUES']))) === count($payload[$source]), 'Missing complex content: '.$property);
    }
    $seo = (new \Bitrix\Iblock\InheritedProperty\ElementValues($block, $fields['ID']))->getValues();
    verify($seo['ELEMENT_META_TITLE'] === $payload['meta_title'], 'Incorrect translated META TITLE');
    verify($seo['ELEMENT_META_DESCRIPTION'] === $payload['meta_description'], 'Incorrect translated META DESCRIPTION');
    verify(count((array)$props['FAQ_LINKED']['VALUE']) === 7, 'Missing FAQ links');
    if ($block !== 5) {
        verify((int)$props['FAQ_LINKED']['LINK_IBLOCK_ID'] === $blocks['usticom_en_faq'], 'English FAQ links to RU content');
        verify((int)$props['PRICES']['LINK_IBLOCK_ID'] === $blocks['usticom_en_prices'], 'English prices link to RU content');
        verify((int)$props['HERO_TIZZERS']['LINK_IBLOCK_ID'] === $blocks['usticom_en_tizzers'], 'English cards link to RU content');
        verify((int)$props['RU_ORIGINAL']['VALUE'] === 2047, 'Wrong Russian counterpart');
    } else {
        verify((int)$fields['ID'] !== 2067, 'Existing audit card was replaced');
        verify(str_contains(json_encode($props['REASONS'], JSON_UNESCAPED_UNICODE), 'иностранные сотрудники'), 'Foreign employee direction was omitted');
    }
}
// Simulate an editor's own name and SEO fields on a disposable inactive element.
$api = new CIBlockElement();
$xml = 'usticom:local-test:'.bin2hex(random_bytes(8));
$id = $api->Add(['IBLOCK_ID' => $blocks['usticom_en_services'], 'NAME' => 'Editor name', 'XML_ID' => $xml, 'ACTIVE' => 'N',
    'CODE' => 'local-preservation-test', 'IPROPERTY_TEMPLATES' => ['ELEMENT_META_TITLE' => 'Editor title', 'ELEMENT_META_DESCRIPTION' => 'Editor description']]);
verify($id, 'Cannot create disposable integration fixture');
try {
    $payload = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/local/migrations/content/en-hr-outsourcing.json'), true);
    $payload = array_replace($payload, ['xml_id' => $xml, 'name' => 'Importer must not overwrite this', 'iblock_id' => $blocks['usticom_en_services'],
        'section_id' => 1, 'faq_iblock_id' => $blocks['usticom_en_faq'], 'prices_iblock_id' => $blocks['usticom_en_prices'], 'prices_parent_id' => 1]);
    verify(usticomImportService($payload, true)['status'] === 'already_exists', 'Existing element was imported again');
    verify(CIBlockElement::GetList([], ['ID' => $id], false, false, ['NAME'])->Fetch()['NAME'] === 'Editor name', 'Editor name was overwritten');
    $seo = (new \Bitrix\Iblock\InheritedProperty\ElementValues($blocks['usticom_en_services'], $id))->getValues();
    verify($seo['ELEMENT_META_TITLE'] === 'Editor title' && $seo['ELEMENT_META_DESCRIPTION'] === 'Editor description', 'Editor SEO was overwritten');
} finally {
    $owned = CIBlockElement::GetList([], ['ID' => $id, '=XML_ID' => $xml], false, false, ['ID'])->Fetch();
    if ($owned) CIBlockElement::Delete($owned['ID']);
}
echo "OK: separate RU card, EN links, complete content, independent SEO and editor changes preserved\n";
