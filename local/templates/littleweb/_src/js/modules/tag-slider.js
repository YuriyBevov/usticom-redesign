import Swiper from "swiper";
import { FreeMode } from "swiper/modules";

const tagSliders = document.querySelectorAll(".tag-slider");

tagSliders.forEach((slider) => {
	if (slider.swiper) {
		return;
	}

	const slides = Array.from(slider.querySelectorAll(".swiper-slide"));
	const activeSlideIndex = slides.findIndex((slide) =>
		slide.querySelector(".active"),
	);

	new Swiper(slider, {
		modules: [FreeMode],
		slidesPerView: "auto",
		spaceBetween: 8,
		initialSlide: Math.max(activeSlideIndex, 0),
		grabCursor: true,
		watchOverflow: true,
		freeMode: {
			enabled: true,
			momentum: true,
		},
	});
});
