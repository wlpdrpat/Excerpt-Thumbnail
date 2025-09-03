<?php
/**
 * Plugin uninstall cleanup.
 *
 * Deletes plugin options only when the user has opted in via the
 * "Remove Data on Uninstall" setting.
 *
 * @link       https://wellplanet.com
 * @since      1.0.0
 * @package    Excerpt_Thumbnail
 * @author     Patrick Coleman
 * @license    GPL-2.0-or-later
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Only remove data if the user explicitly opted in.
 * This setting should be stored as 'excerpt_thumbnail_cleanup_on_uninstall'.
 */
if ( 'yes' !== get_option( 'excerpt_thumbnail_cleanup_on_uninstall', 'no' ) ) {
	return;
}

/**
 * List all options this plugin creates.
 * Update this list if new options are added in future versions.
 */
$option_keys = [
	'excerpt_thumbnail_width',
	'excerpt_thumbnail_height',
	'excerpt_thumbnail_align',
	'excerpt_thumbnail_default_image',
	'excerpt_thumbnail_default_image_src',
	'excerpt_thumbnail_withlink',
	'excerpt_thumbnail_on_home',
	'excerpt_thumbnail_on_archives',
	'excerpt_thumbnail_on_search',
	'excerpt_thumbnail_exclusion',
	'excerpt_thumbnail_add_og_image',
	'excerpt_thumbnail_modern_mode',
	'excerpt_thumbnail_cleanup_on_uninstall',
];

foreach ( $option_keys as $key ) {
	delete_option( $key );
}
