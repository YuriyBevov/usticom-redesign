const initFeatures = () => {
	const mobile = window.matchMedia("(max-width: 767.98px)");
	document.querySelectorAll(".features-list").forEach((list) => {
		if (list.dataset.featuresReady === "true") return;
		list.dataset.featuresReady = "true";
		const cards = [...list.querySelectorAll(".features-list__details")];
		const updateLayout = () => {
			cards.forEach((card, index) => {
				card.open = !mobile.matches || index === 0;
				card.querySelector("summary").tabIndex = mobile.matches ? 0 : -1;
			});
		};
		cards.forEach((card) => {
			card.querySelector("summary").addEventListener("click", (event) => {
				if (!mobile.matches) event.preventDefault();
			});
		});
		mobile.addEventListener("change", updateLayout);
		updateLayout();
	});
};

if (document.readyState === "loading") {
	document.addEventListener("DOMContentLoaded", initFeatures, { once: true });
} else {
	initFeatures();
}
