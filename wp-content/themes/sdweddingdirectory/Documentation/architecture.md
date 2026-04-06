# SDWeddingDirectory Architecture (Current Code)

## Runtime Split
- Theme: `wp-content/themes/sdweddingdirectory`
  - Owns the site shell, global assets (including the `sdwd-foundation.css` design system), header/footer/body hooks, page banners, SEO/structured data, native admin settings, and all public-facing templates.
- Core plugin: `wp-content/plugins/sdweddingdirectory`
  - Owns CPT/taxonomy registration, venue/vendor search controllers, singular vendor and venue detail rendering, dashboard shell output, shortcode rendering, AJAX/filter helpers, and the `term-meta-helpers.php` abstraction layer that replaced ACF.
- Couple plugin: `wp-content/plugins/sdweddingdirectory-couple`
  - Owns the couple tool modules such as budget, guest list, website, request quote, reviews, RSVP, seating chart, todo list, and wishlist.

## Content Model
- Key custom post types:
  - `venue`
  - `vendor`
  - `couple`
  - `real-wedding`
  - `website`
  - `venue-review`
  - `venue-request`
  - `claim-venue`
  - `team`
  - `testimonial`
- Key taxonomies:
  - Venues: `venue-type`, `venue-location`
  - Vendors: `vendor-category`
  - Websites: `website-category`, `website-location`
  - Real weddings: `real-wedding-category`, `real-wedding-location`, `real-wedding-tag`, `real-wedding-space-preferance`, `real-wedding-color`, `real-wedding-community`, `real-wedding-season`, `real-wedding-style`

## Theme Bootstrap
- `wp-content/themes/sdweddingdirectory/functions.php`
  - Bootstraps `SDWeddingDirectory`, defines theme constants, registers menus/widget areas/image sizes, and loads the theme subsystems from `config/`, `inc/`, `template-parts/`, and `admin/`.
- `wp-content/themes/sdweddingdirectory/config/site-config.php`
  - Centralizes site constants for logo path, map defaults, placeholders, footer copy, and 404 defaults.
- `wp-content/themes/sdweddingdirectory/inc/migrate-optiontree.php`
  - Performs one-time migration from serialized OptionTree data into native `sdwd_*` options.
- `wp-content/themes/sdweddingdirectory/inc/admin-settings.php`
  - Replaces most legacy OptionTree usage with native Settings API pages.
- `wp-content/themes/sdweddingdirectory/inc/page-builder/index.php`
  - Contains Elementor compatibility/bootstrap behavior. Elementor itself has been removed as an active plugin; this file remains as a safety guard.

## Plugin Data Layer
- `wp-content/plugins/sdweddingdirectory/config-file/term-meta-helpers.php`
  - Provides `sdwd_get_term_field()`, `sdwd_get_term_repeater()`, and `sdwd_update_term_field()` as drop-in replacements for ACF's `get_field()` and `update_field()` on taxonomy terms. All front-end and dashboard code uses these helpers; ACF has been fully removed.
  - Loaded first in the plugin bootstrap (before `config-file/index.php`) so all downstream code can call these helpers.

## Design System
- `assets/css/sdwd-foundation.css`
  - Single source of truth for spacing tokens, typography scale, color variables, breadcrumb styling (`.sdwd-breadcrumb`), button patterns (`.sdwd-btn-primary`, `.sdwd-btn-outline`), and column layout helpers.
  - Enqueued before `global.css` and `theme-style.css` so its `:root` custom properties cascade into all other stylesheets.
  - Responsive overrides tighten spacing at 991px, 767px, and 575px breakpoints.
- All breadcrumbs across `single.php`, `index.php`, and `page-header-banner.php` use the shared `.sdwd-breadcrumb` class rather than inline styles.

## Rendering Model
- `header.php` and `footer.php` are thin shells around custom theme actions:
  - `sdweddingdirectory/head`
  - `sdweddingdirectory_body`
  - `sdweddingdirectory/header`
  - `sdweddingdirectory/footer`
- The actual markup and behavior for those hooks live in:
  - `inc/theme-head.php`
  - `inc/theme-body.php`
  - `inc/theme-header.php`
  - `inc/theme-footer.php`
  - `inc/page-header-banner.php`
- Most archive/content wrappers use:
  - `sdweddingdirectory_main_container`
  - `sdweddingdirectory_main_container_end`
- Grid, container, and sidebar behavior comes from `inc/grid-managmnet/`.
- Blog cards, post metadata, pagination, and empty states come from the theme helper layer in:
  - `template-parts/blog.php`
  - `template-parts/content-helper.php`
  - `inc/template-helper.php`

## Page Ownership
- Theme-owned public pages:
  - Home: `user-template/front-page.php` plus `template-parts/home/*` (DJ category receives featured/primary button treatment)
  - Wedding planning hub and child pages: `page-wedding-planning.php` plus `template-parts/planning/*` (FAQ data is theme-owned PHP; Elementor data dependency removed)
  - Vendor category taxonomy archive: `taxonomy-vendor-category.php` (uses `sdwd_get_term_field()` / `sdwd_get_term_repeater()` for term-level filter configuration)
  - Blog/inspiration/category/search/FAQ/team/about/contact templates under the theme root
  - Contact page is static HTML with email link; Contact Form 7 has been removed
  - 404 page and static page shell behavior
