# Codebase Index — SDWeddingDirectory

Every file in the theme, its purpose, and status.

**Status key:** `complete` = built and verified | `active` = exists, needs work | `planned` = does not exist yet | `stub` = file exists but is a minimal placeholder

Last updated: 2026-04-07

---

## Root Files

| File | Purpose | Status |
|------|---------|--------|
| `style.css` | Theme metadata only (name, version). No actual styles. | complete |
| `functions.php` | Theme setup, enqueues, nav menus, widget areas, image sizes. Includes walker. | active |
| `header.php` | Header markup: logo, nav menu with mega menu walker, CTA buttons, mobile layout. | active |
| `footer.php` | Footer markup: multi-column links, social icons, copyright. | active |
| `index.php` | Minimal WordPress fallback template. Required by WP. | complete |
| `CLAUDE.md` | AI coding rules, locked pages, absolute constraints. | active |
| `PROJECT.md` | Task tracker — single source of truth for what's done and remaining. | active |

---

## Documentation

| File | Purpose | Status |
|------|---------|--------|
| `Documentation/README.md` | Pointer file explaining what each doc does. | active |
| `Documentation/architecture.md` | System rules, CSS architecture, naming, brand palette, typography, icons, interactions. | active |
| `Documentation/codebase-index.md` | This file. | active |
| `Documentation/component-index.md` | Reusable component catalog with inputs, usage, styling. | active |
| `Documentation/route-template-map.md` | URL → template → data flow mapping. | active |

---

## Page Templates (Orchestrators)

| File | Purpose | Status |
|------|---------|--------|
| `front-page.php` | Homepage. Assembles all homepage sections. LOCKED — do not modify without founder approval. | complete |
| `page-wedding-planning.php` | Planning hub. 9 sections via template parts. | complete |
| `page.php` | Default page. Routes planning children to `planning-child-page.php` (parent ID 4180), others get `the_content()`. | complete |
| `page-style-guide.php` | Living design system reference at `/style-guide/`. | complete |
| `page-venues.php` | Venues landing. Fires plugin action for content. | stub |
| `page-inspiration.php` | Inspiration/blog archive. | stub |
| `page-about.php` | About page. | stub |
| `page-contact.php` | Contact page. | stub |
| `page-faqs.php` | FAQ page with tabbed accordion. | stub |
| `page-policy.php` | Shared policy template (privacy, terms, CA privacy). | stub |
| `page-our-team.php` | Team member grid page. | stub |

---

## Archive Templates

| File | Purpose | Status |
|------|---------|--------|
| `archive-venue.php` | Venue card grid + pagination. | stub |
| `archive-real-wedding.php` | Real wedding card grid + pagination. | stub |
| `archive-couple.php` | Couple card grid + pagination. | stub |
| `archive-website.php` | Wedding website card grid + pagination. | stub |
| `archive.php` | Blog archive fallback. | stub |
| `category.php` | Category archive with planning subcategory handling. | stub |

---

## Taxonomy Templates

| File | Taxonomy | Status |
|------|----------|--------|
| `taxonomy-venue-type.php` | `venue-type` | stub |
| `taxonomy-venue-location.php` | `venue-location` | stub |
| `taxonomy-real-wedding-location.php` | `real-wedding-location` (parent/child logic) | stub |
| `taxonomy-real-wedding-category.php` | `real-wedding-category` | stub |
| `taxonomy-real-wedding-color.php` | `real-wedding-color` | stub |
| `taxonomy-real-wedding-community.php` | `real-wedding-community` | stub |
| `taxonomy-real-wedding-season.php` | `real-wedding-season` | stub |
| `taxonomy-real-wedding-space-preferance.php` | `real-wedding-space-preferance` | stub |
| `taxonomy-real-wedding-style.php` | `real-wedding-style` | stub |
| `taxonomy-real-wedding-tag.php` | `real-wedding-tag` | stub |

Note: `taxonomy-vendor-category.php` does not exist yet (planned, needs advanced filter sidebar).

---

## Single Templates

| File | Post Type | Status |
|------|-----------|--------|
| `single-vendor.php` | `vendor` | active — full profile template using `sdwd-core` meta fields |
| `single-venue.php` | `venue` | active — parallel to vendor, adds location/map/capacity |
| `single-real-wedding.php` | `real-wedding` | stub |
| `single-couple.php` | `couple` | stub |
| `single-website.php` | `website` | stub |
| `single-team.php` | `team` | stub |
| `single-changelog.php` | `changelog` | stub |
| `single.php` | `post` (blog) | stub |

---

## Other Templates

