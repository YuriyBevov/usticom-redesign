import customSelect from "custom-select";
import IMask from "imask";

const showInlineFeedback = (status, message) => {
	document.querySelector(".form-inline-result")?.close();
	const dialog = document.createElement("dialog");
	dialog.className = "form-inline-result";
	const title = status === "success" ? "Заявка отправлена" : "Не удалось отправить заявку";
	dialog.setAttribute("aria-label", title);
	const heading = document.createElement("h2");
	heading.textContent = title;
	const text = document.createElement("p");
	text.textContent = message;
	const close = document.createElement("button");
	close.className = "main-btn main-btn--size-lg";
	close.type = "button";
	close.textContent = "Закрыть";
	close.addEventListener("click", () => dialog.close());
	dialog.addEventListener("close", () => dialog.remove(), { once: true });
	dialog.addEventListener("click", (event) => {
		if (event.target !== dialog) return;
		const rect = dialog.getBoundingClientRect();
		if (event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom) dialog.close();
	});
	dialog.append(heading, text, close);
	document.body.append(dialog);
	dialog.showModal();
};

const finishInlineRequest = () => {
	const controller = window.UsticomInlineForm;
	if (!controller?.pending) return;
	clearTimeout(controller.pending.timer);
	controller.pending.form.removeAttribute("aria-busy");
	controller.pending = null;
};

const initInlineForms = () => {
	document.querySelectorAll("[data-inline-feedback]").forEach((feedback) => {
		if (feedback.dataset.feedbackShown === "true") return;
		feedback.dataset.feedbackShown = "true";
		const message = feedback.tagName === "TEMPLATE" ? feedback.content.textContent : feedback.textContent;
		finishInlineRequest();
		showInlineFeedback(feedback.dataset.inlineFeedback, message.trim());
	});
	document.querySelectorAll("[data-inline-form]").forEach((form) => {
		if (form.dataset.inlineInitialized === "true") return;
		form.dataset.inlineInitialized = "true";
		const fields = [...form.querySelectorAll("[data-inline-field]")];

		form.querySelectorAll("select:not([multiple])").forEach((select) => {
			if (select.customSelect) return;
			const [control] = customSelect(select);
			if (select.getAttribute("aria-required") === "true") control.opener.setAttribute("aria-required", "true");
			if (select.getAttribute("aria-invalid") === "true") control.opener.setAttribute("aria-invalid", "true");
			control.container.addEventListener("keydown", (event) => {
				if ([" ", "ArrowDown", "ArrowUp", "Enter", "Escape"].includes(event.key)) event.preventDefault();
			});
		});

		form.querySelectorAll('[data-kind="phone"] input').forEach((input) => {
			IMask(input, { mask: "+{7}(000)000-00-00" });
		});

		const validate = (field) => {
			const controls = [...field.querySelectorAll('input:not([type="hidden"]), textarea, select')].filter((control) => !control.disabled);
			const control = controls[0];
			const required = field.dataset.required === "true" || controls.some((input) => input.required);
			let invalid = !control && field.dataset.kind === "consent";
			if (control) {
				const value = control.value.trim();
				const empty = ["checkbox", "radio"].includes(control.type) ? !controls.some((input) => input.checked) : !value;
				invalid = required && empty;
				if (value && field.dataset.kind === "phone" && !/^7\d{10}$/.test(value.replace(/\D/g, ""))) invalid = true;
				if (value && (control.type === "email" || field.dataset.kind === "email") && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) invalid = true;
				if (control.validity && !control.validity.valid) invalid = true;
			}
			field.classList.toggle("invalid-field", invalid);
			field.querySelectorAll("input, textarea, select, .custom-select-opener").forEach((input) => {
				if (invalid) input.setAttribute("aria-invalid", "true");
				else input.removeAttribute("aria-invalid");
			});
			return !invalid;
		};

		fields.forEach((field) => {
			field.addEventListener("focusout", () => validate(field));
			["input", "change"].forEach((name) => field.addEventListener(name, () => {
				if (field.classList.contains("invalid-field")) validate(field);
			}));
		});
		// Проверяем до обработчика AJAX, который Битрикс привязывает к submit.
		form.addEventListener("submit", (event) => {
			const invalid = fields.filter((field) => !validate(field));
			if (!invalid.length) {
				const controller = window.UsticomInlineForm;
				if (controller.pending) {
					event.preventDefault();
					event.stopImmediatePropagation();
					return;
				}
				form.setAttribute("aria-busy", "true");
				controller.pending = {
					form,
					timer: setTimeout(() => {
						finishInlineRequest();
						showInlineFeedback("error", "Сервер не подтвердил отправку. Проверьте соединение и попробуйте ещё раз.");
					}, 30000),
				};
				return;
			}
			event.preventDefault();
			event.stopImmediatePropagation();
			const first = invalid[0];
			(first.querySelector(".custom-select-opener") || first.querySelector('input:not([type="hidden"]), textarea, select'))?.focus();
		}, true);
	});
	const pending = window.UsticomInlineForm?.pending;
	if (pending && !pending.form.isConnected) {
		finishInlineRequest();
		showInlineFeedback("error", "Не удалось получить подтверждение от сервера. Попробуйте отправить заявку ещё раз.");
	}
};

// Повторная загрузка ассета Битриксом не должна повторно привязывать общие события.
if (!window.UsticomInlineForm) {
	window.UsticomInlineForm = { init: initInlineForms, ajaxBound: false, pending: null };
	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", initInlineForms, { once: true });
	}
}
const inlineForm = window.UsticomInlineForm;
if (document.readyState !== "loading") inlineForm.init();
if (window.BX && !inlineForm.ajaxBound) {
	BX.addCustomEvent("onAjaxSuccess", inlineForm.init);
	inlineForm.ajaxBound = true;
}
