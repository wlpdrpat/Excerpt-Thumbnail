<?php
/**
 * Admin-area functionality.
 *
 * @link       https://wellplanet.com
 * @since      3.0.0
 * @package    Excerpt_Thumbnail
 * @subpackage Excerpt_Thumbnail/admin
 * @author     Patrick Coleman <pat@wellplanet.com>
 * @license    GPL-2.0-or-later
 */

/**
 * Enqueues admin assets and (next step) registers settings/screens.
 *
 * @since 3.0.0
 */
class Excerpt_Thumbnail_Admin {

    /** @since 3.0.0 @var string */
    private $slug;

    /** @since 3.0.0 @var string */
    private $version;

    /**
     * Ctor.
     *
     * @since 3.0.0
     * @param string $slug     Plugin slug.
     * @param string $version  Plugin version.
     */
    public function __construct( $slug, $version ) {
        $this->slug    = $slug;
        $this->version = $version;
    }

    /** @since 3.0.0 @return void */
    public function enqueue_styles() {}

    /** @since 3.0.0 @return void */
    public function enqueue_scripts() {}
}
