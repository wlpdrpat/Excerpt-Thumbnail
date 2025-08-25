<?php
/**
 * Public-facing functionality.
 *
 * Hooks into front-end rendering to:
 * - Prepend a thumbnail to post excerpts (site + feed).
 * - Force full content into an excerpt on home/archive/search contexts (legacy behavior).
 *
 * NOTE: This is a parity pass with the legacy plugin. We intentionally keep:
 * - Option keys: tfe_width, tfe_height, tfe_align, tfe_default_image, tfe_default_image_src,
 *                tfe_withlink, tfe_on_home, tfe_on_archives, tfe_on_search, tfe_exclusion.
 * - “Read More >>” link appended to forced excerpts (55 words).
 *
 * @link       https://wellplanet.com
 * @since      3.0.0
 * @package    Excerpt_Thumbnail
 * @subpackage Excerpt_Thumbnail/public
 * @author     Patrick Coleman <pat@wellplanet.com>
 * @license    GPL-2.0-or-later
 */

/**
 * Enqueues public assets and implements legacy excerpt/thumbnail behavior.
 *
 * @since 3.0.0
 */
class Excerpt_Thumbnail_Public {

    /** @since 3.0.0 @var string */
    private $slug;

    /** @since 3.0.0 @var string */
    private $version;

    /**
     * Constructor.
     *
     * @since 3.0.0
     * @param string $slug    Plugin slug / text domain.
     * @param string $version Plugin version.
     */
    public function __construct( $slug, $version ) {
        $this->slug    = $slug;
        $this->version = $version;
    }

    /** @since 3.0.0 @return void */
    public function enqueue_styles() {}

    /** @since 3.0.0 @return void */
    public function enqueue_scripts() {}

    // ======================================================================
    // Filters
    // ======================================================================

    /**
     * Prepend a thumbnail to excerpts (site + feed).
     *
     * Mirrors legacy behavior of filtering 'the_excerpt' and 'the_excerpt_rss'.
     * Respects category exclusions (only on category archives, per the legacy plugin).
     *
     * @since 3.0.0
     * @param string $excerpt Current excerpt HTML/text.
     * @return string Modified excerpt with image prepended (when applicable).
     */
    public function filter_the_excerpt( $excerpt ) {
        if ( is_admin() ) {
            return $excerpt;
        }

        global $post;
        if ( ! $post instanceof WP_Post ) {
            return $excerpt;
        }

        // Legacy: On category archive + post in excluded categories → skip.
        $exclusion_csv = get_option( 'tfe_exclusion', '' );
        if ( is_category() && $this->post_in_excluded_categories( $post->ID, $exclusion_csv ) ) {
            return $excerpt;
        }

        $image_html = $this->build_image_html( $post->ID );

        // For feeds: convert class alignment to attribute alignment, as in legacy.
        if ( is_feed() && $image_html ) {
            $image_html = $this->feed_align_transform( $image_html );
        }

        // Legacy calls do_shortcode() over the image HTML.
        if ( $image_html ) {
            $image_html = do_shortcode( $image_html );
        }

        return $image_html . $excerpt;
    }

    /**
     * Force content → excerpt on home/archive/search (legacy behavior), then prepend thumbnail.
     *
     * The legacy plugin filtered 'the_content', created an excerpt if needed (55 words) and
     * appended a “Read More >>” link, then ran its image-prepend routine.
     *
     * @since 3.0.0
     * @param string $content The current post content.
     * @return string Modified content (image + excerpt) or original content when not applicable.
     */
    public function filter_the_content( $content ) {
        if ( is_admin() || is_single() || is_feed() ) {
            return $content; // Legacy: do not force on singles or in feeds.
        }

        global $post;
        if ( ! $post instanceof WP_Post ) {
            return $content;
        }

        // Legacy: only apply on these contexts when enabled.
        $on_home     = $this->yesno( get_option( 'tfe_on_home', 'yes' ) );
        $on_archives = $this->yesno( get_option( 'tfe_on_archives', 'yes' ) );
        $on_search   = $this->yesno( get_option( 'tfe_on_search', 'yes' ) );

        $apply = ( is_home()     && $on_home )
              || ( is_archive()  && $on_archives )
              || ( is_search()   && $on_search );

        if ( ! $apply ) {
            return $content;
        }

        // Legacy: On category archive + post in excluded categories → skip.
        $exclusion_csv = get_option( 'tfe_exclusion', '' );
        if ( is_category() && $this->post_in_excluded_categories( $post->ID, $exclusion_csv ) ) {
            return $content;
        }

        // Build an excerpt (parity with legacy): prefer post_excerpt; otherwise trim to 55 words.
        $excerpt_html = $this->build_excerpt_html( $post );

        // Prepend thumbnail (legacy order).
        $image_html = $this->build_image_html( $post->ID );
        if ( $image_html ) {
            $image_html = do_shortcode( $image_html );
        }

        return $image_html . $excerpt_html;
    }

