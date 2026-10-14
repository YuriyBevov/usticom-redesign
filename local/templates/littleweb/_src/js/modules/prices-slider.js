import Swiper from "swiper";
import { Navigation, A11y } from "swiper/modules";

const initPricesSliders = () => {
	document.querySelectorAll("[data-prices-directions]").forEach((directions) => {
		const slider = directions.querySelector("[data-prices-filters]");
		if (!slider || slider.swiper) return;

		directions.hidden = false;
		new Swiper(slider, {
			modules: [Navigation, A11y],
			slidesPerView: "auto",
			spaceBetween: 10,
			grabCursor: true,
			watchOverflow: true,
			speed: window.matchMedia("(prefers-reduced-motion: reduce)").matches ? 0 : 300,
			navigation: {
				prevEl: directions.querySelector("[data-prices-prev]"),
				nextEl: directions.querySelector("[data-prices-next]"),
			},
			a11y: {
				prevSlideMessage: "Предыдущие направления",
				nextSlideMessage: "Следующие направления",
				slideLabelMessage: "{{index}} из {{slidesLength}}",
			},
		});
	});
};

if (document.readyState === "loading") {
	document.addEventListener("DOMContentLoaded", initPricesSliders, { once: true });
} else {
	initPricesSliders();
}
