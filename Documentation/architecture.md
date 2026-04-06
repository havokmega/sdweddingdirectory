# Architecture — SDWeddingDirectory

This document defines the rules that govern how the theme is structured. Every file, every class name, every CSS rule must follow these rules. No exceptions.

---

## Separation of Responsibilities

### Theme owns

- All HTML markup (every `<tag>` rendered on the frontend)
- All CSS (every visual rule)
- All JS for frontend interactivity (carousels, toggles, sticky elements)
- Template hierarchy (`front-page.php`, `single-*.php`, `archive-*.php`, `page-*.php`)
- Template parts (`template-parts/components/`, `template-parts/planning/`)
- Header and footer (`header.php`, `footer.php`)
- Navigation menus (registration, walker class)
- Asset loading (`functions.php` enqueues only)
- Image references via `get_template_directory_uri()`

### Plugins own

- Custom post types (vendor, venue, real-wedding, couple, website)
- Taxonomies (vendor-category, venue-type, venue-location, real-wedding-*)
- Meta fields and custom data
- Business logic (dashboards, registration, quote requests, reviews, availability)
- Admin settings and configuration
- Data queries (the plugin may run WP_Query or get_terms — the theme may also query data for display)

### The boundary

The plugin may call `get_header()`, `get_footer()`, and `get_template_part()` into the theme. The plugin fires action hooks (e.g., `sdweddingdirectory/vendor/detail-page`) that the plugin itself handles — the theme provides the wrapper templates that fire those hooks.

**The plugin must NOT output layout HTML, grid wrappers, or section containers.** If plugin code currently renders markup (e.g., vendor/venue detail pages), that markup is the plugin's responsibility during this rebuild — the theme provides CSS to style it. In future phases, markup may migrate from plugin to theme.

---

## Rendering Rules

### Allowed to output markup

| File type | Example | May output HTML? |
|-----------|---------|-----------------|
| Page templates | `front-page.php`, `single-vendor.php` | Yes |
| Template parts | `template-parts/components/vendor-card.php` | Yes |
| `header.php` / `footer.php` | — | Yes |
| `functions.php` | — | **No** (setup and enqueues only) |
| `inc/*.php` | `sd-mega-navwalker.php` | Yes (walker output only) |

### Forbidden

- **No shortcodes for layout.** Do not call `do_shortcode()`, `page_builder()`, or any shortcode rendering method to produce section markup.
- **No plugin HTML for layout.** Plugin files should not contain `<section>`, `<div class="container">`, or grid wrappers. (Exception: plugin detail page handlers that currently own their markup — these are accepted during the rebuild and will be migrated later.)
- **No Elementor class names.** No `elementor-*`, `e-*`, or `ekit-*` classes anywhere.
- **No Bootstrap classes.** No `row`, `col-*`, `container-fluid`, `d-flex`, or any Bootstrap utility class.
- **No inline styles.** All styling goes in CSS files. No `style=""` attributes except for dynamic values (e.g., background images set from PHP data).
- **No `!important`.** Ever. Fix specificity instead.

---

## Template Hierarchy

WordPress selects the template file for each URL. The theme uses these:

### Page-level templates (orchestrators)

| Template | URL | Purpose |
|----------|-----|---------|
| `front-page.php` | `/` | Homepage — assembles all homepage sections |
| `page-venues.php` | `/venues/` | Venues landing — fires plugin action |
| `page-wedding-planning.php` | `/wedding-planning/` | Planning hub — calls planning template parts |
| `page-inspiration.php` | `/wedding-inspiration/` | Inspiration archive |
| `page-about.php` | `/about/` | About / team |
| `page-contact.php` | `/contact/` | Contact |
| `page-faqs.php` | `/faqs/` | FAQ page with tabbed accordions |
| `page-policy.php` | `/privacy-policy/`, `/terms-of-use/` | Policy pages |
| `page-our-team.php` | `/our-team/` | Team grid |
| `page-style-guide.php` | `/style-guide/` | Design system reference |
| `page.php` | Any page without a named template | Default page |

### Archive templates

| Template | URL pattern | Purpose |
|----------|------------|---------|
| `archive-vendor.php` | `/vendors/` | Vendor listing |
| `archive-venue.php` | `/venues/` (archive context) | Venue listing |
| `archive-real-wedding.php` | `/real-weddings/` | Real wedding listing |
| `archive-couple.php` | Couple archive | Couple listing |
| `archive-website.php` | Website archive | Wedding website listing |
| `archive.php` | `/blog/`, category archives | Blog archive fallback |
| `category.php` | `/category/*` | Category archive (special planning subcategory handling) |

### Taxonomy templates

