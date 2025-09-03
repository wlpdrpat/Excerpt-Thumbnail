# Changelog
All notable changes to this project will be documented in this file.

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/)
and adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added
- (Planned) Setting + filter to customize the “Read More” link text.
- (Planned) Small cache layer for first-image DOM parsing.
- (Planned) CI + expanded WordPress Coding Standards coverage.

---

## [1.0.0] — 2025-09-03
> Initial public release of the clean redevelopment. Legacy behavior preserved by default.  
> **Note:** Version numbering reset from internal `3.0.0-dev` to `1.0.0` for first WordPress.org submission.

### Added
- Boilerplate structure (`includes/`, `admin/`, `public/`), loader, i18n, activation/deactivation classes.
- Settings page (**Settings → Excerpt Thumbnail**) using the Settings API with sanitization and capability checks.
- Optional Open Graph `<meta property="og:image">` output on single posts.
- **Modern Mode** (recommended): excerpt-only output (no content forcing) and named image size `excerpt-thumbnail`.
- Uninstall safety: removes options only when “Remove Data on Uninstall” is enabled.
- Legacy-to-new settings migration: automatically copies `tfe_*` options to `excerpt_thumbnail_*` (without overwriting existing new keys) and deletes the old keys.

### Changed
- Safer image handling via core APIs (`has_post_thumbnail()`, `get_the_post_thumbnail[_url]()`, `get_post_field()`).
- Sanitize-on-save and escape-on-output throughout settings/admin and public rendering.
- Unified naming to `excerpt_thumbnail_*` option keys and `Excerpt_Thumbnail_*` class prefix.

### Fixed
- Feed alignment parity (adds legacy-compatible alignment attributes/wrappers for RSS readers).
- Category-exclusion logic clarified and applied on category archives.

### Security
- Strict sanitizers: `absint`, URL via `esc_url_raw`, “yes/no” whitelists, alignment whitelist, CSV ID normalization.
- Capability checks (`manage_options`) and nonces for settings.
- `ABSPATH` guards across files.

### Migration notes
- **Settings migration:** `tfe_*` → `excerpt_thumbnail_*` runs on activation and again on `admin_init` as a safety net.  
  New keys are only populated if unset; legacy keys are deleted after successful copy.
- **Defaults:** Legacy behavior (content-forcing on archive contexts) remains the default; enable **Modern Mode** to switch to excerpt-only output.
- **Thumbnails:** After changing width/height in Modern Mode, use a thumbnail regeneration plugin so existing attachments get the new `excerpt-thumbnail` size.
- **SEO:** If your SEO plugin already outputs `og:image`, disable **Add Open Graph Image** here to avoid duplicates.

---

## [2.x] — Pre-rebuild (legacy)
- Historical plugin providing thumbnails on home/archive/search with priority:
  Featured Image → first image in content → default image URL.
- Options stored as individual `tfe_*` keys; legacy admin page and forcing of excerpts on archive contexts.

### Historical note
- Legacy inspiration: **“Thumbnail For Excerpts”** by **@radukn** (WordPress.org).  
  That plugin was closed on **June 16, 2022** for **Guideline Violation** (per WordPress.org).  
- Since **2016**, this codebase was independently maintained and later rebuilt as a clean, modern implementation.
