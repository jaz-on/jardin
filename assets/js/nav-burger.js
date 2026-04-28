/**
 * Header mobile menu toggle (<=768px).
 *
 * @package Jardin
 */
(function () {
	'use strict';

	function initNavBurger() {
		var proxyBurger = document.querySelector( '#header-burger-proxy' );
		var navRow = document.querySelector( '.site-header-shell .site-row-nav' );

		if ( ! proxyBurger || ! navRow ) {
			return;
		}

		function isMobile() {
			return window.innerWidth <= 768;
		}

		function closeMenu() {
			navRow.classList.remove( 'is-open-mobile' );
			proxyBurger.setAttribute( 'aria-expanded', 'false' );
		}

		proxyBurger.addEventListener( 'click', function () {
			if ( ! isMobile() ) {
				return;
			}

			var isOpen = navRow.classList.toggle( 'is-open-mobile' );
			proxyBurger.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
		} );

		navRow.addEventListener( 'click', function (event) {
			if ( ! isMobile() ) {
				return;
			}
			var link = event.target.closest( '.wp-block-navigation-item__content, a' );
			if ( link ) {
				closeMenu();
			}
		} );

		document.addEventListener( 'keydown', function (event) {
			if ( event.key === 'Escape' ) {
				closeMenu();
			}
		} );

		window.addEventListener( 'resize', function () {
			if ( ! isMobile() ) {
				closeMenu();
			}
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initNavBurger );
	} else {
		initNavBurger();
	}
})();
