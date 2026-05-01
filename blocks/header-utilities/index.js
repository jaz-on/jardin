( function () {
	const { registerBlockType } = wp.blocks;
	const { useBlockProps, InspectorControls } = wp.blockEditor;
	const { PanelBody, SelectControl, TextControl } = wp.components;
	const { createElement: el } = wp.element;
	const { __ } = wp.i18n;

	registerBlockType( 'jardin-theme/header-utilities', {
		apiVersion: 3,
		edit( { attributes, setAttributes } ) {
			const variant = attributes.variant === 'drawer' ? 'drawer' : 'header';
			const supportUrl =
				typeof attributes.supportUrl === 'string' && attributes.supportUrl !== ''
					? attributes.supportUrl
					: '/soutenir/';
			return el(
				'div',
				useBlockProps( { className: 'jardin-header-utilities-editor-note' } ),
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Placement', 'jardin-theme' ), initialOpen: true },
						el( SelectControl, {
							label: __( 'Variant', 'jardin-theme' ),
							value: variant,
							options: [
								{ label: __( 'Header row (brand line)', 'jardin-theme' ), value: 'header' },
								{ label: __( 'Mobile drawer tools', 'jardin-theme' ), value: 'drawer' },
							],
							onChange: ( v ) => setAttributes( { variant: v } ),
						} ),
						el( TextControl, {
							label: __( 'Support link', 'jardin-theme' ),
							help: __(
								'Path from site root (e.g. /soutenir/) or full https URL. Used for the coffee / support icon.',
								'jardin-theme'
							),
							value: supportUrl,
							onChange: ( v ) => setAttributes( { supportUrl: v } ),
						} )
					)
				),
				el( 'p', { style: { marginTop: 0 } }, __( 'Header utilities', 'jardin-theme' ) ),
				el(
					'p',
					{ className: 'has-text-muted-color', style: { fontSize: '13px' } },
					variant === 'drawer'
						? __( 'Drawer: same actions as the header chrome, shown inside the menu on small screens.', 'jardin-theme' )
						: __( 'Renders language, menu, search, theme, music, and support on the live site.', 'jardin-theme' )
				)
			);
		},
	} );
} )();
