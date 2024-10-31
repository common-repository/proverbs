<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              Proverbs
 * @package           Proverbs
 *
 * @wordpress-plugin
 * Plugin Name:       Proverbs
 * Description:       This plugin displays different proverbs. After activating plugin, user can insert shortcode [proverb] where he or she wants proverb to be displayed. Table of proverbs can be inserted too, shortcode for that is [category-search]. Table of proverbs has search capability, where user can find proverbs by categories.  Also page titled Proverbs is created with shortcode [category-search] as its content.  Menu link Proverbs gets created in admin side. From there user can manage proverbs and their categories.
 * Version:           2.0.0
 * Author:            castellar120
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       proverbs
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '2.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-proverbs-activator.php
 */
function activate_proverbs() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-proverbs-activator.php';
	Proverbs_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-proverbs-deactivator.php
 */
function deactivate_proverbs() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-proverbs-deactivator.php';
	Proverbs_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_proverbs' );
register_deactivation_hook( __FILE__, 'deactivate_proverbs' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-proverbs.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function run_proverbs() {

	$plugin = new Proverbs();
	$plugin->run();

}
run_proverbs();