    // ======================================================================
    // Helpers (parity)
    // ======================================================================

    /**
     * Build the excerpt HTML:
     * - If post_excerpt exists, use it as-is (no recursive excerpt filters to avoid double-images).
     * - Else, trim content to 55 words and append the legacy “Read More >>” link.
     * - Wrap in <p>…</p> (legacy did this for the generated case; we match for consistency).
     *
     * @since 3.0.0
     * @param WP_Post $post Post object.
     * @return string HTML.
     */
    private function build_excerpt_html( WP_Post $post ) {
        $excerpt = $post->post_excerpt;

        if ( is_string( $excerpt ) && '' !== trim( $excerpt ) ) {
            // Use author-provided excerpt. Keep simple paragraph wrapper for consistency.
            return '<p>' . wp_kses_post( $excerpt ) . '</p>';
        }

        // Derive from content:
        $raw = get_post_field( 'post_content', $post->ID );
        $raw = strip_shortcodes( $raw );
        $raw = str_replace( ']]>', ']]&gt;', $raw );
        $raw = wp_strip_all_tags( $raw );

        // Legacy: 55 words, then append link.
        $trimmed = wp_trim_words( $raw, 55, '' );

        $read_more = sprintf(
            '...<br /><br /><a href="%s">%s</a>',
            esc_url( get_permalink( $post ) ),
            esc_html__( 'Read More >>', 'excerpt-thumbnail' )
        );

        return '<p>' . esc_html( $trimmed ) . $read_more . '</p>';
    }

    /**
     * Build the image HTML using the legacy priority:
     * 1) Featured image (if set) using user width/height as a size array.
     * 2) First image in post content (DOM parse fallback).
     * 3) Default image (if enabled).
     *
     * Alignment classes match legacy: alignleft|alignright|aligncenter + "wp-post-image tfe".
     * If "with link" is enabled, the image is wrapped in <a href="permalink">…</a>.
     *
     * @since 3.0.0
     * @param int $post_id Post ID.
     * @return string HTML (possibly empty).
     */
    private function build_image_html( $post_id ) {
        $width        = max( 0, absint( get_option( 'tfe_width', 100 ) ) );
        $height       = max( 0, absint( get_option( 'tfe_height', 100 ) ) );
        $align        = $this->sanitize_align( get_option( 'tfe_align', 'left' ) );
        $with_link    = $this->yesno( get_option( 'tfe_withlink', 'yes' ) );        // legacy default ≈ yes
        $use_default  = $this->yesno( get_option( 'tfe_default_image', 'yes' ) );   // legacy default ≈ yes
        $default_src  = (string) get_option( 'tfe_default_image_src', '' );

        $classes = 'align' . $align . ' wp-post-image tfe';

        // 1) Featured image first (legacy).
        if ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( $post_id ) ) {
            $img = get_the_post_thumbnail(
                $post_id,
                [ $width ?: 100, $height ?: 100 ],
                [ 'class' => $classes ]
            );
            if ( $img ) {
                return $with_link ? $this->wrap_link( get_permalink( $post_id ), $img ) : $img;
            }
        }

        // 2) First image in content.
        $content = (string) get_post_field( 'post_content', $post_id );
        $img     = $this->first_image_from_content( $content, $width, $height, $classes );
        if ( $img ) {
            return $with_link ? $this->wrap_link( get_permalink( $post_id ), $img ) : $img;
        }

        // 3) Default image if enabled.
        if ( $use_default && $default_src ) {
            $img = $this->img_tag( $default_src, $width, $height, $classes, '', '' );
            return $with_link ? $this->wrap_link( get_permalink( $post_id ), $img ) : $img;
        }

