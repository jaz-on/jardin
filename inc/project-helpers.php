<?php
/**
 * Helpers for the project CPT (theme-owned).
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * User-Agent string for GitHub API requests.
 *
 * @return string
 */
function jardin_theme_projects_user_agent(): string {
	static $ua = null;
	if ( null !== $ua ) {
		return $ua;
	}
	$theme = wp_get_theme();
	if ( $theme->parent() ) {
		$theme = $theme->parent();
	}
	$ver = (string) $theme->get( 'Version' );
	$ua  = 'jardin-theme/' . ( '' !== $ver ? $ver : '1' );
	return $ua;
}

/**
 * Post type slug.
 *
 * @return string
 */
function jardin_projects_get_post_type(): string {
	return 'project';
}

/**
 * Status taxonomy slug.
 *
 * @return string
 */
function jardin_projects_get_status_taxonomy(): string {
	return 'project_status';
}

/**
 * Allowed sync modes.
 *
 * @return array<int, string>
 */
function jardin_projects_get_sync_modes(): array {
	return array( 'auto', 'manual' );
}

/**
 * Allowed status slugs.
 *
 * @return array<int, string>
 */
function jardin_projects_get_status_slugs(): array {
	return array( 'actif', 'wip', 'stable', 'planned', 'archived' );
}

/**
 * Default meta values for project fields.
 *
 * @return array<string, mixed>
 */
function jardin_projects_get_default_meta(): array {
	return array(
		'current_version'  => '',
		'license'          => 'GPL-2.0-or-later',
		'stack_label'      => '',
		'repo_url'         => '',
		'wporg_url'        => '',
		'project_featured' => 0,
		'sync_mode'        => 'auto',
	);
}

/**
 * Return sanitized sync mode.
 *
 * @param string $raw Raw sync mode.
 * @return string
 */
function jardin_projects_sanitize_sync_mode( $raw ): string {
	$value = sanitize_key( (string) $raw );
	return in_array( $value, jardin_projects_get_sync_modes(), true ) ? $value : 'auto';
}

/**
 * Whether project should be synced automatically.
 *
 * @param int $project_id Project ID.
 * @return bool
 */
function jardin_projects_is_auto_sync_enabled( int $project_id ): bool {
	$mode = get_post_meta( $project_id, 'sync_mode', true );
	return 'manual' !== jardin_projects_sanitize_sync_mode( is_string( $mode ) ? $mode : '' );
}

/**
 * Convert project status term slug to CSS token.
 *
 * @param string $slug Status slug.
 * @return string
 */
function jardin_projects_status_class( string $slug ): string {
	$slug = sanitize_html_class( $slug );
	return '' !== $slug ? $slug : 'stable';
}

/**
 * Return status label for a project.
 *
 * @param int $project_id Project ID.
 * @return string
 */
function jardin_projects_get_status_label( int $project_id ): string {
	$terms = get_the_terms( $project_id, jardin_projects_get_status_taxonomy() );
	if ( ! is_array( $terms ) || is_wp_error( $terms ) || ! isset( $terms[0] ) ) {
		return '';
	}
	return (string) $terms[0]->name;
}

/**
 * Return status slug for a project.
 *
 * @param int $project_id Project ID.
 * @return string
 */
function jardin_projects_get_status_slug( int $project_id ): string {
	$terms = get_the_terms( $project_id, jardin_projects_get_status_taxonomy() );
	if ( ! is_array( $terms ) || is_wp_error( $terms ) || ! isset( $terms[0] ) ) {
		return '';
	}
	return sanitize_key( (string) $terms[0]->slug );
}
