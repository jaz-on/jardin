<?php
/**
 * Appearance → Jardin dev pages: import/clean/reset manifest + FSE DB reset.
 *
 * @package Jardin
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'JARDIN_DEV_PAGES_SLUG' ) ) {
	define( 'JARDIN_DEV_PAGES_SLUG', 'jardin-dev-pages' );
}

/**
 * Admin URL for this screen.
 */
function jardin_page_seed_admin_url(): string {
	return admin_url( 'themes.php?page=' . JARDIN_DEV_PAGES_SLUG );
}

/**
 * Register submenu under Appearance.
 */
function jardin_page_seed_register_admin_page(): void {
	add_theme_page(
		__( 'Jardin dev pages', 'jardin' ),
		__( 'Jardin dev pages', 'jardin' ),
		'manage_options',
		JARDIN_DEV_PAGES_SLUG,
		'jardin_page_seed_render_admin_page'
	);
}
add_action( 'admin_menu', 'jardin_page_seed_register_admin_page' );

/**
 * Handle POST actions.
 */
function jardin_page_seed_handle_admin_post(): void {
	$method = isset( $_SERVER['REQUEST_METHOD'] ) ? sanitize_text_field( wp_unslash( (string) $_SERVER['REQUEST_METHOD'] ) ) : '';
	if ( 'POST' !== $method || empty( $_POST['jardin_seed_action'] ) ) {
		return;
	}
	$pg = isset( $_GET['page'] ) ? sanitize_key( (string) wp_unslash( $_GET['page'] ) ) : '';
	if ( JARDIN_DEV_PAGES_SLUG !== $pg ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to do that.', 'jardin' ) );
	}
	check_admin_referer( 'jardin_page_seed' );

	$action   = sanitize_key( (string) wp_unslash( $_POST['jardin_seed_action'] ) );
	$base     = admin_url( 'themes.php' );
	$redirect = add_query_arg( array( 'page' => JARDIN_DEV_PAGES_SLUG ), $base );

	switch ( $action ) {
		case 'import':
			$ids = jardin_page_seed_run(
				array(
					'sync_content'     => ! empty( $_POST['jardin_sync_content'] ),
					'assign_templates' => ! empty( $_POST['jardin_assign_templates'] ),
					'set_reading_home' => ! empty( $_POST['jardin_set_reading_home'] ),
				)
			);
			$count = is_array( $ids ) ? count( $ids ) : 0;
			$redirect = add_query_arg(
				array(
					'page'          => JARDIN_DEV_PAGES_SLUG,
					'jardinmsg'     => 'imported',
					'jardincount'   => (string) (int) $count,
				),
				$base
			);
			break;

		case 'assign_templates':
			$n = 0;
			foreach ( jardin_page_seed_get_manifest() as $row ) {
				$path = jardin_page_seed_page_path( $row['slug'], $row['parent_slug'] );
				$post = get_page_by_path( $path, OBJECT, 'page' );
				if ( $post instanceof WP_Post ) {
					jardin_page_seed_set_page_template( (int) $post->ID, $row['template'] );
					++$n;
				}
			}
			$redirect = add_query_arg(
				array(
					'page'        => JARDIN_DEV_PAGES_SLUG,
					'jardinmsg'   => 'templates',
					'jardincount' => (string) $n,
				),
				$base
			);
			break;

		case 'clean_safe':
			$force   = ! empty( $_POST['jardin_force_safe'] );
			$removed = jardin_page_seed_clean_tagged_only( $force );
			jardin_page_seed_reset_reading_after_clean();
			$redirect = add_query_arg(
				array(
					'page'        => JARDIN_DEV_PAGES_SLUG,
					'jardinmsg'   => 'cleaned_safe',
					'jardincount' => (string) (int) $removed,
				),
				$base
			);
			break;

		case 'clean_manifest':
			$force   = ! empty( $_POST['jardin_force_manifest'] );
			$removed = jardin_page_seed_clean_manifest_slugs( $force );
			jardin_page_seed_reset_reading_after_clean();
			$redirect = add_query_arg(
				array(
					'page'        => JARDIN_DEV_PAGES_SLUG,
					'jardinmsg'   => 'cleaned_manifest',
					'jardincount' => (string) (int) $removed,
				),
				$base
			);
			break;

		case 'reset_safe':
			$force   = ! empty( $_POST['jardin_reset_safe_force'] );
			$cleaned = jardin_page_seed_clean_tagged_only( $force );
			jardin_page_seed_reset_reading_after_clean();
			$ids = jardin_page_seed_run(
				array(
					'sync_content'     => ! empty( $_POST['jardin_reset_sync'] ),
					'assign_templates' => ! empty( $_POST['jardin_reset_assign_templates'] ),
					'set_reading_home' => ! empty( $_POST['jardin_reset_reading_home'] ),
				)
			);
			$count = is_array( $ids ) ? count( $ids ) : 0;
			$redirect = add_query_arg(
				array(
					'page'           => JARDIN_DEV_PAGES_SLUG,
					'jardinmsg'      => 'reset_safe',
					'jardincount'    => (string) (int) $count,
					'jardincleaned'  => (string) (int) $cleaned,
				),
				$base
			);
			break;

		case 'reset_manifest':
			$force   = ! empty( $_POST['jardin_reset_manifest_force'] );
			$cleaned = jardin_page_seed_clean_manifest_slugs( $force );
			jardin_page_seed_reset_reading_after_clean();
			$ids = jardin_page_seed_run(
				array(
					'sync_content'     => ! empty( $_POST['jardin_reset_manifest_sync'] ),
					'assign_templates' => ! empty( $_POST['jardin_reset_manifest_assign'] ),
					'set_reading_home' => ! empty( $_POST['jardin_reset_manifest_reading'] ),
				)
			);
			$count = is_array( $ids ) ? count( $ids ) : 0;
			$redirect = add_query_arg(
				array(
					'page'          => JARDIN_DEV_PAGES_SLUG,
					'jardinmsg'     => 'reset_manifest',
					'jardincount'   => (string) (int) $count,
					'jardincleaned' => (string) (int) $cleaned,
				),
				$base
			);
			break;

		case 'reset_fse':
			$removed  = jardin_page_seed_reset_fse_customizations();
			$redirect = add_query_arg(
				array(
					'page'        => JARDIN_DEV_PAGES_SLUG,
					'jardinmsg'   => 'fse_reset',
					'jardincount' => (string) (int) $removed,
				),
				$base
			);
			break;

		default:
			return;
	}

	wp_safe_redirect( $redirect );
	exit;
}
add_action( 'load-appearance_page_' . JARDIN_DEV_PAGES_SLUG, 'jardin_page_seed_handle_admin_post' );

