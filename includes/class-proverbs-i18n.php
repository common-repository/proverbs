<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       proverbs
 * @since      2.0.0
 *
 * @package    Proverbs
 * @subpackage Proverbs/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      2.0.0
 * @package    Proverbs
 * @subpackage Proverbs/includes
 * @author     castellar120
 */
class Proverbs_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    2.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'proverbs',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
