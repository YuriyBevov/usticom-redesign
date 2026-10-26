<?php

use Bitrix\Main\EventManager;
use Bitrix\Main\UserConsent\Agreement;
use Bitrix\Main\UserConsent\Consent;

final class UsticomInlineFormConsent
{
	private static bool $accepted = false;

	public static function beforeResultAdd($formId, array &$fields, array &$values): void
	{
		if ((int)$formId !== 5 || (defined('ADMIN_SECTION') && ADMIN_SECTION === true)) {
			return;
		}

		self::$accepted = false;
		if (($values['form_inline_consent'] ?? '') !== 'Y') {
			$GLOBALS['APPLICATION']->ThrowException('Необходимо принять условия соглашения.');
			return;
		}

		$agreement = new Agreement(1);
		if (!$agreement->isExist() || !$agreement->isActive()) {
			$GLOBALS['APPLICATION']->ThrowException('Соглашение временно недоступно. Попробуйте отправить заявку позже.');
			return;
		}

		self::$accepted = true;
	}

	public static function afterResultAdd($formId, $resultId): void
	{
		if ((int)$formId !== 5 || !self::$accepted) {
			return;
		}

		self::$accepted = false;
		Consent::addByContext(1, 'form', (string)$resultId);
	}
}

EventManager::getInstance()->addEventHandlerCompatible('form', 'onBeforeResultAdd', [UsticomInlineFormConsent::class, 'beforeResultAdd']);
EventManager::getInstance()->addEventHandlerCompatible('form', 'onAfterResultAdd', [UsticomInlineFormConsent::class, 'afterResultAdd']);