/**
 * Admin notices after redirect.
 */
function jardin_page_seed_admin_notices(): void {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	global $pagenow;
	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- Flash query args from our redirects.
	$pg = isset( $_GET['page'] ) ? sanitize_key( (string) wp_unslash( $_GET['page'] ) ) : '';
	if ( 'themes.php' !== $pagenow || JARDIN_DEV_PAGES_SLUG !== $pg ) {
		return;
	}
	$msg = isset( $_GET['jardinmsg'] ) ? sanitize_key( (string) wp_unslash( $_GET['jardinmsg'] ) ) : '';
	if ( '' === $msg ) {
		return;
	}
	$count   = isset( $_GET['jardincount'] ) ? (int) $_GET['jardincount'] : 0;
	$cleaned = isset( $_GET['jardincleaned'] ) ? (int) $_GET['jardincleaned'] : 0;
	// phpcs:enable WordPress.Security.NonceVerification.Recommended

	$text = '';
	switch ( $msg ) {
		case 'imported':
			$text = sprintf(
				/* translators: %d: number of manifest slugs touched */
				_n( 'Jardin import finished (%d slug).', 'Jardin import finished (%d slugs).', $count, 'jardin' ),
				$count
			);
			break;
		case 'templates':
			$text = sprintf(
				/* translators: %d: pages updated */
				_n( 'Assigned templates on %d existing page.', 'Assigned templates on %d existing pages.', $count, 'jardin' ),
				$count
			);
			break;
		case 'cleaned_safe':
			$text = sprintf(
				/* translators: %d: pages removed */
				_n( 'Removed %d page created by Jardin seed (tagged only).', 'Removed %d pages created by Jardin seed (tagged only).', $count, 'jardin' ),
				$count
			);
			break;
		case 'cleaned_manifest':
			$text = sprintf(
				/* translators: %d: pages removed */
				_n( 'Removed %d page matching manifest slugs.', 'Removed %d pages matching manifest slugs.', $count, 'jardin' ),
				$count
			);
			break;
		case 'reset_safe':
			$text = sprintf(
				/* translators: 1: removed tagged pages, 2: slugs re-imported */
				__( 'Safe reset: removed %1$d tagged page(s), then processed %2$d manifest slug(s).', 'jardin' ),
				$cleaned,
				$count
			);
			break;
		case 'reset_manifest':
			$text = sprintf(
				/* translators: 1: manifest pages removed, 2: slugs re-imported */
				__( 'Full manifest reset: removed %1$d page(s) by slug, then processed %2$d slug(s).', 'jardin' ),
				$cleaned,
				$count
			);
			break;
		case 'fse_reset':
			$text = sprintf(
				/* translators: %d: template posts removed */
				_n( 'Site Editor DB overrides removed (%d post).', 'Site Editor DB overrides removed (%d posts).', $count, 'jardin' ),
				$count
			);
			break;
		default:
			return;
	}

	printf(
		'<div class="notice notice-success is-dismissible"><p>%s</p></div>',
		esc_html( $text )
	);
}
add_action( 'admin_notices', 'jardin_page_seed_admin_notices' );

