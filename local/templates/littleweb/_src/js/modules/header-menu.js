import { gsap } from "gsap";

const initHeaderMenu = () => {
    const header = document.querySelector("[data-site-header]");
    const menu = document.querySelector(".mobile-menu");

    if (header && menu) {
        const toggle = header.querySelector(".header__toggle");
        const panel = menu.querySelector(".mobile-menu__panel");
        const closeButton = menu.querySelector(".mobile-menu__close");
        const overlay = menu.querySelector(".mobile-menu__overlay");
        const desktopOverlay = header.querySelector(".header__overlay");
        desktopOverlay.hidden = false;
        desktopOverlay.setAttribute("aria-hidden", "true");
        const desktopItems = [...header.querySelectorAll(".nav__list-item--parent")];
        const desktopMedia = window.matchMedia("(min-width: 1200px)");
        const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
        const focusableSelector = 'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex="0"]';
        let mobileOpen = false;
        let closing = false;
        let desktopOpen = null;
        let afterClose = null;
        let lockedState = null;
        let leaveTimer;

        const updateHeaderBottom = () => {
            document.documentElement.style.setProperty("--header-bottom", `${Math.max(0, header.getBoundingClientRect().bottom)}px`);
        };
        new ResizeObserver(updateHeaderBottom).observe(header);
        window.addEventListener("scroll", updateHeaderBottom, { passive: true });
        window.addEventListener("resize", updateHeaderBottom);
        updateHeaderBottom();

        const lockPage = () => {
            if (lockedState) return;
            const targets = [...document.querySelectorAll("body > :not(.mobile-menu):not(script):not(style):not(link)")];
            lockedState = {
                bodyOverflow: document.body.style.overflow,
                bodyScrollbarGutter: document.body.style.scrollbarGutter,
                htmlOverflow: document.documentElement.style.overflow,
                targets: targets.map((element) => ({ element, inert: element.inert })),
            };
            // The root keeps its stable gutter; a second gutter on the locked body would narrow the page.
            document.body.style.scrollbarGutter = "auto";
            document.body.style.overflow = "hidden";
            document.documentElement.style.overflow = "hidden";
            targets.forEach((element) => { element.inert = true; });
        };
        const unlockPage = () => {
            if (!lockedState) return;
            document.body.style.overflow = lockedState.bodyOverflow;
            document.body.style.scrollbarGutter = lockedState.bodyScrollbarGutter;
            document.documentElement.style.overflow = lockedState.htmlOverflow;
            lockedState.targets.forEach(({ element, inert }) => { element.inert = inert; });
            lockedState = null;
        };

        const closeDesktop = () => {
            clearTimeout(leaveTimer);
            desktopItems.forEach((item) => {
                item.classList.remove("is-open");
                item.querySelector(".nav__link").setAttribute("aria-expanded", "false");
            });
            desktopOpen = null;
            desktopOverlay.classList.remove("is-visible");
            desktopOverlay.setAttribute("aria-hidden", "true");
        };
        const openDesktop = (item) => {
            if (!desktopMedia.matches) return;
            clearTimeout(leaveTimer);
            closeDesktop();
            desktopOpen = item;
            item.classList.add("is-open");
            item.querySelector(".nav__link").setAttribute("aria-expanded", "true");
            desktopOverlay.classList.add("is-visible");
            desktopOverlay.setAttribute("aria-hidden", "false");
            updateHeaderBottom();
        };
        desktopItems.forEach((item) => {
            item.addEventListener("pointerenter", (event) => {
                if (event.pointerType !== "touch") openDesktop(item);
            });
            item.addEventListener("pointerleave", () => {
                leaveTimer = setTimeout(() => {
                    if (!item.contains(document.activeElement)) closeDesktop();
                }, 120);
            });
            item.addEventListener("focusin", (event) => {
                if (!event.target.matches(".nav__more-toggle")) openDesktop(item);
            });
            item.addEventListener("focusout", (event) => {
                if (!item.contains(event.relatedTarget) && !item.matches(":hover")) closeDesktop();
            });
            const link = item.querySelector(".nav__link");
            link.addEventListener("keydown", (event) => {
                if (event.key !== "ArrowDown") return;
                event.preventDefault();
                openDesktop(item);
                item.querySelector(".nav__sublink")?.focus();
            });
            // First tap opens the menu on large touch screens; a second tap follows the section link.
            let touchShouldOpen = false;
            link.addEventListener("pointerdown", (event) => {
                touchShouldOpen = event.pointerType === "touch" && desktopMedia.matches && desktopOpen !== item;
            });
            link.addEventListener("click", (event) => {
                if (link.tagName === "BUTTON") {
                    event.preventDefault();
                    if ((event.detail > 0 && event.pointerType !== "touch") || touchShouldOpen || desktopOpen !== item) openDesktop(item);
                    else closeDesktop();
                    touchShouldOpen = false;
                    return;
                }
                if (!touchShouldOpen) return;
                touchShouldOpen = false;
                event.preventDefault();
                openDesktop(item);
            });
        });
        desktopOverlay.addEventListener("click", closeDesktop);

        gsap.set(overlay, { autoAlpha: 0 });
        gsap.set(panel, { xPercent: 100 });
        const finishClose = () => {
            closing = false;
            menu.classList.remove("is-open");
            menu.style.visibility = "hidden";
            menu.inert = true;
            menu.setAttribute("aria-hidden", "true");
            unlockPage();
            if (!desktopMedia.matches) toggle.focus({ preventScroll: true });
            const callback = afterClose;
            afterClose = null;
            callback?.();
        };
        const timeline = gsap.timeline({
            paused: true,
            defaults: { duration: 0.42, ease: "power2.out" },
            onReverseComplete: finishClose,
        }).to(overlay, { autoAlpha: 1 }, 0).to(panel, { xPercent: 0 }, 0);

        const closeMobile = (callback) => {
            if (!mobileOpen) return;
            mobileOpen = false;
            closing = true;
            afterClose = callback;
            toggle.setAttribute("aria-expanded", "false");
            toggle.setAttribute("aria-label", "Открыть меню");
            if (reducedMotion.matches || timeline.progress() === 0) {
                timeline.pause(0);
                finishClose();
            } else timeline.reverse();
        };
        const openMobile = () => {
            if (desktopMedia.matches) return;
            closeDesktop();
            afterClose = null;
            closing = false;
            mobileOpen = true;
            updateHeaderBottom();
            menu.style.visibility = "visible";
            menu.classList.add("is-open");
            menu.inert = false;
            menu.setAttribute("aria-hidden", "false");
            toggle.setAttribute("aria-expanded", "true");
            toggle.setAttribute("aria-label", "Закрыть меню");
            lockPage();
            if (reducedMotion.matches) timeline.progress(1).pause();
            else timeline.play();
            closeButton.focus({ preventScroll: true });
        };
        toggle.addEventListener("click", () => mobileOpen ? closeMobile() : openMobile());
        overlay.addEventListener("click", () => closeMobile());
        closeButton.addEventListener("click", () => closeMobile());

        menu.querySelectorAll(".nav__toggle").forEach((button) => {
            button.addEventListener("click", () => {
                const dropdown = document.getElementById(button.getAttribute("aria-controls"));
                const expanded = button.getAttribute("aria-expanded") === "true";
                button.setAttribute("aria-expanded", String(!expanded));
                dropdown.hidden = expanded;
            });
        });
        menu.addEventListener("click", (event) => {
            const link = event.target.closest("a[href]");
            if (!link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
            if (link.hasAttribute("data-fancybox")) return;
            if (link.target === "_blank") { closeMobile(); return; }
            event.preventDefault();
            closeMobile(() => { window.location.href = link.href; });
        });
        // Complete the drawer close before Fancybox takes over focus and scroll locking.
        document.addEventListener("click", (event) => {
            const request = event.target.closest('[data-header-request]');
            if (!request) return;
            closeDesktop();
            if (mobileOpen || closing) {
                timeline.pause(0);
                mobileOpen = false;
                toggle.setAttribute("aria-expanded", "false");
                toggle.setAttribute("aria-label", "Открыть меню");
                finishClose();
            }
        }, true);

        document.addEventListener("keydown", (event) => {
            if (event.key === "Escape") {
                if (mobileOpen) { event.preventDefault(); closeMobile(); }
                if (desktopOpen) {
                    event.preventDefault();
                    desktopOpen.querySelector(".nav__link").focus();
                    closeDesktop();
                }
            }
            if (event.key !== "Tab" || (!mobileOpen && !closing)) return;
            const controls = [...panel.querySelectorAll(focusableSelector)].filter((element) => element.getClientRects().length && !element.closest("[hidden]"));
            const first = controls[0];
            const last = controls.at(-1);
            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault(); last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault(); first.focus();
            }
        });
        desktopMedia.addEventListener("change", () => {
            closeDesktop();
            if (mobileOpen || closing) {
                timeline.pause(0);
                mobileOpen = false;
                toggle.setAttribute("aria-expanded", "false");
                toggle.setAttribute("aria-label", "Открыть меню");
                afterClose = null;
                finishClose();
            }
            updateHeaderBottom();
        });
    }

};

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initHeaderMenu, { once: true });
} else {
    initHeaderMenu();
}
