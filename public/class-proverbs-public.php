<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       proverbs
 * @since      2.0.0
 *
 * @package    Proverbs
 * @subpackage Proverbs/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Proverbs
 * @subpackage Proverbs/public
 * @author     castellar120
 */
class Proverbs_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Proverbs_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Proverbs_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/proverbs-public.css', array(), $this->version, 'all' );

		wp_enqueue_style( 'casia/css', plugin_dir_url( __FILE__ ) . 'css/casia-framework-styles.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Proverbs_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Proverbs_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( 'casia/js', plugin_dir_url( __FILE__ ) . 'js/casia-framework-js.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/proverbs-public.js', array( 'jquery', 'casia/js' ), $this->version, false );
	}

}