<?php
/**
 * Public-facing functionality.
 *
 * In legacy mode (default):
 * - Forces content into excerpt on home/archive/search and prepends thumbnail.
 *
 * In Modern Mode:
 * - Only modifies the_excerpt (no content forcing).
 * - Uses a named image size "excerpt-thumbnail" for featured images.
 *
 * @link      https://wellplanet.com
 * @since     1.0.0
 * @package   Excerpt_Thumbnail
 * @subpackage Excerpt_Thumbnail/public
 * @author    Patrick Coleman
 * @license   GPL-2.0-or-later
 */

defined( 'ABSPATH' ) || exit;

class Excerpt_Thumbnail_Public {

	/** @since 1.0.0 @var string */
	private $slug;

	/** @since 1.0.0 @var string */
	private $version;

	/**
	 * Ctor.
	 *
	 * @since 1.0.0
	 * @param string $slug
	 * @param string $version
	 */
	public function __construct( $slug, $version ) {
		$this->slug    = $slug;
		$this->version = $version;
	}

	/** @since 1.0.0 @return void */
	public function enqueue_styles() {}

	/** @since 1.0.0 @return void */
	public function enqueue_scripts() {}

	// ======================================================================
	// Setup
	// ======================================================================

	/**
	 * Register a named image size "excerpt-thumbnail" using current width/height.
	 * This lets WP pick a properly sized source for featured images in Modern Mode.
	 *
	 * Note: existing images won’t gain this size retroactively; use a regen plugin if needed.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_image_size() {
		$w = max( 0, absint( $this->opt( 'excerpt_thumbnail_width', 'tfe_width', 100 ) ) );
		$h = max( 0, absint( $this->opt( 'excerpt_thumbnail_height', 'tfe_height', 100 ) ) );

		// Reasonable defaults to avoid zeroed size.
		if ( 0 === $w && 0 === $h ) {
			$w = 100; $h = 100;
		}

		// Hard crop if both provided, otherwise soft constraint.
		$crop = ( $w > 0 && $h > 0 );
		add_image_size( 'excerpt-thumbnail', $w ?: 9999, $h ?: 9999, $crop );
	}

	// ======================================================================
	// Filters
	// ======================================================================

	/**
	 * Prepend a thumbnail to excerpts (site + feed).
	 * Respects contexts (home/archive/search) on site views; feed gets image regardless.
	 *
	 * @since 1.0.0
	 * @param string $excerpt HTML/text.
	 * @return string
	 */
	public function filter_the_excerpt( $excerpt ) {
		if ( is_admin() ) {
			return $excerpt;
		}

		global $post;
		if ( ! $post instanceof WP_Post ) {
		 return $excerpt;
		}

		// On site (non-feed), respect context toggles.
		if ( ! is_feed() ) {
			$on_home     = $this->yesno( $this->opt( 'excerpt_thumbnail_on_home', 'tfe_on_home', 'yes' ) );
			$on_archives = $this->yesno( $this->opt( 'excerpt_thumbnail_on_archives', 'tfe_on_archives', 'yes' ) );
			$on_search   = $this->yesno( $this->opt( 'excerpt_thumbnail_on_search', 'tfe_on_search', 'yes' ) );

			$apply = ( is_home()    && $on_home )
				  || ( is_archive() && $on_archives )
				  || ( is_search()  && $on_search );

			if ( ! $apply ) {
				return $excerpt;
			}
		}

		// Exclusion behavior (legacy: only checked on category archives).
		$exclusion_csv = $this->opt( 'excerpt_thumbnail_exclusion', 'tfe_exclusion', '' );
		if ( is_category() && $this->post_in_excluded_categories( $post->ID, $exclusion_csv ) ) {
			return $excerpt;
		}

		$image_html = $this->build_image_html( $post->ID );

		// Feed alignment transformation (legacy).
		if ( is_feed() && $image_html ) {
			$image_html = $this->feed_align_transform( $image_html );
		}

		if ( $image_html ) {
			$image_html = do_shortcode( $image_html );
		}

		return $image_html . $excerpt;
	}

