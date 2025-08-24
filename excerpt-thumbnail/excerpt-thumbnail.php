<?php
/**
 * Plugin Name: Excerpt Thumbnail
 * Plugin URI:  https://github.com/wlpdrpat/Excerpt-Thumbnail
 * Description: Safely adds a thumbnail to post excerpts on archive/search/home views. Rebuilt using the WordPress Plugin Boilerplate.
 * Version:     3.0.0-dev
 * Author:      Patrick Coleman et al.
 * Text Domain: excerpt-thumbnail
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */
/**
 * Main plugin bootstrap.
 *
 * @link       https://wellplanet.com
 * @since      3.0.0
 * @package    Excerpt_Thumbnail
 * @author     Patrick Coleman <pat@wellplanet.com>
 * @license    GPL-2.0-or-later
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'EXCERPT_THUMBNAIL_VERSION', '3.0.0-dev' );
define( 'EXCERPT_THUMBNAIL_SLUG', 'excerpt-thumbnail' );
define( 'EXCERPT_THUMBNAIL_FILE', __FILE__ );
define( 'EXCERPT_THUMBNAIL_DIR', plugin_dir_path( __FILE__ ) );
define( 'EXCERPT_THUMBNAIL_URL', plugin_dir_url( __FILE__ ) );

register_activation_hook( __FILE__, 'activate_excerpt_thumbnail' );
register_deactivation_hook( __FILE__, 'deactivate_excerpt_thumbnail' );

function activate_excerpt_thumbnail() {
    require_once EXCERPT_THUMBNAIL_DIR . 'includes/class-excerpt-thumbnail-activator.php';
    Excerpt_Thumbnail_Activator::activate();
}

function deactivate_excerpt_thumbnail() {
    require_once EXCERPT_THUMBNAIL_DIR . 'includes/class-excerpt-thumbnail-deactivator.php';
    Excerpt_Thumbnail_Deactivator::deactivate();
}

require EXCERPT_THUMBNAIL_DIR . 'includes/class-excerpt-thumbnail.php';

function run_excerpt_thumbnail() {
    $plugin = new Excerpt_Thumbnail();
    $plugin->run();
}
run_excerpt_thumbnail();
