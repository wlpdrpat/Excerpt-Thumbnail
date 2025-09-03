<?php
/**
 * Fired during plugin activation.
 *
 * @package Excerpt_Thumbnail
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

class Excerpt_Thumbnail_Activator {

	/**
	 * Run on plugin activation.
	 *
	 * - Migrates legacy tfe_* options to excerpt_thumbnail_* (idempotent).
	 * - Seeds sensible defaults if options are not set.
	 * - Stores current plugin version for future reference.
	 *
	 * @return void
	 */
	public static function activate() {
		// Ensure migrator is available and run it once.
		if ( ! class_exists( 'Excerpt_Thumbnail_Migrator' ) ) {
			require_once plugin_dir_path( __FILE__ ) . 'class-excerpt-thumbnail-migrator.php';
		}
		Excerpt_Thumbnail_Migrator::maybe_migrate_legacy_options();

		// Seed defaults (only if not already set).
		self::seed_defaults();

		// Record plugin version.
		if ( defined( 'EXCERPT_THUMBNAIL_VERSION' ) ) {
			update_option( 'excerpt_thumbnail_version', EXCERPT_THUMBNAIL_VERSION );
		}
	}

	/**
	 * Seed default options without overwriting existing user choices.
	 *
	 * @return void
	 */
	private static function seed_defaults() {
		$defaults = [
			// Layout/behavior.
			'excerpt_thumbnail_align'             => 'left', // left|right|above (UI should enforce).
			'excerpt_thumbnail_withlink'          => 'yes',  // 'yes'|'no'
			'excerpt_thumbnail_on_home'           => 'yes',
			'excerpt_thumbnail_on_archives'       => 'yes',
			'excerpt_thumbnail_on_search'         => 'yes',
			'excerpt_thumbnail_modern_mode'       => 'no',
			'excerpt_thumbnail_add_og_image'      => 'yes',

			// Sizing (leave blank so theme/image sizes can control).
			'excerpt_thumbnail_width'             => '',
			'excerpt_thumbnail_height'            => '',

			// Defaults/fallbacks.
			'excerpt_thumbnail_default_image'     => '', // e.g., attachment ID if you support it.
			'excerpt_thumbnail_default_image_src' => '', // e.g., URL fallback.

			// Exclusions.
			'excerpt_thumbnail_exclusion'         => '',

			// Uninstall behavior (respect user choice; default is do not remove).
			'excerpt_thumbnail_cleanup_on_uninstall' => 'no',
		];

		foreach ( $defaults as $key => $value ) {
			if ( false === get_option( $key, false ) ) {
				update_option( $key, $value );
			}
		}
	}
}
