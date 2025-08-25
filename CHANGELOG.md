# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added
- (Planned) Setting + filter to customize the “Read More” link text.
- (Planned) Small cache layer for first-image DOM parsing.
- (Planned) Consolidate scattered `tfe_*` options into a single array option with backward-compat shim.

### Changed
- (Planned) Expand WordPress Coding Standards coverage and re-enable CI once org settings allow.

---

## [3.0.0-dev] — 2025-08-25
> Major internal rebuild on the WordPress Plugin Boilerplate. **Legacy behavior is preserved by default.**

### Added
- **Boilerplate core**: `includes/`, `admin/`, `public/`, loader, i18n scaffold, activator/deactivator classes.
- **Settings page (Settings → Excerpt Thumbnail)** using the Settings API with sanitization and capability checks.
- **Open Graph (`og:image`) output** on single posts, controlled by `tfe_add_og_image` (default **on**).
- **Modern Mode** (opt-in via `tfe_modern_mode`): uses `the_excerpt` only (no content forcing) and registers image size `excerpt-thumbnail`.
- **Uninstall safety**: `uninstall.php` that deletes options only when `tfe_cleanup_on_uninstall` is enabled.
- **Documentation**: WordPress `readme.txt` and GitHub `README.md`.
- **Tooling (local)**: `composer.json` with PHPCS/WPCS/PHPCompatibility dev deps and a `phpcs.xml.dist` ruleset (CI setup deferred).

### Changed
- **Safer image selection**: core APIs (`has_post_thumbnail`, `get_the_post_thumbnail[_url]`, `get_post_field`) replace any direct DB access.
- **Sanitize-on-save / escape-on-output** throughout admin UI and public rendering.
- **Consistent naming**: unified slug `excerpt-thumbnail` and class prefix `Excerpt_Thumbnail_*`.

### Fixed
- Feed alignment now uses legacy-compatible attributes (e.g., `align="left|right"` or centered `<p>`), improving reader compatibility.
- Category exclusion logic clarified to match legacy scope (category archives).

### Deprecated
- None.

### Removed
- No functional removals; legacy behavior remains the default.

### Security
- Added strict sanitizers: `absint`, URL whitelisting via `esc_url_raw`, yes/no whitelists, alignment whitelist, CSV ID normalization.
- Capability checks (`manage_options`) and nonces for settings operations.

### Migration notes
- **Settings**: existing `tfe_*` option keys are preserved. New options:
  - `tfe_add_og_image` (yes/no) — controls `<meta property="og:image">` on single posts.
  - `tfe_modern_mode` (yes/no) — excerpt-only mode + named image size.
  - `tfe_cleanup_on_uninstall` (yes/no) — opt-in data removal when the plugin is uninstalled.
- **Defaults**: Legacy behavior remains the default (content-forcing on archive contexts). Enable **Modern Mode** to switch to excerpt-only output.
- **Thumbnails**: After changing width/height in Modern Mode, run a thumbnail regeneration plugin so existing attachments get the new `excerpt-thumbnail` size.
- **SEO**: If your SEO plugin already outputs `og:image`, uncheck **Add Open Graph Image** to avoid duplicates.

### Historical note
- The legacy plugin inspiration was **“Thumbnail For Excerpts”** by **@radukn** on WordPress.org: <https://wordpress.org/plugins/thumbnail-for-excerpts/>.  
  That plugin was **closed on June 16, 2022** and is not available for download due to **Guideline Violation** (per WordPress.org).  
- Since **2016**, Patrick Coleman has independently developed and maintained a version based on that legacy plugin.  
- **This 3.x line** is a clean **redevelopment** using the modern WordPress Plugin Boilerplate, with security and maintainability improvements while preserving expected behavior.

---

## [2.x] — Pre-rebuild (legacy)
- Historical plugin providing thumbnails on home/archive/search with the following priority:
  Featured Image → first image in content → default image URL.
- Options stored as individual `tfe_*` keys; legacy admin page and forcing of excerpts on archive contexts.
- **Historical note:** 2.x lineage was based on the WordPress.org plugin “Thumbnail For Excerpts” (by @radukn). After that plugin’s closure on **June 16, 2022** for **Guideline Violation**, this codebase continued as an independently maintained fork until being rebuilt for 3.x.
