<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */
/** @var array $arResult */

$config = \Bitrix\Main\Web\Json::encode($arResult['CONFIG']);
?>

<label data-bx-user-consent="<?= htmlspecialcharsbx($config) ?>" class="main-user-consent-request">
	<input type="checkbox" value="Y" <?= ($arParams['IS_CHECKED'] ? 'checked' : '') ?> name="<?= htmlspecialcharsbx($arParams['INPUT_NAME']) ?>" required>
	<span>Я даю согласие на обработку и использование моих персональных данных в соответствии с <a href="/privacy-policy/" target="_blank">Политикой в отношении обработки персональных данныхи</a>.</span>
</label>