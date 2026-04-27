/**
 * Accessibility tweaks for the core Navigation responsive overlay (dialog semantics).
 *
 * @package Jardin
 */
(function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		document.querySelectorAll( '.wp-block-navigation__responsive-container-open' ).forEach( function (btn) {
			btn.setAttribute( 'aria-haspopup', 'dialog' );
		} );
	} );
})();
