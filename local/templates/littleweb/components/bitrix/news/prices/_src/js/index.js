const initPrices = () => {
	document.querySelectorAll("[data-prices-catalog]").forEach((catalog) => {
		if (catalog.dataset.pricesInitialized === "true") return;
		const grid = catalog.querySelector("[data-prices-grid]");
		if (!grid) return;
		catalog.dataset.pricesInitialized = "true";

		const cards = [...grid.querySelectorAll("[data-price-category]")];
		let columnCount = 0;

		const layout = () => {
			const count = Number.parseInt(getComputedStyle(grid).getPropertyValue("--prices-columns"), 10) || 1;
			if (count === columnCount) return;
			columnCount = count;
			const focused = grid.contains(document.activeElement) ? document.activeElement : null;
			const columns = Array.from({ length: count }, () => {
				const column = document.createElement("div");
				column.className = "prices-catalog__column";
				return column;
			});
			let visibleIndex = 0;
			cards.forEach((card) => {
				columns[visibleIndex % count].append(card);
				if (!card.hidden) visibleIndex += 1;
			});
			grid.replaceChildren(...columns);
			focused?.focus({ preventScroll: true });
		};

		layout();
		window.addEventListener("resize", () => layout(), { passive: true });
	});
};

if (document.readyState === "loading") {
	document.addEventListener("DOMContentLoaded", initPrices, { once: true });
} else {
	initPrices();
}
