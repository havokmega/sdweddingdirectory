# Codebase Index

## Theme Bootstrap and Config
- `wp-content/themes/sdweddingdirectory/functions.php`
  - Main bootstrap, theme setup, widget registration, helper loading, rewrite/menu tweaks, profile AJAX handlers.
- `wp-content/themes/sdweddingdirectory/config/site-config.php`
  - Site constants for logo, map defaults, placeholders, footer copy, and 404 defaults.
- `wp-content/themes/sdweddingdirectory/inc/migrate-optiontree.php`
  - One-time OptionTree to `sdwd_*` option migration.

## Theme `inc/` Subsystems
- `wp-content/themes/sdweddingdirectory/inc/admin-settings.php`
  - Native Settings API replacement for old OptionTree theme settings.
- `wp-content/themes/sdweddingdirectory/inc/admin-pages.php`
  - Admin submenu pages and form loaders for adding couples/vendors and redirecting taxonomy management pages.
- `wp-content/themes/sdweddingdirectory/inc/page-builder/index.php`
  - Elementor compatibility bootstrap (safety guard; Elementor is no longer active).
- `wp-content/themes/sdweddingdirectory/inc/theme-scripts.php`
  - Global asset enqueue stack, profile-page asset loading, editor font/style helpers.
- `wp-content/themes/sdweddingdirectory/inc/gutenberg-support.php`
  - Gutenberg palette, font-size, inline-style, and editor-style support.
- `wp-content/themes/sdweddingdirectory/inc/theme-head.php`
  - `<head>` markup hook output.
- `wp-content/themes/sdweddingdirectory/inc/theme-body.php`
  - Body schema/body class hooks and dashboard-aware body-state logic.
- `wp-content/themes/sdweddingdirectory/inc/theme-header.php`
  - Header variants, brand/menu rendering, user button output, custom-header support.
- `wp-content/themes/sdweddingdirectory/inc/theme-footer.php`
  - Footer markup, widget/static fallback, back-to-top, quick links.
- `wp-content/themes/sdweddingdirectory/inc/page-header-banner.php`
  - Page-banner logic, archive-title cleanup, vendor/venue search hero, planning hero variants, breadcrumbs.
- `wp-content/themes/sdweddingdirectory/inc/template-helper.php`
  - Pagination helper plus option/logo convenience helpers.
- `wp-content/themes/sdweddingdirectory/inc/seo.php`
  - Canonical, robots, title/meta description, sitemap, and trailing-slash logic.
- `wp-content/themes/sdweddingdirectory/inc/structured-data.php`
  - JSON-LD output for organization, breadcrumbs, local business, and articles.
- `wp-content/themes/sdweddingdirectory/inc/comment-template-part.php`
  - Comment avatar/reply markup helpers.
- `wp-content/themes/sdweddingdirectory/inc/404-error-page.php`
  - Hook-driven 404 content blocks.
- `wp-content/themes/sdweddingdirectory/inc/grid-managmnet/index.php`
  - Grid/sidebar/container utility methods.
- `wp-content/themes/sdweddingdirectory/inc/grid-managmnet/container.php`
  - Main content container start/end hooks.
- `wp-content/themes/sdweddingdirectory/inc/grid-managmnet/sidebar-managment.php`
  - Sidebar wrapper/output behavior.
- `wp-content/themes/sdweddingdirectory/inc/bootstrap-menu.php`
  - Bootstrap nav walker dependency.
- `wp-content/themes/sdweddingdirectory/inc/sd-mega-navwalker.php`
  - Theme mega-menu walker.

## Root Theme Templates
- `wp-content/themes/sdweddingdirectory/header.php`
  - HTML shell, ASCII art easter egg comment, `sdweddingdirectory/head`, `sdweddingdirectory_body`, and `sdweddingdirectory/header` entry points.
- `wp-content/themes/sdweddingdirectory/footer.php`
  - `sdweddingdirectory/footer` entry point.
- `wp-content/themes/sdweddingdirectory/page.php`
  - Default page wrapper.
- `wp-content/themes/sdweddingdirectory/index.php`
  - Main blog index template. Breadcrumbs use shared `.sdwd-breadcrumb` class.
- `wp-content/themes/sdweddingdirectory/archive.php`
  - Generic archive loop.
- `wp-content/themes/sdweddingdirectory/search.php`
  - Search results loop.
- `wp-content/themes/sdweddingdirectory/single.php`
  - Standard blog single template. Breadcrumbs use shared `.sdwd-breadcrumb` class.
- `wp-content/themes/sdweddingdirectory/category.php`
  - Customized blog category archive.
- `wp-content/themes/sdweddingdirectory/404.php`
  - Hook-driven 404 page shell.

