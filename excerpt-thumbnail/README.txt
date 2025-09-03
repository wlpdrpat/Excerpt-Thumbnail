=== Excerpt Thumbnail ===
Contributors: wlpdrpat
Donate link: https://wellplanet.com
Tags: excerpt, thumbnail, featured image, archive, search
Requires at least: 5.8
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Safely adds a featured image thumbnail to post excerpts on archive, search, and home views. Rebuilt using the WordPress Plugin Boilerplate.

== Description ==

The Excerpt Thumbnail plugin automatically adds a thumbnail image to post excerpts on your home page, archives, and search results.  

**Features:**
* Image priority: Featured Image → first image in content → default image URL.
* Context toggles for home/archives/search.
* Options for alignment and link-to-post behavior.
* Optional: Add `<meta property="og:image">` for better social sharing.
* **Modern Mode**: only modifies `the_excerpt` and uses a named image size.
* Built with the WordPress Plugin Boilerplate for maintainability and security.
* Lightweight — no tracking, no bloat.

== Project History ==

* Legacy inspiration: *Thumbnail For Excerpts* by @radukn — https://wordpress.org/plugins/thumbnail-for-excerpts/
* That plugin was closed on June 16, 2022 and is no longer available (Guideline Violation per WordPress.org).
* Since 2016, Patrick Coleman has independently developed and maintained a forked version based on that plugin.
* This plugin is a full redevelopment, rebuilt using the WordPress Plugin Boilerplate. Legacy behavior is preserved by default for continuity.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`.
2. Activate **Excerpt Thumbnail** from the Plugins menu in WordPress.
3. Go to **Settings → Excerpt Thumbnail** to configure options.

== Frequently Asked Questions ==

= Will this change single post pages? =
No. By default it only affects home, archive, and search (and adds an optional `og:image` meta tag). Single posts remain unchanged.

= How do I stop it from forcing excerpts? =
Enable **Modern Mode** in Settings. This ensures the plugin only modifies `the_excerpt`.

== Screenshots ==

1. Settings screen
2. Example archive output

== Changelog ==

= 1.0.0 =
* Initial release of the redeveloped plugin.
* Rebuilt on WordPress Plugin Boilerplate with full Settings API and uninstall cleanup.
* Added Modern Mode and optional `og:image` output.
* Preserved legacy behavior as default for parity with earlier versions.

== Upgrade Notice ==

= 1.0.0 =
Legacy behavior maintained by default. Use **Modern Mode** in Settings to restrict output to excerpts only.