        return '';
    }

    /**
     * Extract the first <img> from post content using DOM, capturing src/alt/title.
     * Falls back gracefully if DOM parsing fails.
     *
     * @since 3.0.0
     * @param string $content Post content.
     * @param int    $width   Width attribute (0 means omit).
     * @param int    $height  Height attribute (0 means omit).
     * @param string $classes Class attribute to apply.
     * @return string HTML or empty string.
     */
    private function first_image_from_content( $content, $width, $height, $classes ) {
        if ( '' === trim( $content ) ) {
            return '';
        }

        // Suppress libxml warnings for malformed HTML fragments.
        if ( function_exists( 'libxml_use_internal_errors' ) ) {
            libxml_use_internal_errors( true );
        }

        $doc = new DOMDocument();
        $loaded = $doc->loadHTML(
            '<?xml encoding="utf-8" ?>' . $content,
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        if ( ! $loaded ) {
            return '';
        }

        $imgs = $doc->getElementsByTagName( 'img' );
        if ( 0 === $imgs->length ) {
            return '';
        }

        $img   = $imgs->item( 0 );
        $src   = $img->getAttribute( 'src' );
        if ( ! $src ) {
            return '';
        }
        $alt   = $img->getAttribute( 'alt' );
        $title = $img->getAttribute( 'title' );

        return $this->img_tag( $src, $width, $height, $classes, $alt, $title );
    }

    /**
     * Create an <img> tag with optional width/height and classes.
     *
     * @since 3.0.0
     * @param string $src     Image URL.
     * @param int    $width   Width attribute (0 means omit).
     * @param int    $height  Height attribute (0 means omit).
     * @param string $classes Class attribute.
     * @param string $alt     Alt text.
     * @param string $title   Title attribute.
     * @return string HTML.
     */
    private function img_tag( $src, $width, $height, $classes, $alt = '', $title = '' ) {
        $attrs = [];
        $attrs[] = 'src="' . esc_url( $src ) . '"';
        if ( $width > 0 ) {
            $attrs[] = 'width="' . intval( $width ) . '"';
        }
        if ( $height > 0 ) {
            $attrs[] = 'height="' . intval( $height ) . '"';
        }
        if ( $classes ) {
            $attrs[] = 'class="' . esc_attr( $classes ) . '"';
        }
        if ( '' !== $alt ) {
            $attrs[] = 'alt="' . esc_attr( $alt ) . '"';
        }
        if ( '' !== $title ) {
            $attrs[] = 'title="' . esc_attr( $title ) . '"';
        }

        return '<img ' . implode( ' ', $attrs ) . ' />';
    }

    /**
     * Wrap content in a permalink <a>.
     *
     * @since 3.0.0
     * @param string $url URL.
     * @param string $html Inner HTML.
     * @return string
     */
    private function wrap_link( $url, $html ) {
        return '<a href="' . esc_url( $url ) . '">' . $html . '</a>';
    }

    /**
     * Legacy “category exclusion” check:
     * It only applies on category archive pages (is_category()).
     *
     * @since 3.0.0
     * @param int    $post_id       Post ID.
     * @param string $exclusion_csv CSV of category IDs (e.g., "2,7,15").
     * @return bool True if the post is in any excluded category.
     */
    private function post_in_excluded_categories( $post_id, $exclusion_csv ) {
        $exclusion_csv = trim( (string) $exclusion_csv );
        if ( '' === $exclusion_csv ) {
            return false;
        }
        $ids = array_filter( array_map( 'absint', preg_split( '/\s*,\s*/', $exclusion_csv, -1, PREG_SPLIT_NO_EMPTY ) ) );
        if ( empty( $ids ) ) {
            return false;
        }
        return has_term( $ids, 'category', $post_id );
    }

    /**
     * Transform class-based alignment into feed-safe align attributes (legacy).
     * If 'aligncenter' → wrap in <p align="center">…</p>, else set <img align="left|right">.
     *
     * @since 3.0.0
     * @param string $image_html HTML of the image.
     * @return string
     */
    private function feed_align_transform( $image_html ) {
        if ( false !== strpos( $image_html, 'aligncenter' ) ) {
            return '<p align="center">' . $image_html . '</p>';
        }

        // Convert class="alignleft|alignright …" into align="left|right" on <img>.
        if ( preg_match( '/class="([^"]*)"/i', $image_html, $m ) ) {
            $classes = $m[1];
            if ( false !== strpos( $classes, 'alignleft' ) ) {
                return preg_replace( '/<img /i', '<img align="left" hspace="5" ', $image_html, 1 );
            }
            if ( false !== strpos( $classes, 'alignright' ) ) {
                return preg_replace( '/<img /i', '<img align="right" hspace="5" ', $image_html, 1 );
            }
        }
        return $image_html;
    }

    /**
     * Sanitize alignment (left|right|center).
     *
     * @since 3.0.0
     * @param mixed $value Raw option.
     * @return string
     */
    private function sanitize_align( $value ) {
        $value   = is_string( $value ) ? strtolower( $value ) : '';
        $allowed = [ 'left', 'right', 'center' ];
        return in_array( $value, $allowed, true ) ? $value : 'left';
    }

    /**
     * Legacy yes/no → boolean.
     *
     * @since 3.0.0
     * @param mixed $value Raw option.
     * @return bool True when "yes".
     */
    private function yesno( $value ) {
        return ( 'yes' === $value );
    }
}