| Template | Taxonomy | Purpose |
|----------|----------|---------|
| `taxonomy-vendor-category.php` | `vendor-category` | Vendor category with advanced filters |
| `taxonomy-venue-type.php` | `venue-type` | Venue type archive |
| `taxonomy-venue-location.php` | `venue-location` | Venue location archive |
| `taxonomy-real-wedding-location.php` | `real-wedding-location` | Parent/child term logic |
| `taxonomy-real-wedding-category.php` | `real-wedding-category` | Standard grid |
| `taxonomy-real-wedding-color.php` | `real-wedding-color` | Standard grid |
| `taxonomy-real-wedding-community.php` | `real-wedding-community` | Standard grid |
| `taxonomy-real-wedding-season.php` | `real-wedding-season` | Standard grid |
| `taxonomy-real-wedding-space-preferance.php` | `real-wedding-space-preferance` | Standard grid |
| `taxonomy-real-wedding-style.php` | `real-wedding-style` | Standard grid |
| `taxonomy-real-wedding-tag.php` | `real-wedding-tag` | Standard grid |

### Single templates

| Template | Post type | Purpose |
|----------|-----------|---------|
| `single-vendor.php` | `vendor` | Thin wrapper — fires plugin action |
| `single-venue.php` | `venue` | Thin wrapper — fires plugin action |
| `single-real-wedding.php` | `real-wedding` | Thin wrapper — fires plugin action |
| `single-couple.php` | `couple` | Thin wrapper — fires plugin action |
| `single-website.php` | `website` | Thin wrapper — plugin handler may not exist |
| `single-team.php` | `team` | Team member detail |
| `single-changelog.php` | `changelog` | Changelog detail |
| `single.php` | `post` (blog) | Blog post detail |

### Other templates

| Template | Purpose |
|----------|---------|
| `404.php` | Not found page |
| `search.php` | Search results |
| `searchform.php` | Search form markup |
| `sidebar.php` | Sidebar template |

### Dashboard templates (user-template/)

| Template | Purpose |
|----------|---------|
| `user-template/vendor-dashboard.php` | Wrapper for vendor/venue dashboard |
| `user-template/couple-dashboard.php` | Wrapper for couple dashboard |
| `user-template/front-page.php` | Homepage (if WP uses this path) |

---

## Template Parts System

Template parts live in `template-parts/` and are called via `get_template_part()`.

### Three categories

**Components** (`template-parts/components/`)
Reusable UI elements used on 2+ page types. Each accepts arguments via `$args` parameter or global variables. Components are the anti-duplication system.

Examples: `vendor-card.php`, `section-title.php`, `faq-accordion.php`, `inline-link-grid.php`

**Sections** (`template-parts/planning/`, `template-parts/blog/`)
Page-specific sections that are too large to inline but only used on one page type. Grouped by page.

Examples: `planning/planning-hero.php`, `planning/planning-checklist.php`

**Standalone** (`template-parts/`)
One-off template parts that don't fit neatly into components or sections.

Examples: `why-use-sdwd.php` (used by plugin on vendor + venue detail pages), `policy-subnav.php`

### Naming rules

- Component files: noun describing the component (`vendor-card.php`, `pagination.php`)
- Section files: `{page}-{section}.php` (`planning-hero.php`, `planning-faq.php`)
- No generic names (`part-1.php`, `section.php`, `block.php`)

---

## CSS Architecture

Four CSS files, loaded in this order. No overlap between domains.

| File | Domain | Purpose |
|------|--------|---------|
| `assets/css/foundation.css` | Tokens + base | CSS custom properties, reset, `@font-face`, base typography (`body`, `h1`–`h6`, `a`, `p`), button base styles |
| `assets/css/components.css` | Reusable UI | Cards, section titles, breadcrumbs, badges, FAQ accordion, feature blocks, icon card rows, inline link grids, pagination, mega menu panels, search forms |
| `assets/css/layout.css` | Layout + chrome | `.container`, `.grid` classes, section spacing, header layout, footer layout, sidebar layout, responsive breakpoints |
| `assets/css/pages/*.css` | Page-specific | Styles that apply to ONE page type only. `home.css`, `archive.css`, `profile.css`, `planning.css`, `blog.css`, etc. |

### CSS rules

- **No file imports from another file's domain.** `home.css` must not define component styles. `components.css` must not define layout.
- **Every selector is a single class or one level of nesting max.** `.card__title` is OK. `.home-vendors .card .card__title` is not.
- **Responsive rules live next to the thing they modify.** Do not collect all media queries at the bottom.
- **No `!important`.** If you need it, the architecture is wrong.
- **Mobile-first.** Base styles are mobile. Complexity added via `min-width` breakpoints.
- **Design tokens for every value.** Colors, font sizes, spacing, border radius — all come from CSS custom properties in `foundation.css`.
- **Tokens must resolve to the value you need.** Do not use `calc()` to halve, double, or otherwise transform a token. If you need a different value, create a new token at the correct size. The only acceptable use of `calc()` with tokens is combining two different tokens (e.g., `calc(var(--a) + var(--b))`).

