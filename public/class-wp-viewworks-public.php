<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    WP_ViewWorks
 * @subpackage WP_ViewWorks/public
 * @author     NovaGraphix <info@nova-graphix.com>
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if (! class_exists('WP_ViewWorks_Public')) {
	class WP_ViewWorks_Public {

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @param    string    $plugin_name    The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 * @param    string    $plugin_name       The name of the plugin.
		 */
		public function __construct( $plugin_name ) {
			$this->plugin_name = $plugin_name;
		}

		/**
		 * Register the stylesheets for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {
			global $novagraphix_config;

			$version = $novagraphix_config[$this->plugin_name]['version'];

			wp_enqueue_style('dashicons');

			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/public.css', array(), $version, 'all' );

		}

		/**
		 * Register the stylesheets for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {
			global $novagraphix_config;

			$version = $novagraphix_config[$this->plugin_name]['version'];
			$textdomain = $novagraphix_config[$this->plugin_name]['text_domain'];

			wp_register_script( $this->plugin_name . '-viewworks', plugin_dir_url( __FILE__ ) . 'js/jamie.min.js', array(), $version, true );
			wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/public.js', array( $this->plugin_name . '-viewworks' ), $version, true );

			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( $this->plugin_name . '-viewworks' );
			wp_enqueue_script( $this->plugin_name );
		}

		/**
		 * viewworks shortcode
		 *
		 * @since    1.0.0
		 * @param    array    $atts 	The shortcode attributes array
		 * @param    string   $content	The shortcode content
		 * @return   mixed    $result 	The shortcode output
		 */
		public function viewworks_shortcode( $atts,  $content = null ) {
			extract(shortcode_atts( array(
				'models' => '',
				'width'  => '100%',
				'height' => '800',
				'images' => plugin_dir_url( __FILE__ ) . 'images/',
				'uid'    => uniqid(),
			), $atts ));

			ob_start();
			include 'partials/shortcode.php';
			$result = ob_get_clean();
		
			return $result;
		}

	}
}