<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       utu.fi
 * @since      1.0.0
 *
 * @package    Vuosikello
 * @subpackage Vuosikello/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Vuosikello
 * @subpackage Vuosikello/includes
 * @author     Sami Jokela <sami.jokela@utu.fi>
 */
class Vuosikello_i18n {


	/**
	 * Load the plugin text domains for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'vuosikello',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

		load_plugin_textdomain(
			'päiväkoti',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
