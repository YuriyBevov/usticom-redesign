import { Fancybox } from "@fancyapps/ui";

const initFancybox = () => {
    if (document.querySelector("[data-fancybox]")) {
        Fancybox.bind("[data-fancybox]", {
            fadeEffect: true,
            hideScrollbar: true,
        });
    }
};

if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", initFancybox, { once: true });
else initFancybox();
