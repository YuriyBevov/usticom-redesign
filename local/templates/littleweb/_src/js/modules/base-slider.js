import Swiper from "swiper";
import { Navigation, Pagination } from "swiper/modules";
const sliders = document.querySelectorAll(".base-slider");

if (sliders) {
	sliders.forEach((slider) => {
		console.log(slider.closest("section"));
		const nextBtn = slider
			.closest("section")
			.querySelector(".swiper-button--next");

		const prevBtn = slider
			.closest("section")
			.querySelector(".swiper-button--prev");

		const pagination = slider
			.closest("section")
			.querySelector(".swiper-pagination");

		console.log(nextBtn, prevBtn, pagination);
		new Swiper(slider, {
			slidesPerView: "auto",
			spaceBetween: 24,
			modules: [Navigation, Pagination],

			navigation: {
				prevEl: prevBtn ?? null,
				nextEl: nextBtn ?? null,
			},

			pagination: {
				el: pagination ?? null,
				clickable: true,
				type: "bullets",
			},
		});
	});
}
