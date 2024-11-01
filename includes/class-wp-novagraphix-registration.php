<?php

/**
 * Fired during plugin activation and deactivation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WP_NovaGraphix
 * @subpackage WP_NovaGraphix/includes
 * @author     NovaGraphix <info@nova-graphix.com>
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if (! class_exists('WP_NovaGraphix_Registration')) {
	class WP_NovaGraphix_Registration {

		/**
		 * Handle the plugin activation
		 *
		 * @since    1.0.0
		 * @param    string    $plugin_name    The slug of this plugin.
		 */
		public static function activate( $plugin_name ) {
			add_option( 'Activated_Plugin', $plugin_name );
		}

		/**
		 * Handle the plugin deactivation
		 *
		 * @since    1.0.0
		 * @param    string    $plugin_name    The slug of this plugin.
		 */
		public static function deactivate( $plugin_name ) {
		}
	}
}