/**
 * Query Loop variations (jardin-docs theme/blocks_inventory.md § E).
 *
 * Namespace `jardin-theme/journal-mixed` (?kind=) plus `jardin-theme/now-feed`,
 * `jardin-theme/events-upcoming`, and `jardin-theme/events-past-by-role` are handled in PHP (`inc/journal-hub.php`).
 * Phase 2 shipped post + IndieBlocks only; Phase 4 extends journal-mixed with plugin CPTs.
 * Namespaces `jardin-theme/notes-by-kind` (activities archive /activites|/activities), `jardin-theme/articles-pinned`, and `jardin-theme/blogroll-grid`
 * are editor presets until matching filters exist (plugins / theme follow-up).
 *
 * @package Jardin_Theme
 */
( function ( wp ) {
	const { registerBlockVariation } = wp.blocks;
	const { __ } = wp.i18n;

	const mixedTypes = [
		'post',
		'iwcpt_note',
		'iwcpt_like',
		'favorite',
		'event',
		'beer_checkin',
		'listen',
	];

	registerBlockVariation( 'core/query', {
		name: 'journal-mixed',
		title: __( 'Journal hub (mixed)', 'jardin-theme' ),
		description: __( 'Recent items across journal post types.', 'jardin-theme' ),
		category: 'theme',
		attributes: {
			namespace: 'jardin-theme/journal-mixed',
			query: {
				perPage: 10,
				pages: 0,
				offset: 0,
				postType: mixedTypes,
				order: 'desc',
				orderBy: 'date',
				search: '',
				exclude: [],
				sticky: 'exclude',
				inherit: false,
			},
		},
		scope: [ 'inserter' ],
	} );

	registerBlockVariation( 'core/query', {
		name: 'notes-by-kind',
		title: __( 'Notes (IndieBlocks)', 'jardin-theme' ),
		description: __( 'Notes CPT only.', 'jardin-theme' ),
		category: 'theme',
		attributes: {
			namespace: 'jardin-theme/notes-by-kind',
			query: {
				perPage: 10,
				pages: 0,
				offset: 0,
				postType: 'iwcpt_note',
				order: 'desc',
				orderBy: 'date',
				sticky: 'exclude',
				inherit: false,
			},
		},
		scope: [ 'inserter' ],
	} );

	registerBlockVariation( 'core/query', {
		name: 'events-upcoming',
		title: __( 'Events (upcoming)', 'jardin-theme' ),
		description: __( 'Events ordered by event date meta (ascending).', 'jardin-theme' ),
		category: 'theme',
		attributes: {
			namespace: 'jardin-theme/events-upcoming',
			query: {
				perPage: 12,
				pages: 0,
				offset: 0,
				postType: 'event',
				order: 'asc',
				orderBy: 'meta_value',
				metaKey: 'event_date',
				sticky: '',
				inherit: false,
			},
		},
		scope: [ 'inserter' ],
	} );

	registerBlockVariation( 'core/query', {
		name: 'events-past-by-role',
		title: __( 'Events (by role meta)', 'jardin-theme' ),
		description: __( 'Events CPT; refine with filters in the inspector.', 'jardin-theme' ),
		category: 'theme',
		attributes: {
			namespace: 'jardin-theme/events-past-by-role',
			query: {
				perPage: 10,
				postType: 'event',
				order: 'desc',
				orderBy: 'date',
				inherit: false,
			},
		},
		scope: [ 'inserter' ],
	} );

	registerBlockVariation( 'core/query', {
		name: 'articles-pinned',
		title: __( 'Pinned articles', 'jardin-theme' ),
		description: __( 'Sticky posts only.', 'jardin-theme' ),
		category: 'theme',
		attributes: {
			namespace: 'jardin-theme/articles-pinned',
			query: {
				perPage: 5,
				postType: 'post',
				order: 'desc',
				orderBy: 'date',
				sticky: 'only',
				inherit: false,
			},
		},
		scope: [ 'inserter' ],
	} );

	registerBlockVariation( 'core/query', {
		name: 'blogroll-grid',
		title: __( 'Blogroll grid', 'jardin-theme' ),
		description: __( 'Blogroll CPT from jardin-bookmarks.', 'jardin-theme' ),
		category: 'theme',
		attributes: {
			namespace: 'jardin-theme/blogroll-grid',
			query: {
				perPage: 24,
				postType: 'blogroll',
				order: 'asc',
				orderBy: 'title',
				inherit: false,
			},
		},
		scope: [ 'inserter' ],
	} );

	registerBlockVariation( 'core/query', {
		name: 'now-feed',
		title: __( 'Now', 'jardin-theme' ),
		description: __( 'Entries from the now (monthly edition) post type.', 'jardin-theme' ),
		category: 'theme',
		attributes: {
			namespace: 'jardin-theme/now-feed',
			query: {
				perPage: 12,
				postType: 'now',
				order: 'desc',
				orderBy: 'date',
				sticky: 'exclude',
				inherit: false,
			},
		},
		scope: [ 'inserter' ],
	} );
} )( window.wp );
