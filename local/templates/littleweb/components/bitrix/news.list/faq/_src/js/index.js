const initFaq = () => {
	document.querySelectorAll("[data-faq-list]").forEach((list) => {
		list.querySelectorAll("[data-faq-button]").forEach((button) => {
			if (button.dataset.faqInitialized === "true") return;

			button.dataset.faqInitialized = "true";
			button.addEventListener("click", () => {
				const panel = document.getElementById(button.getAttribute("aria-controls"));
				if (!panel) return;

				const isExpanded = button.getAttribute("aria-expanded") === "true";
				button.setAttribute("aria-expanded", String(!isExpanded));
				button.closest(".faq-list__item")?.classList.toggle("is-open", !isExpanded);
				panel.hidden = isExpanded;
			});
		});
	});
};

if (document.readyState === "loading") {
	document.addEventListener("DOMContentLoaded", initFaq);
} else {
	initFaq();
}
