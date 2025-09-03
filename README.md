# Excerpt Thumbnail

Safely adds a featured image thumbnail to post excerpts on **home**, **archive**, and **search** views — rebuilt on the WordPress Plugin Boilerplate with modern security and an opt-in **Modern Mode**.

- **Image priority:** Featured Image → first image in content → default image URL  
- **Contexts:** Home / Archives / Search (toggle each)  
- **Optional:** Add `<meta property="og:image">` on single posts  
- **Modern Mode (recommended):** modifies `the_excerpt` only (no content forcing) and uses a named image size `excerpt-thumbnail`

---

## Requirements

- WordPress **5.8+**  
- PHP **7.4 – 8.3**  
- A theme that outputs excerpts on archive contexts (or keep Legacy mode enabled to force excerpts)

---

## Installation

1. Upload the plugin folder to `/wp-content/plugins/excerpt-thumbnail/` or install the ZIP via **Plugins → Add New**.
2. Activate **Excerpt Thumbnail**.
3. Go to **Settings → Excerpt Thumbnail** and configure.

> For WordPress.org users: listing information and changelog live in `readme.txt`.

---

## Settings Overview

> **Note:** Settings are stored with **new keys** (`excerpt_thumbnail_*`). If you have legacy keys (`tfe_*`) from an older install, the plugin automatically migrates them on activation/admin load.

**Image sizing & layout**
- **Image Width (px):** `excerpt_thumbnail_width` *(legacy: `tfe_width`)* — default **150**
- **Image Height (px):** `excerpt_thumbnail_height` *(legacy: `tfe_height`)* — default **150**
- **Alignment:** `excerpt_thumbnail_align` *(legacy: `tfe_align`)* — **left | right | center**
- **Link Image to Post:** `excerpt_thumbnail_withlink` *(legacy: `tfe_withlink`)* — **yes/no** (default **yes**)

**Image source fallback**
- **Use Default Image:** `excerpt_thumbnail_default_image` *(legacy: `tfe_default_image`)* — **yes/no** (default **no**)
- **Default Image URL:** `excerpt_thumbnail_default_image_src` *(legacy: `tfe_default_image_src`)*

**Where to show**
- **Home / Blog Index:** `excerpt_thumbnail_on_home` *(legacy: `tfe_on_home`)* — **yes/no** (default **yes**)
- **Archives:** `excerpt_thumbnail_on_archives` *(legacy: `tfe_on_archives`)* — **yes/no** (default **yes**)
- **Search Results:** `excerpt_thumbnail_on_search` *(legacy: `tfe_on_search`)* — **yes/no** (default **yes**)
- **Exclude Categories (CSV of IDs):** `excerpt_thumbnail_exclusion` *(legacy: `tfe_exclusion`)* — e.g., `2,7,15`  
  _Legacy behavior: exclusions are enforced on **category archives**._

**SEO / Social**
- **Add Open Graph Image:** `excerpt_thumbnail_add_og_image` *(legacy: `tfe_add_og_image`)* — **yes/no** (default **yes**)  
  Outputs `<meta property="og:image" ...>` on **single posts** using the same image-selection logic. Disable if your SEO plugin already sets `og:image`.

**Mode**
- **Modern Mode (recommended):** `excerpt_thumbnail_modern_mode` *(legacy: `tfe_modern_mode`)* — **yes/no** (default **no**)  
  - Only modifies `the_excerpt` (no forcing `the_content`)  
  - Registers image size `excerpt-thumbnail` and uses it for featured images

**Uninstall**
- **Remove Data on Uninstall:** `excerpt_thumbnail_cleanup_on_uninstall` *(legacy: `tfe_cleanup_on_uninstall`)* — **yes/no** (default **no**)  
  If enabled, plugin options are deleted when the plugin is removed.

---

## Behavior Details

