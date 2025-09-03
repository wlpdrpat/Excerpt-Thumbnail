<?php
/**
 * Core plugin class.
 *
 * Wires dependencies and registers admin/public hooks.
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
 * Core plugin class that loads dependencies and defines hooks.
 *
 * @since 1.0.0
 */
class Excerpt_Thumbnail {

	/**
	 * Hook loader.
	 *
	 * @since 1.0.0
	 * @var Excerpt_Thumbnail_Loader
	 */
	protected $loader;

	/**
	 * Plugin slug (text domain).
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $plugin_slug = EXCERPT_THUMBNAIL_SLUG;

	/**
	 * Plugin version.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $version = EXCERPT_THUMBNAIL_VERSION;

	/**
	 * Bootstraps dependencies and hooks.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Require class files and prepare the loader.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function load_dependencies() {
		require_once EXCERPT_THUMBNAIL_DIR . 'includes/class-excerpt-thumbnail-loader.php';
		require_once EXCERPT_THUMBNAIL_DIR . 'includes/class-excerpt-thumbnail-i18n.php';
		require_once EXCERPT_THUMBNAIL_DIR . 'admin/class-excerpt-thumbnail-admin.php';
		require_once EXCERPT_THUMBNAIL_DIR . 'public/class-excerpt-thumbnail-public.php';

		$this->loader = new Excerpt_Thumbnail_Loader();
	}

	/**
	 * Load translations.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function set_locale() {
		$plugin_i18n = new Excerpt_Thumbnail_i18n();
		$plugin_i18n->set_domain( $this->plugin_slug );
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register admin area hooks.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Excerpt_Thumbnail_Admin( $this->plugin_slug, $this->version );

		// Enqueues (kept for future use).
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Settings page and settings registration.
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_options_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
	}

	/**
	 * Register public-facing hooks.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function define_public_hooks() {
		$plugin_public = new Excerpt_Thumbnail_Public( $this->plugin_slug, $this->version );

		// Enqueues (kept for future use).
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// === LEGACY-PARITY FILTERS ===

		// Add thumbnail to excerpts (site + RSS).
		$this->loader->add_action( 'the_excerpt',     $plugin_public, 'filter_the_excerpt', 10, 1 );
		$this->loader->add_action( 'the_excerpt_rss', $plugin_public, 'filter_the_excerpt', 10, 1 );

		// Force content → excerpt on home/archive/search (legacy behavior; Modern Mode disables in public class).
		$this->loader->add_action( 'the_content',     $plugin_public, 'filter_the_content', 10, 1 );

		// Add <meta property="og:image" ...> on single posts when enabled.
		$this->loader->add_action( 'wp_head',         $plugin_public, 'maybe_add_og_image', 5 );

		// Register 'excerpt-thumbnail' size based on current settings.
		$this->loader->add_action( 'after_setup_theme', $plugin_public, 'register_image_size' );
	}

	/**
	 * Attach all collected hooks to WordPress.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function run() {
		$this->loader->run();
	}

	/** @since 1.0.0 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/** @since 1.0.0 */
	public function get_version() {
		return $this->version;
	}
}
