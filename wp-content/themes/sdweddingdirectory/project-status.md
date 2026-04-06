# Project Status — SD Wedding Directory

Last updated: 2026-03-16

## Purpose

Single source of truth for remaining work before launch. Architecture, code structure, and historical notes live in `Documentation/architecture.md`, `Documentation/codebase-index.md`, and `Documentation/function-index.md`. Historical task logs live in `Documentation/TASK_LOG.md` and `Documentation/CLAIM_SLUG_PHASE_LOG.md`.

## Guardrails

- Do not modify the hero/search responsive behavior, structure, breakpoints, or related CSS unless a task explicitly targets that area.
- If a layout task touches shared CSS, verify it does not regress the hero/search sections.
- Dashboards remain plugin-driven for now.
- Social login is out of scope for launch.
- Site goal: attract couples with free tools and vendor discovery, featuring the DJ business prominently on high-intent pages.
- During development, all static images live in the theme (`/assets/images/`), not in `/uploads/`.

## What's Done

These phases are complete and don't need revisiting unless a regression is found.

- **Phase 2 — UI Foundation**: Design system deployed via `sdwd-foundation.css`. Shared breadcrumb class, button patterns, spacing tokens, typography scale, mobile pass.
- **Phase 3 — Public Template Ownership**: All public pages use correct theme templates. Elementor data dependency removed. Stale template assignments fixed.
- **Phase 5 — ACF Replacement**: All front-end and dashboard `get_field()` calls replaced with `sdwd_get_term_field()`/`sdwd_get_term_repeater()`. Location taxonomy flattened. ACF plugin removed from disk.
- **Plugin Consolidation**: Plugins reduced from 30+ to 2 custom plugins (`sdweddingdirectory` core + `sdweddingdirectory-couple`). Venue, claim-venue, real-wedding, and shortcodes merged into core. Couple tools consolidated. Elementor deactivated. WooCommerce removed. Payment gateways removed. Invoice/pricing plugins removed.
- **Contact Form 7**: Removed. Contact page is static HTML.
- **Nextend Social Login**: Removed. Auth flows are email/password only.
- **Plugin Metadata Rebrand**: All plugins rebranded to SDWeddingDirectory.
- **Image Migration**: Most images moved from uploads to theme assets (banners, categories, icons, locations, blog, planning).
- **Easter Egg**: ASCII art HTML comment in `header.php`.
- **Admin Dashboard**: Custom stats widget added.
- **Claim + Slug Refactor**: Shared slug validator, clean claim workflow, registration triggers, admin approval, onboarding flow all complete.

---

## Remaining Work (in priority order)

### 1. Auth and Account Stability

- [X] Review vendor/venue account bootstrap logic so login does not silently create, overwrite, or corrupt profile data.
- [X] Confirm dashboard entry routing works with and without `?dashboard=` query arguments.
- [X] Security audit: removed plaintext passwords from registration meta and emails, fixed email verification role name case mismatch, strict token comparisons, sanitized dashboard input.
- [X] Localhost auto-verify bypass replaced with explicit `SDWD_AUTO_VERIFY` constant.
- [X] Couple dashboard no longer requires `?dashboard=` param — defaults to `couple-dashboard` like vendors.
- [X] Vendor post recovery system added (matches existing couple repair on init hook).
- [X] Password reset: replaced plaintext password email with secure token-based reset link (1-hour expiry, rate-limited to 1 per 5 minutes, restricted to couple/vendor roles, prevents email enumeration).
- [X] Fixed `esc_attr()` on passwords in registration and login (was silently mangling passwords with `&`, `<`, `>`, `"`, `'`). Now uses `wp_unslash()`.
- [X] Fixed `wp_signon($info, true)` forcing HTTPS cookies — now uses `is_ssl()` so login works on HTTP localhost.
- [X] Dashboard non-logged-in visitors now redirect to wp-login instead of raw `exit()`.
- [X] Re-tested vendor, venue, and couple signup, login, logout, and dashboard re-entry — all working.
- [X] Auth behavior frozen (2026-03-16). No auth changes without explicit decision.