	/**
	 * Legacy behavior: Force content → excerpt on home/archive/search, then prepend thumbnail.
	 * If Modern Mode is enabled, we skip this entirely.
	 *
	 * @since 1.0.0
	 * @param string $content
	 * @return string
	 */
	public function filter_the_content( $content ) {
		// Modern Mode aborts content forcing.
		if ( $this->yesno( $this->opt( 'excerpt_thumbnail_modern_mode', 'tfe_modern_mode', 'no' ) ) ) {
			return $content;
		}

		if ( is_admin() || is_single() || is_feed() ) {
			return $content;
		}

		global $post;
		if ( ! $post instanceof WP_Post ) {
			return $content;
		}

		$on_home     = $this->yesno( $this->opt( 'excerpt_thumbnail_on_home', 'tfe_on_home', 'yes' ) );
		$on_archives = $this->yesno( $this->opt( 'excerpt_thumbnail_on_archives', 'tfe_on_archives', 'yes' ) );
		$on_search   = $this->yesno( $this->opt( 'excerpt_thumbnail_on_search', 'tfe_on_search', 'yes' ) );

		$apply = ( is_home()    && $on_home )
			  || ( is_archive() && $on_archives )
			  || ( is_search()  && $on_search );

		if ( ! $apply ) {
			return $content;
		}

		$exclusion_csv = $this->opt( 'excerpt_thumbnail_exclusion', 'tfe_exclusion', '' );
		if ( is_category() && $this->post_in_excluded_categories( $post->ID, $exclusion_csv ) ) {
			return $content;
		}

		// Build excerpt HTML (55 words + “Read More >>”).
		$excerpt_html = $this->build_excerpt_html( $post );

		// Prepend thumbnail.
		$image_html = $this->build_image_html( $post->ID );
		if ( $image_html ) {
			$image_html = do_shortcode( $image_html );
		}

		return $image_html . $excerpt_html;
	}

	// ======================================================================
	// Helpers
	// ======================================================================

	/**
	 * Option getter with legacy fallback.
	 * Checks the new key first; if not set, falls back to the legacy key.
	 *
	 * @since 1.0.0
	 * @param string     $new_key  New option key (excerpt_thumbnail_*).
	 * @param string     $old_key  Legacy option key (tfe_*).
	 * @param string|int $default  Default if neither present.
	 * @return mixed
	 */
	private function opt( $new_key, $old_key, $default = '' ) {
		$new = get_option( $new_key, null );
		if ( null !== $new && false !== $new ) {
			return $new;
		}
		return get_option( $old_key, $default );
	}

	/**
	 * Build the excerpt HTML block (parity: 55 words + Read More link).
	 *
	 * @since 1.0.0
	 * @param WP_Post $post
	 * @return string
	 */
	private function build_excerpt_html( WP_Post $post ) {
		$excerpt = $post->post_excerpt;

		if ( is_string( $excerpt ) && '' !== trim( $excerpt ) ) {
			return '<p>' . wp_kses_post( $excerpt ) . '</p>';
		}

		$raw = get_post_field( 'post_content', $post->ID );
		$raw = strip_shortcodes( $raw );
		$raw = str_replace( ']]>', ']]&gt;', $raw );
		$raw = wp_strip_all_tags( $raw );

		$trimmed = wp_trim_words( $raw, 55, '' );

		$read_more = sprintf(
			'...<br /><br /><a href="%s">%s</a>',
			esc_url( get_permalink( $post ) ),
			esc_html__( 'Read More >>', 'excerpt-thumbnail' )
		);

		return '<p>' . esc_html( $trimmed ) . $read_more . '</p>';
	}

