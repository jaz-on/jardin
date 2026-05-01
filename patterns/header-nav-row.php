<?php
/**
 * Title: Header — nav row (primary nav + mobile drawer tools)
 * Slug: jardin-theme/header-nav-row
 * Categories: header
 * Description: Primary Navigation (starter links) and drawer utilities — edit links in Site Editor after inserting or reset template part.
 * Inserter: no
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

?>
<!-- wp:group {"className":"site-row site-row-nav","layout":{"type":"flex","flexWrap":"nowrap"},"style":{"spacing":{"blockGap":"0"}}} -->
<div class="wp-block-group site-row site-row-nav">
	<!-- wp:navigation {"className":"primary jardin-theme-primary-nav","overlayMenu":"never","layout":{"type":"flex","setCascadingProperties":true,"justifyContent":"left","orientation":"horizontal","flexWrap":"nowrap"}} -->
		<!-- wp:navigation-link {"label":"/journal","type":"custom","url":"/journal/","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"/articles","type":"custom","url":"/articles/","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"/activites","type":"custom","url":"/activites/","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"/evenements","type":"custom","url":"/evenements/","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"/projets","type":"custom","url":"/projets/","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"/maintenant","type":"custom","url":"/maintenant/","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"/a-propos","type":"custom","url":"/a-propos/","kind":"custom"} /-->
	<!-- /wp:navigation -->

	<!-- wp:jardin-theme/header-utilities {"variant":"drawer"} /-->
</div>
<!-- /wp:group -->
