const initPartnershipTabs = () => {
	document.querySelectorAll('[data-partnership-tabs]').forEach((root) => {
		if (root.dataset.tabsReady) return;
		const tabs = [...root.querySelectorAll('[role="tab"]')];
		const panels = tabs.map((tab) => document.getElementById(tab.getAttribute('aria-controls')));
		if (!tabs.length || panels.some((panel) => !panel || !root.contains(panel))) return;
		root.dataset.tabsReady = 'true';

		const activate = (index, focus = false) => {
			tabs.forEach((tab, i) => {
				const selected = i === index;
				tab.setAttribute('aria-selected', String(selected));
				tab.tabIndex = selected ? 0 : -1;
				panels[i].hidden = !selected;
			});
			if (focus) tabs[index].focus({ preventScroll: true });
		};

		tabs.forEach((tab, index) => {
			tab.addEventListener('click', () => activate(index));
			tab.addEventListener('keydown', (event) => {
				let next;
				if (event.key === 'ArrowRight') next = (index + 1) % tabs.length;
				if (event.key === 'ArrowLeft') next = (index - 1 + tabs.length) % tabs.length;
				if (event.key === 'Home') next = 0;
				if (event.key === 'End') next = tabs.length - 1;
				if (next === undefined) return;
				event.preventDefault();
				activate(next, true);
			});
		});
		activate(Math.max(0, tabs.findIndex((tab) => tab.getAttribute('aria-selected') === 'true')));
	});
};

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', initPartnershipTabs);
} else {
	initPartnershipTabs();
}