	/**
	 * Build the image HTML using priority:
	 * 1) Featured image
	 * 2) First <img> in content
	 * 3) Default image (if enabled)
	 *
	 * Modern Mode uses the named size "excerpt-thumbnail" for featured images.
	 *
	 * @since 1.0.0
	 * @param int $post_id
	 * @return string
	 */
	private function build_image_html( $post_id ) {
		$width        = max( 0, absint( $this->opt( 'excerpt_thumbnail_width', 'tfe_width', 100 ) ) );
		$height       = max( 0, absint( $this->opt( 'excerpt_thumbnail_height', 'tfe_height', 100 ) ) );
		$align        = $this->sanitize_align( $this->opt( 'excerpt_thumbnail_align', 'tfe_align', 'left' ) );
		$with_link    = $this->yesno( $this->opt( 'excerpt_thumbnail_withlink', 'tfe_withlink', 'yes' ) );
		$use_default  = $this->yesno( $this->opt( 'excerpt_thumbnail_default_image', 'tfe_default_image', 'yes' ) );
		$default_src  = (string) $this->opt( 'excerpt_thumbnail_default_image_src', 'tfe_default_image_src', '' );
		$modern_mode  = $this->yesno( $this->opt( 'excerpt_thumbnail_modern_mode', 'tfe_modern_mode', 'no' ) );

		$classes = 'align' . $align . ' wp-post-image excerpt-thumbnail tfe';

		// 1) Featured image
		if ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( $post_id ) ) {
			$size = $modern_mode ? 'excerpt-thumbnail' : [ $width ?: 100, $height ?: 100 ];

			$img  = get_the_post_thumbnail(
				$post_id,
				$size,
				[ 'class' => $classes ]
			);

			if ( $img ) {
				$label = sprintf( /* translators: %s: post title */ __( 'View: %s', 'excerpt-thumbnail' ), get_the_title( $post_id ) );
				return $with_link ? $this->wrap_link( get_permalink( $post_id ), $img, $label ) : $img;
			}
		}

		// 2) First content image
		$content = (string) get_post_field( 'post_content', $post_id );
		$img     = $this->first_image_from_content( $content, $width, $height, $classes, get_the_title( $post_id ) );
		if ( $img ) {
			$label = sprintf( __( 'View: %s', 'excerpt-thumbnail' ), get_the_title( $post_id ) );
			return $with_link ? $this->wrap_link( get_permalink( $post_id ), $img, $label ) : $img;
		}

		// 3) Default image
		if ( $use_default && $default_src ) {
			$img   = $this->img_tag( $default_src, $width, $height, $classes, get_the_title( $post_id ), '' );
			$label = sprintf( __( 'View: %s', 'excerpt-thumbnail' ), get_the_title( $post_id ) );
			return $with_link ? $this->wrap_link( get_permalink( $post_id ), $img, $label ) : $img;
		}

