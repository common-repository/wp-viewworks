<?php

/**
 * @link              https://www.nova-graphix.com/
 * @since             1.0.0
 * @package           WP-ViewWorks
 *
 * @wordpress-plugin
 * Plugin Name:       WP-ViewWorks
 * Plugin URI:        https://wordpress.org/plugins/wp-viewworks/
 * Description:       A Fast 3D Model Viewer using a shortcode: [wp-viewworks models="file_URLs" width="100%" height=800][/wp-viewworks].
 * Version:           1.1.0
 * Author:            NovaGraphix <info@nova-graphix.com>
 * Author URI:        https://www.nova-graphix.com/
 * Text Domain:       wp-viewworks
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// call global configuration variable
global $novagraphix_config;

// if exist doesn't instantiate
if( ! isset($novagraphix_config)) {
	$novagraphix_config = array();
}

// Configuration array
$novagraphix_config['wp-viewworks'] = array(
	'title'          => 'WP ViewWorks',
	'version'        => '1.0.0',
	'text_domain'    => 'wp-viewworks',
	'prefix'         => 'wpvwk'
);

// include files
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-novagraphix-registration.php';

/**
 * The code that runs during plugin activation.
 */
function wpvwk_activate () { WP_NovaGraphix_Registration::activate( 'wp-viewworks' ); }

/**
 * The code that runs during plugin deactivation.
 */
function wpvwk_deactivate () { WP_NovaGraphix_Registration::deactivate( 'wp-viewworks' ); }

register_activation_hook( __FILE__, 'wpvwk_activate' );
register_deactivation_hook( __FILE__, 'wpvwk_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-viewworks-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function wpvwk_run () {
	$plugin = new WP_ViewWorks_Plugin ( 'wp-viewworks' );
	$plugin->run();
}
wpvwk_run();
