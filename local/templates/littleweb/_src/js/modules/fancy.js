import { Fancybox } from "@fancyapps/ui";

const fancy = document.querySelectorAll("[data-fancybox]");

if (fancy.length) {
	Fancybox.bind("[data-fancybox]", {
		fadeEffect: true,
		hideScrollbar: true,
	});
}

// window.FancyboxInit = fancyInit;
