/**
 * Sets aria-current on the active journal hub filter link (?kind= vs "All").
 *
 * @package Jardin
 */
(function () {
	'use strict';

	function kindFromHref(href) {
		if (!href || href === '?') {
			return '';
		}
		var q = href.indexOf('?');
		if (q < 0) {
			return '';
		}
		return new URLSearchParams(href.slice(q)).get('kind') || '';
	}

	function syncJournalFilters() {
		var nav = document.querySelector('.jardin-journal-filters');
		if (!nav) {
			return;
		}
		var current = (new URLSearchParams(window.location.search).get('kind') || '').trim();
		var links = nav.querySelectorAll('.jardin-journal-filters__link');
		links.forEach(function (a) {
			a.removeAttribute('aria-current');
			var raw = a.getAttribute('href') || '';
			var resolved;
			try {
				resolved = new URL(raw, window.location.href).searchParams.get('kind') || '';
			} catch (e) {
				resolved = kindFromHref(raw);
			}
			if (resolved === current) {
				a.setAttribute('aria-current', 'page');
			}
		});
	}

	document.addEventListener('DOMContentLoaded', syncJournalFilters);
}());