### Conditional loading

Page-specific CSS files are enqueued conditionally in `functions.php`:

```php
if ( is_front_page() ) {
    wp_enqueue_style( 'sdwd-home', ... );
}
```

Foundation, components, and layout load on every page.

---

## Naming Conventions (BEM-lite)

### Sections

Page-level sections use `{page}-{section}` naming:

```
.hero                    /* homepage hero */
.home-vendors            /* homepage vendor cards */
.home-realweddings       /* homepage real weddings */
.planning-hero           /* planning page hero */
.venues-landing          /* venues landing page */
```

### BEM elements

```
.hero__title             /* h1 inside hero */
.hero__subtitle          /* p below h1 */
.hero__search            /* search form wrapper */
.card__image             /* image inside card */
.card__body              /* text content area */
.card__title             /* heading inside card */
```

### Modifiers

```
.btn--primary            /* primary button variant */
.btn--outline            /* outline button variant */
.btn--small              /* small button */
.section-title--center   /* centered section title */
.grid--4col              /* 4-column grid */
```

### Rules

- One class per styled element
- Named by purpose, not appearance (`card__title`, not `card__bold-text`)
- No IDs for styling
- No element selectors for styling (no `section h2 { }` — use `.section-title__heading`)
- No utility classes (no `.mt-4`, `.text-center`, `.d-flex`)

---

## functions.php

### What goes in functions.php

- `after_setup_theme` — theme supports, image sizes, nav menus
- `wp_enqueue_scripts` — style and script enqueues (conditional where appropriate)
- `widgets_init` — widget area registration
- `require_once` for walker class and any helper includes in `inc/`

### What does NOT go in functions.php

- HTML markup output
- Ajax handlers (those belong in the plugin)
- Custom post type registration (plugin responsibility)
- Taxonomy registration (plugin responsibility)
- Shortcode registration
- Admin-only code

---

## Asset Structure

```
assets/
├── css/
│   ├── foundation.css          ← tokens, reset, base, @font-face
│   ├── components.css          ← reusable UI component styles
│   ├── layout.css              ← container, grid, header, footer, breakpoints
│   └── pages/
│       ├── home.css            ← homepage-only
│       ├── archive.css         ← archive pages
│       ├── venues-landing.css  ← venues landing page
│       ├── profile.css         ← vendor/venue/real-wedding profiles
│       ├── planning.css        ← planning hub + child pages
│       ├── blog.css            ← blog single + archive
│       ├── static.css          ← about, contact, FAQs, policy, 404, search
│       └── dashboard.css       ← dashboard compatibility overrides
├── js/
│   └── app.js                  ← all frontend JS (carousels, toggles, sticky, gallery)
├── images/                     ← theme images (copied from old theme)
├── fonts/                      ← WOFF2 font files (Work Sans, Inter)
└── library/
    └── sdwd-icons/             ← custom icon font (icomoon) — replaces Font Awesome
        ├── style.css
        └── fonts/
```

### Font rules

- All typography uses locally hosted WOFF2 fonts
- No Google Fonts or external font services
- Fonts declared via `@font-face` in `foundation.css`
- Font files live in `assets/fonts/`

### Icon rules

- Single icon font: `sdwd-icons` (icomoon)
- Class format: `icon-{name}` (not `fa fa-{name}`)
- No Font Awesome. No theme-icon library.
- CSS pseudo-elements use `font-family: 'icomoon'` with sdwd-icons unicode values

### Image rules

- Theme images in `assets/images/`
- Referenced via `get_template_directory_uri() . '/assets/images/...'`
- No hardcoded absolute URLs
- User uploads go to `/uploads/` (post-launch only, not during development)

---

## Style Guide

The theme has a **living style guide** at `page-style-guide.php` (URL: `/style-guide/`). It documents the brand palette, typography scale, spacing tokens, button system, grid system, and all reusable components.

**Consult the style guide before creating new visual patterns.**

When the style guide and a screenshot disagree: style guide wins for token values (colors, font sizes, spacing), screenshot wins for layout/visual arrangement.

---

## Brand Palette

Refer to `/style-guide` for the live version. Key tokens:

| Token | Hex | Role |
|---|---|---|
| `--sdwd-accent` | `#2BCFCE` | Brand accent, links, identity |
| `--sdwd-accent-dark` | `#007f80` | Darker accent, hover states |
| `--sdwd-cta` | `#EC4D25` | CTA buttons, urgent actions |
| `--sdwd-dark` | `#1d2733` | Dark backgrounds, footer |
| `--sdwd-subtext` | `#2d2d2d` | Headings, strong body text |
| `--sdwd-body-text` | `#444444` | Default body text |
| `--sdwd-muted` | `#5f6d7b` | Secondary text, icons |
| `--sdwd-soft` | `#f3f5f7` | Soft backgrounds, fills |
| `--sdwd-border` | `#dce4ea` | Borders, dividers |
| `--sdwd-focus` | `#007BFF` | Focus outline for form fields |

