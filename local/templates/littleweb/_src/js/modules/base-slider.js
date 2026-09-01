import Swiper from "swiper";
import { Navigation, Pagination } from "swiper/modules";

export const createBaseSlider = (slider, options = {}) => {
	const section = slider.closest("section");
	const nextBtn = section?.querySelector(".swiper-button--next");
	const prevBtn = section?.querySelector(".swiper-button--prev");
	const pagination = section?.querySelector(".swiper-pagination");
	const baseNavigation = {
		prevEl: prevBtn ?? null,
		nextEl: nextBtn ?? null,
	};
	const basePagination = {
		el: pagination ?? null,
		clickable: true,
		type: "bullets",
	};

	return new Swiper(slider, {
		slidesPerView: "auto",
		spaceBetween: 24,
		modules: [Navigation, Pagination],
		...options,
		navigation: {
			...baseNavigation,
			...options.navigation,
		},
		pagination: {
			...basePagination,
			...options.pagination,
		},
	});
};

const sliders = document.querySelectorAll(".base-slider");

sliders.forEach((slider) => {
	if (!slider.swiper) {
		createBaseSlider(slider);
	}
});
