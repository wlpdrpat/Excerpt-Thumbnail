<?php
/**
 * Core plugin class
 */

class Excerpt_Thumbnail {

    /**
     * @var Excerpt_Thumbnail_Loader
     */
    protected $loader;

    /**
     * @var string
     */
    protected $plugin_slug = EXCERPT_THUMBNAIL_SLUG;

    /**
     * @var string
     */
    protected $version = EXCERPT_THUMBNAIL_VERSION;

    public function __construct() {
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    private function load_dependencies() {
        require_once EXCERPT_THUMBNAIL_DIR . 'includes/class-excerpt-thumbnail-loader.php';
        require_once EXCERPT_THUMBNAIL_DIR . 'includes/class-excerpt-thumbnail-i18n.php';

        // Admin and Public classes (empty stubs for now; we’ll add logic later)
        require_once EXCERPT_THUMBNAIL_DIR . 'admin/class-excerpt-thumbnail-admin.php';
        require_once EXCERPT_THUMBNAIL_DIR . 'public/class-excerpt-thumbnail-public.php';

        $this->loader = new Excerpt_Thumbnail_Loader();
    }

    private function set_locale() {
        $plugin_i18n = new Excerpt_Thumbnail_i18n();
        $plugin_i18n->set_domain( $this->plugin_slug );

        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }

    private function define_admin_hooks() {
        $plugin_admin = new Excerpt_Thumbnail_Admin( $this->plugin_slug, $this->version );

        // Keep these in place; we’ll wire real settings in Step 2
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
    }

    private function define_public_hooks() {
        $plugin_public = new Excerpt_Thumbnail_Public( $this->plugin_slug, $this->version );

        // No front-end behavior yet — parity comes later
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
    }

    public function run() {
        $this->loader->run();
    }

    public function get_plugin_slug() {
        return $this->plugin_slug;
    }

    public function get_version() {
        return $this->version;
    }
}
