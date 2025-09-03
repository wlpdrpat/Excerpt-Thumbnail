# Excerpt Thumbnail

Safely adds a featured image thumbnail to post excerpts on archive, search, and home views. Built for modern WordPress with secure settings, internationalization, and graceful migration from legacy installs.

- **Requires WordPress:** 5.8+
- **Tested up to:** 6.6
- **Requires PHP:** 7.4+
- **License:** GPLv2 or later

---

## Features

- **Smart image selection:** Featured Image → first image in content → default image URL.
- **Per-context control:** Toggle for Home, Archives, and Search.
- **Layout options:** Alignment (left/right/center), link image to post, optional `<meta property="og:image">`.
- **Modern Mode (recommended):** Only modifies `the_excerpt` (no content forcing) and uses a named image size `excerpt-thumbnail`.
- **Lightweight & secure:** No tracking, no remote assets. Strict sanitize/escape. Clean uninstall (opt-in).

---

## Installation

1. Upload the `excerpt-thumbnail` folder to `/wp-content/plugins/` (or install from the Plugins screen once published).
2. Activate **Excerpt Thumbnail**.
3. Open **Settings → Excerpt Thumbnail** and configure.

> **Tip:** If you switch Modern Mode on and change width/height, use a thumbnail regeneration plugin so existing images get the new `excerpt-thumbnail` size.

---

## How it works

- **Where it applies:** Home, archive, and search templates; RSS also receives the image with legacy-compatible alignment.
- **Image priority:**  
  1) Featured Image  
  2) First `<img>` found in post content  
  3) Default image URL (if enabled)
- **Modern Mode:** Registers `add_image_size( 'excerpt-thumbnail', {W}, {H}, $crop )` and uses it for featured images. Disables legacy “force content → excerpt” behavior.

---

## Settings overview

- **Image Width / Height (px)** – used for manual `<img>` tags and the registered image size in Modern Mode.  
- **Alignment** – `left`, `right`, or `center`.  
- **Link Image to Post** – wraps the thumbnail in a permalink.  
- **Use Default Image** + **Default Image URL** – fallback if no featured/content image.  
- **Where to show** – Home, Archives, Search.  
- **Exclude Categories (CSV of IDs)** – skip output on certain category archives.  
- **Add Open Graph Image** – outputs `<meta property="og:image">` on single posts.  
- **Modern Mode (recommended)** – use `the_excerpt` only + named image size.  
- **Remove Data on Uninstall** – opt-in cleanup of settings on delete.

---

## Accessibility & performance

- Featured images use core markup; manually built images include `alt` where possible and `loading="lazy"` hints.
- The clickable image (when enabled) includes an accessible label like **“View: {Post Title}”**.

---

## Migration (legacy → new)

If you previously used a version that stored **`tfe_*`** options (or the legacy “Thumbnail For Excerpts” plugin), this plugin:

- **Automatically migrates** `tfe_*` options → `excerpt_thumbnail_*` on activation (and again on admin load as a safety net).  
- Only **copies values if the new keys aren’t already set**, then **deletes** the old `tfe_*` keys.  
- You can safely remove any old plugin; settings will carry over.

> Legacy note: “Thumbnail For Excerpts” by @radukn was closed on June 16, 2022 (WordPress.org). This project is a clean redevelopment preserving expected behavior.

---

## Internationalization

- Text domain: `excerpt-thumbnail`  
- POT file: `languages/excerpt-thumbnail.pot`

Contributions of translations are welcome via PRs or translation platforms.

---

## Contributing

- Follow WordPress Coding Standards (PHPCS).  
- Keep user-facing strings wrapped for i18n.  
- Avoid remote assets or tracking.  
- PRs welcome for docs, i18n, and feature refinements.

---

## Security

- Input sanitized; output escaped.  
- Capability checks on settings (`manage_options`).  
- Nonces for settings save actions.  
- **Uninstall:** Deletes options **only** if the user enables “Remove Data on Uninstall”.

---

## Changelog

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

### [Unreleased]
**Planned**
- Setting + filter to customize the “Read More” link text.
- Small cache layer for first-image DOM parsing.
- CI + expanded PHPCS/WPCS coverage.

---

### [1.0.0] — 2025-09-03
> Initial public release (clean redevelopment). Legacy behavior preserved by default.

**Added**
- Boilerplate structure (`includes/`, `admin/`, `public/`), loader, i18n, activation/deactivation.
- Settings page (Settings → Excerpt Thumbnail) with sanitization and caps checks.
- Optional Open Graph `og:image` output on single posts.
- **Modern Mode:** excerpt-only output + `excerpt-thumbnail` image size.
- Uninstall safety: removes options only if opted in.

**Changed**
- Safer image handling via core APIs; sanitize-on-save, escape-on-output; unified naming (`excerpt_thumbnail_*`).

**Fixed**
- Feed alignment behavior maintained for better reader compatibility.
- Category exclusion logic clarified for category archives.

---

## License

GPLv2 or later. See `LICENSE`.

