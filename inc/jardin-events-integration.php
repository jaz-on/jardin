<?php
/**
 * Liaison avec le plugin Jardin Events (dépôt jaz-on/jardin-event).
 *
 * Périmètre thème : détection du plugin et message admin si le CPT `event` n’est pas disponible.
 * Données événements (CPT, métas, motif de blocs par défaut, gabarit archive minimal du plugin) :
 * côté plugin uniquement.
 * Présentation FSE riche : gabarits et styles dans ce thème.
 *
 * @package Jardin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Indique si le plugin Jardin Events est actif et fonctionnel.
 *
 * @return bool
 */
function jardin_events_available() {
	return function_exists( 'jardin_events_is_active' ) && jardin_events_is_active();
}

/**
 * Après activation du thème : permettre l’affichage ponctuel de la notice plugin.
 */
function jardin_events_flag_notice_on_theme_switch() {
	update_option( 'jardin_show_events_notice', 1 );
}
add_action( 'after_switch_theme', 'jardin_events_flag_notice_on_theme_switch' );

/**
 * Notice d’admin si le plugin événements n’est pas installé (écran des thèmes uniquement).
 */
function jardin_events_render_missing_plugin_notice() {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	$theme_screens = array( 'themes', 'themes-network' );
	if ( ! $screen || ! in_array( $screen->id, $theme_screens, true ) ) {
		return;
	}

	if ( ! get_option( 'jardin_show_events_notice' ) ) {
		return;
	}

	if ( jardin_events_available() ) {
		delete_option( 'jardin_show_events_notice' );
		return;
	}
	?>
	<div class="notice notice-info is-dismissible">
		<p>
			<strong><?php esc_html_e( 'Thème Jardin', 'jardin' ); ?></strong>
			<?php esc_html_e( 'Pour activer les événements, installez et activez le plugin Jardin Events.', 'jardin' ); ?>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'jardin_events_render_missing_plugin_notice' );
add_action( 'network_admin_notices', 'jardin_events_render_missing_plugin_notice' );
