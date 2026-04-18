<?php
/**
 * Title: Prochains événements (Jardin)
 * Slug: jardin/upcoming-events
 * Categories: query, featured
 * Description: Bloc de mise en avant des prochains événements, stylé pour le thème Jardin.
 */
?>
<!-- wp:group {"className":"jardin-events-upcoming jardin-section-highlight","layout":{"type":"constrained"}} -->
<div class="wp-block-group jardin-events-upcoming jardin-section-highlight">
<!-- wp:heading {"level":2} --><h2>Prochains événements</h2><!-- /wp:heading -->
<!-- wp:separator {"className":"is-style-wide"} --><hr class="wp-block-separator is-style-wide" /><!-- /wp:separator -->
<!-- wp:query {"query":{"perPage":3,"postType":"event","order":"asc","orderBy":"date","inherit":false},"displayLayout":{"type":"list"}} -->
<div class="wp-block-query"><!-- wp:post-template -->
<!-- wp:group {"layout":{"type":"constrained"}} --><div class="wp-block-group jardin-events-item">
<!-- wp:post-title {"level":3,"isLink":true,"className":"jardin-events-item-title"} /-->
<!-- wp:paragraph {"className":"jardin-events-item-meta"} --><p><!-- Event date and location rendered via template/theme --></p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"jardin-events-item-link"} --><p><a href="#">En savoir plus…</a></p><!-- /wp:paragraph -->
</div><!-- /wp:group -->
<!-- /wp:post-template --></div><!-- /wp:query -->
</div><!-- /wp:group -->
