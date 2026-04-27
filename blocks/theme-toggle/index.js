( function () {
	const { registerBlockType } = wp.blocks;
	const { useBlockProps } = wp.blockEditor;
	const { createElement: el } = wp.element;

	registerBlockType( 'jardin/theme-toggle', {
		apiVersion: 3,
		edit() {
			return el(
				'div',
				useBlockProps( { className: 'jardin-theme-toggle-editor-note' } ),
				el( 'p', {}, 'Theme palette' ),
				el(
					'p',
					{ className: 'has-text-muted-color' },
					'Interactive on the site.'
				)
			);
		},
	} );
} )();
