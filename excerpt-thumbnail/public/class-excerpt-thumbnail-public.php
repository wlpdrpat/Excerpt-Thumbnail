<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://wellplanet.com
 * @since      1.0.0
 *
 * @package    Excerpt_Thumbnail
 * @subpackage Excerpt_Thumbnail/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Excerpt_Thumbnail
 * @subpackage Excerpt_Thumbnail/public
 * @author     Patrick Coleman <pat@wellplanet.com>
 */
class Excerpt_Thumbnail_Public {
    private $slug;
    private $version;

    public function __construct( $slug, $version ) {
        $this->slug    = $slug;
        $this->version = $version;
    }

    public function enqueue_styles() {}
    public function enqueue_scripts() {}
}
