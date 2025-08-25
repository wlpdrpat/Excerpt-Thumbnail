<?php
/**
 * Plugin uninstall cleanup.
 *
 * Deletes plugin options only when the user has opted in via the
 * "Remove Data on Uninstall" setting.
 *
 * @link       https://wellplanet.com
 * @since      3.0.0
 * @package    Excerpt_Thumbnail
 * @author     Patrick Coleman <pat@wellplanet.com>
 * @license    GPL-2.0-or-later
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Only remove data if the user explicitly opted in.
if ( 'yes' !== get_option( 'tfe_cleanup_on_uninstall', 'no' ) ) {
    return;
}

// List all options this plugin creates.
$option_keys = [
    'tfe_width',
    'tfe_height',
    'tfe_align',
    'tfe_default_image',
    'tfe_default_image_src',
    'tfe_withlink',
    'tfe_on_home',
    'tfe_on_archives',
    'tfe_on_search',
    'tfe_exclusion',
    'tfe_add_og_image',
    'tfe_modern_mode',
    'tfe_cleanup_on_uninstall',
];

foreach ( $option_keys as $key ) {
    delete_option( $key );
}