		return '';
	}

	/**
	 * Extract the first <img> from HTML and rebuild it with our sizing/classes.
	 * Falls back on provided $fallback_alt when no alt is present.
	 *
	 * @since 1.0.0
	 * @param string $content
	 * @param int    $width
	 * @param int    $height
	 * @param string $classes
	 * @param string $fallback_alt
	 * @return string
	 */
	private function first_image_from_content( $content, $width, $height, $classes, $fallback_alt = '' ) {
		if ( '' === trim( $content ) ) {
			return '';
		}

		if ( function_exists( 'libxml_use_internal_errors' ) ) {
			libxml_use_internal_errors( true );
		}

		$doc    = new DOMDocument();
		$loaded = $doc->loadHTML( '<?xml encoding="utf-8" ?>' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
		if ( ! $loaded ) {
			return '';
		}

		$imgs = $doc->getElementsByTagName( 'img' );
		if ( 0 === $imgs->length ) {
			return '';
		}

		$node  = $imgs->item( 0 );
		$src   = $node->getAttribute( 'src' );
		if ( ! $src ) {
			return '';
		}
		$alt   = $node->getAttribute( 'alt' );
		$title = $node->getAttribute( 'title' );

		if ( '' === $alt && '' !== $fallback_alt ) {
			$alt = $fallback_alt;
		}

		return $this->img_tag( $src, $width, $height, $classes, $alt, $title );
	}

	/**
	 * Create an <img> tag with optional width/height and classes.
	 * Adds loading="lazy" to non-featured images we build manually.
	 *
	 * @since 1.0.0
	 * @param string $src
	 * @param int    $width
	 * @param int    $height
	 * @param string $classes
	 * @param string $alt
	 * @param string $title
	 * @return string
	 */
	private function img_tag( $src, $width, $height, $classes, $alt = '', $title = '' ) {
		$attrs   = [];
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
		// Performance hint.
		$attrs[] = 'loading="lazy"';

		return '<img ' . implode( ' ', $attrs ) . ' />';
	}

	/**
	 * Wrap content in a permalink <a>.
	 *
	 * @since 1.0.0
	 * @param string $url
	 * @param string $html
	 * @param string $aria_label Optional accessible label.
	 * @return string
	 */
	private function wrap_link( $url, $html, $aria_label = '' ) {
		$label_attr = $aria_label ? ' aria-label="' . esc_attr( $aria_label ) . '"' : '';
		return '<a href="' . esc_url( $url ) . '"' . $label_attr . '>' . $html . '</a>';
	}

	/**
	 * Category-exclusion check (applies on category archives).
	 *
	 * @since 1.0.0
	 * @param int    $post_id
	 * @param string $exclusion_csv
	 * @return bool
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
	 * Feed align transform (legacy).
	 *
	 * @since 1.0.0
	 * @param string $image_html
	 * @return string
	 */
	private function feed_align_transform( $image_html ) {
		if ( false !== strpos( $image_html, 'aligncenter' ) ) {
			return '<p align="center">' . $image_html . '</p>';
		}
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
	 * Sanitize alignment to left|right|center.
	 *
	 * @since 1.0.0
	 * @param mixed $value
	 * @return string
	 */
	private function sanitize_align( $value ) {
		$value   = is_string( $value ) ? strtolower( $value ) : '';
		$allowed = [ 'left', 'right', 'center' ];
		return in_array( $value, $allowed, true ) ? $value : 'left';
	}

	/**
	 * yes/no → bool helper.
	 *
	 * @since 1.0.0
	 * @param mixed $value
	 * @return bool
	 */
	private function yesno( $value ) {
		return ( 'yes' === $value );
	}

	// ======================================================================
	// og:image
	// ======================================================================

	/**
	 * Output <meta property="og:image"> on single posts (if enabled).
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function maybe_add_og_image() {
		if ( is_admin() || ! is_single() ) {
			return;
		}
		if ( ! $this->yesno( $this->opt( 'excerpt_thumbnail_add_og_image', 'tfe_add_og_image', 'yes' ) ) ) {
			return;
		}

		global $post;
		if ( ! $post instanceof WP_Post ) {
			return;
		}

		$src = $this->resolve_image_src( $post->ID );
		if ( ! $src ) {
			return;
		}
		echo '<meta property="og:image" content="' . esc_url( $src ) . "\" />\n";
	}

	/**
	 * Resolve image URL via Featured → first content image → default URL.
	 *
	 * @since 1.0.0
	 * @param int $post_id
	 * @return string
	 */
	private function resolve_image_src( $post_id ) {
		if ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( $post_id ) ) {
			$url = get_the_post_thumbnail_url( $post_id, 'full' );
			if ( $url ) {
				return $url;
			}
		}
		$content = (string) get_post_field( 'post_content', $post_id );
		$src     = $this->first_image_src_from_content( $content );
		if ( $src ) {
			return $src;
		}
		if ( $this->yesno( $this->opt( 'excerpt_thumbnail_default_image', 'tfe_default_image', 'yes' ) ) ) {
			$fallback = (string) $this->opt( 'excerpt_thumbnail_default_image_src', 'tfe_default_image_src', '' );
			if ( $fallback ) {
				return esc_url_raw( $fallback );
			}
		}
		return '';
	}

	/**
	 * Extract "src" of first content image.
	 *
	 * @since 1.0.0
	 * @param string $content
	 * @return string
	 */
	private function first_image_src_from_content( $content ) {
		if ( '' === trim( $content ) ) {
			return '';
		}
		if ( function_exists( 'libxml_use_internal_errors' ) ) {
			libxml_use_internal_errors( true );
		}
		$doc    = new DOMDocument();
		$loaded = $doc->loadHTML( '<?xml encoding="utf-8" ?>' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
		if ( ! $loaded ) {
			return '';
		}
		$imgs = $doc->getElementsByTagName( 'img' );
		if ( 0 === $imgs->length ) {
			return '';
		}
		$src = $imgs->item( 0 )->getAttribute( 'src' );
		return $src ? esc_url_raw( $src ) : '';
	}
}
