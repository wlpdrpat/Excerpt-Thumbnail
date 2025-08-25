<?php
/**
 * Admin-area functionality.
 *
 * Provides a Settings API page under “Settings → Excerpt Thumbnail”
 * and registers/sanitizes legacy options (tfe_*).
 *
 * @link       https://wellplanet.com
 * @since      3.0.0
 * @package    Excerpt_Thumbnail
 * @subpackage Excerpt_Thumbnail/admin
 * @author     Patrick Coleman <pat@wellplanet.com>
 * @license    GPL-2.0-or-later
 */

/**
 * Enqueues admin assets and registers the settings screen.
 *
 * @since 3.0.0
 */
class Excerpt_Thumbnail_Admin {

    /**
     * Plugin slug / text domain.
     *
     * @since 3.0.0
     * @var string
     */
    private $slug;

    /**
     * Plugin version.
     *
     * @since 3.0.0
     * @var string
     */
    private $version;

    /**
     * Settings API group slug.
     *
     * All options registered to this group will be sanitized on save.
     *
     * @since 3.0.0
     * @var string
     */
    private $settings_group = 'excerpt_thumbnail_options';

    /**
     * Settings page slug (screen id).
     *
     * @since 3.0.0
     * @var string
     */
    private $page_slug = 'excerpt-thumbnail';

    /**
     * Constructor.
     *
     * @since 3.0.0
     * @param string $slug     Plugin slug / text domain.
     * @param string $version  Plugin version.
     */
    public function __construct( $slug, $version ) {
        $this->slug    = $slug;
        $this->version = $version;
    }

    /**
     * Placeholder for enqueueing admin styles.
     *
     * @since 3.0.0
     * @return void
     */
    public function enqueue_styles() {
        // Intentionally empty for now.
    }

    /**
     * Placeholder for enqueueing admin scripts.
     *
     * @since 3.0.0
     * @return void
     */
    public function enqueue_scripts() {
        // Intentionally empty for now.
    }

    /**
     * Add the options page under Settings.
     *
     * @since 3.0.0
     * @return void
     */
    public function add_options_page() {
        add_options_page(
            __( 'Excerpt Thumbnail', 'excerpt-thumbnail' ),
            __( 'Excerpt Thumbnail', 'excerpt-thumbnail' ),
            'manage_options',
            $this->page_slug,
            [ $this, 'render_settings_page' ]
        );
    }

    /**
     * Register legacy options and settings fields using the Settings API.
     *
     * Keeps existing individual option keys (tfe_*) for maximum compatibility.
     *
     * @since 3.0.0
     * @return void
     */
    public function register_settings() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // --- Register each legacy option with dedicated sanitizers ---
        register_setting( $this->settings_group, 'tfe_width',               [ $this, 'sanitize_absint' ] );
        register_setting( $this->settings_group, 'tfe_height',              [ $this, 'sanitize_absint' ] );
        register_setting( $this->settings_group, 'tfe_align',               [ $this, 'sanitize_align' ] );
        register_setting( $this->settings_group, 'tfe_default_image',       [ $this, 'sanitize_yesno' ] );
        register_setting( $this->settings_group, 'tfe_default_image_src',   [ $this, 'sanitize_url' ] );
        register_setting( $this->settings_group, 'tfe_withlink',            [ $this, 'sanitize_yesno' ] );
        register_setting( $this->settings_group, 'tfe_on_home',             [ $this, 'sanitize_yesno' ] );
        register_setting( $this->settings_group, 'tfe_on_archives',         [ $this, 'sanitize_yesno' ] );
        register_setting( $this->settings_group, 'tfe_on_search',           [ $this, 'sanitize_yesno' ] );
        register_setting( $this->settings_group, 'tfe_exclusion',           [ $this, 'sanitize_csv_ids' ] );
        register_setting( $this->settings_group, 'tfe_add_og_image',        [ $this, 'sanitize_yesno' ] );
        register_setting( $this->settings_group, 'tfe_modern_mode',         [ $this, 'sanitize_yesno' ] );


