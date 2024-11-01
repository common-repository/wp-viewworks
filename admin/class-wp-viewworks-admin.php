<?php

/**
 * The dashboard-specific functionality of the plugin.
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

if (! class_exists('WP_ViewWorks_Admin')) {
	class WP_ViewWorks_Admin {

		/**
		 * The text domain of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @param    string    $plugin_name    The text domain of the plugin (used as unique ID)
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
		 * The notices handler class responsible to handle all administration notices for the plugin (excluded the 
		 * configuration page ones, which are handled directly from Redux Framework).
		 *
		 * @since    2.0.0
		 * @access   protected
		 * @param    WP_NovaGraphix_Notices    $notices    Maintains and registers all notices for the plugin.
		 */
		protected $notices;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 * @param    string    $plugin_name     The name of this plugin.
		 */
		public function __construct( $plugin_name ) {

			$this->plugin_name = $plugin_name;
			$this->loader = WP_NovaGraphix_Loader::get_instance();
			$this->notices = WP_NovaGraphix_Notices::get_instance();
		}

		/**
		 * Register the stylesheets for the Dashboard.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {
			global $novagraphix_config;

			$version = $novagraphix_config[$this->plugin_name]['version'];

			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), $version, 'all' );
		}

		/**
		 * Register the JavaScript for the dashboard.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {
			global $novagraphix_config;
			
			$version = $novagraphix_config[$this->plugin_name]['version'];

			//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), $version, false );
		}

		/**
		 * Display a notice after plugin activation once
		 *
		 * @since    1.0.0
		 */
		public function display_activation_notices () {
	    	if ( is_admin() && get_option( 'Activated_Plugin' ) == $this->plugin_name ) {

	        	delete_option( 'Activated_Plugin' );
	       	}
		}

		/**
		 * Enqueue custom TinyMCE shortcode generator buttons
		 *
		 * @since    1.0.0
		 */
		public function enqueue_mce_buttons() {
			$tiny_mce = new WP_ViewWorks_Tinymce( $this->plugin_name );

			// Check if user have permission
			if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
				return;
			}
			// Check if WYSIWYG is enabled
			if ( 'true' == get_user_option( 'rich_editing' ) ) {
				//$this->loader->add_filter('mce_external_plugins', $tiny_mce, 'custom_tinymce_plugin');
				//$this->loader->add_filter('mce_buttons', $tiny_mce, 'register_mce_button');

				add_filter( 'mce_buttons', array( $tiny_mce, 'register_button' ));
				add_filter( 'mce_external_plugins', array( $tiny_mce, 'register_plugin' ));		
			}
		}

		/**
		 * Add STL/GLB MIME types for media uploading
		 *
		 * @since    1.0.0
		 * @params $mimes <array> Array of allowed MIME types
		 */
		public function add_upload_mime_types ( $mimes ) {
			$mimes['json'] = 'application/json';
			$mimes['obj|mtl|gltf'] = 'text/plain';
			$mimes['stl'] = 'application/sla';
			$mimes['ply|amf|3mf|fbx|bin'] = 'application/octet-stream';
			$mimes['dae'] = 'text/xml';
			$mimes['glb'] = 'application/gltf-buffer';
			// $mimes['wrl'] = 'model/vrml'; // <=== loading error
			return $mimes;
		}

		/**
		 * Add STL/GLB MIME types into media manager
		 *
		 * @since    1.0.0
		 * @params $post_mime_types <array> Array of MIME data for media gallery
		 */
		public function add_media_mime_types ( $post_mime_types ) {
			global $novagraphix_config;

			$textdomain = $novagraphix_config[$this->plugin_name]['text_domain'];

			$post_mime_types['application/json'] = array(
				__( 'JSON Files', $textdomain ),
				__( 'Manage JSON', $textdomain ),
				__( 'JSON Files <span class="count">(%s)</span>', $textdomain )
			);
			$post_mime_types['text/plain'] = array(
				__( 'OBJ | MTL | GLTF Files', $textdomain ),
				__( 'Manage OBJ | MTL| GLTF', $textdomain ),
				__( 'OBJ | MTL | GLTF Files <span class="count">(%s)</span>', $textdomain )
			);
			$post_mime_types['application/sla'] = array(
				__( 'STL Files', $textdomain ),
				__( 'Manage STL', $textdomain ),
				__( 'STL Files <span class="count">(%s)</span>', $textdomain )
			);
			$post_mime_types['application/octet-stream'] = array(
				__( 'PLY | AMF | 3MF | FBX | BIN Files', $textdomain ),
				__( 'Manage PLY | AMF | 3MF | FBX | BIN', $textdomain ),
				__( 'PLY | AMF | 3MF | FBX | BIN Files <span class="count">(%s)</span>', $textdomain )
			);
			$post_mime_types['text/xml'] = array(
				__( 'DAE Files', $textdomain ),
				__( 'Manage DAE', $textdomain ),
				__( 'DAE Files <span class="count">(%s)</span>', $textdomain )
			);
			$post_mime_types['application/gltf-buffer'] = array(
				__( 'GLB Files', $textdomain ),
				__( 'Manage GLB', $textdomain ),
				__( 'GLB Files <span class="count">(%s)</span>', $textdomain )
			);
			// $post_mime_types['model/vrml'] = array(
			// 	__( 'WRL Files', $textdomain ),
			// 	__( 'Manage WRL', $textdomain ),
			// 	__( 'WRL Files <span class="count">(%s)</span>', $textdomain )
			// );

			return $post_mime_types;
		}

	}
}