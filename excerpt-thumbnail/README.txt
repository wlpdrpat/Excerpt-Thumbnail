=== Excerpt Thumbnail ===
Contributors: wlpdrpat
Donate link: https://wellplanet.com
Tags: excerpt, thumbnail, featured image, archive, search, blog
Requires at least: 5.8
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Safely adds a featured image thumbnail to post excerpts on archive, search, and home views. Rebuilt using the WordPress Plugin Boilerplate.


== Description ==
- Image priority: Featured Image → first image in content → default image URL.
- Context toggles (home/archives/search), link-to-post, alignment.
- Optional: Add `<meta property="og:image">` on single posts.
- **Modern Mode**: only modifies `the_excerpt` and uses a named image size.

== Project History ==
- Legacy inspiration: Thumbnail For Excerpts by @radukn — https://wordpress.org/plugins/thumbnail-for-excerpts/
- That plugin was closed on June 16, 2022 and is not available for download (Guideline Violation per WordPress.org).
- Since 2016, Patrick Coleman has independently developed and maintained a version based on that plugin.
- This plugin (3.x) is a redevelopment using the WordPress Plugin Boilerplate; legacy behavior is preserved by default.

== Installation ==
1. Upload the plugin folder to `/wp-content/plugins/`.
2. Activate **Excerpt Thumbnail**.
3. Go to **Settings → Excerpt Thumbnail** and configure.

== Frequently Asked Questions ==
= Will this change single post pages? =
No. By default it only affects home, archive, and search (and RSS image markup). Single posts are unchanged except the optional `og:image` meta tag.

= How do I stop it from forcing excerpts? =
Enable **Modern Mode** in Settings. That uses only `the_excerpt`.

== Screenshots ==
1. Settings screen
2. Example archive output

== Changelog ==
= 3.0.0-dev =
- Rebuilt on boilerplate, Settings API, and uninstall cleanup.
- Added Modern Mode and optional `og:image` output.
- Preserved legacy behavior as default for parity.

== Upgrade Notice ==
= 3.0.0-dev =
Legacy behavior maintained by default. Flip **Modern Mode** in Settings to use excerpt-only output.
