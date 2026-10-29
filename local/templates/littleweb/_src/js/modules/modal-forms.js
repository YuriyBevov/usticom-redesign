const uiText = (ru, en) => document.documentElement?.lang === "en" ? en : ru;
// Как в проекте-примере: data-form-id → загрузка компонента → BX.PopupWindow.
let activePopup = null;

const showFeedback = (status, message) => {
	if (!activePopup) return false;
	const { popup, content } = activePopup;
	content.querySelector('.form-popup-feedback')?.remove();
	const formContent = content.querySelector('[data-popup-form-content]');
	if (formContent) formContent.hidden = true;
	const feedback = document.createElement('div');
	feedback.className = 'form-popup-feedback';
	feedback.setAttribute('role', status === 'success' ? 'status' : 'alert');
	const heading = document.createElement('h2');
	heading.textContent = status === 'success' ? uiText('Заявка отправлена', "Request sent") : uiText('Не удалось отправить заявку', "Request could not be sent");
	const text = document.createElement('p');
	text.textContent = message;
	const button = document.createElement('button');
	button.className = 'main-btn main-btn--size-lg';
	button.type = 'button';
	button.textContent = status === 'error' && formContent?.querySelector('form') ? uiText('Вернуться к форме', "Return to the form") : uiText('Закрыть', "Close");
	button.addEventListener('click', () => {
		if (status === 'error' && formContent?.querySelector('form')) {
			feedback.remove();
			formContent.hidden = false;
			const title = formContent.querySelector('.section-title');
			popup.getPopupContainer().setAttribute('aria-label', title?.textContent.trim() || uiText('Форма заявки', "Request form"));
			formContent.querySelector('input:not([type="hidden"]), textarea, .custom-select-opener')?.focus({ preventScroll: true });
		} else popup.close();
	});
	feedback.append(heading, text, button);
	content.append(feedback);
	popup.getPopupContainer().setAttribute('aria-label', heading.textContent);
	button.focus({ preventScroll: true });
	return true;
};

const openForm = (formId, trigger) => {
	if (activePopup || !/^[1-9]\d*$/.test(String(formId))) return;
	const query = new URLSearchParams({ form_id: String(formId) });
	const theme = trigger?.dataset.formTheme?.trim() || '';
	if (theme) query.set('form_theme', theme);
	const content = document.createElement('div');
	content.className = 'form-popup-content';
	content.setAttribute('aria-busy', 'true');
	const loading = document.createElement('p');
	loading.className = 'form-popup-loading';
	loading.setAttribute('role', 'status');
	loading.textContent = uiText('Загрузка формы…', "Loading the form\u2026");
	content.append(loading);
	const previousOverflow = document.body.style.overflow;
	const popup = new BX.PopupWindow(`usticom-form-${formId}`, null, {
		content,
		className: 'usticom-form-popup',
		closeIcon: true,
		closeByEsc: true,
		autoHide: true,
		fixed: true,
		focusTrap: { restoreFocus: false },
		overlay: { backgroundColor: '#1f1f1f', opacity: 55 },
		zIndex: 2000,
		events: {
			onPopupClose() {
				if (activePopup?.popup !== popup) return;
				activePopup = null;
				// Не оставляем таймер сообщения для уже закрытой формы.
				const controller = window.UsticomInlineForm;
				if (controller?.pending && content.contains(controller.pending.form)) {
					clearTimeout(controller.pending.timer);
					controller.pending = null;
				}
				document.body.style.overflow = previousOverflow;
				trigger?.removeAttribute('aria-busy');
				popup.destroy();
				requestAnimationFrame(() => {
					const returnFocus = trigger?.isConnected && !trigger.closest('[inert]') ? trigger : document.querySelector('.header__toggle');
					returnFocus?.focus({ preventScroll: true });
				});
			},
		},
	});
	activePopup = { popup, content };
	trigger?.setAttribute('aria-busy', 'true');
	popup.getPopupContainer().setAttribute('aria-label', uiText('Форма заявки', "Request form"));
	popup.getPopupContainer().querySelector('.popup-window-close-icon')?.setAttribute('aria-label', uiText('Закрыть окно', "Close window"));
	popup.show();
	document.body.style.overflow = 'hidden';
	BX.ajax({
		url: `${document.documentElement?.lang === "en" ? "/en" : ""}/local/ajax/popup_form.php?${query}`,
		method: 'GET',
		dataType: 'html',
		timeout: 30,
		onsuccess(html) {
			if (activePopup?.popup !== popup) return;
			content.removeAttribute('aria-busy');
			trigger?.removeAttribute('aria-busy');
			const formContent = document.createElement('div');
			formContent.dataset.popupFormContent = '';
			formContent.innerHTML = html;
			content.replaceChildren(formContent);
			const title = formContent.querySelector('.section-title');
			if (title) popup.getPopupContainer().setAttribute('aria-label', title.textContent.trim());
			window.UsticomInlineForm?.init();
			formContent.querySelector('input:not([type="hidden"]), textarea, .custom-select-opener')?.focus({ preventScroll: true });
		},
		onfailure() {
			if (activePopup?.popup !== popup) return;
			content.removeAttribute('aria-busy');
			trigger?.removeAttribute('aria-busy');
			content.replaceChildren();
			showFeedback('error', uiText('Не удалось загрузить форму. Попробуйте открыть её ещё раз.', "The form could not be loaded. Please try opening it again."));
		},
	});
};

window.UsticomModalForms = { open: openForm, showFeedback };

const initFormLinks = () => {
	document.querySelectorAll('[data-form-links]').forEach((container) => {
		const formId = container.dataset.formLinks;
		if (!/^[1-9]\d*$/.test(formId)) return;
		container.querySelectorAll('a:not([data-form-links-ignore])').forEach((link) => {
			link.dataset.formId = formId;
			if (!link.hasAttribute('data-form-theme') && container.dataset.formTheme) link.dataset.formTheme = container.dataset.formTheme;
			link.href = `${document.documentElement?.lang === "en" ? "/en" : ""}/local/ajax/popup_form.php?form_id=${formId}`;
			link.setAttribute('aria-haspopup', 'dialog');
			link.removeAttribute('target');
		});
	});
};

if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initFormLinks, { once: true });
else initFormLinks();

document.addEventListener('click', (event) => {
	const trigger = event.target.closest('[data-form-id]');
	if (!trigger || event.defaultPrevented || event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
	event.preventDefault();
	openForm(trigger.dataset.formId, trigger);
});
