<?php

/**
 * Fired during plugin deactivation
 *
 * @link       proverbs
 * @since      2.0.0
 *
 * @package    Proverbs
 * @subpackage Proverbs/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      2.0.0
 * @package    Proverbs
 * @subpackage Proverbs/includes
 * @author     castellar120
 */
class Proverbs_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    2.0.0
	 */
	public static function deactivate() {
		// get the child posts of the current post being deleted
		global $wpdb;

		$query = $wpdb->prepare(
			'DELETE FROM ' . $wpdb->posts . '
			WHERE post_type = %s',
			'proverb'
		);
		$wpdb->query( $query );
	}

}