<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       test
 * @since      1.0.0
 *
 * @package    Fetch_Random_Posts
 * @subpackage Fetch_Random_Posts/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Fetch_Random_Posts
 * @subpackage Fetch_Random_Posts/includes
 * @author     Olivija Guzelyte <o.guzelyte@gmail.com>
 */
class Fetch_Random_Posts_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'fetch-random-posts',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
