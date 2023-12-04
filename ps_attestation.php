<?php
/**
 * Plugin Name:     Privacy Sandbox Attestation
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     Serves the privacy sandbox attestation json file at the corresponding path.
 * Author:          Newscorp
 * Text Domain:     ps_attestation
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Ps_attestation
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access.
}

define( 'PS_ATTESTATION_DIR', plugin_dir_path( __FILE__ ) );

// Autoload
require_once PS_ATTESTATION_DIR . 'autoload.php';

// Init the plugin
add_action( 'plugins_loaded', 'plugins_loaded_redspace_admin_plugin' );
function plugins_loaded_redspace_admin_plugin() {
	// Client
	PS_Attestation\Main::get_instance();

}