        // If we later expose a custom "Read More" label, we’ll register it here.
        // register_setting( $this->settings_group, 'tfe_read_more_text',    [ $this, 'sanitize_text' ] );

        // --- Section ---
        add_settings_section(
            'excerpt_thumbnail_main',
            __( 'Excerpt Thumbnail Settings', 'excerpt-thumbnail' ),
            function() {
                echo '<p>' . esc_html__( 'Control how thumbnails are added to excerpts on home, archive, and search pages.', 'excerpt-thumbnail' ) . '</p>';
            },
            $this->page_slug
        );

        // --- Fields ---
        add_settings_field(
            'tfe_width',
            __( 'Image Width (px)', 'excerpt-thumbnail' ),
            [ $this, 'field_width' ],
            $this->page_slug,
            'excerpt_thumbnail_main'
        );

        add_settings_field(
            'tfe_height',
            __( 'Image Height (px)', 'excerpt-thumbnail' ),
            [ $this, 'field_height' ],
            $this->page_slug,
            'excerpt_thumbnail_main'
        );

        add_settings_field(
            'tfe_align',
            __( 'Alignment', 'excerpt-thumbnail' ),
            [ $this, 'field_align' ],
            $this->page_slug,
            'excerpt_thumbnail_main'
        );

        add_settings_field(
            'tfe_withlink',
            __( 'Link Image to Post', 'excerpt-thumbnail' ),
            [ $this, 'field_withlink' ],
            $this->page_slug,
            'excerpt_thumbnail_main'
        );

        add_settings_field(
            'tfe_default_image',
            __( 'Use Default Image if none found', 'excerpt-thumbnail' ),
            [ $this, 'field_default_image' ],
            $this->page_slug,
            'excerpt_thumbnail_main'
        );

        add_settings_field(
            'tfe_default_image_src',
            __( 'Default Image URL', 'excerpt-thumbnail' ),
            [ $this, 'field_default_image_src' ],
            $this->page_slug,
            'excerpt_thumbnail_main'
        );

        add_settings_field(
            'tfe_contexts',
            __( 'Where to show thumbnails', 'excerpt-thumbnail' ),
            [ $this, 'field_contexts' ],
            $this->page_slug,
            'excerpt_thumbnail_main'
        );

        add_settings_field(
            'tfe_exclusion',
            __( 'Exclude Categories (CSV of IDs)', 'excerpt-thumbnail' ),
            [ $this, 'field_exclusion' ],
            $this->page_slug,
            'excerpt_thumbnail_main'
        );

        add_settings_field(
            'tfe_add_og_image',
            __( 'Add Open Graph Image', 'excerpt-thumbnail' ),
            [ $this, 'field_add_og_image' ],
            $this->page_slug,
            'excerpt_thumbnail_main'
        );

