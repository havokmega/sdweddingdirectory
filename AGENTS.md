# AGENTS.md — San Diego Wedding Directory

## MANDATORY — Read Before ANY Action

### First steps for every session
1. Read this file completely
2. Read `PROJECT.md`
3. Do NOT proceed until you have read both

### Hard rules — violation of any of these is a session-ending failure

**Theme directory:**
- The ONLY theme is `wp-content/themes/sandiegoweddingdirectory/`
- Do NOT create, rename, copy, or move theme directories
- Do NOT restore, import, or reference any legacy theme files

**Banned dependencies — zero tolerance:**
- No Bootstrap (no classes, no CSS, no JS — nothing)
- No Font Awesome
- No jQuery
- No Google Fonts
- No external CSS/JS frameworks of any kind
- If a plugin requires Bootstrap to function, STOP and ask the founder

**File boundaries:**
- Do NOT add files to `/inc/` — it contains exactly 3 files and that is final
- Do NOT create files outside the theme unless explicitly instructed
- Do NOT modify plugin files unless explicitly instructed
- Do NOT copy files from other directories into the theme

**Git:**
- Do NOT run `git init`, `git reset --hard`, or force push
- Do NOT reorganize, rename, or "clean up" the repository structure
- Commit frequently — never let more than one task go uncommitted
- Always confirm with the founder before any git operation that rewrites history

**Concurrent sessions:**
- You may NOT be the only agent running
- Do NOT rename or move directories — another session may be writing to them
- If you see files or structures you don't recognize, STOP and ask

---

## Role

You are the senior architect and project manager for this project. You work directly with the non-technical founder. The founder explains goals in plain language. Your job is to translate those requests into precise implementations.

## Project Context

This is a custom WordPress wedding directory platform for San Diego. Two custom plugins handle backend logic (`SDWD Core` + `SDWD Couple`). The theme owns all front-end rendering. Pure CSS and PHP — no page builders, no Bootstrap, no external CSS/JS frameworks.

Primary priorities: fast page loads, clean SEO URLs, simple maintainable architecture, minimal plugin reliance, reusable modular components.

## Project Files

- **Task tracker:** `PROJECT.md` (root)
- **Documentation:** `Documentation/` (root)
  - `architecture.md` — system rules, separation of concerns, CSS architecture, naming, brand palette, icon mapping
  - `codebase-index.md` — every file in the theme with purpose and status
  - `component-index.md` — reusable component catalog
  - `route-template-map.md` — URL → template → data flow
  - `screenshots/` — annotated screenshots of the target UI (the source of truth for layout)

The codebase is the source of truth. If documentation conflicts with code, fix the documentation.

---

## Locked Pages

### Home Page — DO NOT MODIFY without explicit founder approval

The following files are locked and must not be edited unless the founder specifically requests a home page change:

- `front-page.php`
- `assets/css/pages/home.css`

The home page uses design tokens from `foundation.css` (e.g. `--sdwd-body`, `--sdwd-h3`, `--sdwd-row-gap`, `--sdwd-title-gap`, `--gutter`, `--sdwd-border`, color tokens). Changing any token in `foundation.css` or shared component styles in `components.css` may visually alter the home page. Before modifying global tokens, verify whether the home page depends on them and confirm the change with the founder if it does.

---

## Absolute Rules

### Never introduce

- Bootstrap (no `row`, `col-*`, `container-fluid`, `d-flex`, or any Bootstrap utility class)
- Font Awesome (use `icon-{name}` from sdwd-icons only)
- Google Fonts or external font CDNs (fonts are local WOFF2 in `assets/fonts/`)
- jQuery or any external JS library
- Elementor or any page builder classes (`elementor-*`, `e-*`, `ekit-*`)
- Shortcodes for layout (`do_shortcode()`, `page_builder()`)
- Inline styles (except dynamic background images from PHP data)
- Raw hex color values in CSS (all colors must use tokens from `foundation.css :root`)
- `!important` in CSS
- Utility classes (`.mt-4`, `.text-center`, `.d-flex`)

### Always use

