/**
 * Query Loop variations (jardin-docs theme/blocks_inventory.md § E).
 *
 * Namespace `jardin/journal-mixed` (?kind=) plus `jardin/now-updates-feed`,
 * `jardin/events-upcoming`, and `jardin/events-past-by-role` are handled in PHP (`inc/journal-hub.php`).
 * Phase 2 shipped post + IndieBlocks only; Phase 4 extends journal-mixed with plugin CPTs.
 * Namespaces `jardin/notes-by-kind`, `jardin/articles-pinned`, and `jardin/blogroll-grid`
 * are editor presets until matching filters exist (plugins / theme follow-up).
 *
 * @package Jardin
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
		title: __( 'Journal hub (mixed)', 'jardin' ),
		description: __( 'Recent items across journal post types.', 'jardin' ),
		category: 'theme',
		attributes: {
			namespace: 'jardin/journal-mixed',
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
		title: __( 'Notes (IndieBlocks)', 'jardin' ),
		description: __( 'Notes CPT only.', 'jardin' ),
		category: 'theme',
		attributes: {
			namespace: 'jardin/notes-by-kind',
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
		title: __( 'Events (upcoming)', 'jardin' ),
		description: __( 'Events ordered by event date meta (ascending).', 'jardin' ),
		category: 'theme',
		attributes: {
			namespace: 'jardin/events-upcoming',
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
		title: __( 'Events (by role meta)', 'jardin' ),
		description: __( 'Events CPT; refine with filters in the inspector.', 'jardin' ),
		category: 'theme',
		attributes: {
			namespace: 'jardin/events-past-by-role',
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
		title: __( 'Pinned articles', 'jardin' ),
		description: __( 'Sticky posts only.', 'jardin' ),
		category: 'theme',
		attributes: {
			namespace: 'jardin/articles-pinned',
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
		title: __( 'Blogroll grid', 'jardin' ),
		description: __( 'Blogroll CPT from jardin-feed.', 'jardin' ),
		category: 'theme',
		attributes: {
			namespace: 'jardin/blogroll-grid',
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
		name: 'now-updates-feed',
		title: __( 'Now updates (category)', 'jardin' ),
		description: __( 'Posts in the now-updates category.', 'jardin' ),
		category: 'theme',
		attributes: {
			namespace: 'jardin/now-updates-feed',
			query: {
				perPage: 12,
				postType: 'post',
				order: 'desc',
				orderBy: 'date',
				categories: [],
				categoryIds: [],
				sticky: 'exclude',
				inherit: false,
			},
		},
		scope: [ 'inserter' ],
	} );
} )( window.wp );
