( function () {
	const { registerBlockType } = wp.blocks;
	const { useBlockProps } = wp.blockEditor;
	const { createElement: el } = wp.element;
	const { __ } = wp.i18n;

	registerBlockType( 'jardin-theme/post-engage', {
		apiVersion: 3,
		edit( { context } ) {
			const b = useBlockProps( {
				className: 'jardin-theme-post-engage is-editor',
			} );
			return el(
				'div',
				b,
				el( 'h2', { className: 'has-sm-font-size' }, __( 'Post-engage (syndication + webmentions)', 'jardin-theme' ) ),
				el(
					'p',
					{ className: 'has-text-muted-color has-xs-font-size' },
					context && context.postId
						? __( 'Preview uses the current post in the site editor when available.', 'jardin-theme' )
						: __(
							'On the front, this block shows syndication out-links and webmention replies for this post.',
							'jardin-theme'
						)
				)
			);
		},
	} );
} )();
