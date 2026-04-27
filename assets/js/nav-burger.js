/**
 * Optional enhancements for core Navigation overlay (Phase 2.2+).
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