## Directory and CPT Templates
- `wp-content/themes/sdweddingdirectory/page-venues.php`
  - Venue search bridge into plugin action `sdweddingdirectory/find-venue`.
- `wp-content/themes/sdweddingdirectory/taxonomy-venue-type.php`
  - Venue-type taxonomy bridge into the venue search controller.
- `wp-content/themes/sdweddingdirectory/taxonomy-venue-location.php`
  - Venue-location term page bridge into plugin output.
- `wp-content/themes/sdweddingdirectory/taxonomy-vendor-category.php`
  - Theme-owned vendor category archive with sidebar filters and `WP_Query`. Uses `sdwd_get_term_field()` and `sdwd_get_term_repeater()` for term-level filter configuration (pricing, services, style, specialties).
- `wp-content/themes/sdweddingdirectory/archive-venue.php`
  - Venue archive cards using `apply_filters( 'sdweddingdirectory/venue/post', ... )`.
- `wp-content/themes/sdweddingdirectory/archive-vendor.php`
  - Vendor archive cards using `apply_filters( 'sdweddingdirectory/vendor/post', ... )`.
- `wp-content/themes/sdweddingdirectory/archive-real-wedding.php`
  - Real wedding archive bridge using plugin card filters.
- `wp-content/themes/sdweddingdirectory/single-venue.php`
  - Delegates venue detail output to plugin action `sdweddingdirectory/venue/detail-page`.
- `wp-content/themes/sdweddingdirectory/single-vendor.php`
  - Delegates vendor detail output to plugin action `sdweddingdirectory/vendor/detail-page`.
- `wp-content/themes/sdweddingdirectory/single-real-wedding.php`
  - Delegates real-wedding detail output to plugin action.
- `wp-content/themes/sdweddingdirectory/single-couple.php`
  - Delegates couple detail output to plugin action.
- `wp-content/themes/sdweddingdirectory/single-website.php`
  - Delegates website detail output to plugin action.
- `wp-content/themes/sdweddingdirectory/single-changelog.php`
  - Delegates changelog detail output to plugin action.

## Theme-Owned Marketing and Utility Pages
- `wp-content/themes/sdweddingdirectory/page-wedding-planning.php`
  - Wedding planning hub/child-page template pulling `template-parts/planning/*`. FAQ data is theme-owned PHP (Elementor data dependency removed).
- `wp-content/themes/sdweddingdirectory/page-inspiration.php`
  - Blog/inspiration listing page.
- `wp-content/themes/sdweddingdirectory/page-about.php`
  - Team listing driven by the plugin shortcode renderer.
- `wp-content/themes/sdweddingdirectory/page-our-team.php`
  - Dedicated team page using the plugin team card renderer.
- `wp-content/themes/sdweddingdirectory/page-contact.php`
  - Static contact page with email link (Contact Form 7 removed).
- `wp-content/themes/sdweddingdirectory/page-faqs.php`
  - Static FAQ content page.

## `user-template/`
- `wp-content/themes/sdweddingdirectory/user-template/front-page.php`
  - Home page orchestrator that loads `template-parts/home/*`.
- `wp-content/themes/sdweddingdirectory/user-template/couple-dashboard.php`
  - Couple dashboard wrapper that emits `sdweddingdirectory/dashboard`.
- `wp-content/themes/sdweddingdirectory/user-template/vendor-dashboard.php`
  - Vendor dashboard page template.
- `wp-content/themes/sdweddingdirectory/user-template/search-venue.php`
  - Alternate venue-search template bridge.
- `wp-content/themes/sdweddingdirectory/user-template/vendor-category.php`
  - Legacy/manual vendor category page template with custom filters/querying.
- `wp-content/themes/sdweddingdirectory/user-template/venue-type.php`
  - Manual venue-type landing/template page.
- `wp-content/themes/sdweddingdirectory/user-template/venue-location.php`
  - Manual venue-location landing/template page.
- `wp-content/themes/sdweddingdirectory/user-template/real-wedding-category.php`
  - Manual real-wedding category landing/template page.
- `wp-content/themes/sdweddingdirectory/user-template/real-wedding-location.php`
  - Manual real-wedding location landing/template page.
- `wp-content/themes/sdweddingdirectory/user-template/product-category.php`
  - Manual WooCommerce product category landing/template page.
- `wp-content/themes/sdweddingdirectory/user-template/request-missing-info.php`
  - Guest-list related plugin handoff page.
- `wp-content/themes/sdweddingdirectory/user-template/privacy-policy.php`
  - Static page wrapper.
- `wp-content/themes/sdweddingdirectory/user-template/terms-of-use.php`
  - Static page wrapper.
- `wp-content/themes/sdweddingdirectory/user-template/wedding-website.php`
  - Website-related page template.
