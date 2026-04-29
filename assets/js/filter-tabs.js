/**
 * Sets aria-current on the active journal hub filter link (?kind= vs "All").
 * Build marker: 2026-04-29-events-filter-v2
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
				// Keep existing progressive behavior when REST endpoint is unavailable.
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
				.map(function (item) { return item.trim(); })
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

	function formatDateFr(isoLikeDate) {
		if (!isoLikeDate) {
			return '';
		}
		var d = new Date(isoLikeDate);
		if (Number.isNaN(d.getTime())) {
			return '';
		}
		return d.toLocaleDateString('fr-FR', {
			day: '2-digit',
			month: '2-digit',
			year: 'numeric'
		});
	}

	function formatDateRangeFr(startDate, endDate) {
		var start = formatDateFr(startDate);
		var end = formatDateFr(endDate);
		if (!start && !end) {
			return '';
		}
		if (!start) {
			return end;
		}
		if (!end || end === start) {
			return start;
		}
		return start + ' - ' + end;
	}

	function hydrateHomeIrlRowsIfMissing() {
		var rows = Array.prototype.slice.call(document.querySelectorAll('.events-upcoming .event-row'));
		if (!rows.length) {
			return;
		}

		var hasMissingData = rows.some(function (row) {
			return !row.querySelector('.entry-when') || !row.querySelector('.entry-loc');
		});
		if (!hasMissingData) {
			return;
		}

		var idToRow = {};
		rows.forEach(function (row) {
			var host = row.closest('li.wp-block-post') || row.parentElement;
			if (!host || !host.className) {
				return;
			}
			var m = host.className.match(/\bpost-(\d+)\b/);
			if (m) {
				idToRow[parseInt(m[1], 10)] = row;
			}
		});

		var ids = Object.keys(idToRow);
		if (!ids.length) {
			return;
		}

		fetch('/wp-json/wp/v2/event?per_page=100&_fields=id,event_start,event_end,event_location', {
			credentials: 'same-origin'
		})
			.then(function (res) {
				return res.ok ? res.json() : [];
			})
			.then(function (items) {
				(items || []).forEach(function (item) {
					var row = idToRow[item.id];
					if (!row) {
						return;
					}

					var when = row.querySelector('.entry-when');
					var start = item && item.event_start ? item.event_start : '';
					var end = item && item.event_end ? item.event_end : '';
					var range = formatDateRangeFr(start, end);
					if (range) {
						if (!when) {
							when = document.createElement('span');
							when.className = 'entry-when';
							row.insertBefore(when, row.firstChild);
						}
						when.textContent = range;
					}

					var where = row.querySelector('.what');
					if (!where) {
						return;
					}
					var loc = where.querySelector('.entry-loc');
					var eventLoc = item && item.event_location ? String(item.event_location).trim() : '';
					if (eventLoc) {
						if (!loc) {
							loc = document.createElement('span');
							loc.className = 'entry-loc';
							where.appendChild(loc);
						}
						loc.textContent = eventLoc;
					}
				});
			})
			.catch(function () {
				// Keep server-rendered content if REST fetch fails.
			});
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
		syncJournalFilters();
		initEventFilters();
		hydrateHomeIrlRowsIfMissing();
	});
}());
