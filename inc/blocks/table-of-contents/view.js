/**
 * JavaScript minimal pour le bloc Table of Contents.
 * Gère uniquement la mise à jour de l'URL (le smooth scroll est géré par CSS).
 *
 * @package Jardin
 * @since 0.1.0
 */

(function() {
	'use strict';

	// Attendre que le DOM soit chargé.
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}

	function init() {
		const tocLinks = document.querySelectorAll('.jardin-toc__link');

		tocLinks.forEach(function(link) {
			link.addEventListener('click', handleTocClick);
		});
	}

	function handleTocClick(e) {
		const href = e.currentTarget.getAttribute('href');

		if (!href || !href.startsWith('#')) {
			return;
		}

		// Laisser le navigateur gérer le scroll (CSS scroll-behavior: smooth)
		// Juste mettre à jour l'URL après un délai pour éviter les conflits
		setTimeout(function() {
			if (history.pushState) {
				history.pushState(null, null, href);
			}
		}, 100);
	}
})();

