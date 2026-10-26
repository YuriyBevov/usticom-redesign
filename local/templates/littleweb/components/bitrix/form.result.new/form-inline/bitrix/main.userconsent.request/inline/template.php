<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}
// Без data-bx-user-consent стандартный popup не инициализируется.
// Проверка и запись согласия выполняются обработчиками результата веб-формы.
?>
<label class="form-inline__consent">
	<input type="checkbox" name="<?= htmlspecialcharsbx($arParams['INPUT_NAME']) ?>" value="Y" aria-required="true"<?= !empty($arParams['IS_INVALID']) ? ' aria-invalid="true"' : '' ?><?= $arParams['IS_CHECKED'] ? ' checked' : '' ?>>
	<span><?= htmlspecialcharsbx($arResult['INPUT_LABEL']) ?></span>
</label>