### 2. Plugin Cleanup (Remaining)

- [X] Finish OptionTree removal verification — no `ot_get_option()` calls remain. Removed all 16 dead OptionTree settings-fields files.
- [X] Remove residual Nextend hooks — only reference is in ShortPixel vendor code (not our code).
- [X] Delete remaining third-party plugins — `regenerate-thumbnails`, `white-label-cms`, `mailchimp-for-wp`, `option-tree` all confirmed removed from disk.
- [X] Clean up remaining `get_field()` calls — removed ACF field directories (3), old-acf.php files (3), tax-meta-switcher, migration/1.4.8. Fixed last active `get_field()` in venue meta-box to use `get_term_meta()`.
- [X] Removed RTL test module, RTL stylesheets, and RTL ternary in dashboard CSS loader.
- [X] Removed dead admin modules: new-release-fix, paid-plugin-info.
- [X] Removed Leaflet map option from provider dropdown, defaulted to Google Maps, added Google Maps API Key field, set default lat/lng to San Diego.
- [X] Removed dead shortcodes: woocommerce-product, marker-with-map.
- [X] Sweep stale Elementor labels — no Elementor references remain in theme or plugin code. *(Note: this was incomplete — see new task below.)*
- [ ] **Elementor class purge** — see detailed plan below in "Elementor Markup Replacement Plan".
- [X] Duplicate venue-location/venue-type admin menu entries fixed — theme `admin-pages.php` was registering redirect submenu pages on top of WordPress auto-generated taxonomy menus. Same fix applied to vendor-category duplicate.
- [ ] Shortcode module registers ~30 shortcodes (none used as `[shortcode]` tags in content) but several classes provide `page_builder()` methods called directly from templates (hero slider, search form, real wedding grids, team, venue categories). Module must remain loaded; individual unused shortcode directories can be pruned later.
- [ ] Leaflet JS calls remain in vendor/venue/search scripts (guarded by `typeof` checks, won't execute). Replace with Google Maps when map feature is built out.
- [ ] Sweep entire codebase for `demo/` and `demo-content/` directories and delete them (both locally and from the repository).
- [ ] **Full rebrand from `weddingdir` to `sdweddingdirectory`**: Search every file name and file contents across plugins, theme, images, and assets for any reference to `weddingdir` (the original commercial theme/plugin name). All text domains, function prefixes, class names, constants, slugs, image filenames, directory names, plugin headers, `style.css` metadata, and version numbers must be updated to use `sdweddingdirectory` and version `1.0.0`. No trace of the original branding should remain.

### 3. Email System

- [ ] Activate and configure `wp-mail-smtp` so email links, quote requests, and contact forms actually work.
- [ ] Set up email templates for all transactional emails (quote requests, registration confirmations, claim notifications, etc.).

### 4. Data Backup and Integrity

- [ ] Export all venues with full data as XLSX/CSV (name, description, address, city, contact info, categories, etc.).
- [ ] Export all vendors by category as CSV.
- [ ] Review venue city assignments against Wedding Wire data — many may be in wrong cities after previous bulk edits.
- [ ] Ensure only cities with at least 1 venue appear in city links on `/venues/`.

### 5. Dashboard and Profile QA

- [ ] Run structured QA on vendor dashboard tabs (profile, working hours, FAQs, video, slug).
- [ ] Run structured QA on venue dashboard tabs (profile, seating capacity, settings, amenities, working hours, FAQs).
- [ ] Run structured QA on couple dashboard tools (budget, guest list, seating chart, todo, website, wishlist/vendor manager).
- [ ] Fix front-end vendor and venue profile issues found during QA.
- [X] Venue dashboard: address field with Google Maps — enabled map provider (`google`), set San Diego defaults (32.7157, -117.1611), enabled Places API. Existing location tab now shows address + zip + city selector + interactive map with autocomplete. Added server-side geocoding fallback in venue AJAX save handler.
- [X] Venue dashboard: guest capacity field already exists in category-info tab and WP admin meta box. Updated search page cards to show "Starting at $X" + capacity icon (fa-users). Added capacity to venue profile sidebar card (under price, above availability).
- [X] Venue dashboard: service filtering already works — guest capacity, indoor/outdoor settings, amenities, and services filters are all implemented via term-level configuration. FAQ is manually managed (same as vendors).
- [X] Venue dashboard: slug editing — added Profile URL Slug field to venue-info tab, AJAX handler uses explicit slug if provided (falls back to title). Same validation/collision system as vendors.
- [X] Venue profile page: remove second sidebar — single sidebar scrolls down after sticky header (venue only, not vendor).
- [X] Venue dashboard: hide "My Venues" / "Add New Venue" / "Update Venue" from sidebar (dead code — venue is created with account).
- [X] Venue dashboard: "preferred vendor" shows venues instead of actual vendors — fixed in couple dashboard.
- [X] Vendor dashboard: remove address and zip code fields.
- [X] Vendor dashboard: moved Set Pricing from sidebar to tab under My Profile.
- [X] Vendor dashboard: extracted Business Hours from Business Profile into its own tab under My Profile.
- [X] Vendor dashboard: added "View Business Page" button next to "Update Profile" in My Profile tab.
- [X] Vendor dashboard: sidebar simplified to Dashboard, My Profile, Quote Requests, My Reviews, Logout.
- [X] Vendor dashboard: rename "request quote" to "quote request" throughout.
- [X] Couple dashboard: vendor manager overview shows vendor categories instead of venue types.
- [X] Couple dashboard: seating chart sidebar icon changed to four-side-table-1.
- [X] Vendor pricing tiers: horizontal rule under price, hours field (backend + frontend), popular badge above card, alternating row backgrounds.
- [X] Couple dashboard real wedding: rename "Hire Vendors" tab to "Venue Booked", limit to single venue selection, remove outside vendors option.
- [X] Couple dashboard real wedding: create new "Hire Vendors" tab below Venue Booked for vendors only (keeps outside vendors option).
- [X] Couple dashboard real wedding: eliminate "Wedding Info" tab entirely (season, community, space preference, style, categories, function, tags).
- [X] Remove all wedding-info taxonomies: real-wedding-season, real-wedding-community, real-wedding-space-preferance, real-wedding-style, real-wedding-category, real-wedding-color. Eradicated from theme, plugins, admin, and front-end.
- [X] Couple main dashboard: remove "Wedding details" section at bottom.
- [X] WP Admin real wedding: remove Wedding Information tab fields from meta box.
- [X] Couple dashboard vendor manager: rename "Favorite Vendors" tab to "Saved Vendors".
- [X] Connect Save button on vendor profile to "Saved Vendors" tab (sdwd_saved_profiles user meta → dashboard display).
- [X] Connect Hired button on vendor profile to "Hired" tab (sdwd_hired_profiles user meta → dashboard display).
- [X] Preload new couple budget with these figures: DJ 1900, Catering 3000, Photography 2200, Cake 550, Flowers 1600, Wedding Planner 1400, Hair and Makeup 400, Venue 8500, Videographer 1800, Dress 2000, Tuxedo Rental 200, Officiant 400, Ceremony Music 500, Photo Booth 650, Event Rentals 650, Transportation 750.
- [X] Vendor slug/URL rewrite: working — slug field in My Profile tab, validation + claim system, rewrite rules flushed.
- [X] Vendor Quote Requests page: already built by request-quote module (page slug `vendor-request-quote`), includes lead status, activity tags, notes, badge count.
- [X] Vendor My Reviews page: already built by reviews module (page slug `vendor-reviews`), shows reviews couples wrote about vendor, organized by venue.
- [X] Fix seating-chart print output — added print CSS to hide dashboard chrome and content after floorplan.
- [X] Verify inbox and request-tracking states end to end — vendor side fully functional (lead status, notes, activity tags, history, booked-date calendar auto-marking). Couple side shows requests in table but has no notification when vendor responds (feature gap, not a bug — couple notification system is post-launch).
- [X] Add "Awards" badges for vendor and venue profiles — trophy badge in sidebar card shows tier (Rising Star 5+, Highly Rated 10+, Couples' Favorite 25+, Hall of Fame 50+) based on 5-star review count.
- [X] Add neighboring-cities toggle to venue profile footer — shows nearby cities (same region) by default with toggle to view all cities. Uses venue-location taxonomy parent/child hierarchy.
- [X] Vendor dashboard quote requests: new quote count badge in dashboard menu.
- [X] Vendor dashboard quote requests: lead status system (New, Attempted Contact, Replied, Appointment Set, Researching Options, Follow Up Later, No Response, Not Interested, Lost to Competitor, Booked, Invalid/Spam).
- [X] Vendor dashboard quote requests: activity tags (Sent Email, Left Voicemail, Texted, Called, Meeting Scheduled, Quote Sent, Quote Viewed).
- [X] Vendor dashboard quote requests: notes and history tracking with timestamps per quote request.
- [X] Vendor dashboard quote requests: history filtering by lead status.
- [X] Vendor dashboard quote requests: delete button for dead leads.
- [X] Vendor dashboard quote requests: when status moves to Booked, auto-mark couple's event date as booked on vendor availability calendar.
- [X] Vendor dashboard availability: daily booking capacity field (default 1) for multi-event vendors.
- [X] Real wedding about: location auto-derived from Venue Booked selection — when couple saves Venue Booked tab, venue's `venue-location` terms are mapped to `real-wedding-location` terms on the real wedding post.
- [ ] Professional Network & Endorsements: when a vendor writes a review for another vendor, that vendor should appear in the reviewed vendor's endorsements section (currently endorsements are manually set via `sdwd_endorsed_venues` post meta).
- [X] Vendor profile: removed Services section, Gallery section, duplicate Reviews widget, and "Venues by city" footer block.
- [X] Vendor profile: About section redesigned with vertical separator and icon-based quick facts (matching Wedding Wire layout).
- [X] Vendor profile: sidebar card de-boxed to fill space naturally next to photo collage.
- [X] Vendor profile: "Why Use SDWeddingDirectory" section constrained to col-lg-8 (no longer full-width).
- [X] Vendor profile: Professional Network & Endorsements widget added (priority 82), with Tom Ham's, BRICK, and Marina Village endorsements for Top Notch Entertainment.
- [X] Vendor profile: FAQ moved to priority 26 (directly after availability).
- [X] Vendor profile: yellow CTA boxes (availability + FAQ) restructured with left-aligned text and image slot on right.
- [X] Vendor profile: "Write a review" button always visible in Review section header.

### 6. Public Page Polish

- [ ] `/venues/` hero: responsive diagonal treatment matching homepage (drop image below 1000px, keep search in place).
- [ ] `/vendors/` hero: same responsive treatment.
- [ ] `/venues/` page: fix grey outline on "San Diego wedding venues" box (should be grey background or nothing). Remove lorem ipsum.
- [ ] `/venues/` page: city links at bottom showing 0 venues — filter to only cities with venues, decide whether to show counts.
- [ ] `/vendors/` page: remove lorem ipsum at bottom. Fix 3-column grey background not stretching full width. Fix bottom links to match homepage style. Left-justify bottom section. Fix section spacing.
- [ ] Real Weddings nav bar link goes to 404 — fix the URL.
- [ ] `/wedding-inspiration/` sidebar: compare to weddingdir.net original and improve.
- [ ] Breadcrumb spacing: ensure spaces around `/` separator (e.g., `Wedding  /  Wedding Inspiration` not `Wedding/Wedding Inspiration`).
- [ ] Home page: "Why SDWeddingDirectory" section needs more top/bottom padding.
- [ ] Home page: real wedding cards need rounded corners on all 4 sides.
- [ ] `/wedding-planning/` grey background section: match font formatting to child page `/wedding-planning/wedding-checklist`.
- [ ] Planning FAQ section: reduce title gap and increase padding before footer.
- [ ] Planning child pages: reduce padding between FAQ section and footer (template-level fix).
- [ ] Planning child pages: add hero images and block 1/2/3 images.
- [ ] `/wedding-checklist/`: add hero image, block images, rounded corners on grey background.
- [ ] Single blog post: UI redesign — decide sidebar or no sidebar, make it look professional.
- [ ] Vendor cost pages: create original content with San Diego-specific pricing data (sourced from Wedding Wire).
- [ ] City pages (`/[city]`): add custom header "[City] Wedding Venues".
- [ ] Generate unique images for blog posts (~50-230 needed).
- [ ] More city images for location carousel.
- [ ] Venue card images: ensure cities shown have at least 4 venues across all sections.

### 7. URL Structure (Stretch Goal)

- [ ] Evaluate `/[city-name]` as canonical venue-location URL instead of `/venues/?location=[city]`.
- [ ] Evaluate `/[type]-weddings` for venue-type URLs vs current `/venue-types/[type]`.
- [ ] Decide: locations as canonical, types as query params? Or keep current structure.

### 8. Uploads Reorganization

Goal: `/uploads/` should contain only `blog/`, `couples/`, `vendors/`, `venues/`.

- [X] Delete empty/unused upload folders: `elementor/`, `tg-demo-pack/`, `woocommerce_uploads/`, `branding/`, `icons/`, `wpcode/`, `2021/`, `2025` (file).
- [X] Rename `Blog Post Images/` to `blog/`.
- [X] Fix planning template images to use theme assets instead of uploads.
- [ ] Migrate team member featured images from `uploads/2026/` to theme assets (`/assets/images/team/`) — requires DB attachment URL updates.
- [ ] Migrate remaining `uploads/2026/` content (real wedding images, logos, banners, blog images) to appropriate theme asset folders or organized upload directories.
- [ ] Delete `uploads/2026/` once all references are migrated.
- [ ] Add upload organization: when users upload images, sort into `couples/`, `venues/`, `vendors/` subdirectories based on account type, with per-account subdirectories if possible.
- [ ] Blog image uploads should go to `blog/` and auto-rename using the post title if image filename is generic.

### 9. Admin and Settings Cleanup

- [ ] Test all native admin settings that replaced OptionTree and confirm which still matter.
- [ ] Remove dead or redundant admin controls.
- [ ] Audit couple, real-wedding, and website admin surfaces and cut what's unnecessary.
- [ ] Build lightweight admin term-edit forms for taxonomy meta that ACF previously managed (color pickers, filter toggles, image uploads per category/type) — needed if those values ever need changing.

### 10. SEO and Launch Stack

- [ ] Activate and configure `updraftplus` for backups.
- [ ] Activate and configure `seo-by-rank-math` (offline-safe parts).
- [ ] Crawl and fix 404s, redirect chains, missing H1s, duplicate titles, thin pages.
- [ ] Finalize canonicals, robots, sitemap, schema, and metadata.
- [ ] Add unique local copy, FAQs, and internal linking on money pages.
- [ ] Optimize images and trim render-blocking assets.
- [ ] Evaluate `shortpixel-image-optimiser` when media is stable.
- [ ] Prepare launch backup, freeze, and verification checklists.

### 11. Staging and Live Only

- [ ] Activate and configure `wordfence`.
- [ ] Decide caching stack before touching `w3-total-cache`. Clean up existing cache residue.
- [ ] Connect Search Console, Bing, analytics, and mail-deliverability checks.
- [ ] Run final pre-launch and post-launch checklists.

### 12. Wedding Website Themes

- [ ] Expand couple wedding website from 1 template to 6 distinct themes (currently only "Website Template One" exists in the `weddingdir-couple-website` plugin).
- [ ] Theme concepts: modern minimal, romantic classic, rustic, bold/colorful, elegant dark, garden/floral (finalize with founder).
- [ ] Each theme shares the same data model (couple names, photos, RSVP, events, gallery, story) but differs in layout, typography, color scheme, and section ordering.
- [ ] Plugin architecture already supports multiple templates via `weddingdir/wedding-website/layout-N` filters — add new template folders under `singular-file/` and register in meta box dropdown.
- [ ] Update template selector UI so couples can preview and switch themes from their dashboard.
- [ ] Add theme thumbnail previews for the selector.

### 13. Parking Lot (Post-Launch)

- [ ] Admin dashboard stats enhancements.
- [ ] Set up second wp-docker site with original theme for visual reference.
- [ ] Non-launch refactors that don't improve stability, speed, SEO, or conversion.

---

## Theme Rebuild Plan

### Why Rebuild

The current theme was inherited from a commercial theme and has been incrementally cleaned up over many phases. Despite significant progress (ACF removal, Elementor deactivation, OptionTree removal, plugin consolidation), the codebase still carries deep structural debt:

- **6 levels of Elementor wrapper nesting** around every section of content
- **4+ competing CSS files** with conflicting specificity, `!important` overrides, and CSS variables that sometimes win and sometimes don't
- **Bootstrap loaded for ~20 rules** of actual use, adding 200KB+ of CSS overhead and specificity conflicts
- **Shortcode rendering layer** that round-trips static content through WordPress's shortcode parser
- **Legacy class names** (`elementor-element-527cc8b`, `slider-versin-two`) that make CSS changes unpredictable
- **Mixed responsibilities** — rendering logic in shortcode classes, styling spread across files with no clear ownership

Every future styling change hits these walls. A clean rebuild preserves all existing functionality and visual design while replacing the underlying structure with code that is predictable, readable, and easy for both humans and AI to modify.

### What Gets Rebuilt vs. What Stays

**REBUILT (new clean code):**
- All theme template files (front-page, archives, singles, pages, header, footer)
- All CSS (replaced with clean architecture)
- Homepage section markup (flat semantic HTML, no wrapper nesting)
- Template parts (only where genuinely reused)

**STAYS AS-IS (not touched):**
- `sdweddingdirectory` core plugin — post types, taxonomies, meta fields, admin settings, shortcode classes that provide `page_builder()` methods
- `sdweddingdirectory-couple` plugin — couple dashboard tools
- All plugin PHP logic (registration, claim flow, dashboards, quote requests, reviews, search)
- WordPress database (posts, terms, options, user meta)
- `/assets/images/` — all theme images
- `/assets/fonts/` — all local WOFF2 fonts

**STRIPPED FROM PLUGIN (during rebuild):**
- All rendering/UI output (move to theme templates)
- Shortcode registration for layout rendering (keep `page_builder()` methods only if they provide data, not markup)
- Any Elementor widget registration code
- `is_elementor_edit_mode()` and related dead logic

### Architecture Principles

1. **Flat semantic HTML.** One `<section>` with a `<div class="container">` inside it. No wrapper nesting beyond what the content requires.
2. **BEM-lite class naming.** `.hero`, `.hero__title`, `.hero__subtitle`, `.hero__search`. One class per styled element, named by purpose.
3. **No Bootstrap.** Replace with CSS Grid for layout and a small set of custom utility classes. No framework CSS loaded.
4. **No `!important`.** If a rule needs `!important`, the architecture is broken.
5. **No shortcode rendering for layout.** Templates use plain PHP and `get_template_part()`. Shortcode classes may provide data (arrays, query results) but never HTML output.
6. **Design tokens from the start.** Every color, size, spacing, and font value comes from a CSS variable. Changing `--sdwd-h2` changes every h2, no exceptions.
7. **Mobile-first CSS.** Base styles are mobile. Complexity added via `min-width` breakpoints.
8. **CPTs stay in the plugin.** Post types, taxonomies, and meta fields are data — they belong in the plugin so they survive theme changes. The plugin is stripped to data-only: no rendering, no UI output.
9. **Site images stay in `/assets/images/`.** User-uploaded content goes to `/uploads/`. These are two different categories and the current approach is correct.
10. **Homepage as orchestrator.** `front-page.php` is a thin file that calls `get_template_part()` only for components that are reused elsewhere. Simple one-off sections live inline.

### CSS Architecture

Four files, loaded in this order. No overlap in responsibility.

| File | Purpose | Contains |
|---|---|---|
| `assets/css/foundation.css` | Design tokens, reset, base typography | CSS variables (colors, sizes, spacing, fonts), box-sizing reset, body/heading/link defaults, `@font-face` declarations |
| `assets/css/components.css` | Reusable UI patterns | Cards (`.card`, `.card__image`, `.card__body`), buttons (`.btn`, `.btn--primary`), section titles (`.section-title`), search forms, breadcrumbs, badges, carousels |
| `assets/css/layout.css` | Page structure and grid | `.container`, `.grid`, `.grid--2col`, `.grid--3col`, `.grid--4col`, section spacing, header, footer, sidebar, responsive breakpoints |
| `assets/css/pages/home.css` | Homepage-only styles | `.hero`, `.hero__title`, hero search bar, section-specific overrides. Other page-specific files added only when needed (e.g., `pages/venue.css`) |

**Rules:**
- No file imports from another file's domain (components.css never sets layout, layout.css never styles a card)
- Every selector is a single class or one level of nesting max (`.card__body p`, not `.section > .container > .row > .col > .card .card__body p`)
- Responsive rules live next to the thing they modify, not in a separate media query block at the bottom

### Class Naming Convention

```
/* Sections */
.hero                    /* homepage hero */
.home-vendors            /* homepage vendor cards section */
.home-realweddings       /* homepage real weddings section */
.home-planning           /* homepage planning tools section */
.home-inspiration        /* homepage inspiration section */
.home-locations          /* homepage location carousel */
.home-venues-city        /* homepage venues by city */
.home-search-cat         /* homepage search by category links */
.home-value-cols         /* homepage 4-column text section */
.home-why                /* homepage why SDWeddingDirectory */

/* BEM elements */
.hero__title             /* h1 inside hero */
.hero__subtitle          /* p below h1 */
.hero__search            /* search form wrapper */
.hero__image             /* background/diagonal image */

/* Components (reusable) */
.card                    /* vendor/venue card */
.card__image
.card__body
.card__title
.btn
.btn--primary
.btn--outline
.section-title           /* h2 + subtitle combo */
.section-title__heading
.section-title__desc
.breadcrumb

/* Layout */
.container               /* max-width centered wrapper */
.grid                    /* CSS Grid parent */
.grid--2col
.grid--3col
.grid--4col
```

### Execution Phases

Each phase produces a working site. No phase leaves the site broken.

#### Phase R1: Foundation + Header + Footer

**Goal:** New theme skeleton that loads and renders without errors.

1. Create new theme directory `sdweddingdirectory-v2` alongside the existing theme (allows instant rollback by switching themes in WP admin)
2. Set up `style.css` (theme metadata only), `functions.php`, `screenshot.png`
3. Create `assets/css/foundation.css` with all design tokens migrated from current `sdwd-foundation.css`
4. Create `assets/css/layout.css` with `.container` grid and responsive breakpoints (replacing Bootstrap grid)
5. Create `assets/css/components.css` (empty initially, populated as components are built)
6. Build `header.php` — clean markup, no legacy classes, same visual result as current header
7. Build `footer.php` — same approach
8. Create `functions.php` with: theme support, enqueue styles/scripts, nav menus, widget areas, image sizes
9. Copy `/assets/images/` and `/assets/fonts/` from current theme
10. **Verify:** Activate theme, header and footer render correctly, fonts load, no console errors

#### Phase R2: Homepage

**Goal:** Homepage matches current design with clean markup.

1. Build `front-page.php` as an orchestrator
2. Rebuild each homepage section as flat semantic HTML:
   - Hero + search (this is the most complex — diagonal image, responsive behavior, search form with venue/vendor toggle)
   - Vendor category cards
   - Plan your wedding tools
   - Real weddings carousel
   - Inspiration cards
   - Location carousel
   - Venues by city links
   - Search by category links
   - 4-column value text
   - Why SDWeddingDirectory
3. Extract only genuinely reused components as template parts (vendor card, venue card, section title)
4. Create `assets/css/pages/home.css` for homepage-only styles
5. Populate `components.css` as reusable patterns emerge
6. **Verify:** Homepage matches current site pixel-for-pixel at all breakpoints. Use current site screenshots as reference.

**Critical note on hero:** The hero section has carefully tuned responsive behavior across breakpoints. The visual design and breakpoint behavior must be preserved exactly. The markup can be simplified but the CSS behavior at each breakpoint must match.

#### Phase R3: Archive Pages (Venues + Vendors)

**Goal:** `/venues/` and `/vendors/` pages render with clean templates.

1. Build `archive-venue.php` — hero, search/filter bar, venue card grid, pagination
2. Build `archive-vendor.php` — same structure adapted for vendors
3. Build vendor category archive template
4. Build venue type and venue location archive templates
5. Reuse card components from Phase R2
6. **Verify:** All archive pages render, search/filtering works, pagination works

#### Phase R4: Single Pages (Venue + Vendor Profiles)

**Goal:** Individual venue and vendor profile pages render with clean templates.

1. Build `single-venue.php` — photo collage, about section, sidebar card, amenities, availability, FAQs, reviews, endorsements, neighboring cities
2. Build `single-vendor.php` — same structure adapted for vendors
3. These templates call plugin functions for data but own all markup
4. **Verify:** All profile sections render, review submission works, save/hire buttons work

#### Phase R5: Content Pages

**Goal:** Blog, inspiration, real weddings, planning pages.

1. Build `single.php` (blog posts)
2. Build `archive.php` (blog archive)
3. Build `single-real-wedding.php`
4. Build `archive-real-wedding.php`
5. Build `page-wedding-planning.php` and child planning pages
6. Build `page-wedding-inspiration.php`
7. Build static pages: about, contact, FAQs
8. **Verify:** All content pages render correctly

#### Phase R6: Dashboard Integration

**Goal:** Vendor, venue, and couple dashboards work with new theme.

1. Dashboard templates are largely plugin-driven — the theme provides the page wrapper (header/footer) and the plugin renders dashboard content
2. Verify all dashboard routes load correctly with new header/footer
3. Fix any CSS conflicts between new theme styles and dashboard plugin styles
4. **Verify:** Full dashboard QA — login, all tabs, form submissions, file uploads

#### Phase R7: Plugin Cleanup

**Goal:** Strip rendering code from plugin now that theme owns all markup.

1. Remove Elementor widget registration from plugin
2. Remove `is_elementor_edit_mode()` and all references
3. Remove `inc/page-builder/index.php` (Elementor config class)
4. Remove shortcode rendering output where theme now handles markup directly
5. Keep `page_builder()` methods only if they provide data arrays, not HTML strings
6. Remove `fa-elementor` icon entry and `.elementor-message` CSS
7. **Verify:** `grep -ri "elementor"` returns zero results across theme and plugin

#### Phase R8: Delete Old Theme

**Goal:** Clean break.

1. Confirm new theme is stable and all functionality works
2. Delete `sdweddingdirectory` (old theme directory)
3. Rename `sdweddingdirectory-v2` to `sdweddingdirectory`
4. Update any hardcoded theme directory references
5. Final full-site QA pass

### Style Guide Page

Create a `/style-guide/` page (template: `page-style-guide.php`) that renders every component:
- All heading levels (h1–h6) with current token values displayed
- Buttons (primary, outline, disabled)
- Cards (vendor, venue, real wedding)
- Form elements (inputs, selects, radio buttons, search bars)
- Section title + subtitle combos
- Grid layouts (2-col, 3-col, 4-col)
- Breadcrumbs
- Color swatches with variable names

This page serves as a living reference. Any CSS change can be verified here before checking individual pages.

### What This Replaces

This rebuild plan supersedes the following items in the Remaining Work list above:
- "Elementor class purge" (Section 2) — no longer needed, old markup is discarded entirely
- "Full rebrand from weddingdir to sdweddingdirectory" (Section 2) — new theme starts clean with `sdweddingdirectory` naming; plugin rebrand still applies separately
- "Sweep demo directories" (Section 2) — old theme deletion handles this
- Several items in "Public Page Polish" (Section 6) — rebuilt pages address spacing, styling, and layout issues from scratch

### Rollback Strategy

The old theme stays on disk until Phase R8. Rolling back is: WP Admin → Themes → Activate `sdweddingdirectory`. Takes 5 seconds. No data is lost because all content lives in the database and the plugin.
