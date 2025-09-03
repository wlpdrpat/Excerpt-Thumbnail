<?php
/**
 * Internationalization loader.
 *
 * @link      https://wellplanet.com
 * @since     1.0.0
 * @package   Excerpt_Thumbnail
 * @subpackage Excerpt_Thumbnail/includes
 * @author    Patrick Coleman
 * @license   GPL-2.0-or-later
 */

defined( 'ABSPATH' ) || exit;

/**
 * Loads the text domain for translating plugin strings.
 *
 * @since 1.0.0
 */
class Excerpt_Thumbnail_i18n {

	/**
	 * Text domain slug.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $domain = EXCERPT_THUMBNAIL_SLUG;

	/**
	 * Set the text domain (sanitized).
	 *
	 * @since 1.0.0
	 * @param string $domain Text domain.
	 * @return void
	 */
	public function set_domain( $domain ) {
		// Keep it safe; avoid accidental whitespace or invalid chars.
		if ( is_string( $domain ) && '' !== $domain ) {
			$this->domain = sanitize_key( $domain );
		}
	}

	/**
	 * Load the plugin textdomain.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			$this->domain,
			false,
			dirname( plugin_basename( EXCERPT_THUMBNAIL_FILE ) ) . '/languages'
		);
	}
}
