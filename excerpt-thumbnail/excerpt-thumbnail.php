<?php
/**
 * Plugin Name: Excerpt Thumbnail
 * Plugin URI:  https://github.com/wlpdrpat/Excerpt-Thumbnail
 * Description: Safely adds a featured image thumbnail to post excerpts on archive, search, and home views. Rebuilt using the WordPress Plugin Boilerplate.
 * Version:     1.0.0
 * Author:      Patrick Coleman
 * Author URI:  https://wellplanet.com
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: excerpt-thumbnail
 * Domain Path: /languages
 * Requires at least: 5.8
 * Tested up to: 6.6
 * Requires PHP: 7.4
 *
 * @package Excerpt_Thumbnail
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'EXCERPT_THUMBNAIL_VERSION', '1.0.0' );
define( 'EXCERPT_THUMBNAIL_SLUG', 'excerpt-thumbnail' );
define( 'EXCERPT_THUMBNAIL_FILE', __FILE__ );
define( 'EXCERPT_THUMBNAIL_DIR', plugin_dir_path( __FILE__ ) );
define( 'EXCERPT_THUMBNAIL_URL', plugin_dir_url( __FILE__ ) );

/**
 * Activation and deactivation hooks
 */
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

/**
 * The core plugin class that defines hooks
 */
require EXCERPT_THUMBNAIL_DIR . 'includes/class-excerpt-thumbnail.php';

function run_excerpt_thumbnail() {
	$plugin = new Excerpt_Thumbnail();
	$plugin->run();
}
run_excerpt_thumbnail();
