import { createBaseSlider } from "./base-slider";

const ACTIVE_SLIDE_CLASS = "cases-slider__slide--active";
const sliders = document.querySelectorAll(".cases-slider");

const setActiveSlide = (swiper) => {
	swiper.slides.forEach((slide, index) => {
		slide.classList.toggle(ACTIVE_SLIDE_CLASS, index === swiper.activeIndex);
	});
};

const updateSliderLayout = (swiper, speed = swiper.params.speed) => {
	window.requestAnimationFrame(() => {
		if (swiper.destroyed) {
			return;
		}

		swiper.updateSlides();
		swiper.slideTo(swiper.activeIndex, speed, false);
		swiper.updateProgress();
		swiper.updateSlidesClasses();
	});
};

sliders.forEach((slider) => {
	if (slider.swiper) {
		return;
	}

	createBaseSlider(slider, {
		initialSlide: 0,
		slideToClickedSlide: true,
		on: {
			init(swiper) {
				setActiveSlide(swiper);
				updateSliderLayout(swiper, 0);
			},
			activeIndexChange(swiper) {
				setActiveSlide(swiper);
				updateSliderLayout(swiper);
			},
		},
	});
});