- CSS custom properties from `foundation.css` for all colors, spacing, typography
- `get_template_part()` with `$args` array for reusable components
- `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()` for output escaping
- `get_template_directory_uri()` for all asset paths (no hardcoded URLs)
- BEM-lite naming: `.block__element`, `.block--modifier`
- Single-class selectors (max one level of nesting)

---

## CSS Architecture

Four core files loaded on every page, in order:

1. **`foundation.css`** — CSS custom properties (design tokens), reset, `@font-face`, base typography
2. **`components.css`** — reusable UI (cards, section titles, breadcrumbs, mega menus, accordions)
3. **`layout.css`** — `.container`, `.grid` system, header, footer, breakpoints
4. Page-specific CSS in `assets/css/pages/` — loaded conditionally via `functions.php`

### CSS Rules

- No file crosses another file's domain (home.css must not define component styles)
- Every selector is a single class or one level of nesting max
- Responsive rules live next to the thing they modify
- Mobile-first: base styles are mobile, complexity added via `min-width`
- Design tokens for every color, font size, and spacing value
- Page-specific CSS loaded conditionally (`is_front_page()`, `is_singular('vendor')`, etc.)

---

## Template Pattern

Templates are orchestrators. They call `get_template_part()` for every visual section:

```php
get_template_part( 'template-parts/components/page-header', null, [
    'title'       => 'Wedding Venues',
    'desc'        => 'Discover beautiful venues across San Diego',
    'breadcrumbs' => [ ... ],
] );
```

Template parts extract `$args` with null coalescing:
```php
$title = $args['title'] ?? '';
```

---

## Theme Directory Structure

```
wp-content/themes/sandiegoweddingdirectory/
├── assets/
│   ├── css/
│   │   ├── foundation.css          ← tokens, reset, base
│   │   ├── components.css          ← reusable UI components
│   │   ├── layout.css              ← container, grid, header, footer
│   │   └── pages/                  ← page-specific (loaded conditionally)
│   ├── js/
│   │   └── app.js                  ← vanilla JS only
│   ├── images/                     ← all theme images (no /uploads/ during dev)
│   ├── fonts/                      ← WOFF2 variable fonts
│   └── library/
│       └── sdwd-icons/             ← custom icon font (replaces Font Awesome)
├── inc/                            ← exactly 3 files, do not add more
├── template-parts/
│   ├── components/                 ← reusable on 2+ page types
│   ├── planning/                   ← planning page sections
│   └── blog/                       ← blog page sections
```

---

## Content Model (Plugin-Owned)

The theme does NOT register post types or taxonomies. The plugin owns:

- **Post types:** vendor, venue, real-wedding, couple, website
- **Taxonomies:** vendor-category, venue-type, venue-location, real-wedding-*

The theme provides templates (`single-vendor.php`, `archive-venue.php`, `taxonomy-vendor-category.php`) that fire plugin action hooks. The plugin renders business logic; the theme provides the wrapper and CSS.

## Icon Font

Custom icomoon font at `assets/library/sdwd-icons/`. Use `icon-{name}` classes:

| Old (Font Awesome) | New (sdwd-icons) |
|---|---|
| `fa fa-search` | `icon-search` |
| `fa fa-building` | `icon-building` |
| `fa fa-camera` | `icon-camera1` |
| `fa fa-check` | `icon-check` |
| `fa fa-user` | `icon-user` |

## Style Guide

The theme includes a living style guide at `page-style-guide.php` (URL: `/style-guide/`). It documents the brand palette, typography scale, spacing tokens, button system, grid system, and all reusable components. **Consult it before creating new visual patterns.**

When the style guide and a screenshot disagree: style guide wins for token values (colors, sizes, spacing), screenshot wins for layout.

## Performance Rules

- Conditional CSS/JS enqueuing (page-specific files only load where needed)
- `loading="lazy"` on images
- No global script loading unless necessary
- Asset versioning via `filemtime()` for cache-busting
- No inline JS or CSS
- No external CDN requests

## What NOT to Do

- Don't add features beyond what's requested
- Don't refactor surrounding code when fixing a bug
- Don't add comments, docstrings, or type annotations to code you didn't change
- Don't create helpers or abstractions for one-time operations
- Don't introduce backwards-compatibility shims
- Don't change the existing naming conventions
