<?php
/**
 * Admin list actions and manual changelog sync (sans métabox).
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Admin behavior for project CPT.
 */
class Jardin_Projects_Admin {
	public function __construct() {
		add_action( 'admin_post_jardin_projects_sync', array( $this, 'handle_manual_sync' ) );
		add_filter( 'post_row_actions', array( $this, 'add_row_actions' ), 10, 2 );
		add_filter( 'bulk_actions-edit-project', array( $this, 'register_bulk_sync_action' ) );
		add_filter( 'handle_bulk_actions-edit-project', array( $this, 'handle_bulk_sync_action' ), 10, 3 );
		add_action( 'admin_notices', array( $this, 'render_admin_notices' ) );
	}

	public function register_bulk_sync_action( $actions ) {
		$actions['jardin_projects_bulk_sync'] = __( 'Sync changelog (GitHub)', 'jardin-theme' );
		return $actions;
	}

	public function handle_bulk_sync_action( $redirect_url, $action, $post_ids ) {
		if ( 'jardin_projects_bulk_sync' !== $action ) {
			return $redirect_url;
		}

		if ( ! current_user_can( 'edit_posts' ) ) {
			return $redirect_url;
		}

		$sync  = jardin_projects_sync_service();
		$ok    = 0;
		$fails = 0;

		foreach ( (array) $post_ids as $post_id ) {
			$post_id = (int) $post_id;
			if ( $post_id <= 0 || ! current_user_can( 'edit_post', $post_id ) ) {
				continue;
			}
			$result = $sync->sync_project( $post_id, 'bulk' );
			if ( isset( $result['state'] ) && 'ok' === $result['state'] ) {
				++$ok;
			} else {
				++$fails;
			}
		}

		return add_query_arg(
			array(
				'jardin_projects_bulk_ok'    => $ok,
				'jardin_projects_bulk_fails' => $fails,
			),
			$redirect_url
		);
	}

	public function render_admin_notices() {
		if ( ! function_exists( 'get_current_screen' ) ) {
			return;
		}
		$screen = get_current_screen();
		if ( ! $screen || 'project' !== $screen->post_type ) {
			return;
		}

		if ( isset( $_GET['jardin_projects_synced'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$state = sanitize_key( wp_unslash( $_GET['jardin_projects_synced'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( 'ok' === $state ) {
				echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Changelog synchronisé avec succès.', 'jardin-theme' ) . '</p></div>';
			} elseif ( 'error' === $state ) {
				echo '<div class="notice notice-warning is-dismissible"><p>' . esc_html__( 'Synchronisation terminée avec des erreurs — voir le panneau « Données projet » dans l’éditeur.', 'jardin-theme' ) . '</p></div>';
			}
		}

		if ( isset( $_GET['jardin_projects_bulk_ok'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$ok    = absint( wp_unslash( $_GET['jardin_projects_bulk_ok'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$fails = isset( $_GET['jardin_projects_bulk_fails'] ) ? absint( wp_unslash( $_GET['jardin_projects_bulk_fails'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( $ok > 0 || $fails > 0 ) {
				printf(
					'<div class="notice notice-info is-dismissible"><p>%s</p></div>',
					esc_html(
						sprintf(
							/* translators: 1: successful sync count, 2: failed sync count */
							__( 'Synchronisation groupée : %1$d réussie(s), %2$d échec(s).', 'jardin-theme' ),
							$ok,
							$fails
						)
					)
				);
			}
		}
	}

	public function handle_manual_sync() {
		$project_id = isset( $_GET['project_id'] ) ? absint( wp_unslash( $_GET['project_id'] ) ) : 0;
		if ( $project_id <= 0 || ! current_user_can( 'edit_post', $project_id ) ) {
			wp_die( esc_html__( 'Unauthorized request.', 'jardin-theme' ) );
		}
		check_admin_referer( 'jardin_projects_sync_' . $project_id );

		$result = jardin_projects_sync_service()->sync_project( $project_id, 'manual' );
		$state  = isset( $result['state'] ) ? sanitize_key( (string) $result['state'] ) : 'error';
		$redirect = add_query_arg(
			array(
				'post'                   => $project_id,
				'action'                 => 'edit',
				'jardin_projects_synced' => $state,
			),
			admin_url( 'post.php' )
		);
		wp_safe_redirect( $redirect );
		exit;
	}

	public function add_row_actions( $actions, $post ) {
		if ( ! $post instanceof WP_Post || jardin_projects_get_post_type() !== $post->post_type ) {
			return $actions;
		}
		if ( ! current_user_can( 'edit_post', $post->ID ) ) {
			return $actions;
		}
		$url = wp_nonce_url(
			add_query_arg(
				array(
					'action'     => 'jardin_projects_sync',
					'project_id' => (int) $post->ID,
				),
				admin_url( 'admin-post.php' )
			),
			'jardin_projects_sync_' . (int) $post->ID
		);
		$actions['jardin_projects_sync'] = sprintf(
			'<a href="%1$s">%2$s</a>',
			esc_url( $url ),
			esc_html__( 'Sync changelog', 'jardin-theme' )
		);
		return $actions;
	}
}

if ( is_admin() ) {
	new Jardin_Projects_Admin();
}
