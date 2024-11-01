<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @since      1.0.0
 * @package    WP_ViewWorks
 * @subpackage WP_ViewWorks/includes
 * @author     NovaGraphix <info@nova-graphix.com>
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if (! class_exists('WP_ViewWorks_Plugin')) {
	class WP_ViewWorks_Plugin {

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
		 * The unique identifier of this plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @param    string    $plugin_name    The string used to uniquely identify this plugin.
		 */
		protected $plugin_name;

		/**
		 * Define the core functionality of the plugin.
		 *
		 * Set the plugin name and the plugin version that can be used throughout the plugin.
		 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
		 * the public-facing side of the site.
		 *
		 * @since    1.0.0
		 * @param    string    $plugin_name    The slug of this plugin.
		 */
		public function __construct($plugin_name ) {
			$this->plugin_name = $plugin_name;

			$this->load_dependencies();
			$this->set_locale();
			$this->define_admin_hooks();
			$this->define_public_hooks();
		}

		/**
		 * Load the required dependencies for this plugin.
		 *
		 * Include the following files that make up the plugin:
		 *
		 * - WP_NovaGraphix_Loader. Orchestrates the hooks of the plugin.
		 * - WP_NovaGraphix_i18n. Defines internationalization functionality.
		 * - WP_NovaGraphix_Tinymce. Defines all TinyMCE customizations.
		 * - WP_NovaGraphix_Admin. Defines all hooks for the dashboard.
		 * - WP_NovaGraphix_Notices. Defines all hooks for notices handling.
		 * - WP_NovaGraphix_Public. Defines all hooks for the public side of the site.
		 *
		 * Create an instance of the loader which will be used to register the hooks
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function load_dependencies() {

			/**
			 * The class responsible for orchestrating the actions and filters of the
			 * core plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-novagraphix-loader.php';

			/**
			 * The class responsible for defining internationalization functionality
			 * of the plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-novagraphix-i18n.php';

			/*
			 * The class responsible for defining all TinyMCE editor customization
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-viewworks-tinymce.php';

			/**
			 * The class responsible for handlig the plugin notices.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-novagraphix-notices.php';

			/**
			 * The class responsible for defining all actions that occur in the Dashboard.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-viewworks-admin.php';

			/**
			 * The class responsible for defining all actions that occur in the public-facing side of the site.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-viewworks-public.php';

			$this->loader = WP_NovaGraphix_Loader::get_instance();

		}

		/**
		 * Define the locale for this plugin for internationalization.
		 *
		 * Uses the WP_NovaGraphix_i18n class in order to set the domain and to register the hook
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function set_locale() {

			$plugin_i18n = new WP_NovaGraphix_i18n();
			$plugin_i18n->set_domain( $this->plugin_name );

			$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
		}

		/**
		 * Register all of the hooks related to the dashboard functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_admin_hooks() {

			$plugin_admin = new WP_ViewWorks_Admin( $this->plugin_name );
		
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
				
			$this->loader->add_action( 'admin_head', $plugin_admin, 'enqueue_mce_buttons' );

			$this->loader->add_action( 'admin_init', $plugin_admin, 'display_activation_notices' );

			$this->loader->add_filter( 'post_mime_types', $plugin_admin, 'add_media_mime_types' );
			$this->loader->add_filter( 'upload_mimes', $plugin_admin, 'add_upload_mime_types' );

		}

		/**
		 * Register all of the hooks related to the public-facing functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_public_hooks() {

			$plugin_public = new WP_ViewWorks_Public( $this->plugin_name );

			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

			add_shortcode( 'wp-viewworks', array($plugin_public, 'viewworks_shortcode' ) );
		}

		/**
		 * Run the loader to execute all of the hooks with WordPress.
		 *
		 * @since    1.0.0
		 */
		public function run() {
			$this->loader->run();
		}

		/**
		 * The name of the plugin used to uniquely identify it within the context of
		 * WordPress and to define internationalization functionality.
		 *
		 * @since     1.0.0
		 * @return    string    The name of the plugin.
		 */
		public function get_plugin_name() {
			return $this->plugin_name;
		}
	}
}