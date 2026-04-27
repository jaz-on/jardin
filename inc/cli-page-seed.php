<?php
/**
 * WP-CLI: jardin-seed (parity with Appearance → Jardin dev pages).
 *
 * @package Jardin
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

WP_CLI::add_command(
	'jardin-seed',
	/**
	 * @param list<string>              $args       Subcommand.
	 * @param array<string, bool|null> $assoc_args Flags.
	 */
	function ( array $args, array $assoc_args ): void {
		$sub = isset( $args[0] ) ? $args[0] : '';
		switch ( $sub ) {
			case 'import':
				$ids = jardin_page_seed_run(
					array(
						'sync_content'       => \WP_CLI\Utils\get_flag_value( $assoc_args, 'sync', false ),
						'assign_templates'   => ! \WP_CLI\Utils\get_flag_value( $assoc_args, 'no-templates', false ),
						'set_reading_home'   => \WP_CLI\Utils\get_flag_value( $assoc_args, 'set-home', false ),
						'set_reading_posts'  => \WP_CLI\Utils\get_flag_value( $assoc_args, 'set-blog', false ),
						'create_navigation'  => \WP_CLI\Utils\get_flag_value( $assoc_args, 'create-nav', false ),
					)
				);
				WP_CLI::success( sprintf( 'Jardin import finished (%d slugs).', count( $ids ) ) );
				break;

			case 'assign-templates':
				$n = 0;
				foreach ( jardin_page_seed_get_manifest() as $row ) {
					$path = jardin_page_seed_page_path( $row['slug'], $row['parent_slug'] );
					$post = get_page_by_path( $path, OBJECT, 'page' );
					if ( $post instanceof WP_Post ) {
						jardin_page_seed_set_page_template( (int) $post->ID, $row['template'] );
						++$n;
					}
				}
				WP_CLI::success( sprintf( 'Assigned templates on %d page(s).', $n ) );
				break;

			case 'clean-safe':
				$force   = \WP_CLI\Utils\get_flag_value( $assoc_args, 'force', false );
				$n       = jardin_page_seed_clean_tagged_only( $force );
				jardin_page_seed_reset_reading_after_clean();
				WP_CLI::success( sprintf( $force ? 'Deleted %d tagged page(s).' : 'Trashed %d tagged page(s).', $n ) );
				break;

			case 'clean-manifest':
				$force = \WP_CLI\Utils\get_flag_value( $assoc_args, 'force', false );
				$n     = jardin_page_seed_clean_manifest_slugs( $force );
				jardin_page_seed_reset_reading_after_clean();
				WP_CLI::success( sprintf( $force ? 'Deleted %d manifest page(s).' : 'Trashed %d manifest page(s).', $n ) );
				break;

			case 'reset-safe':
				$force   = \WP_CLI\Utils\get_flag_value( $assoc_args, 'force', false );
				$cleaned = jardin_page_seed_clean_tagged_only( $force );
				jardin_page_seed_reset_reading_after_clean();
				$ids = jardin_page_seed_run(
					array(
						'sync_content'       => \WP_CLI\Utils\get_flag_value( $assoc_args, 'sync', false ),
						'assign_templates'   => ! \WP_CLI\Utils\get_flag_value( $assoc_args, 'no-templates', false ),
						'set_reading_home'   => \WP_CLI\Utils\get_flag_value( $assoc_args, 'set-home', false ),
						'set_reading_posts'  => \WP_CLI\Utils\get_flag_value( $assoc_args, 'set-blog', false ),
						'create_navigation'  => \WP_CLI\Utils\get_flag_value( $assoc_args, 'create-nav', false ),
					)
				);
				WP_CLI::success( sprintf( 'Safe reset: cleaned %d, processed %d slug(s).', $cleaned, count( $ids ) ) );
				break;

			case 'reset-manifest':
				$force   = \WP_CLI\Utils\get_flag_value( $assoc_args, 'force', false );
				$cleaned = jardin_page_seed_clean_manifest_slugs( $force );
				jardin_page_seed_reset_reading_after_clean();
				$ids = jardin_page_seed_run(
					array(
						'sync_content'       => \WP_CLI\Utils\get_flag_value( $assoc_args, 'sync', false ),
						'assign_templates'   => ! \WP_CLI\Utils\get_flag_value( $assoc_args, 'no-templates', false ),
						'set_reading_home'   => \WP_CLI\Utils\get_flag_value( $assoc_args, 'set-home', false ),
						'set_reading_posts'  => \WP_CLI\Utils\get_flag_value( $assoc_args, 'set-blog', false ),
						'create_navigation'  => \WP_CLI\Utils\get_flag_value( $assoc_args, 'create-nav', false ),
					)
				);
				WP_CLI::success( sprintf( 'Manifest reset: cleaned %d, processed %d slug(s).', $cleaned, count( $ids ) ) );
				break;

			case 'reset-fse':
				$n = jardin_page_seed_reset_fse_customizations();
				WP_CLI::success( sprintf( 'Removed %d Site Editor override post(s).', $n ) );
				break;

			default:
				WP_CLI::error( 'Usage: wp jardin-seed import|assign-templates|clean-safe|clean-manifest|reset-safe|reset-manifest|reset-fse [--sync] [--no-templates] [--set-home] [--set-blog] [--create-nav] [--force]' );
		}
	},
	array(
		'shortdesc' => 'Import or clean Jardin manifest pages (same as Appearance → Jardin dev pages).',
		'synopsis'  => array(
			array(
				'type'        => 'positional',
				'name'        => 'action',
				'description' => 'import, assign-templates, clean-safe, clean-manifest, reset-safe, reset-manifest, reset-fse',
				'optional'    => false,
			),
			array(
				'type'        => 'flag',
				'name'        => 'sync',
				'description' => 'With import/reset-*: overwrite post_content from content/seeds when files exist.',
			),
			array(
				'type'        => 'flag',
				'name'        => 'no-templates',
				'description' => 'With import/reset-*: do not assign _wp_page_template.',
			),
			array(
				'type'        => 'flag',
				'name'        => 'set-home',
				'description' => 'With import/reset-*: set static front page to page slug "home" if it exists.',
			),
			array(
				'type'        => 'flag',
				'name'        => 'set-blog',
				'description' => 'With import/reset-*: set posts page to slug "blog" when Reading is already static.',
			),
			array(
				'type'        => 'flag',
				'name'        => 'create-nav',
				'description' => 'With import/reset-*: build wp_navigation jardin-dev-nav and wire header Navigation block.',
			),
			array(
				'type'        => 'flag',
				'name'        => 'force',
				'description' => 'With clean-* / reset-*: permanently delete instead of trash.',
			),
		),
	)
);
