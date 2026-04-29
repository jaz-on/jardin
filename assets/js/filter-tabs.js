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

	function getEventRolesFromEntry(entry) {
		var existing = (entry.getAttribute('data-roles') || '').trim();
		if (existing) {
			return existing.split(',').filter(Boolean);
		}

		var meta = entry.querySelector('.entry-meta');
		var fromMeta = meta ? (meta.getAttribute('data-event-roles') || '').trim() : '';
		if (fromMeta) {
			return fromMeta.split(',').filter(Boolean);
		}

		var roles = [];
		entry.querySelectorAll('.entry-role').forEach(function (pill) {
			var cls = pill.className || '';
			var m = cls.match(/entry-role--([a-z0-9_-]+)/i);
			if (m && roles.indexOf(m[1]) === -1) {
				roles.push(m[1]);
			}
		});
		return roles;
	}

	function annotateEventEntries(entries) {
		entries.forEach(function (entry) {
			var roles = getEventRolesFromEntry(entry);
			if (roles.length) {
				entry.setAttribute('data-roles', roles.join(','));
				entry.setAttribute('data-role', roles[0]);
			}
		});
	}

	function syncEventFilterButtons(nav, role) {
		nav.setAttribute('data-filter', role || 'all');
		nav.querySelectorAll('.ff-btn').forEach(function (btn) {
			var type = (btn.getAttribute('data-type') || 'all').trim();
			var active = (role === '' && type === 'all') || type === role;
			btn.classList.toggle('active', active);
			if (active) {
				btn.setAttribute('aria-current', 'page');
				btn.setAttribute('aria-pressed', 'true');
			} else {
				btn.removeAttribute('aria-current');
				btn.setAttribute('aria-pressed', 'false');
			}
		});
	}

	function applyEventRoleFilter(entries, role) {
		entries.forEach(function (entry) {
			if (!role) {
				entry.hidden = false;
				return;
			}
			var roles = (entry.getAttribute('data-roles') || '')
				.split(',')
				.map(function (item) { return item.trim(); })
				.filter(Boolean);
			entry.hidden = roles.indexOf(role) < 0;
		});
	}

	function updateEventFilterUrl(role) {
		var url = new URL(window.location.href);
		if (!role) {
			url.searchParams.delete('event_role');
		} else {
			url.searchParams.set('event_role', role);
		}
		window.history.replaceState({}, '', url.toString());
	}

	function initEventFilters() {
		var nav = document.querySelector('.events-filters');
		var list = document.querySelector('.entries.is-events');
		if (!nav || !list) {
			return;
		}

		var entries = Array.prototype.slice.call(list.querySelectorAll('.entry[data-kind="event"]'));
		if (!entries.length) {
			return;
		}

		annotateEventEntries(entries);

		var current = (new URLSearchParams(window.location.search).get('event_role') || '').trim();
		syncEventFilterButtons(nav, current);
		applyEventRoleFilter(entries, current);

		nav.addEventListener('click', function (event) {
			var target = event.target;
			if (!(target instanceof Element)) {
				return;
			}
			var btn = target.closest('a.ff-btn');
			if (!btn || !nav.contains(btn)) {
				return;
			}

			if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
				return;
			}

			event.preventDefault();
			var type = (btn.getAttribute('data-type') || 'all').trim();
			var next = type === 'all' ? '' : type;
			syncEventFilterButtons(nav, next);
			applyEventRoleFilter(entries, next);
			updateEventFilterUrl(next);
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		syncJournalFilters();
		initEventFilters();
	});
}());