- `wp-content/themes/sdweddingdirectory/user-template/compatibility-checker.php`
  - Compatibility checker plugin handoff template.

## `template-parts/`
- `wp-content/themes/sdweddingdirectory/template-parts/home/`
  - Home sections for hero search, vendors (DJ category receives featured/primary button treatment), planning, real weddings, inspiration, location browsing, category browsing, value columns, and city-based venue links.
- `wp-content/themes/sdweddingdirectory/template-parts/planning/`
  - Wedding planning sections: hero, intros, secondary-intro, checklist, vendor organizer, website, tool cards, detailed copy, FAQ accordion, and scroll CTA.
- `wp-content/themes/sdweddingdirectory/template-parts/blog.php`
  - Blog card rendering and empty-state hooks.
- `wp-content/themes/sdweddingdirectory/template-parts/content-helper.php`
  - Blog category/tag/link helpers.
- `wp-content/themes/sdweddingdirectory/template-parts/search/`
  - Search bar partials used by directory pages.
- `wp-content/themes/sdweddingdirectory/template-parts/why-use-sdwd.php`
  - Shared marketing section partial.

## Theme Admin Files
- `wp-content/themes/sdweddingdirectory/admin/forms/add-couple.php`
  - Admin-side add-couple form.
- `wp-content/themes/sdweddingdirectory/admin/forms/add-vendor.php`
  - Admin-side add-vendor form.
- `wp-content/themes/sdweddingdirectory/admin/js/create-couple.js`
  - Admin JS for couple creation flow.
- `wp-content/themes/sdweddingdirectory/admin/js/create-vendor.js`
  - Admin JS for vendor creation flow.

## Theme Assets
- `wp-content/themes/sdweddingdirectory/assets/css/sdwd-foundation.css`
  - Design system: `:root` tokens for colors, typography, spacing; `.sdwd-breadcrumb`, `.sdwd-btn-primary`, `.sdwd-btn-outline`, column helpers, responsive overrides. Enqueued before all other theme stylesheets.
- `wp-content/themes/sdweddingdirectory/assets/css/global.css`
- `wp-content/themes/sdweddingdirectory/assets/css/theme-style.css`
- `wp-content/themes/sdweddingdirectory/assets/css/local-fonts.css`
- `wp-content/themes/sdweddingdirectory/assets/css/sd-profile.css`
- `wp-content/themes/sdweddingdirectory/assets/css/editor-style.css`
- `wp-content/themes/sdweddingdirectory/assets/js/theme-script.js`
- `wp-content/themes/sdweddingdirectory/assets/js/hero-search-toggle.js`
- `wp-content/themes/sdweddingdirectory/assets/js/sd-profile-sidebar.js`
- `wp-content/themes/sdweddingdirectory/assets/js/sd-availability-calendar.js`
- `wp-content/themes/sdweddingdirectory/assets/images/`
- `wp-content/themes/sdweddingdirectory/assets/fonts/`

## Plugin Touchpoints
- `wp-content/plugins/sdweddingdirectory/sdweddingdirectory.php`
  - Core plugin loader. Requires `term-meta-helpers.php` first in the bootstrap chain.
- `wp-content/plugins/sdweddingdirectory/config-file/term-meta-helpers.php`
  - Defines `sdwd_get_term_field()`, `sdwd_get_term_repeater()`, and `sdwd_update_term_field()` — drop-in replacements for ACF's `get_field()` / `update_field()` on taxonomy terms. All front-end and dashboard code uses these.
- `wp-content/plugins/sdweddingdirectory/front-file/find-post-helper/search-venue/index.php`
  - Venue search controller used by `/venues/` and venue taxonomies.
- `wp-content/plugins/sdweddingdirectory/front-file/find-post-helper/venue-term-page/index.php`
  - Venue-location term-page renderer.
- `wp-content/plugins/sdweddingdirectory/front-file/singular-vendor/index.php`
  - Vendor detail-page renderer.
- `wp-content/plugins/sdweddingdirectory/venue/singular-venue/index.php`
  - Venue detail-page renderer.
- `wp-content/plugins/sdweddingdirectory/front-file/vendor-layout/index.php`
  - Vendor card renderer used by theme archives/taxonomies.
- `wp-content/plugins/sdweddingdirectory/venue/venue-layout/index.php`
  - Venue card renderer used by theme archives/search results.
- `wp-content/plugins/sdweddingdirectory/dashboard/index.php`
  - Dashboard shell/menu injection.
- `wp-content/plugins/sdweddingdirectory-couple/modules/`
  - Couple-tool module root for budget, website, guest list, request quote, reviews, RSVP, seating chart, todo list, and wishlist.