| File | Purpose | Status |
|------|---------|--------|
| `404.php` | Not found page. | stub |
| `search.php` | Search results. | stub |
| `searchform.php` | Search form markup. | stub |
| `sidebar.php` | Sidebar template. | stub |

---

## Dashboard Templates (user-template/)

| File | Purpose | Status |
|------|---------|--------|
| `user-template/vendor-dashboard.php` | Frontend vendor profile editor. AJAX save via `sdwd_save_dashboard`. | active |
| `user-template/venue-dashboard.php` | Frontend venue profile editor. Same as vendor + location/capacity. | active |
| `user-template/couple-dashboard.php` | Frontend couple profile editor. Contact, wedding date, social. | active |
| `user-template/front-page.php` | Homepage (if WP routes through user-template). | stub |

---

## Template Parts — Components

Reusable UI elements used on 2+ page types.

| File | Purpose | Status |
|------|---------|--------|
| `template-parts/components/section-title.php` | Heading + optional subtext, centered or left-aligned. | complete |
| `template-parts/components/inline-link-grid.php` | Multi-column text link grid. | complete |
| `template-parts/components/vendor-card.php` | Vendor card (image, name, category, link). | complete |
| `template-parts/components/venue-card.php` | Venue card (image, name, type, location, link). | complete |
| `template-parts/components/real-wedding-card.php` | Real wedding card (image, couple names, date, link). | complete |
| `template-parts/components/post-card.php` | Blog/article card (image, category, title, link). | complete |
| `template-parts/components/pagination.php` | Numbered page navigation. | complete |
| `template-parts/components/feature-block.php` | Alternating text+CTA / image split section. | complete |
| `template-parts/components/tool-card-row.php` | Row of icon + heading + subtext + CTA cards. | complete |
| `template-parts/components/faq-section.php` | FAQ accordion section with heading, description, items array. | complete |
| `template-parts/components/faq-accordion.php` | Standalone expand/collapse accordion. | stub |
| `template-parts/components/page-header.php` | Archive/taxonomy page header banner. | stub |
| `template-parts/components/breadcrumbs.php` | Breadcrumb navigation trail. | stub |
| `template-parts/components/venue-popout.php` | Venue pop-out display section. | stub |
| `template-parts/components/policy-subnav.php` | Horizontal nav for FAQs + policy pages. | stub |
| `template-parts/components/contact-details.php` | 3-column icon contact grid. | stub |

---

## Template Parts — Planning Sections

| File | Purpose | Status |
|------|---------|--------|
| `template-parts/planning/planning-hero.php` | Hero with 2-step registration form, floating labels, inline validation, random bg images. | complete |
| `template-parts/planning/planning-intro.php` | Intro heading + subtext. | complete |
| `template-parts/planning/planning-checklist.php` | Checklist feature (uses `feature-block`). | complete |
| `template-parts/planning/planning-wedding-website.php` | Website builder feature (uses `feature-block`). | complete |
| `template-parts/planning/planning-secondary-intro.php` | Bridge section. | complete |
| `template-parts/planning/planning-tool-cards.php` | 3-card tool row (uses `tool-card-row`). | complete |
| `template-parts/planning/planning-detailed-copy.php` | Long-form content. | complete |
| `template-parts/planning/planning-faq.php` | FAQ section (uses `faq-section` component). | complete |
| `template-parts/planning/planning-child-page.php` | Full-width orchestrator for 6 planning child pages with per-slug data arrays. | complete |

Note: `planning-vendor-organizer.php` does not exist as a separate file — vendor organizer feature is handled within the child page orchestrator.

---

## Template Parts — Venues

| File | Purpose | Status |
|------|---------|--------|
| `template-parts/venues/listing.php` | Venue listing section. | stub |

---

## Template Parts — Blog

| File | Purpose | Status |
|------|---------|--------|
| `template-parts/blog/blog-grid-5.php` | 5-column blog grid layout. | stub |
| `template-parts/blog/blog-list-with-sidebar.php` | Blog list with sidebar. | stub |

---

## Template Parts — Standalone

| File | Purpose | Status |
|------|---------|--------|
| `template-parts/why-use-sdwd.php` | "Why use SDWeddingDirectory" value prop. Called by plugin on vendor + venue detail pages. | stub |

---

## Includes (inc/)

| File | Purpose | Status |
|------|---------|--------|
| `inc/sd-mega-navwalker.php` | Custom Nav Walker class for mega menu HTML generation. | active |
| `inc/venues.php` | Venue-related helper functions. | stub |

---

## Stylesheets