- Theme-to-plugin handoff pages:
  - `/venues/` search: `page-venues.php` and `user-template/search-venue.php` call `do_action( 'sdweddingdirectory/find-venue' )`
  - Venue type and venue location taxonomies bridge into plugin controllers
  - Single vendor: `single-vendor.php` calls `do_action( 'sdweddingdirectory/vendor/detail-page' )`
  - Single venue: `single-venue.php` calls `do_action( 'sdweddingdirectory/venue/detail-page' )`
  - Single couple, real wedding, website, and changelog pages still rely on plugin actions/render helpers
  - Couple dashboard: `user-template/couple-dashboard.php` calls `do_action( 'sdweddingdirectory/dashboard' )`
- Legacy/manual page templates still exist under `user-template/` for vendor, venue, real-wedding, and product-category landing pages.

## Routing and URL Rules
- Theme-level rewrite adjustments in `functions.php` force:
  - `venue-type` -> `/venue-types/{slug}/`
  - `venue-location` -> `/locations/{slug}/`
  - `vendor-category` -> `/vendors/{slug}/`
- Special route overrides:
  - The `vendor-category` term `venues` redirects to `/venues/`
  - The `team` CPT archive is disabled
  - Standard posts and categories live under the `/wedding-inspiration/` namespace
- Planning parent/child pages disable `wpautop` so custom HTML sections render untouched.

## Frontend Interaction Layer
- `assets/js/hero-search-toggle.js`
  - Conditionally loaded on the home page and the `/vendors` and `/venues` entry pages.
- Singular profile pages load additional theme assets from `inc/theme-scripts.php`:
  - `assets/css/sd-profile.css`
  - `assets/js/sd-profile-sidebar.js`
  - `assets/js/sd-availability-calendar.js`
- Theme AJAX handlers in `functions.php` manage:
  - saved profile toggle
  - hired profile toggle
  - signed-in profile inquiry form submission and message logging
- Availability payloads are still served by plugin AJAX endpoints; the theme provides the UI and request wiring.
- `header.php` includes an ASCII art HTML comment (SAN DIEGO / WEDDING / DIRECTORY) visible in View Source.

## Admin and Settings Layer
- The theme now adds native admin pages for:
  - Site Style
  - Couple Tools
  - Real Wedding Settings
  - Venue Settings
  - Email Settings
- The theme also adds custom admin submenu/form flows for:
  - Add Couple
  - Add Vendor
  - Vendor Category redirect
  - Venue Type redirect
  - Venue Location redirect

## SEO and Metadata
- `inc/seo.php`
  - Handles canonical tags, robots/meta description output, custom title parts, trailing-slash enforcement, sitemap exclusions, and `robots.txt`.
- `inc/structured-data.php`
  - Outputs Organization, BreadcrumbList, LocalBusiness, and Article JSON-LD.
- `inc/page-header-banner.php`
  - Owns archive-title normalization, the vendor/venue search hero banner, and planning-page hero variants.

## Taxonomy Architecture
- `venue-location` taxonomy is flat — all cities are top-level terms (`parent = 0`). The original 4-level hierarchy (Country → State → Region → City) was removed because the site is San Diego–only. Dropdowns, carousels, AJAX handlers, and venue saves all use flat queries (`'parent' => 0`).
- `venue-type` and `vendor-category` taxonomies remain flat as originally designed.
- Term-level configuration (filter widgets, labels, icons, images, pricing ranges, etc.) is stored in `wp_termmeta` and read via `sdwd_get_term_field()` / `sdwd_get_term_repeater()`. There is currently no admin UI for editing these values — the data was originally managed through ACF's term-edit screens.

## Removed Plugin Dependencies
- **ACF (Advanced Custom Fields)**: Fully removed. All `get_field()` calls replaced with `sdwd_get_term_field()` / `sdwd_get_term_repeater()` helpers that read the same `wp_termmeta` rows. A few legacy `get_field()` references remain in migration scripts and dead admin code (`old-acf.php`, `tax-meta-switcher`, `meta-box`, `migration/1.4.8`) but are not called at runtime.
- **Contact Form 7**: Fully removed. Contact page uses static HTML.
- **Nextend Social Login**: Fully removed. Social login buttons, copy, and entry points stripped from auth flows. Login/registration is email/password only.
- **Elementor**: Removed as an active plugin. The theme's `inc/page-builder/index.php` compatibility file remains as a guard. No templates depend on `_elementor_data` storage.
- **OptionTree**: Removed as an active plugin. Theme settings migrated to native `sdwd_*` options via `inc/migrate-optiontree.php` and `inc/admin-settings.php`.

## Current Architectural Notes
- New theme settings should use native `sdwd_*` options rather than `ot_get_option()`.
- Vendor/venue detail rendering and venue search controllers are still primarily plugin-driven.
- Several legacy names/typos remain in active APIs, including `sdsdweddingdirectoryectory_*` and `grid-managmnet`; treat them as live code, not dead references.
- Single profile pages depend on both theme assets and plugin markup/actions. Changes to vendor/venue profiles usually require checking both layers.
