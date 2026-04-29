/**
 * Feed filters: home (mixed hub), journal hub links, notes archive, events archive.
 * Single init per section — no double-binding (build marker: 2026-04-29-feed-v3).
 *
 * @package Jardin_Theme
 */
(function () {
	'use strict';

	function syncJournalHubFilters() {
		var nav = document.querySelector('.journal-hub-filters');
		if (!nav) {
			return;
		}
		var current = (new URLSearchParams(window.location.search).get('kind') || '').trim();
		var norm = current === '' ? 'all' : current;
		nav.setAttribute('data-filter', norm);
		nav.querySelectorAll('a.ff-btn').forEach(function (a) {
			var type = (a.getAttribute('data-type') || 'all').trim();
			var active = type === norm;
			a.classList.toggle('active', active);
			if (active) {
				a.setAttribute('aria-current', 'page');
			} else {
				a.removeAttribute('aria-current');
			}
		});
	}

	function applyHomeKindFilter(entries, kindType) {
		var t = (kindType || 'all').trim();
		entries.forEach(function (entry) {
			if (!t || t === 'all') {
				entry.hidden = false;
				return;
			}
			var dk = (entry.getAttribute('data-kind') || '').trim();
			entry.hidden = dk !== t;
		});
	}

	function syncHomeFilterButtons(nav, kindType) {
		var raw = (kindType || '').trim();
		var norm = !raw || raw === 'all' ? 'all' : raw;
		nav.setAttribute('data-filter', norm);
		nav.querySelectorAll('button.ff-btn').forEach(function (btn) {
			var type = (btn.getAttribute('data-type') || 'all').trim();
			var active = type === norm;
			btn.classList.toggle('active', active);
			btn.setAttribute('aria-pressed', active ? 'true' : 'false');
			if (active) {
				btn.setAttribute('aria-current', 'true');
			} else {
				btn.removeAttribute('aria-current');
			}
		});
	}

	function updateHomeKindUrl(kindType) {
		var url = new URL(window.location.href);
		var t = (kindType || 'all').trim();
		if (!t || t === 'all') {
			url.searchParams.delete('kind');
		} else {
			url.searchParams.set('kind', t);
		}
		window.history.replaceState({}, '', url.toString());
	}

	function initHomeFeedFilters() {
		var nav = document.querySelector('.home-feed-filters');
		if (!nav || nav.getAttribute('data-jardin-theme-home-bound') === '1') {
			return;
		}
		nav.setAttribute('data-jardin-theme-home-bound', '1');

		var section = nav.closest('.home-feed-section');
		if (!section) {
			return;
		}
		var list = section.querySelector('.wp-block-query .entries');
		if (!list) {
			return;
		}
		var entries = list.querySelectorAll('.entry');
		if (!entries.length) {
			return;
		}

		var initial = (new URLSearchParams(window.location.search).get('kind') || '').trim();
		if (initial === 'post' || initial === 'note' || initial === 'event') {
			syncHomeFilterButtons(nav, initial);
			applyHomeKindFilter(entries, initial);
		} else {
			syncHomeFilterButtons(nav, 'all');
			applyHomeKindFilter(entries, 'all');
		}

		nav.addEventListener('click', function (event) {
			var target = event.target;
			if (!(target instanceof Element)) {
				return;
			}
			var btn = target.closest('button.ff-btn');
			if (!btn || !nav.contains(btn)) {
				return;
			}
			event.preventDefault();
			var type = (btn.getAttribute('data-type') || 'all').trim();
			var nextKind = type === 'all' ? 'all' : type;
			syncHomeFilterButtons(nav, nextKind);
			applyHomeKindFilter(entries, nextKind === 'all' ? 'all' : nextKind);
			updateHomeKindUrl(nextKind === 'all' ? '' : nextKind);
		});
	}

	function applyNotesKindFilter(entries, slug) {
		var target = (slug || '').trim();
		entries.forEach(function (entry) {
			if (!target || target === 'all') {
				entry.hidden = false;
				return;
			}
			var nk = (entry.getAttribute('data-note-kind') || '').trim();
			entry.hidden = nk !== target;
		});
	}

	function syncNotesFilterButtons(nav, slug) {
		var raw = (slug || '').trim();
		var norm = !raw || raw === 'all' ? 'all' : raw;
		nav.setAttribute('data-filter', norm);
		nav.querySelectorAll('a.ff-btn').forEach(function (a) {
			var type = (a.getAttribute('data-type') || 'all').trim();
			var active = type === norm;
			a.classList.toggle('active', active);
			if (active) {
				a.setAttribute('aria-current', 'page');
			} else {
				a.removeAttribute('aria-current');
			}
		});
	}

	function updateNotesKindUrl(slug) {
		var url = new URL(window.location.href);
		var s = (slug || '').trim();
		if (!s || s === 'all') {
			url.searchParams.delete('note_kind');
		} else {
			url.searchParams.set('note_kind', s);
		}
		window.history.replaceState({}, '', url.toString());
	}

	function initNotesArchiveFilters() {
		var nav = document.querySelector('.notes-archive-filters');
		var list = document.querySelector('.entries.is-notes');
		if (!nav || !list || nav.getAttribute('data-jardin-theme-notes-bound') === '1') {
			return;
		}
		nav.setAttribute('data-jardin-theme-notes-bound', '1');

		var entries = list.querySelectorAll('.entry');
		if (!entries.length) {
			return;
		}

		var initial = (new URLSearchParams(window.location.search).get('note_kind') || '').trim();
		if (initial) {
			syncNotesFilterButtons(nav, initial);
			applyNotesKindFilter(entries, initial);
		} else {
			syncNotesFilterButtons(nav, 'all');
			applyNotesKindFilter(entries, 'all');
		}

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
			syncNotesFilterButtons(nav, next === '' ? 'all' : next);
			applyNotesKindFilter(entries, next === '' ? 'all' : next);
			updateNotesKindUrl(next);
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

	function getPostIdFromEntry(entry) {
		var host = entry.closest('li.wp-block-post') || entry.parentElement;
		if (!host || !host.className) {
			return 0;
		}
		var m = host.className.match(/\bpost-(\d+)\b/);
		return m ? parseInt(m[1], 10) : 0;
	}

	function hydrateEventRolesFromRest(entries) {
		var missing = entries.filter(function (entry) {
			return !(entry.getAttribute('data-roles') || '').trim();
		});
		if (!missing.length) {
			return Promise.resolve();
		}

		return fetch('/wp-json/wp/v2/event?per_page=100&_fields=id,event_roles', {
			credentials: 'same-origin'
		})
			.then(function (res) {
				return res.ok ? res.json() : [];
			})
			.then(function (items) {
				var byId = {};
				(items || []).forEach(function (item) {
					if (item && item.id && Array.isArray(item.event_roles)) {
						byId[item.id] = item.event_roles.filter(Boolean);
					}
				});
				missing.forEach(function (entry) {
					var id = getPostIdFromEntry(entry);
					var roles = byId[id] || [];
					if (roles.length) {
						entry.setAttribute('data-roles', roles.join(','));
						entry.setAttribute('data-role', roles[0]);
					}
				});
			})
			.catch(function () {
				// REST unavailable.
			});
	}

	function refreshFilterCounts(nav, entries) {
		var totals = {
			all: entries.length,
			speaker: 0,
			organizer: 0,
			sponsor: 0,
			attendee: 0
		};

		entries.forEach(function (entry) {
			var roles = (entry.getAttribute('data-roles') || '')
				.split(',')
				.map(function (item) {
					return item.trim();
				})
				.filter(Boolean);
			roles.forEach(function (role) {
				if (Object.prototype.hasOwnProperty.call(totals, role)) {
					totals[role] += 1;
				}
			});
		});

		nav.querySelectorAll('.ff-btn').forEach(function (btn) {
			var type = (btn.getAttribute('data-type') || 'all').trim();
			var count = Object.prototype.hasOwnProperty.call(totals, type) ? totals[type] : 0;
			var tag = btn.querySelector('.ff-count');
			if (!tag) {
				tag = document.createElement('span');
				tag.className = 'ff-count';
				btn.appendChild(document.createTextNode(' '));
				btn.appendChild(tag);
			}
			tag.textContent = String(count);
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
				.map(function (item) {
					return item.trim();
				})
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
		if (!list) {
			return;
		}
		if (!nav) {
			var feedHeader = document.querySelector('.feed-header');
			if (feedHeader) {
				var archiveBase = window.location.pathname.replace(/\/+$/, '') + '/';
				var fallback = document.createElement('div');
				fallback.className = 'feed-filters events-filters u-w-full';
				fallback.setAttribute('role', 'navigation');
				fallback.setAttribute('aria-label', 'Filter events by role');
				fallback.innerHTML = [
					'<a class="ff-btn" href="' + archiveBase + '" data-type="all">tous</a>',
					'<a class="ff-btn" href="' + archiveBase + '?event_role=speaker" data-type="speaker">Speaker</a>',
					'<a class="ff-btn" href="' + archiveBase + '?event_role=organizer" data-type="organizer">Organisateur·rice</a>',
					'<a class="ff-btn" href="' + archiveBase + '?event_role=sponsor" data-type="sponsor">Sponsor</a>',
					'<a class="ff-btn" href="' + archiveBase + '?event_role=attendee" data-type="attendee">Participant·e</a>'
				].join('');
				feedHeader.appendChild(fallback);
				nav = fallback;
			}
		}
		if (!nav) {
			return;
		}

		var entries = Array.prototype.slice.call(list.querySelectorAll('.entry[data-kind="event"]'));
		if (!entries.length) {
			return;
		}

		var current = (new URLSearchParams(window.location.search).get('event_role') || '').trim();
		annotateEventEntries(entries);
		hydrateEventRolesFromRest(entries).finally(function () {
			refreshFilterCounts(nav, entries);
			syncEventFilterButtons(nav, current);
			applyEventRoleFilter(entries, current);
		});

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
		syncJournalHubFilters();
		initHomeFeedFilters();
		initNotesArchiveFilters();
		initEventFilters();
	});
}());