| File | Domain | Status |
|------|--------|--------|
| `assets/css/foundation.css` | Tokens, reset, base typography, `@font-face`. | active |
| `assets/css/components.css` | Reusable UI component styles (mega menus, buttons, cards, section titles, breadcrumbs, FAQ accordion). | active |
| `assets/css/layout.css` | Container, grid, header, footer, breakpoints. | active |
| `assets/css/pages/home.css` | Homepage-only section styles. LOCKED — do not modify without founder approval. | complete |
| `assets/css/pages/planning.css` | Planning hub + child page styles. | active |
| `assets/css/pages/venues.css` | Venues page styles. | stub |
| `assets/css/pages/venues-landing.css` | Venues landing pop-out sections, carousel. | stub |
| `assets/css/pages/archive.css` | Archive grid and filter styles. | stub |
| `assets/css/pages/profile.css` | Vendor/venue/real-wedding profile styles (photo collage, sticky nav, 2-col layout, contact card, pricing, reviews, map). | active |
| `assets/css/pages/blog.css` | Blog single + archive styles. | stub |
| `assets/css/pages/static.css` | About, contact, FAQs, policy, 404, search. | stub |
| `assets/css/pages/dashboard.css` | Frontend dashboard form styles. | active |
| `assets/css/pages/modals.css` | Login/registration/forgot-password modal styles. | active |

---

## Scripts

| File | Purpose | Status |
|------|---------|--------|
| `assets/js/app.js` | All frontend JS: mobile nav toggle, header hide-on-scroll, mega menu hover debounce, carousels. | active |
| `assets/js/modals.js` | Login/registration/forgot-password modals: open/close/switch, AJAX form submission, vendor/venue toggle. | active |
| `assets/js/dashboard.js` | Frontend dashboard AJAX save, social row add/remove. | active |

---

## Static Assets

| Directory | Contents | Status |
|-----------|----------|--------|
| `assets/images/` | Theme images (banners, category icons, hero backgrounds, logo, placeholders). | active |
| `assets/fonts/` | WOFF2 font files (Work Sans variable, Inter variable). | complete |
| `assets/library/sdwd-icons/` | Custom icon font (icomoon) — replaces Font Awesome. | complete |
| `screenshots/` | Annotated screenshots for visual reference. | active |

---

## Template Parts — Modals

| File | Purpose | Status |
|------|---------|--------|
| `template-parts/modals/couple-login.php` | Couple login modal (split layout, photo + form). | active |
| `template-parts/modals/couple-registration.php` | Couple registration modal. | active |
| `template-parts/modals/vendor-login.php` | Vendor/venue login modal. | active |
| `template-parts/modals/vendor-registration.php` | Vendor/venue registration modal with role toggle. | active |
| `template-parts/modals/forgot-password.php` | Forgot password modal. | active |

---

## Plugin — sdwd-core

Core backend plugin. Located at `plugins/sdwd-core/`.

| File | Purpose | Status |
|------|---------|--------|
| `sdwd-core.php` | Plugin entry point, activation hooks, file loading, admin asset enqueue. | active |
| `includes/roles.php` | Registers 3 user roles: couple, vendor, venue. | active |
| `includes/post-types.php` | Registers CPTs: couple (private), vendor, venue (public). | active |
| `includes/taxonomies.php` | Registers taxonomies: vendor-category, venue-type, venue-location. | active |
| `includes/auth.php` | AJAX handlers for login, registration, forgot password. Blocks wp-admin for frontend roles. Hides admin bar. | active |
| `includes/user-post-link.php` | Auto-creates CPT post on user registration, links via `post_author` + `sdwd_post_id`. | active |
| `includes/dashboard.php` | AJAX handler for frontend dashboard profile saves. | active |
| `includes/claim.php` | Claim system: submit/approve/reject claims on unclaimed profiles. | active |
| `includes/admin/couple-meta.php` | Admin meta boxes for couple posts (contact, wedding date, social). | active |
| `includes/admin/vendor-meta.php` | Admin meta boxes for vendor posts (business info, social, hours, pricing). | active |
| `includes/admin/venue-meta.php` | Admin meta boxes for venue posts (vendor fields + location, capacity). | active |
| `assets/admin.css` | Admin meta box styles. | active |
| `assets/admin.js` | Admin repeatable field JS (social rows, pricing tiers). | active |

---

## Plugin — sdwd-couple

Couple feature plugin. Located at `plugins/sdwd-couple/`.

| File | Purpose | Status |
|------|---------|--------|
| `sdwd-couple.php` | Plugin entry point. Loads couple modules when `sdwd-core` is active. | active |
| `modules/reviews.php` | Couple-facing review features. | active |
| `modules/request-quote.php` | Couple quote-request features. | active |
| `modules/wishlist.php` | Couple wishlist features. | active |
| `modules/checklist.php` | Couple checklist features. | active |
| `modules/budget.php` | Couple budget features. | active |
