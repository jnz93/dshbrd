<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://unitycode.tech
 * @since      1.0.0
 *
 * @package    Uc_Dshbrd
 * @subpackage Uc_Dshbrd/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Uc_Dshbrd
 * @subpackage Uc_Dshbrd/includes
 * @author     UnityCode <contato@unitycode.tech>
 */
class Uc_Dshbrd_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'uc-dshbrd',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
