<?php
/**
 * Fired during plugin deactivation.
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
 * Defines all code executed during plugin deactivation.
 *
 * @since 1.0.0
 */
class Excerpt_Thumbnail_Deactivator {

	/**
	 * Run deactivation routines.
	 *
	 * Note: Do not delete options or data here. That belongs in uninstall.php.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function deactivate() {
		// If you ever schedule wp-cron events, clear them here.
		// Example:
		// $timestamp = wp_next_scheduled( 'excerpt_thumbnail_cron_hook' );
		// if ( $timestamp ) {
		//     wp_unschedule_event( $timestamp, 'excerpt_thumbnail_cron_hook' );
		// }

		// If you ever set transients, consider deleting them here.
		// Example:
		// delete_transient( 'excerpt_thumbnail_some_cache' );
	}
}
