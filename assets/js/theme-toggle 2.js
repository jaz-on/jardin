/**
 * Front-end theme toggle (pairs with inline boot in inc/setup.php).
 *
 * @package Jardin_Theme
 */
(function () {
	'use strict';

	var STORAGE_KEY = 'jardin-theme';
	var VALID = [
		'rose-pine',
		'rose-pine-moon',
		'rose-pine-dawn',
		'catppuccin-latte',
		'catppuccin-frappe',
		'catppuccin-macchiato',
		'brewery-pale',
		'brewery-amber',
		'brewery-stout',
	];

	function getSystemTheme() {
		var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
		return prefersDark ? 'rose-pine' : 'catppuccin-latte';
	}

	function applyTheme(slug) {
		document.documentElement.setAttribute('data-theme', slug);
		try {
			localStorage.setItem(STORAGE_KEY, slug);
		} catch (e) {
			// Private mode or blocked storage.
		}

		document.querySelectorAll('[data-theme-option]').forEach(function (btn) {
			var active = btn.getAttribute('data-theme-option') === slug;
			btn.classList.toggle('is-active', active);
			btn.setAttribute('aria-pressed', active ? 'true' : 'false');
		});
	}

	function followSystem() {
		try {
			localStorage.removeItem(STORAGE_KEY);
		} catch (e) {
			// Ignore.
		}
		applyTheme(getSystemTheme());
	}

	function getInitialTheme() {
		try {
			var saved = localStorage.getItem(STORAGE_KEY);
			if (saved && VALID.indexOf(saved) !== -1) {
				return saved;
			}
		} catch (e) {
			// Ignore.
		}
		return getSystemTheme();
	}

	function closeAllPickers() {
		document.querySelectorAll('details.jardin-theme-toggle[open]').forEach(function (d) {
			d.removeAttribute('open');
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		applyTheme(getInitialTheme());

		document.querySelectorAll('[data-theme-option]').forEach(function (btn) {
			btn.addEventListener('click', function () {
				var slug = btn.getAttribute('data-theme-option');
				if (slug && VALID.indexOf(slug) !== -1) {
					applyTheme(slug);
				}
			});
		});

		document.querySelectorAll('[data-theme-system]').forEach(function (btn) {
			btn.addEventListener('click', function () {
				followSystem();
			});
		});

		document.querySelectorAll('.jardin-theme-toggle__close').forEach(function (btn) {
			btn.addEventListener('click', function () {
				var details = btn.closest('details.jardin-theme-toggle');
				if (details) {
					details.removeAttribute('open');
				}
			});
		});

		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape') {
				closeAllPickers();
			}
		});

		if (window.matchMedia) {
			window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function (e) {
				try {
					if (localStorage.getItem(STORAGE_KEY)) {
						return;
					}
				} catch (err) {
					return;
				}
				applyTheme(e.matches ? 'rose-pine' : 'catppuccin-latte');
			});
		}
	});
})();
