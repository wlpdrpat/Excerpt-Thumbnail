<?php
/**
 * Migrates legacy options (tfe_*) to the new excerpt_thumbnail_* options.
 *
 * @package Excerpt_Thumbnail
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

class Excerpt_Thumbnail_Migrator {

	/**
	 * Option key used to mark that we've already migrated.
	 */
	const MIGRATED_FLAG = 'excerpt_thumbnail_migrated';

	/**
	 * DB version (bump if you add more migrations later).
	 */
	const DB_VERSION_KEY = 'excerpt_thumbnail_db_version';
	const DB_VERSION     = '1.0.0';

	/**
	 * Map legacy (old) option keys to new option keys.
	 *
	 * @return array<string,string>
	 */
	protected static function get_migration_map() {
		return [
			'tfe_width'                  => 'excerpt_thumbnail_width',
			'tfe_height'                 => 'excerpt_thumbnail_height',
			'tfe_align'                  => 'excerpt_thumbnail_align',
			'tfe_default_image'          => 'excerpt_thumbnail_default_image',
			'tfe_default_image_src'      => 'excerpt_thumbnail_default_image_src',
			'tfe_withlink'               => 'excerpt_thumbnail_withlink',
			'tfe_on_home'                => 'excerpt_thumbnail_on_home',
			'tfe_on_archives'            => 'excerpt_thumbnail_on_archives',
			'tfe_on_search'              => 'excerpt_thumbnail_on_search',
			'tfe_exclusion'              => 'excerpt_thumbnail_exclusion',
			'tfe_add_og_image'           => 'excerpt_thumbnail_add_og_image',
			'tfe_modern_mode'            => 'excerpt_thumbnail_modern_mode',
			'tfe_cleanup_on_uninstall'   => 'excerpt_thumbnail_cleanup_on_uninstall',
		];
	}

	/**
	 * Migrate once if legacy options are present.
	 *
	 * - Copies legacy values to new keys (does not overwrite if new key already set).
	 * - Deletes legacy keys after successful copy.
	 * - Marks migration as complete to avoid repeat work.
	 */
	public static function maybe_migrate_legacy_options() {
		// If already migrated, just ensure DB version is current and stop.
		if ( 'yes' === get_option( self::MIGRATED_FLAG ) ) {
			update_option( self::DB_VERSION_KEY, self::DB_VERSION );
			return;
		}

		$map        = self::get_migration_map();
		$migrated   = false;

		foreach ( $map as $old_key => $new_key ) {
			// Using null so we can detect existence vs empty string/zero.
			$old_value = get_option( $old_key, null );

			// If legacy key doesn't exist at all, skip.
			if ( null === $old_value ) {
				continue;
			}

			// Only populate the new key if it's not set yet.
			if ( false === get_option( $new_key, false ) ) {
				update_option( $new_key, $old_value );
			}

			// Remove the legacy key either way (we've captured its value if needed).
			delete_option( $old_key );
			$migrated = true;
		}

		if ( $migrated ) {
			update_option( self::MIGRATED_FLAG, 'yes' );
		}

		// Always keep a DB version for future migrations.
		update_option( self::DB_VERSION_KEY, self::DB_VERSION );
	}
}
