<?php

/**
 * Excerpt Thumbnail bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://wellplanet.com
 * @since             1.0.0
 * @package           Excerpt_Thumbnail
 *
 * @wordpress-plugin
 * Plugin Name:       Excerpt Thumbnail
 * Plugin URI:        http://wellplanet.com
 * Description:       Excerpt Thumbnail generates thumbnails wherever you show excerpts (archive page, feed...).
 * Version:           1.0.0
 * Author:            Patrick Coleman
 * Author URI:        http://wellplanet.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       excerpt-thumbnail
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 * Started at version 1.0.0 and uses SemVer - https://semver.org
 * This will be updated as new versions are released.
 */
define( 'EXCERPT_THUMBNAIL_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-excerpt-thumbnail-activator.php
 */
function activate_excerpt_thumbnail() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-excerpt-thumbnail-activator.php';
	Excerpt_Thumbnail_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-excerpt-thumbnail-deactivator.php
 */
function deactivate_excerpt_thumbnail() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-excerpt-thumbnail-deactivator.php';
	Excerpt_Thumbnail_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_excerpt_thumbnail' );
register_deactivation_hook( __FILE__, 'deactivate_excerpt_thumbnail' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-excerpt-thumbnail.php';

/**
 * Begin execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_excerpt_thumbnail() {

	$plugin = new Excerpt_Thumbnail();
	$plugin->run();

}
run_excerpt_thumbnail();