        add_settings_field(
            'tfe_modern_mode',
            __( 'Modern Mode (recommended)', 'excerpt-thumbnail' ),
            [ $this, 'field_modern_mode' ],
            $this->page_slug,
            'excerpt_thumbnail_main'
        );

    }

    /**
     * Render the settings page markup.
     *
     * @since 3.0.0
     * @return void
     */
    public function render_settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'Excerpt Thumbnail', 'excerpt-thumbnail' ); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields( $this->settings_group );
                do_settings_sections( $this->page_slug );
                submit_button( __( 'Save Changes', 'excerpt-thumbnail' ) );
                ?>
            </form>
            <hr />
            <p>
                <em>
                    <?php
                    echo esc_html__(
                        'Tip: Featured image is used first; if none, the first image in the content is used; if still none and enabled, the default image is used.',
                        'excerpt-thumbnail'
                    );
                    ?>
                </em>
            </p>
        </div>
        <?php
    }

    // ===== Field renderers ===================================================

    /**
     * Width field.
     *
     * @since 3.0.0
     * @return void
     */
    public function field_width() {
        $value = get_option( 'tfe_width', 150 );
        printf(
            '<input type="number" min="0" step="1" id="tfe_width" name="tfe_width" value="%s" class="small-text" />',
            esc_attr( (string) $value )
        );
    }

    /**
     * Height field.
     *
     * @since 3.0.0
     * @return void
     */
    public function field_height() {
        $value = get_option( 'tfe_height', 150 );
        printf(
            '<input type="number" min="0" step="1" id="tfe_height" name="tfe_height" value="%s" class="small-text" />',
            esc_attr( (string) $value )
        );
    }

    /**
     * Alignment field (select).
     *
     * @since 3.0.0
     * @return void
     */
    public function field_align() {
        $value   = get_option( 'tfe_align', 'left' );
        $choices = [
            'left'   => __( 'Left', 'excerpt-thumbnail' ),
            'right'  => __( 'Right', 'excerpt-thumbnail' ),
            'center' => __( 'Center', 'excerpt-thumbnail' ),
        ];
        echo '<select id="tfe_align" name="tfe_align">';
        foreach ( $choices as $key => $label ) {
            printf(
                '<option value="%s"%s>%s</option>',
                esc_attr( $key ),
                selected( $value, $key, false ),
                esc_html( $label )
            );
        }
        echo '</select>';
    }

    /**
     * Link image to post field (checkbox).
     *
     * @since 3.0.0
     * @return void
     */
    public function field_withlink() {
        $value = get_option( 'tfe_withlink', 'yes' ); // legacy default: yes
        printf(
            '<label><input type="checkbox" name="tfe_withlink" value="yes"%s> %s</label>',
            checked( $value, 'yes', false ),
            esc_html__( 'Wrap thumbnail in a link to the post', 'excerpt-thumbnail' )
        );
    }

    /**
     * Use default image field (checkbox).
     *
     * @since 3.0.0
     * @return void
     */
    public function field_default_image() {
        $value = get_option( 'tfe_default_image', 'no' );
        printf(
            '<label><input type="checkbox" name="tfe_default_image" value="yes"%s> %s</label>',
            checked( $value, 'yes', false ),
            esc_html__( 'If no image found, use the Default Image URL below', 'excerpt-thumbnail' )
        );
    }

    /**
     * Default image URL field.
     *
     * @since 3.0.0
     * @return void
     */
    public function field_default_image_src() {
        $value = get_option( 'tfe_default_image_src', '' );
        printf(
            '<input type="url" id="tfe_default_image_src" name="tfe_default_image_src" value="%s" class="regular-text code" placeholder="https://example.com/path/to/fallback.jpg" />',
            esc_attr( (string) $value )
        );
    }

    /**
     * Context checkboxes (home, archives, search).
     *
     * @since 3.0.0
     * @return void
     */
    public function field_contexts() {
        $home     = get_option( 'tfe_on_home', 'yes' );
        $archives = get_option( 'tfe_on_archives', 'yes' );
        $search   = get_option( 'tfe_on_search', 'yes' );

        echo '<fieldset>';
        printf(
            '<label><input type="checkbox" name="tfe_on_home" value="yes"%s> %s</label><br>',
            checked( $home, 'yes', false ),
            esc_html__( 'Home / blog index', 'excerpt-thumbnail' )
        );
        printf(
            '<label><input type="checkbox" name="tfe_on_archives" value="yes"%s> %s</label><br>',
            checked( $archives, 'yes', false ),
            esc_html__( 'Category/Tag/Date archives', 'excerpt-thumbnail' )
        );
        printf(
            '<label><input type="checkbox" name="tfe_on_search" value="yes"%s> %s</label>',
            checked( $search, 'yes', false ),
            esc_html__( 'Search results', 'excerpt-thumbnail' )
        );
        echo '</fieldset>';
    }

    /**
     * Category exclusions (CSV of IDs).
     *
     * @since 3.0.0
     * @return void
     */
    public function field_exclusion() {
        $value = get_option( 'tfe_exclusion', '' );
        printf(
            '<input type="text" id="tfe_exclusion" name="tfe_exclusion" value="%s" class="regular-text" placeholder="e.g. 2,7,15" />',
            esc_attr( (string) $value )
        );
        echo '<p class="description">' . esc_html__( 'Enter category IDs to exclude (comma-separated).', 'excerpt-thumbnail' ) . '</p>';
    }

    /**
     * Add og:image meta tag on single post pages (checkbox).
     *
     * @since 3.0.0
     * @return void
     */
    public function field_add_og_image() {
        $value = get_option( 'tfe_add_og_image', 'yes' ); // default: enabled
        printf(
            '<label><input type="checkbox" name="tfe_add_og_image" value="yes"%s> %s</label>',
            checked( $value, 'yes', false ),
            esc_html__( 'Output <meta property="og:image"> on single posts using the same image logic (Featured → first content image → default).', 'excerpt-thumbnail' )
        );
        echo '<p class="description">' . esc_html__( 'Uncheck if your SEO plugin already sets og:image to avoid duplicates.', 'excerpt-thumbnail' ) . '</p>';
    }

    /**
     * Modern Mode checkbox.
     *
     * When enabled:
     * - Only modifies the_excerpt (no content forcing).
     * - Registers a named image size "excerpt-thumbnail".
     * - Uses that size for featured images.
     *
     * @since 3.0.0
     * @return void
     */
    public function field_modern_mode() {
        $value = get_option( 'tfe_modern_mode', 'no' ); // default: legacy behavior
        printf(
            '<label><input type="checkbox" name="tfe_modern_mode" value="yes"%s> %s</label>',
            checked( $value, 'yes', false ),
            esc_html__( 'Use excerpt-only output and the "excerpt-thumbnail" image size. Disable if you rely on legacy content-forcing.', 'excerpt-thumbnail' )
        );
        echo '<p class="description">' . esc_html__( 'Tip: After changing width/height, run a thumbnail regeneration plugin if you want new physical sizes for existing images.', 'excerpt-thumbnail' ) . '</p>';
    }


    // ===== Sanitizers ========================================================

    /**
     * Sanitize integer >= 0.
     *
     * @since 3.0.0
     * @param mixed $value Raw value.
     * @return int
     */
    public function sanitize_absint( $value ) {
        $v = absint( $value );
        return ( $v < 0 ) ? 0 : $v;
    }

    /**
     * Sanitize alignment (left|right|center).
     *
     * @since 3.0.0
     * @param mixed $value Raw value.
     * @return string
     */
    public function sanitize_align( $value ) {
        $allowed = [ 'left', 'right', 'center' ];
        $value   = is_string( $value ) ? strtolower( $value ) : '';
        return in_array( $value, $allowed, true ) ? $value : 'left';
    }

    /**
     * Sanitize yes/no toggles.
     *
     * For checkboxes: any non-"yes" value becomes "no".
     *
     * @since 3.0.0
     * @param mixed $value Raw value.
     * @return string "yes"|"no"
     */
    public function sanitize_yesno( $value ) {
        return ( 'yes' === $value ) ? 'yes' : 'no';
    }

    /**
     * Sanitize URL (empty allowed).
     *
     * @since 3.0.0
     * @param mixed $value Raw value.
     * @return string
     */
    public function sanitize_url( $value ) {
        $value = (string) $value;
        if ( '' === $value ) {
            return '';
        }
        $url = esc_url_raw( $value );
        return ( $url ) ? $url : '';
    }

    /**
     * Sanitize CSV of category IDs: "1,2,abc,3" -> "1,2,3".
     *
     * @since 3.0.0
     * @param mixed $value Raw value.
     * @return string Normalized CSV (may be empty).
     */
    public function sanitize_csv_ids( $value ) {
        if ( ! is_string( $value ) ) {
            return '';
        }
        $parts = preg_split( '/\s*,\s*/', $value, -1, PREG_SPLIT_NO_EMPTY );
        $ids   = [];
        foreach ( (array) $parts as $p ) {
            $n = absint( $p );
            if ( $n > 0 ) {
                $ids[] = (string) $n;
            }
        }
        // Ensure unique and sorted.
        $ids = array_values( array_unique( $ids ) );
        sort( $ids, SORT_NUMERIC );
        return implode( ',', $ids );
    }

    /**
     * Sanitize plain text (fallback).
     *
     * @since 3.0.0
     * @param mixed $value Raw value.
     * @return string
     */
    public function sanitize_text( $value ) {
        return sanitize_text_field( (string) $value );
    }
}
