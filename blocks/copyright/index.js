( function () {
	const { registerBlockType } = wp.blocks;
	const { useBlockProps } = wp.blockEditor;
	const { createElement: el } = wp.element;

	registerBlockType( 'jardin/copyright', {
		apiVersion: 3,
		edit() {
			return el(
				'div',
				useBlockProps(),
				el( 'p', {}, '© … ' ),
				el(
					'p',
					{ className: 'has-text-muted-color' },
					'Year and site title are filled in on the site.'
				)
			);
		},
	} );
} )();
