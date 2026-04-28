/**
 * Header mobile menu toggle (mockup parity <=600px).
 *
 * @package Jardin
 */
(function () {
	'use strict';

	function initNavBurger() {
		var proxyBurger = document.querySelector( '#header-burger-proxy' );
		var headerNav = document.querySelector( '.site-header-shell nav.primary.jardin-primary-nav' );

		if ( ! proxyBurger || ! headerNav ) {
			return;
		}

		function isMobile() {
			return window.innerWidth <= 600;
		}

		function closeMenu() {
			headerNav.classList.remove( 'is-open-mobile' );
			proxyBurger.setAttribute( 'aria-expanded', 'false' );
		}

		proxyBurger.addEventListener( 'click', function () {
			if ( ! isMobile() ) {
				return;
			}

			var isOpen = headerNav.classList.toggle( 'is-open-mobile' );
			proxyBurger.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
		} );

		headerNav.addEventListener( 'click', function (event) {
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
