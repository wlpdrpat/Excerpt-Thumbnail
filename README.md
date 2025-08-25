# Excerpt Thumbnail

Safely adds a thumbnail to post excerpts on home, archive, and search views—rebuilt on the WordPress Plugin Boilerplate with modern security and opt-in “Modern Mode.”

- **Image priority:** Featured Image → first image in content → default image URL  
- **Contexts:** Home / Archives / Search (toggle each)  
- **Optional:** Add `<meta property="og:image">` on single posts  
- **Modern Mode (opt-in):** uses `the_excerpt` only (no content forcing) and a named image size `excerpt-thumbnail`

<br>

## Requirements

- WordPress **5.8+**
- PHP **7.4 – 8.3**  
- A theme that displays excerpts on archive contexts (or keep Legacy mode enabled to force excerpts)

<br>

## Installation

1. Upload the plugin folder to `/wp-content/plugins/excerpt-thumbnail/` or install the ZIP via **Plugins → Add New**.
2. Activate **Excerpt Thumbnail**.
3. Go to **Settings → Excerpt Thumbnail** and configure.

> For WordPress.org users: general plugin info and changelog live in `readme.txt`.

<br>

## Settings Overview

**Image sizing & layout**
- **Image Width (px)**: `tfe_width` — default **150**
- **Image Height (px)**: `tfe_height` — default **150**
- **Alignment**: `tfe_align` — **left | right | center**
- **Link Image to Post**: `tfe_withlink` — **yes/no** (default **yes**)

**Image source fallback**
- **Use Default Image**: `tfe_default_image` — **yes/no** (default **no**)
- **Default Image URL**: `tfe_default_image_src`

**Where to show**
- **Home / Blog Index**: `tfe_on_home` — **yes/no** (default **yes**)
- **Archives**: `tfe_on_archives` — **yes/no** (default **yes**)
- **Search Results**: `tfe_on_search` — **yes/no** (default **yes**)
- **Exclude Categories (CSV of IDs)**: `tfe_exclusion` — e.g., `2,7,15`  
  _(Legacy behavior: exclusions are enforced on **category archives**.)_

**SEO / Social**
- **Add Open Graph Image**: `tfe_add_og_image` — **yes/no** (default **yes**)  
  Outputs a single `<meta property="og:image" ...>` on **single posts** using the same image-selection logic. Disable if your SEO plugin already sets og:image.

**Mode**
- **Modern Mode (recommended)**: `tfe_modern_mode` — **yes/no** (default **no**)  
  - Only modifies `the_excerpt` (no forcing `the_content`)  
  - Registers image size `excerpt-thumbnail` and uses it for featured images

**Uninstall**
- **Remove Data on Uninstall**: `tfe_cleanup_on_uninstall` — **yes/no** (default **no**)  
  If enabled, plugin options are deleted when the plugin is removed.

<br>

## Behavior Details

- On **site views** (not feeds), the plugin prepends the chosen image to the excerpt for the enabled contexts.
- In **Legacy mode** (default), home/archive/search views show a **forced 55-word excerpt** with a translatable “Read More >>” link—matching the old plugin’s behavior.
- In **feeds**, alignment is converted to legacy feed-friendly attributes (`align="left|right"` or a centered `<p>` wrapper).
- Accessibility: alt text falls back to the post title when we reconstruct an `<img>` tag from content.

<br>

## Troubleshooting

- **No image appears:**  
  - Ensure the context toggle is enabled (e.g., Archives), and that the post has a Featured Image, a content image, or a Default Image is configured (and allowed).
- **Duplicate `og:image`:**  
  - Uncheck **Add Open Graph Image** here if your SEO plugin already outputs it.
- **Modern Mode shows different sizes:**  
  - Modern Mode uses the named size `excerpt-thumbnail`. If you change width/height, run a thumbnail regeneration plugin so existing attachments get the new size.

<br>

## Development

### Repo structure
