/**
 * Project CPT — meta and sync from the document sidebar (no classic metabox).
 */
( function ( wp ) {
	const { registerPlugin } = wp.plugins;
	const editPost = wp.editPost || wp.editor;
	const PluginDocumentSettingPanel = editPost && editPost.PluginDocumentSettingPanel;
	const {
		TextControl,
		ToggleControl,
		RadioControl,
		Button,
		Notice,
	} = wp.components;
	const { createElement: el, Fragment } = wp.element;
	const { __, sprintf } = wp.i18n;
	const { useSelect } = wp.data;
	const { useEntityProp } = wp.coreData;

	function useProjectMeta() {
		return useEntityProp( 'postType', 'project', 'meta' );
	}

	function fieldGap( child ) {
		return el( 'div', { style: { marginBottom: '12px' } }, child );
	}

	function ProjectDataPanel() {
		const postType = useSelect( function ( select ) {
			return select( 'core/editor' ).getCurrentPostType();
		}, [] );

		if ( postType !== 'project' || ! PluginDocumentSettingPanel ) {
			return null;
		}

		const [ meta, setMeta ] = useProjectMeta();
		const cfg = window.jardinProjectEditor || {};

		const patchMeta = function ( partial ) {
			setMeta( Object.assign( {}, meta || {}, partial ) );
		};

		const syncMode =
			meta && meta.sync_mode ? meta.sync_mode : 'auto';
		const syncModeSafe = syncMode === 'manual' ? 'manual' : 'auto';

		const syncState = meta && meta._jardin_project_sync_state ? String( meta._jardin_project_sync_state ) : '';
		const lastSync = meta && meta._jardin_project_last_sync_at ? String( meta._jardin_project_last_sync_at ) : '';
		const lastErr = meta && meta._jardin_project_last_error ? String( meta._jardin_project_last_error ) : '';

		const children = [];

		children.push(
			fieldGap(
				el( TextControl, {
					label: __( 'GitHub repository', 'jardin-theme' ),
					type: 'url',
					value: meta && meta.repo_url ? meta.repo_url : '',
					placeholder: 'https://github.com/owner/repo',
					onChange: function ( v ) {
						patchMeta( { repo_url: v } );
					},
				} )
			)
		);

		children.push(
			fieldGap(
				el( TextControl, {
					label: __( 'URL WordPress.org', 'jardin-theme' ),
					type: 'url',
					value: meta && meta.wporg_url ? meta.wporg_url : '',
					placeholder: 'https://wordpress.org/plugins/example/',
					onChange: function ( v ) {
						patchMeta( { wporg_url: v } );
					},
				} )
			)
		);

		children.push(
			fieldGap(
				el( TextControl, {
					label: __( 'Current version', 'jardin-theme' ),
					value: meta && meta.current_version ? meta.current_version : '',
					placeholder: 'v1.2.0',
					onChange: function ( v ) {
						patchMeta( { current_version: v } );
					},
				} )
			)
		);

		children.push(
			fieldGap(
				el( TextControl, {
					label: __( 'License', 'jardin-theme' ),
					help: cfg.defaultLicense
						? sprintf( __( 'Common default: %s', 'jardin-theme' ), cfg.defaultLicense )
						: '',
					value: meta && meta.license ? meta.license : '',
					placeholder: cfg.defaultLicense || '',
					onChange: function ( v ) {
						patchMeta( { license: v } );
					},
				} )
			)
		);

		children.push(
			fieldGap(
				el( TextControl, {
					label: __( 'Stack label', 'jardin-theme' ),
					value: meta && meta.stack_label ? meta.stack_label : '',
					placeholder: 'PHP 8.2 · WP 6.9+',
					onChange: function ( v ) {
						patchMeta( { stack_label: v } );
					},
				} )
			)
		);

		children.push(
			fieldGap(
				el( RadioControl, {
					label: __( 'Changelog synchronization', 'jardin-theme' ),
					selected: syncModeSafe,
					options: [
						{
							label: __( 'Automatic (cron + publish)', 'jardin-theme' ),
							value: 'auto',
						},
						{
							label: __( 'Manual only', 'jardin-theme' ),
							value: 'manual',
						},
					],
					onChange: function ( v ) {
						patchMeta( { sync_mode: v } );
					},
				} )
			)
		);

		if ( ! cfg.syncUrl ) {
			children.push(
				fieldGap(
					el(
						Notice,
						{
							status: 'info',
							isDismissible: false,
						},
						__(
							'Save the draft before you can run a manual sync.',
							'jardin-theme'
						)
					)
				)
			);
		} else {
			children.push(
				fieldGap(
					el(
						Button,
						{
							variant: 'secondary',
							href: cfg.syncUrl,
						},
						__( 'Sync changelog (GitHub)', 'jardin-theme' )
					)
				)
			);
		}

		if ( syncState ) {
			children.push(
				el(
					'p',
					{ className: 'components-base-control__help', style: { marginBottom: '6px' } },
					el( 'strong', null, __( 'Sync state: ', 'jardin-theme' ) ),
					syncState
				)
			);
		}
		if ( lastSync ) {
			children.push(
				el(
					'p',
					{ className: 'components-base-control__help', style: { marginBottom: '6px' } },
					el( 'strong', null, __( 'Last sync: ', 'jardin-theme' ) ),
					lastSync
				)
			);
		}
		if ( lastErr ) {
			children.push(
				el(
					'p',
					{ className: 'components-base-control__help', style: { marginBottom: '0' } },
					el( 'strong', null, __( 'Last error: ', 'jardin-theme' ) ),
					lastErr
				)
			);
		}

		return el(
			PluginDocumentSettingPanel,
			{
				name: 'jardin-project-data',
				title: __( 'Project data', 'jardin-theme' ),
				className: 'jardin-project-data-panel',
			},
			children
		);
	}

	function ProjectFeaturedPanel() {
		const postType = useSelect( function ( select ) {
			return select( 'core/editor' ).getCurrentPostType();
		}, [] );

		if ( postType !== 'project' || ! PluginDocumentSettingPanel ) {
			return null;
		}

		const [ meta, setMeta ] = useProjectMeta();
		const featured = !! ( meta && meta.project_featured );

		return el(
			PluginDocumentSettingPanel,
			{
				name: 'jardin-project-featured',
				title: __( 'Featured', 'jardin-theme' ),
				className: 'jardin-project-featured-panel',
			},
			el( ToggleControl, {
				label: __( 'Show in the home “pinned projects” grid', 'jardin-theme' ),
				checked: featured,
				onChange: function ( value ) {
					setMeta(
						Object.assign( {}, meta || {}, {
							project_featured: value,
						} )
					);
				},
			} ),
			el(
				'p',
				{
					className: 'components-base-control__help',
					style: { marginTop: '8px', marginBottom: 0 },
				},
				__(
					'Order in that grid follows the document “Order” field (menu_order), then publish date.',
					'jardin-theme'
				)
			)
		);
	}

	function ProjectSidebarRoot() {
		const postType = useSelect( function ( select ) {
			return select( 'core/editor' ).getCurrentPostType();
		}, [] );

		if ( postType !== 'project' ) {
			return null;
		}

		return el(
			Fragment,
			null,
			el( ProjectDataPanel, null ),
			el( ProjectFeaturedPanel, null )
		);
	}

	registerPlugin( 'jardin-theme-project-sidebar', {
		icon: 'portfolio',
		render: ProjectSidebarRoot,
	} );
} )( window.wp );
