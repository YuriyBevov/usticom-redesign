import { Fancybox } from "@fancyapps/ui";

const initFancybox = () => {
    if (document.querySelector("[data-fancybox]")) {
        Fancybox.bind("[data-fancybox]", {
            fadeEffect: true,
            hideScrollbar: true,
        });
    }
    if (document.body.classList.contains("header-request-page") && window.parent !== window) {
        document.addEventListener("keydown", (event) => {
            if (event.key === "Escape") window.parent.postMessage("usticom:close-request", window.location.origin);
        });
    }
};

// Bitrix can include the global script in the document head.
if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", initFancybox, { once: true });
else initFancybox();

window.addEventListener("message", (event) => {
    if (event.origin !== window.location.origin || event.data !== "usticom:close-request") return;
    const frame = document.querySelector('.fancybox__iframe[src$="/ajax/header-request.php"]');
    if (frame?.contentWindow === event.source) Fancybox.getInstance()?.close();
});