- On **site views** (not feeds), the plugin prepends the chosen image to the excerpt for the enabled contexts.
- In **Legacy mode** (default), home/archive/search views show a forced **55-word** excerpt with a translatable “Read More >>” link — matching the original plugin’s behavior.
- In **feeds**, alignment is converted to feed-friendly attributes (`align="left|right"`) or a centered `<p>` wrapper.
- Accessibility: when rebuilding an `<img>` from content, alt text falls back to the post title.

---

## Troubleshooting

- **No image appears:**  
  Ensure the context toggle is enabled (e.g., Archives), and that the post has a Featured Image, a content image, or a Default Image is configured (and allowed).
- **Duplicate `og:image`:**  
  Uncheck **Add Open Graph Image** here if your SEO plugin already outputs it.
- **Modern Mode shows different sizes:**  
  Modern Mode uses the named size `excerpt-thumbnail`. If you change width/height, run a thumbnail regeneration plugin so existing attachments get the new size.

---

## Development

### Repo structure
```
excerpt-thumbnail/
  admin/
  includes/
  public/
  languages/
  excerpt-thumbnail.php
  uninstall.php
  readme.txt     (WordPress.org)
  README.md      (this file)
```

### Internationalization
- Text domain: `excerpt-thumbnail`  
- POT source: `languages/excerpt-thumbnail.pot`  
Generate/update with WP-CLI:
```bash
wp i18n make-pot . languages/excerpt-thumbnail.pot --domain=excerpt-thumbnail --exclude=node_modules,vendor,tests,assets
```

### Coding standards
We aim to follow WordPress Coding Standards (WPCS). Local linting (optional):

```bash
composer install
composer run lint
composer run lint:fix
```

---

## Build a release ZIP

1. Ensure the version in `excerpt-thumbnail.php` and `readme.txt` (Stable tag) is correct.
2. Add/verify a `.distignore` to exclude dev files (`.github/`, `node_modules/`, tests, etc.).
3. Build a clean archive:

**With WP-CLI (recommended):**
```bash
wp dist-archive . ../excerpt-thumbnail-1.0.0.zip
```

**Manual fallback:**
```bash
cd ..
zip -r excerpt-thumbnail-1.0.0.zip excerpt-thumbnail   -x "*/.git/*" "*/.github/*" "*/node_modules/*" "*/tests/*"      "*/vendor/bin/*" "*/composer.lock" "*/package*.json" "*/phpcs.xml*"      "*/.DS_Store" "*/.vscode/*" "*/.idea/*" "*/*.md" "*/*.yml" "*/*.yaml"
```

---

## Migration

If you previously used legacy `tfe_*` settings, the plugin:

- **Automatically migrates** `tfe_*` → `excerpt_thumbnail_*` on activation and on `admin_init` as a safety net.  
- **Does not overwrite** existing new keys; after copying, it **deletes** the legacy keys.

You can safely remove any old plugin; your settings carry over.

---

## Project history

- Legacy inspiration: **Thumbnail For Excerpts** by **@radukn** — https://wordpress.org/plugins/thumbnail-for-excerpts/  
  (Closed June 16, 2022 by WordPress.org for Guideline Violation.)
- Since **2016**, Patrick Coleman has independently maintained a compatible variant.
- This repository is a **clean redevelopment** using the WordPress Plugin Boilerplate, preserving expected legacy behavior while modernizing structure and security.

---

## Compatibility

- Tested with WordPress 6.6  
- Works with classic themes and most block themes that render excerpts on archive templates

---

## Roadmap (nice-to-haves)

- Filter to customize “Read More >>” text
- Small cache for first-image DOM parse
- Consolidate options into a single settings array (with backward compatibility)
- Add screenshots and a demo archive template snippet

---

## Contributing

Issues and PRs are welcome. Please:
- Keep PRs focused and small.
- Follow WordPress coding standards where practical.
- Include before/after notes or screenshots for UI changes.

---

## License

GPL-2.0-or-later. © Patrick Coleman and contributors.