---

## Typography

| Token | Value | Font |
|---|---|---|
| `--sdwd-h1` | `32px` | Inter, weight 800 |
| `--sdwd-h2` | `24px` | Inter, weight 800 |
| `--sdwd-h3` | `20px` | Inter, weight 700 |
| `--sdwd-h4` | `18px` | Inter, weight 700 |
| `--sdwd-h5` | `16px` | Inter, weight 700 |
| `--sdwd-h6` | `14px` | Inter, weight 700 |
| Body | `16px` | Work Sans, weight 400 |

All headings use Inter. Body text uses Work Sans.

---

## Hover & Interaction Rules

| Element | Hover Effect | Transition |
|---|---|---|
| Buttons (`.btn`) | `translateY(-1px)` lift | 0.2s ease |
| Primary button | Background darkens to `--sdwd-accent-dark` | 0.2s ease |
| Outline button | Fills with accent, text goes white | 0.2s ease |
| CTA button | Slight darken | 0.2s ease |
| Inline links | Color to `--sdwd-cta` (orange-red), underline appears | 0.2s ease |
| Breadcrumb links | Color to `--sdwd-accent` | Instant |
| Form inputs (focus) | Border darkens, background to white | 0.4s ease-in-out |
| Cards | Shadow deepens, `translateY(-2px)` lift | 0.2s ease |

## Button Style

- Border radius: `4px` (subtle rounded rectangle, NOT pill-shaped)
- Padding: `13px 30px`
- Font size: `15px`, weight `800`
- Small variant: `8px 18px`, `13px`

---

## Icon Class Mapping (old to new)

| Old Class (Font Awesome) | New Class (sdwd-icons) |
|---|---|
| `fa fa-search` | `icon-search` |
| `fa fa-building` | `icon-building` |
| `fa fa-camera` | `icon-camera1` |
| `fa fa-cutlery` | `icon-cutlery` |
| `fa fa-female` | `icon-female` |
| `fa fa-check` | `icon-check` |
| `fa fa-tags` | `icon-tags` |
| `fas fa-comments` | `icon-comments` |
| `fas fa-user-circle` | `icon-user-circle` |
| `fa fa-bookmark-o` | `icon-bookmark-o` |
| `fa fa-plus` | `icon-plus` |
| `fa fa-user` | `icon-user` |
| `fa fa-envelope-o` | `icon-envelope-o` |
| `fa fa-chevron-left` | `icon-chevron-left` |
| `fa fa-angle-down` | `icon-angle-down` |
| `fa fa-sliders` | `icon-sliders` |
| `fa fa-arrow-up` | `icon-arrow-up` |
| `fa fa-star` | `icon-star` |
| `fa fa-info-circle` | `icon-info-circle` |

### CSS Pseudo-Element Mapping

| Old (FA Unicode) | Icon | New (sdwd-icons Unicode) | New font-family |
|---|---|---|---|
| `\f078` (chevron-down) | Dropdown arrows | `\e94f` (angle-down) | `'icomoon'` |
| `\f054` (chevron-right) | Submenu/list arrows | `\e9a5` (chevron-left) | `'icomoon'` |
| `\f00c` (check) | List checkmarks | `\e9a1` (check) | `'icomoon'` |

**Important:** The icomoon `font-family` must only be set on `::before` pseudo-elements, never on the element itself.

---

## Header Behavior

The header hides on scroll down and shows on scroll up (via `app.js`). CSS class `header--hidden` applies `transform: translateY(-100%)` with a 0.3s transition. Three responsive states:

- **Full desktop (>1399px):** All 6 nav links + both CTA buttons
- **Narrow desktop (<=1399px):** 4 nav links (Real Weddings + Wedding Website hidden) + both CTA buttons
- **Mobile (<=1199px):** Hamburger left, centered logo, icon-only user button right

## Mega Menu Hover

Mega menu panels use a 120ms debounce on `mouseleave` to prevent the dropdown from closing when the cursor crosses the gap between the nav link and the dropdown panel. Handled in `app.js`.

## Planning Child Page Routing

Planning child pages are WordPress pages with parent ID 4180. `page.php` detects `wp_get_post_parent_id() === 4180` and loads `template-parts/planning/planning-child-page.php`. This orchestrator contains per-slug data arrays for all 6 children: wedding-checklist, wedding-seating-chart, vendor-manager, wedding-guest-list, wedding-budget, wedding-website.
