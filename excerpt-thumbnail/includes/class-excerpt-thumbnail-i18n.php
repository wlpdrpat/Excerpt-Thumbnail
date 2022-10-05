<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://wellplanet.com
 * @since      1.0.0
 *
 * @package    Excerpt_Thumbnail
 * @subpackage Excerpt_Thumbnail/includes
 */
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Excerpt_Thumbnail
 * @subpackage Excerpt_Thumbnail/includes
 * @author     Patrick Coleman <pat@wellplanet.com>
 */
class Excerpt_Thumbnail_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'excerpt-thumbnail',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
