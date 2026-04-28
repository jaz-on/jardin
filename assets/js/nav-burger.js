/**
 * Accessibility tweaks for the core Navigation responsive overlay (dialog semantics).
 *
 * @package Jardin
 */
(function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var openButtons = document.querySelectorAll( '.wp-block-navigation__responsive-container-open' );
		var closeButtons = document.querySelectorAll( '.wp-block-navigation__responsive-container-close' );
		var containers = document.querySelectorAll( '.wp-block-navigation__responsive-container' );

		openButtons.forEach( function (btn) {
			btn.setAttribute( 'aria-haspopup', 'dialog' );
		} );

		// Keep button states in sync for assistive tech and keyboard users.
		openButtons.forEach( function (btn) {
			btn.addEventListener( 'click', function () {
				btn.setAttribute( 'aria-expanded', 'true' );
			} );
		} );

		closeButtons.forEach( function (btn) {
			btn.addEventListener( 'click', function () {
				openButtons.forEach( function (openBtn) {
					openBtn.setAttribute( 'aria-expanded', 'false' );
				} );
			} );
		} );

		// Auto-close responsive menu after navigation click on mobile.
		containers.forEach( function (container) {
			container.addEventListener( 'click', function (event) {
				var link = event.target.closest( '.wp-block-navigation-item__content, a' );
				if ( ! link ) {
					return;
				}

				if ( window.innerWidth > 782 ) {
					return;
				}

				var closeBtn = container.querySelector( '.wp-block-navigation__responsive-container-close' );
				if ( closeBtn ) {
					closeBtn.click();
				}
			} );
		} );
	} );
})();
