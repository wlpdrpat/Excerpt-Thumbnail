<?php
/**
 * Internationalization loader.
 *
 * @link       https://wellplanet.com
 * @since      3.0.0
 * @package    Excerpt_Thumbnail
 * @subpackage Excerpt_Thumbnail/includes
 * @author     Patrick Coleman <pat@wellplanet.com>
 * @license    GPL-2.0-or-later
 */

/**
 * Loads the text domain for translating plugin strings.
 *
 * @since 3.0.0
 */
class Excerpt_Thumbnail_i18n {

    /**
     * Text domain slug.
     *
     * @since 3.0.0
     * @var string
     */
    private $domain;

    /**
     * Set the text domain.
     *
     * @since 3.0.0
     * @param string $domain Text domain.
     * @return void
     */
    public function set_domain( $domain ) {
        $this->domain = $domain;
    }

    /**
     * Load the plugin textdomain.
     *
     * @since 3.0.0
     * @return void
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            $this->domain,
            false,
            dirname( plugin_basename( EXCERPT_THUMBNAIL_FILE ) ) . '/languages/'
        );
    }
}