/**
 * Render admin UI.
 */
function jardin_page_seed_render_admin_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$slugs = array();
	foreach ( jardin_page_seed_get_manifest() as $row ) {
		$slugs[] = $row['slug'];
	}
	$action_url = jardin_page_seed_admin_url();
	$parts      = jardin_page_seed_count_fse_posts( 'wp_template_part' );
	$tmpls      = jardin_page_seed_count_fse_posts( 'wp_template' );
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<p>
			<?php esc_html_e( 'Create or refresh the pages expected by the Jardin theme (journal hub, placeholders, etc.), assign FSE page templates, and reset Site Editor overrides on a disposable dev site.', 'jardin' ); ?>
		</p>
		<p class="description">
			<?php esc_html_e( 'Default import does not overwrite existing page bodies—safe when you cloned production content. Enable “Sync body from theme seeds” only to replace content from content/seeds/*.html.', 'jardin' ); ?>
		</p>
		<p class="description">
			<?php esc_html_e( 'CLI: wp jardin-seed import|assign-templates|clean-safe|clean-manifest|reset-safe|reset-manifest|reset-fse (see wp help jardin-seed).', 'jardin' ); ?>
		</p>
		<p class="description">
			<?php esc_html_e( 'Polylang: run imports in each language admin context, or duplicate pages manually—this tool matches pages by slug/path only.', 'jardin' ); ?>
		</p>

		<h2 class="title"><?php esc_html_e( 'Manifest slugs', 'jardin' ); ?></h2>
		<p class="description"><?php echo esc_html( implode( ', ', $slugs ) ); ?></p>

		<hr />

		<h2 class="title"><?php esc_html_e( 'Import', 'jardin' ); ?></h2>
		<form method="post" action="<?php echo esc_url( $action_url ); ?>">
			<?php wp_nonce_field( 'jardin_page_seed' ); ?>
			<input type="hidden" name="jardin_seed_action" value="import" />
			<p>
				<label>
					<input type="checkbox" name="jardin_sync_content" value="1" />
					<?php esc_html_e( 'Sync body from theme seeds (overwrites post_content for existing pages when a seed file exists)', 'jardin' ); ?>
				</label>
			</p>
			<p>
				<label>
					<input type="checkbox" name="jardin_assign_templates" value="1" checked />
					<?php esc_html_e( 'Assign / update FSE page template for every manifest page found or created', 'jardin' ); ?>
				</label>
			</p>
			<p>
				<label>
					<input type="checkbox" name="jardin_set_reading_home" value="1" />
					<?php esc_html_e( 'Set static front page to the published page with slug “home” if it exists', 'jardin' ); ?>
				</label>
			</p>
			<?php submit_button( __( 'Run import', 'jardin' ), 'primary' ); ?>
		</form>

		<h3><?php esc_html_e( 'Templates only', 'jardin' ); ?></h3>
		<p class="description"><?php esc_html_e( 'Assigns templates from the manifest to existing pages without creating pages or changing bodies.', 'jardin' ); ?></p>
		<form method="post" action="<?php echo esc_url( $action_url ); ?>">
			<?php wp_nonce_field( 'jardin_page_seed' ); ?>
			<input type="hidden" name="jardin_seed_action" value="assign_templates" />
			<?php submit_button( __( 'Assign templates only', 'jardin' ), 'secondary' ); ?>
		</form>

		<hr />

		<h2 class="title"><?php esc_html_e( 'Remove', 'jardin' ); ?></h2>

		<h3><?php esc_html_e( 'Safe remove (tagged pages only)', 'jardin' ); ?></h3>
		<p class="description"><?php esc_html_e( 'Trashes pages this tool created (meta _jardin_seed_created). Does not remove production-cloned pages.', 'jardin' ); ?></p>
		<form method="post" action="<?php echo esc_url( $action_url ); ?>" onsubmit="return window.confirm( '<?php echo esc_js( __( 'Trash pages created by Jardin seed?', 'jardin' ) ); ?>' );">
			<?php wp_nonce_field( 'jardin_page_seed' ); ?>
			<input type="hidden" name="jardin_seed_action" value="clean_safe" />
			<p>
				<label>
					<input type="checkbox" name="jardin_force_safe" value="1" />
					<?php esc_html_e( 'Delete permanently instead of trash', 'jardin' ); ?>
				</label>
			</p>
			<?php submit_button( __( 'Remove tagged pages', 'jardin' ), 'delete' ); ?>
		</form>

		<h3><?php esc_html_e( 'Remove by manifest slugs (destructive)', 'jardin' ); ?></h3>
		<p class="description" style="color:#a00;">
			<?php esc_html_e( 'Trashes every published page whose path matches the manifest (e.g. “journal”). This will remove production-cloned pages with the same slugs. Use only on disposable dev.', 'jardin' ); ?>
		</p>
		<form method="post" action="<?php echo esc_url( $action_url ); ?>" onsubmit="return window.confirm( '<?php echo esc_js( __( 'Remove ALL pages matching manifest slugs? This may delete cloned production content.', 'jardin' ) ); ?>' );">
			<?php wp_nonce_field( 'jardin_page_seed' ); ?>
			<input type="hidden" name="jardin_seed_action" value="clean_manifest" />
			<p>
				<label>
					<input type="checkbox" name="jardin_force_manifest" value="1" />
					<?php esc_html_e( 'Delete permanently instead of trash', 'jardin' ); ?>
				</label>
			</p>
			<?php submit_button( __( 'Remove manifest slug pages', 'jardin' ), 'delete' ); ?>
		</form>

		<hr />

		<h2 class="title"><?php esc_html_e( 'Reset', 'jardin' ); ?></h2>

		<h3><?php esc_html_e( 'Safe reset', 'jardin' ); ?></h3>
		<p class="description"><?php esc_html_e( 'Removes tagged seed pages, then runs import again.', 'jardin' ); ?></p>
		<form method="post" action="<?php echo esc_url( $action_url ); ?>" onsubmit="return window.confirm( '<?php echo esc_js( __( 'Safe reset: trash tagged seed pages and import again?', 'jardin' ) ); ?>' );">
			<?php wp_nonce_field( 'jardin_page_seed' ); ?>
			<input type="hidden" name="jardin_seed_action" value="reset_safe" />
			<p>
				<label>
					<input type="checkbox" name="jardin_reset_safe_force" value="1" />
					<?php esc_html_e( 'Permanently delete tagged pages when cleaning', 'jardin' ); ?>
				</label>
			</p>
			<p>
				<label>
					<input type="checkbox" name="jardin_reset_sync" value="1" />
					<?php esc_html_e( 'Sync body from seeds on import', 'jardin' ); ?>
				</label>
			</p>
			<p>
				<label>
					<input type="checkbox" name="jardin_reset_assign_templates" value="1" checked />
					<?php esc_html_e( 'Assign templates on import', 'jardin' ); ?>
				</label>
			</p>
			<p>
				<label>
					<input type="checkbox" name="jardin_reset_reading_home" value="1" />
					<?php esc_html_e( 'Set static front page to “home” if present', 'jardin' ); ?>
				</label>
			</p>
			<?php submit_button( __( 'Run safe reset', 'jardin' ), 'primary' ); ?>
		</form>

		<h3><?php esc_html_e( 'Full manifest reset (destructive)', 'jardin' ); ?></h3>
		<p class="description" style="color:#a00;"><?php esc_html_e( 'Deletes every manifest slug page, then imports again—same risk as “Remove by manifest slugs”.', 'jardin' ); ?></p>
		<form method="post" action="<?php echo esc_url( $action_url ); ?>" onsubmit="return window.confirm( '<?php echo esc_js( __( 'Delete all manifest slug pages and re-import? This destroys matching pages.', 'jardin' ) ); ?>' );">
			<?php wp_nonce_field( 'jardin_page_seed' ); ?>
			<input type="hidden" name="jardin_seed_action" value="reset_manifest" />
			<p>
				<label>
					<input type="checkbox" name="jardin_reset_manifest_force" value="1" />
					<?php esc_html_e( 'Permanently delete when cleaning', 'jardin' ); ?>
				</label>
			</p>
			<p>
				<label>
					<input type="checkbox" name="jardin_reset_manifest_sync" value="1" />
					<?php esc_html_e( 'Sync body from seeds on import', 'jardin' ); ?>
				</label>
			</p>
			<p>
				<label>
					<input type="checkbox" name="jardin_reset_manifest_assign" value="1" checked />
					<?php esc_html_e( 'Assign templates on import', 'jardin' ); ?>
				</label>
			</p>
			<p>
				<label>
					<input type="checkbox" name="jardin_reset_manifest_reading" value="1" />
					<?php esc_html_e( 'Set static front page to “home” if present', 'jardin' ); ?>
				</label>
			</p>
			<?php submit_button( __( 'Run full manifest reset', 'jardin' ), 'primary' ); ?>
		</form>

		<hr />

		<h2 class="title"><?php esc_html_e( 'Site Editor customizations', 'jardin' ); ?></h2>
		<p class="description">
			<?php
			printf(
				/* translators: 1: template parts count, 2: templates count */
				esc_html__( 'Database overrides: %1$d template part(s), %2$d template(s). Removing them restores files from the theme directory.', 'jardin' ),
				(int) $parts,
				(int) $tmpls
			);
			?>
		</p>
		<form method="post" action="<?php echo esc_url( $action_url ); ?>" onsubmit="return window.confirm( '<?php echo esc_js( __( 'Delete all Site Editor template/template-part overrides?', 'jardin' ) ); ?>' );">
			<?php wp_nonce_field( 'jardin_page_seed' ); ?>
			<input type="hidden" name="jardin_seed_action" value="reset_fse" />
			<?php submit_button( __( 'Reset Site Editor customizations', 'jardin' ), 'delete' ); ?>
		</form>
	</div>
	<?php
}
