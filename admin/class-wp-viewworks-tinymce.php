<?php

/**
 * The class handling the TinyMCE shortcode buttons generator
 *
 * @since      1.0.0
 *
 * @package    WP_ViewWorks
 * @subpackage WP_ViewWorks/admin
 * @author     NovaGraphix <info@nova-graphix.com>
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists('WP_ViewWorks_Tinymce')) {
	class WP_ViewWorks_Tinymce {

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @param    string    $plugin_name    The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The loader that's responsible for maintaining and registering all hooks that power
		 * the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @param    WP_NovaGraphix_Loader    $loader    Maintains and registers all hooks for the plugin.
		 */
		protected $loader;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 * @param    string    $plugin_name     The name of this plugin.
		 */
		public function __construct( $plugin_name ) {

			$this->plugin_name = $plugin_name;
			$this->loader = WP_NovaGraphix_Loader::get_instance();

		}

		/**
		 * Register plugin script in the editor
		 *
		 * @since    1.0.0
		 * @var 	 array 		$plugin_array 	TinyMCE plugin array
		 * @return 	 array 		$plugin_array	Plugin array modified 
		 */
		public function register_plugin( $plugin_array ) {
			global $novagraphix_config;

			$prefix = $novagraphix_config[$this->plugin_name]['prefix'];

			$plugin_array[ $prefix . '_shortcode_mce_button' ] =  plugin_dir_url( __FILE__ ) .'js/tinymce.js';
			return $plugin_array;
		}

		/**
		 * Register new button in the editor
		 *
		 * @since    1.0.0
		 * @var 	 array 		$buttons 	TinyMCE buttons array
		 * @return 	 array 		$buttons	Buttons array modified 
		 */	
		public function register_button( $buttons ) {
			global $novagraphix_config;

			$prefix = $novagraphix_config[$this->plugin_name]['prefix'];

			array_push( $buttons, $prefix . '_shortcode_mce_button' );
			return $buttons;
		}

	}
}