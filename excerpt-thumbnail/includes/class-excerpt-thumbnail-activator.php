<?php
/**
 * Fired during plugin activation
 *
 * @package Excerpt_Thumbnail
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

class Excerpt_Thumbnail_Activator {

	public static function activate() {
		// Run the legacy → new options migration on first activation.
		if ( ! class_exists( 'Excerpt_Thumbnail_Migrator' ) ) {
			require_once plugin_dir_path( __FILE__ ) . 'class-excerpt-thumbnail-migrator.php';
		}
		Excerpt_Thumbnail_Migrator::maybe_migrate_legacy_options();

		// (Optional) Seed defaults here if you have any.
	}
}
