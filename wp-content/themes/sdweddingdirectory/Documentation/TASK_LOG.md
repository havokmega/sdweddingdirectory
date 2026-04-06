# TASK_LOG.md

## Project
SDWeddingDirectory

## Phase
10B — Vendor Card Redesign + Venue Search Overhaul

## Location
`/Users/Havok/Documents/Development/WebDevelopment/wp-docker`

## Task A — Vendor Category Archive Card Redesign

### Summary
Replaced vendor search/archive result cards on taxonomy pages (e.g. `/vendors/djs/`) with a horizontal venue-style card while keeping vendor filter sidebar/query/AJAX behavior intact.

### Files Modified
1. `wp-content/themes/sdweddingdirectory/taxonomy-vendor-category.php`
- Changed vendor result card layout from `layout=1` to `layout=3`.
- Changed result wrapper to single-column list (`row row-cols-1`, each item `col-12`).
- Kept sidebar filter markup and query logic unchanged.

2. `wp-content/plugins/sdweddingdirectory/front-file/vendor-layout/index.php`
- Added helper methods for result cards:
  - `vendor_result_location()`
  - `vendor_result_excerpt()`
  - `vendor_result_price()`
- Added new vendor `layout=3` card markup:
  - left image / right details
  - linked vendor name
  - location row with map pin (when available)
  - short excerpt
  - price row (when available)
  - `Request Pricing` button

3. `wp-content/plugins/sdweddingdirectory/front-file/find-post-helper/search-vendor/index.php`
- Updated AJAX-rendered vendor cards from `layout=1` to `layout=3`.
- Updated wrappers to `col-12` single-column list.

4. `wp-content/themes/sdweddingdirectory/assets/css/theme-style.css`
- Added `.sd-vendor-result-card` styling:
  - border `1px solid #e0e0e0`
  - `border-radius: 8px`
  - `overflow: hidden`
  - hover shadow
  - image `object-fit: cover`, `height: 100%`, `min-height: 200px`
  - mobile image `min-height: 180px`

## Task B — Venue Search & Navigation Overhaul

### B1 — `/venues/` as full search results page with sidebar filters

#### Files Modified
1. `wp-content/themes/sdweddingdirectory/page-venues.php`
- Replaced prior category/location grid template.
- Now delegates to venue venue search controller:
  - `do_action( 'sdweddingdirectory/find-venue' );`

2. `wp-content/plugins/sdweddingdirectory/front-file/find-post-helper/search-venue/index.php`
- Added venues context helpers:
  - `is_venues_search_page()`
  - `_location_slug()`
- Updated `_query_args()` to include `location`.
- Updated `_state_id()` to resolve `location` slug first, with legacy fallback.
- Added venues-specific rendering branch in `sdweddingdirectory_find_venue_markup()`:
  - calls page header banner for breadcrumb output
  - uses `form.sdweddingdirectory-result-page` wrapper
  - left `col-lg-3` sidebar with always-visible filter widgets:
    - Active Filters
    - Venue Type (sub-category)
    - Setting
    - Amenities
    - Number of guests (capacity)
    - Pricing
  - right `col-lg-9` results list
  - hidden query fields include both legacy and new `location` key
  - forces `layout=8` (list-style venue cards)
- Updated script/style enqueue condition for venues page.
- Updated post-per-page defaults from `9` to `12`.
- Added no-category term-box parsing for `venue_setting` and `venue_amenities`.
- Added `location_slug` support in both initial render and AJAX.
- Added `layout=8` rendering path (list-only, no list/grid toggle tabs).

3. `wp-content/plugins/sdweddingdirectory/front-file/find-post-helper/index.php`
- Added venue layout handler for `layout=8` (results header only).

4. `wp-content/plugins/sdweddingdirectory-venue/venue-layout/index.php`
- Added venue card renderer for `layout=8` using `.sd-vendor-result-card` style.
- Added helper methods:
  - `venue_post_price_text()`
  - `venue_post_capacity_text()`
- Card content includes:
  - image
  - linked venue name
  - location row
  - excerpt
  - price
  - capacity (when available)
  - `View Venue` button

5. `wp-content/plugins/sdweddingdirectory/front-file/find-post-helper/search-venue/script.js`
- Added robust layout detection fallback for list-only venues layout.
- Sends `location` in AJAX payload.
- On submit, removes legacy `state/region/city` query keys when `location` is present.

6. Venue sidebar helper widgets updated for no-`cat_id` venues mode:
- `wp-content/plugins/sdweddingdirectory/front-file/find-post-helper/search-venue/helper/sub-category/index.php`
- `wp-content/plugins/sdweddingdirectory/front-file/find-post-helper/search-venue/helper/setting/index.php`
- `wp-content/plugins/sdweddingdirectory/front-file/find-post-helper/search-venue/helper/amenities/index.php`
- `wp-content/plugins/sdweddingdirectory/front-file/find-post-helper/search-venue/helper/seating-capacity/index.php`
- `wp-content/plugins/sdweddingdirectory/front-file/find-post-helper/search-venue/helper/pricing/index.php`

Changes include:
- No-`cat_id` venues-page support for rendering/filtering.
- Venue Type label shown when category not preselected.
- Aggregated options for Setting/Amenities/Capacity/Pricing in venues context.
- Enabled seating/pricing widget classes (feature flags default true).
- Added pricing fallback ranges when ACF `pricing_options` is empty.

### B2 — Location URL structure (`?location=slug`) + backward compatibility

#### Files Modified
1. `wp-content/plugins/sdweddingdirectory/front-file/find-post-helper/search-venue/index.php`
- Added `location` slug handling in query parsing and tax-query resolution.
- Kept backward compatibility for `state_id`.

2. `wp-content/plugins/sdweddingdirectory/front-file/dropdown-box/filters/hidden-query-input/index.php`
- Added hidden query key: `location`.

3. `wp-content/plugins/sdweddingdirectory/front-file/dropdown-box/index.php`
- Added `location` to location term JSON payload.

4. `wp-content/plugins/sdweddingdirectory/front-file/dropdown-box/venue-location/index.php`
- Accepts `location` GET arg and resolves slug first.

5. `wp-content/plugins/sdweddingdirectory/front-file/dropdown-box/venue-type/index.php`
- Carries `location` through category filtering context.

6. `wp-content/plugins/sdweddingdirectory/front-file/dropdown-box/ajax/index.php`
- Passes `location` through dropdown AJAX endpoints.

7. `wp-content/plugins/sdweddingdirectory/front-file/dropdown-box/filters/find-category-data/index.php`
- Accepts `location` slug and resolves location term before legacy fallback.

8. `wp-content/plugins/sdweddingdirectory/front-file/dropdown-box/script.js`
- On dropdown form submit, strips legacy `state/region/city` params when `location` exists.

9. `wp-content/plugins/sdweddingdirectory-shortcodes/shortcodes/venue-location/index.php`
- Updated location card links and counters to `/venues/?location=<slug>`.

10. `wp-content/themes/sdweddingdirectory/inc/page-header-banner.php`
- Added venues breadcrumb override:
  - `Home > Wedding Venues`
  - `Home > Wedding Venues > [City]` when filtered by `location` (or legacy `state_id`).

### B3 — Navigation menu update

Added primary menu child item under `Venues`:
- Label: `See All Venues`
- URL: `/venues/`

### B4 — Venue result cards

Implemented via `layout=8` in venue renderer:
- horizontal card style shared with Task A
- `View Venue` CTA
- price + capacity rows (capacity conditional)

## WP-CLI Commands Run

1. `docker exec wp_ssh wp menu item list primary-menu --allow-root --fields=db_id,menu_item_parent,title,url`
2. `docker exec wp_ssh wp menu item add-custom primary-menu "See All Venues" "/venues/" --parent-id=3878 --allow-root`
3. `docker exec wp_ssh wp term list venue-type --fields=term_id,parent,name,slug,count --orderby=parent --order=ASC --allow-root`
4. `docker exec wp_ssh wp term list venue-location --fields=term_id,parent,name,slug --allow-root`
5. `docker exec wp_ssh wp eval '$terms=get_terms(["taxonomy"=>"venue-type","hide_empty"=>false]); $keys=["setting_available","amenities_available","pricing_available","capacity_available"]; foreach($keys as $k){ $c=0; foreach($terms as $t){ if(function_exists("get_field") && get_field($k,"venue-type_".$t->term_id)){ $c++; }} echo $k.":".$c."\\n"; }' --allow-root`
6. `docker exec wp_ssh wp eval '$terms=get_terms(["taxonomy"=>"venue-type","hide_empty"=>false]); $keys=["setting_options","amenities_options","pricing_options","capacity_options"]; foreach($keys as $k){ $c=0; $n=0; foreach($terms as $t){ $v=function_exists("get_field")?get_field($k,"venue-type_".$t->term_id):null; if(is_array($v)&&count($v)){ $c++; $n+=count($v);} } echo $k.": terms=".$c.", rows=".$n."\\n"; }' --allow-root`
7. `docker exec wp_ssh wp db query "SELECT MIN(CAST(meta_value AS UNSIGNED)) AS min_price, MAX(CAST(meta_value AS UNSIGNED)) AS max_price, COUNT(*) AS rows_count FROM wp_postmeta WHERE meta_key='venue_min_price' AND meta_value REGEXP '^[0-9]+$';" --allow-root`
8. `docker exec wp_ssh wp db query "SELECT MIN(CAST(meta_value AS UNSIGNED)) AS min_price, MAX(CAST(meta_value AS UNSIGNED)) AS max_price, COUNT(*) AS rows_count FROM wp_postmeta WHERE meta_key='venue_max_price' AND meta_value REGEXP '^[0-9]+$';" --allow-root`
9. `docker exec wp_ssh wp db query "SELECT COUNT(*) AS with_capacity, MIN(CAST(meta_value AS UNSIGNED)) AS min_cap, MAX(CAST(meta_value AS UNSIGNED)) AS max_cap FROM wp_postmeta WHERE meta_key='venue_seat_capacity' AND meta_value REGEXP '^[0-9]+$' AND CAST(meta_value AS UNSIGNED) > 0;" --allow-root`

## Testing Results (Checklist)

- [x] `/vendors/` still shows category grid (unchanged)
  - Verified by fetching `/vendors/`; no `.sd-vendor-result-card` result-list markup present.
- [x] `/vendors/djs` shows new horizontal card style with sidebar filters
  - Verified `.sd-vendor-result-card` and `Request Pricing` in rendered HTML.
- [x] Vendor sidebar filters still work (checking boxes filters results)
  - Verified via `sdweddingdirectory_load_vendor_data` AJAX endpoint responses (valid JSON + filtered result counts).
- [x] `/venues/` shows all venues with sidebar filters visible
  - Verified Venue Type, Setting, Amenities, Number of guests, Pricing sections rendered.
- [x] `/venues/` pagination works (12 per page)
  - Verified 12 cards in first page render and page-2 render.
- [x] Venue sidebar filters work (Venue Type, Setting, Amenities, Capacity, Pricing)
  - Verified venue AJAX endpoint accepts and responds for category/location/price/capacity filter payloads.
- [x] `/venues/?location=alpine` shows only Alpine venues
  - Verified rendered cards show Alpine location and filtered count.
- [x] `/venues/?state_id=63` still works (backward compatibility)
  - Verified same filtered behavior and city breadcrumb.
- [x] Breadcrumbs read `Home > Wedding Venues` on `/venues/`
  - Verified; with location filter it renders `Home > Wedding Venues > Alpine`.
- [x] `See All Venues` appears in the Venues nav dropdown
  - Verified in `primary-menu` as child of menu item `3878`.
- [ ] Mobile: sidebar filters collapse or stack properly
  - Code/markup uses Bootstrap responsive classes and existing collapse components; no device-interactive browser test was executed in this run.
- [x] No PHP fatal errors on target pages
  - Verified `/vendors/djs/`, `/venues/`, `/venues/?location=alpine`, `/venues/?state_id=63` render without fatal output.

## Notes

1. Data caveat: venue capacity text in cards is conditional and currently absent because `venue_seat_capacity` meta values are empty in current dataset.
2. Data caveat: taxonomy-level `pricing_options` are empty in current dataset; fallback pricing ranges were added so the Pricing sidebar section remains available on `/venues/`.

---

## Phase
11 — Plugin Audit & Cleanup

## Scope
- Audit-only phase completed.
- No plugin activation/deactivation/deletion was performed.
- No PHP/JS/CSS/theme/plugin logic was modified in this phase.

## Inventory Result
- Found **13** plugins matching `sdweddingdirectory-*` in `wp-content/plugins/`:
  - `sdweddingdirectory-budget-calculator`
  - `sdweddingdirectory-claim-venue`
  - `sdweddingdirectory-couple-website`
  - `sdweddingdirectory-elementor`
  - `sdweddingdirectory-guest-list`
  - `sdweddingdirectory-venue`
  - `sdweddingdirectory-real-wedding`
  - `sdweddingdirectory-request-quote`
  - `sdweddingdirectory-reviews`
  - `sdweddingdirectory-rsvp`
  - `sdweddingdirectory-shortcodes`
  - `sdweddingdirectory-todo-list`
  - `sdweddingdirectory-wishlist`
- Note: prompt context expected 14 plugins, but only 13 exist in this codebase.

## Evidence Snapshot
- Active plugin state: all 13 `sdweddingdirectory-*` plugins are active (`wp plugin list`).
- Registered/used custom post types (DB):
  - `venue`: 123 posts
  - `real-wedding`: 4 posts
  - `claim-venue`: 0 posts
  - `website`: 0 posts
  - `venue-request`: 0 posts
  - `venue-review`: 0 posts
- Registered/used taxonomies (DB):
  - `venue-type`: 17 terms, 123 assignments
  - `venue-location`: 40 terms, 179 assignments
  - `vendor-category`: 21 terms, 921 assignments
  - `website-*` and `real-wedding-*` taxonomies are registered, but currently show no term assignments in this dataset.
- Elementor usage:
  - `_elementor_data` rows containing SDWeddingDirectory widget signatures: 10
  - Widget types present include `elementorsdweddingdirectory_elementor_*` and `sdweddingdirectory-elementor-*`.
- Shortcode usage:
  - `sdweddingdirectory-shortcodes` registers 42 shortcodes.
  - DB `post_content` has 0 direct shortcode instances, but theme/plugin code calls many directly via `do_shortcode(...)`/`sprintf(...)` in templates (for example:
    `page-about.php`, `taxonomy-real-wedding-*.php`, `taxonomy-vendor-location.php`, and multiple Elementor widget renderers).
- Hook wiring confirmed:
  - Core dashboard invokes `do_action('sdweddingdirectory/couple-dashboard', ...)` and applies couple/vendor dashboard menus.
  - Venue/vendor singular templates invoke hooks used by claim, request-quote, reviews, and wishlist plugins.

## Deliverable Table

| Plugin Name | Category | Reason | Removal Notes |
|---|---|---|---|
| `sdweddingdirectory-venue` | ESSENTIAL | Core venue engine. Registers `venue` CPT + `venue-type`/`venue-location`; active data exists (123 venues, assigned terms). Theme/search templates call `apply_filters('sdweddingdirectory/venue/post', ...)`. | Do not remove unless replacing venue CPT, taxonomy, venue layouts, search controller, and all venue templates. |
| `sdweddingdirectory-shortcodes` | ESSENTIAL | Registers 42 shortcodes used by theme/plugin rendering paths (template `do_shortcode` calls + Elementor widget output strings), even though raw shortcode strings are not stored in `post_content`. | Do not remove unless all shortcode-driven template rendering is refactored to native blocks/widgets/templates first. |
| `sdweddingdirectory-elementor` | ESSENTIAL | Custom Elementor widget pack is actively used (`_elementor_data` contains SDWeddingDirectory widget types in 10 rows). Removing would break Elementor-rendered sections/pages. | Do not remove unless those pages are rebuilt with core Elementor/native widgets and content migrated. |
| `sdweddingdirectory-real-wedding` | ESSENTIAL | Registers `real-wedding` CPT with active content (4 posts). Theme real-wedding taxonomy templates and shortcode flows depend on it. | Do not remove unless real wedding feature/pages/content are migrated or intentionally retired. |
| `sdweddingdirectory-budget-calculator` | OPTIONAL | Couple dashboard budget module (hooks into `sdweddingdirectory/couple-dashboard` + menu filters). No `sdweddingdirectory_budget_*` meta data found in current DB snapshot. | Before removal: verify couple dashboard budget tab is not required, export any future `sdweddingdirectory_budget_*` data, remove/replace budget menu entries and widgets. |
| `sdweddingdirectory-guest-list` | OPTIONAL | Couple dashboard guest/RSVP/invitation modules are wired by hooks. No `guest_list_*` postmeta found; no `couple` posts found in DB snapshot. | Before removal: confirm no couples are using Guest Management, migrate guest data if present later (`guest_list_data`, `guest_list_event_group`, etc.), and remove dependent UI tabs. |
| `sdweddingdirectory-todo-list` | OPTIONAL | Couple checklist dashboard module is wired via dashboard/menu hooks. No `todo_list` meta found in current DB snapshot. | Before removal: confirm checklist feature is not needed, migrate/export any checklist data if added later, and remove dashboard tab/widget dependencies. |
| `sdweddingdirectory-wishlist` | OPTIONAL | Injects wishlist/hire actions into venue/vendor flows (`sdweddingdirectory/post/wishlist` and singular header hooks). No `wishlist_venue_id` meta found in current DB snapshot. | Before removal: verify wishlist/hire UX can be dropped; remove venue/vendor wishlist buttons and couple dashboard wishlist tab to avoid dead controls. |
| `sdweddingdirectory-couple-website` | OPTIONAL | Registers `website` CPT + `website-category/location` and wedding-website dashboard flows. Current DB has 0 `website` posts and no assigned website taxonomy terms. | Before removal: confirm Wedding Website feature is intentionally disabled, archive/migrate any future `website` posts, remove menu/tab/share integrations. |
| `sdweddingdirectory-rsvp` | OPTIONAL | RSVP layer is integrated via `sdweddingdirectory/couple/website/layout-1/rsvp` action from couple-website templates. No RSVP-specific persisted option/data found in current snapshot. | Before removal: confirm public RSVP pages/emails are not needed, and remove RSVP section/action references from wedding website templates. |
| `sdweddingdirectory-claim-venue` | OPTIONAL | Registers `claim-venue` CPT and injects claim buttons into venue/vendor singular header hooks. DB currently has 0 claim posts. | Before removal: verify venue claim workflow is not needed, archive any future claim records, and remove claim CTA hooks/buttons from singular layouts. |
| `sdweddingdirectory-request-quote` | OPTIONAL | Registers `venue-request` CPT and injects Request Pricing CTAs/forms into venue/vendor hooks. DB currently has 0 `venue-request` posts and no `request_quote_*` meta. | Before removal: confirm lead/quote funnel is intentionally disabled, remove Request Pricing hooks/buttons/forms, and migrate any future quote records first. |
| `sdweddingdirectory-reviews` | OPTIONAL | Registers `venue-review` CPT and rating/review hooks for venue/vendor singular and dashboards. DB currently has 0 review posts. | Before removal: confirm ratings/reviews are out of scope, remove rating summary widgets/hooks, and migrate any future review content/history. |

## Audit Conclusion
- `ESSENTIAL`: 4 plugins
- `OPTIONAL`: 9 plugins
- `UNUSED`: 0 plugins (all audited plugins have code-level hook/template integration, even when current DB content is empty)

## Commands Run (Phase 11)
1. `ls -1d wp-content/plugins/sdweddingdirectory-*`
2. `docker exec wp_ssh wp plugin list --status=active --field=name --allow-root`
3. `docker exec wp_ssh wp post-type list --fields=name,label,description,public,show_ui,has_archive --allow-root`
4. `docker exec wp_ssh wp taxonomy list --fields=name,label,object_type,public --format=csv --allow-root`
5. `docker exec wp_ssh wp db query "SELECT post_type, COUNT(*) ..."`
6. `docker exec wp_ssh wp db query "SELECT taxonomy, COUNT(*), SUM(count) ..."`
7. `docker exec wp_ssh wp db query "SELECT COUNT(*) FROM wp_postmeta WHERE meta_key='_elementor_data' AND meta_value LIKE '%sdweddingdirectory%';"`
8. `docker exec wp_ssh wp db query "SELECT meta_value FROM wp_postmeta WHERE meta_key='_elementor_data' ..."` + local `rg` extraction of widget types
9. `rg` scans across `wp-content/themes` and `wp-content/plugins` for shortcode registrations/usages and hook wiring

---
---

## Phase
12 — Custom Widget Rename & Alignment

## Scope
- Located all custom Elementor widgets (`extends Widget_Base`) across theme + plugins.
- Found **32 custom widgets**, all in `wp-content/plugins/sdweddingdirectory-elementor/elementor/`.
- No custom Elementor widgets were found in theme files.
- Updated **only** `get_title()` return values.
- Kept all `get_name()` methods unchanged.

## Widget Inventory
| Internal Name | Display Title (Before) | File Path | What It Renders |
|---|---|---|---|
| `sdweddingdirectory-elementor-about-us-carousel` | About Us Carousel | `wp-content/plugins/sdweddingdirectory-elementor/elementor/about-us-carousel/index.php` | About-us image carousel items. |
| `sdweddingdirectory-elementor-accordion` | Accordion | `wp-content/plugins/sdweddingdirectory-elementor/elementor/accordion/index.php` | Accordion/collapsible FAQ-style content sections. |
| `sdweddingdirectory-elementor-blog-post` | Blog Post | `wp-content/plugins/sdweddingdirectory-elementor/elementor/blog-post/index.php` | Recent blog posts list/grid. |
| `sdweddingdirectory-elementor-button-group` | Button Group | `wp-content/plugins/sdweddingdirectory-elementor/elementor/button-group/index.php` | Grouped CTA buttons. |
| `sdweddingdirectory-elementor-button` | Button | `wp-content/plugins/sdweddingdirectory-elementor/elementor/button/index.php` | Single CTA button. |
| `sdweddingdirectory-elementorcall-to-action` | Call to Action | `wp-content/plugins/sdweddingdirectory-elementor/elementor/call-to-action/index.php` | CTA block with heading/content/buttons. |
| `sdweddingdirectory-elementor-carousel` | Carousel Features | `wp-content/plugins/sdweddingdirectory-elementor/elementor/carousel/index.php` | Image/logo carousel block. |
| `sdweddingdirectory-elementorcontact-box` | Contact Box | `wp-content/plugins/sdweddingdirectory-elementor/elementor/contact-box/index.php` | Contact info box (icon/title/content). |
| `sdweddingdirectory-elementorfeatures-info-section` | Features Info Section | `wp-content/plugins/sdweddingdirectory-elementor/elementor/feature-info/index.php` | Feature info section text block. |
| `sdweddingdirectory-elementor-feature-box-group` | Features Box Group | `wp-content/plugins/sdweddingdirectory-elementor/elementor/featured-box-group/index.php` | Grid/carousel of multiple feature cards. |
| `sdweddingdirectory-elementor-feature-box` | Features box | `wp-content/plugins/sdweddingdirectory-elementor/elementor/features-box/index.php` | Single feature card with icon/text/link. |
| `sdweddingdirectory-elementor-find-venue-form` | Find Venue Form | `wp-content/plugins/sdweddingdirectory-elementor/elementor/find-venue-form/index.php` | Venue search form fields and submit action. |
| `sdweddingdirectory-elementor-home-page-slider-one` | Slider | `wp-content/plugins/sdweddingdirectory-elementor/elementor/home-slider/index.php` | Homepage hero slider with search UI. |
| `sdweddingdirectory-elementor-idea-and-tips-group` | Idea And Tips Group | `wp-content/plugins/sdweddingdirectory-elementor/elementor/ideas-and-tips-group/index.php` | Grid/carousel of inspiration topic cards. |
| `sdweddingdirectory-elementor-idea-and-tips` | Idea And Tips | `wp-content/plugins/sdweddingdirectory-elementor/elementor/ideas-and-tips/index.php` | Single inspiration topic card. |
| `sdweddingdirectory-elementor-venue-type-icon` | Venue Category Icon | `wp-content/plugins/sdweddingdirectory-elementor/elementor/venue-type-icon/index.php` | Venue category icon tiles. |
| `sdweddingdirectory-elementor-venue-type` | Venue Category | `wp-content/plugins/sdweddingdirectory-elementor/elementor/venue-type/index.php` | Venue category cards/venue block. |
| `sdweddingdirectory-elementor-venue-location-group` | Venue Location Group | `wp-content/plugins/sdweddingdirectory-elementor/elementor/venue-location-group/index.php` | Grouped venue location cards. |
| `sdweddingdirectory-elementor-venue-location` | Venue Location | `wp-content/plugins/sdweddingdirectory-elementor/elementor/venue-location/index.php` | Single venue location card. |
| `sdweddingdirectory-elementor-venue-post` | Venue Post | `wp-content/plugins/sdweddingdirectory-elementor/elementor/venue-post/index.php` | Venue venue posts (grid/list). |
| `sdweddingdirectory-elementor-marker-map` | Map | `wp-content/plugins/sdweddingdirectory-elementor/elementor/map-with-marker/index.php` | Map with markers/location pins. |
| `sdweddingdirectory-elementor-pricing-table-post` | Pricing Table | `wp-content/plugins/sdweddingdirectory-elementor/elementor/pricing-post/index.php` | Pricing plan/table cards. |
| `sdweddingdirectory-elementor-realwedding-post` | RealWedding Post | `wp-content/plugins/sdweddingdirectory-elementor/elementor/real-wedding-post/index.php` | Real wedding post cards/venue. |
| `sdweddingdirectory-elementor-section-title` | Section title | `wp-content/plugins/sdweddingdirectory-elementor/elementor/section-title/index.php` | Section heading/title block. |
| `sdweddingdirectory-elementor-tab` | Tab | `wp-content/plugins/sdweddingdirectory-elementor/elementor/tabs/index.php` | Tabbed content container. |
| `sdweddingdirectory-elementor-team-group` | Team Group | `wp-content/plugins/sdweddingdirectory-elementor/elementor/team-group/index.php` | Grid/group of team member cards. |
| `sdweddingdirectory-elementor-team` | Team | `wp-content/plugins/sdweddingdirectory-elementor/elementor/team/index.php` | Single team member card. |
| `sdweddingdirectory-elementor-testimonial-group` | Testimonial Group | `wp-content/plugins/sdweddingdirectory-elementor/elementor/testimonials-group/index.php` | Grouped testimonial cards/slider. |
| `sdweddingdirectory-elementor-testimonials` | Testimonial | `wp-content/plugins/sdweddingdirectory-elementor/elementor/testimonials/index.php` | Single testimonial card. |
| `sdweddingdirectory-elementor-vendor-post` | Vendor Post | `wp-content/plugins/sdweddingdirectory-elementor/elementor/vendor-post/index.php` | Vendor venue posts (grid/list). |
| `sdweddingdirectory-elementorwhy-choose-us-group` | Why Choose Us Group | `wp-content/plugins/sdweddingdirectory-elementor/elementor/why-choose-group/index.php` | Group of "why choose us" value cards. |
| `sdweddingdirectory-elementorwhy-choose-us` | Why Choose Us | `wp-content/plugins/sdweddingdirectory-elementor/elementor/why-choose/index.php` | Single "why choose us" value card. |

## Title Update Table
| Old Title | New Title | File Path | Reason |
|---|---|---|---|
| About Us Carousel | About Us Image Carousel | `wp-content/plugins/sdweddingdirectory-elementor/elementor/about-us-carousel/index.php` | Clarifies this widget outputs an about-us image carousel. |
| Accordion | FAQ Accordion | `wp-content/plugins/sdweddingdirectory-elementor/elementor/accordion/index.php` | Better reflects collapsible FAQ-style content. |
| Blog Post | Blog Posts Feed | `wp-content/plugins/sdweddingdirectory-elementor/elementor/blog-post/index.php` | Indicates a list/feed of blog posts. |
| Button Group | Call-To-Action Buttons | `wp-content/plugins/sdweddingdirectory-elementor/elementor/button-group/index.php` | Describes grouped CTA button output. |
| Button | Action Button | `wp-content/plugins/sdweddingdirectory-elementor/elementor/button/index.php` | Clarifies single clickable CTA element. |
| Call to Action | Call to Action | `wp-content/plugins/sdweddingdirectory-elementor/elementor/call-to-action/index.php` | Unchanged to preserve internal widget ID because get_name() uses get_title(). |
| Carousel Features | Brand Logo Carousel | `wp-content/plugins/sdweddingdirectory-elementor/elementor/carousel/index.php` | More accurate for rotating image/logo carousel output. |
| Contact Box | Contact Box | `wp-content/plugins/sdweddingdirectory-elementor/elementor/contact-box/index.php` | Unchanged to preserve internal widget ID because get_name() uses get_title(). |
| Features Info Section | Features Info Section | `wp-content/plugins/sdweddingdirectory-elementor/elementor/feature-info/index.php` | Unchanged to preserve internal widget ID because get_name() uses get_title(). |
| Features Box Group | Feature Cards Grid | `wp-content/plugins/sdweddingdirectory-elementor/elementor/featured-box-group/index.php` | Matches grouped feature card layout. |
| Features box | Feature Card | `wp-content/plugins/sdweddingdirectory-elementor/elementor/features-box/index.php` | Fixes naming/casing and matches single card output. |
| Find Venue Form | Venue Search Form | `wp-content/plugins/sdweddingdirectory-elementor/elementor/find-venue-form/index.php` | Aligns with search behavior for venues. |
| Slider | Homepage Hero Slider | `wp-content/plugins/sdweddingdirectory-elementor/elementor/home-slider/index.php` | More explicit about homepage hero usage. |
| Idea And Tips Group | Inspiration Topics Grid | `wp-content/plugins/sdweddingdirectory-elementor/elementor/ideas-and-tips-group/index.php` | Matches multi-item inspirational topic grid output. |
| Idea And Tips | Inspiration Topic Card | `wp-content/plugins/sdweddingdirectory-elementor/elementor/ideas-and-tips/index.php` | Matches single inspiration card output. |
| Venue Category Icon | Venue Category Icons | `wp-content/plugins/sdweddingdirectory-elementor/elementor/venue-type-icon/index.php` | Pluralized to reflect multiple icons/categories. |
| Venue Category | Venue Categories Grid | `wp-content/plugins/sdweddingdirectory-elementor/elementor/venue-type/index.php` | Clarifies multi-category grid/venue output. |
| Venue Location Group | Venue Locations Grid | `wp-content/plugins/sdweddingdirectory-elementor/elementor/venue-location-group/index.php` | Clarifies grouped location card output. |
| Venue Location | Venue Location Card | `wp-content/plugins/sdweddingdirectory-elementor/elementor/venue-location/index.php` | Clarifies single location card output. |
| Venue Post | Venue Venues | `wp-content/plugins/sdweddingdirectory-elementor/elementor/venue-post/index.php` | Reflects venue collection output. |
| Map | Venue Map | `wp-content/plugins/sdweddingdirectory-elementor/elementor/map-with-marker/index.php` | Clarifies venue/location map purpose. |
| Pricing Table | Pricing Plans | `wp-content/plugins/sdweddingdirectory-elementor/elementor/pricing-post/index.php` | Reads better for package/pricing cards. |
| RealWedding Post | Real Wedding Stories | `wp-content/plugins/sdweddingdirectory-elementor/elementor/real-wedding-post/index.php` | Fixes spacing and aligns with content intent. |
| Section title | Section Heading | `wp-content/plugins/sdweddingdirectory-elementor/elementor/section-title/index.php` | Standardizes naming/casing. |
| Tab | Content Tabs | `wp-content/plugins/sdweddingdirectory-elementor/elementor/tabs/index.php` | Clarifies multi-tab content container usage. |
| Team Group | Team Members Grid | `wp-content/plugins/sdweddingdirectory-elementor/elementor/team-group/index.php` | Clarifies grouped team member output. |
| Team | Team Member Card | `wp-content/plugins/sdweddingdirectory-elementor/elementor/team/index.php` | Clarifies single team member card output. |
| Testimonial Group | Testimonials Grid | `wp-content/plugins/sdweddingdirectory-elementor/elementor/testimonials-group/index.php` | Clarifies grouped testimonials layout. |
| Testimonial | Testimonial Card | `wp-content/plugins/sdweddingdirectory-elementor/elementor/testimonials/index.php` | Clarifies single testimonial card output. |
| Vendor Post | Vendor Venues | `wp-content/plugins/sdweddingdirectory-elementor/elementor/vendor-post/index.php` | Reflects vendor venue collection output. |
| Why Choose Us Group | Why Choose Us Group | `wp-content/plugins/sdweddingdirectory-elementor/elementor/why-choose-group/index.php` | Unchanged to preserve internal widget ID because get_name() uses get_title(). |
| Why Choose Us | Why Choose Us | `wp-content/plugins/sdweddingdirectory-elementor/elementor/why-choose/index.php` | Unchanged to preserve internal widget ID because get_name() uses get_title(). |

## Implementation Notes
- `get_title()` text domains were normalized to `'sdweddingdirectory'` per prompt.
- For widgets where `get_name()` is derived from `self::get_title()` (`Call to Action`, `Contact Box`, `Features Info Section`, `Why Choose Us Group`, `Why Choose Us`), titles were intentionally left unchanged to avoid changing internal widget identifiers.
- No rendering logic, CSS classes, file names, or `get_name()` bodies were changed.

## Verification
- Confirmed each of the 32 widget files now has the expected `get_title()` return value.
- Confirmed diffs are limited to one `get_title()` line per widget file.

---
---

## Phase
13 — Plugin Metadata Rebrand

## Scope
- Scanned plugin headers for all existing `sdweddingdirectory-*` plugins in `wp-content/plugins/`.
- Updated plugin header metadata fields per prompt:
  - `Plugin URI` -> `https://www.sdmusicdirectory.com`
  - `Version` -> `1.0.0`
  - `Author` -> `SDWeddingDirectory`
  - `Author URI` -> `https://www.sdmusicdirectory.com`
  - `Text Domain` -> `sdweddingdirectory`
- Updated remaining theme author metadata in `wp-content/themes/sdweddingdirectory/style.css`.
- Kept all plugin/theme runtime logic, hooks, functions, and file structures unchanged.

## Files Modified
1. `wp-content/plugins/sdweddingdirectory-budget-calculator/sdweddingdirectory-budget-calculator.php`
2. `wp-content/plugins/sdweddingdirectory-claim-venue/sdweddingdirectory-claim-venue.php`
3. `wp-content/plugins/sdweddingdirectory-couple-website/sdweddingdirectory-couple-website.php`
4. `wp-content/plugins/sdweddingdirectory-elementor/sdweddingdirectory-elementor.php`
5. `wp-content/plugins/sdweddingdirectory-guest-list/sdweddingdirectory-guest-list.php`
6. `wp-content/plugins/sdweddingdirectory-venue/sdweddingdirectory-venue.php`
7. `wp-content/plugins/sdweddingdirectory-real-wedding/sdweddingdirectory-real-wedding.php`
8. `wp-content/plugins/sdweddingdirectory-request-quote/sdweddingdirectory-request-quote.php`
9. `wp-content/plugins/sdweddingdirectory-reviews/sdweddingdirectory-reviews.php`
10. `wp-content/plugins/sdweddingdirectory-rsvp/sdweddingdirectory-rsvp.php`
11. `wp-content/plugins/sdweddingdirectory-shortcodes/sdweddingdirectory-shortcodes.php`
12. `wp-content/plugins/sdweddingdirectory-todo-list/sdweddingdirectory-todo-list.php`
13. `wp-content/plugins/sdweddingdirectory-wishlist/sdweddingdirectory-wishlist.php`
14. `wp-content/themes/sdweddingdirectory/style.css`

## Old vs New Header Values
| File | Field | Old Value | New Value |
|---|---|---|---|
| wp-content/plugins/sdweddingdirectory-budget-calculator/sdweddingdirectory-budget-calculator.php | Plugin URI | http://sdweddingdirectory.net | https://www.sdmusicdirectory.com |
| wp-content/plugins/sdweddingdirectory-budget-calculator/sdweddingdirectory-budget-calculator.php | Version | 1.2.5 | 1.0.0 |
| wp-content/plugins/sdweddingdirectory-budget-calculator/sdweddingdirectory-budget-calculator.php | Author | Hitesh Mahavar | SDWeddingDirectory |
| wp-content/plugins/sdweddingdirectory-budget-calculator/sdweddingdirectory-budget-calculator.php | Author URI | https://sdweddingdirectory.net/ | https://www.sdmusicdirectory.com |
| wp-content/plugins/sdweddingdirectory-budget-calculator/sdweddingdirectory-budget-calculator.php | Text Domain | sdweddingdirectory-budget-calculator | sdweddingdirectory |
| wp-content/plugins/sdweddingdirectory-claim-venue/sdweddingdirectory-claim-venue.php | Plugin URI | http://sdweddingdirectory.net | https://www.sdmusicdirectory.com |
| wp-content/plugins/sdweddingdirectory-claim-venue/sdweddingdirectory-claim-venue.php | Version | 1.2.3 | 1.0.0 |
| wp-content/plugins/sdweddingdirectory-claim-venue/sdweddingdirectory-claim-venue.php | Author | Hitesh Mahavar | SDWeddingDirectory |
| wp-content/plugins/sdweddingdirectory-claim-venue/sdweddingdirectory-claim-venue.php | Author URI | https://sdweddingdirectory.net/ | https://www.sdmusicdirectory.com |
| wp-content/plugins/sdweddingdirectory-claim-venue/sdweddingdirectory-claim-venue.php | Text Domain | sdweddingdirectory-claim-venue | sdweddingdirectory |
| wp-content/plugins/sdweddingdirectory-couple-website/sdweddingdirectory-couple-website.php | Plugin URI | http://sdweddingdirectory.net | https://www.sdmusicdirectory.com |
| wp-content/plugins/sdweddingdirectory-couple-website/sdweddingdirectory-couple-website.php | Version | 1.3.8 | 1.0.0 |
| wp-content/plugins/sdweddingdirectory-couple-website/sdweddingdirectory-couple-website.php | Author | Hitesh Mahavar | SDWeddingDirectory |
| wp-content/plugins/sdweddingdirectory-couple-website/sdweddingdirectory-couple-website.php | Author URI | https://sdweddingdirectory.net/ | https://www.sdmusicdirectory.com |
| wp-content/plugins/sdweddingdirectory-couple-website/sdweddingdirectory-couple-website.php | Text Domain | sdweddingdirectory-couple-website | sdweddingdirectory |
| wp-content/plugins/sdweddingdirectory-elementor/sdweddingdirectory-elementor.php | Plugin URI | https://wporganic.com/theme/SDWeddingDirectory/ | https://www.sdmusicdirectory.com |
| wp-content/plugins/sdweddingdirectory-elementor/sdweddingdirectory-elementor.php | Version | 1.3.7 | 1.0.0 |
| wp-content/plugins/sdweddingdirectory-elementor/sdweddingdirectory-elementor.php | Author | wp-organic | SDWeddingDirectory |
| wp-content/plugins/sdweddingdirectory-elementor/sdweddingdirectory-elementor.php | Author URI | http://www.wporganic.com | https://www.sdmusicdirectory.com |
| wp-content/plugins/sdweddingdirectory-elementor/sdweddingdirectory-elementor.php | Text Domain | sdweddingdirectory-elementor | sdweddingdirectory |
| wp-content/plugins/sdweddingdirectory-guest-list/sdweddingdirectory-guest-list.php | Plugin URI | http://sdweddingdirectory.net | https://www.sdmusicdirectory.com |
| wp-content/plugins/sdweddingdirectory-guest-list/sdweddingdirectory-guest-list.php | Version | 1.3.0 | 1.0.0 |
| wp-content/plugins/sdweddingdirectory-guest-list/sdweddingdirectory-guest-list.php | Author | Hitesh Mahavar | SDWeddingDirectory |
| wp-content/plugins/sdweddingdirectory-guest-list/sdweddingdirectory-guest-list.php | Author URI | https://sdweddingdirectory.net/ | https://www.sdmusicdirectory.com |
| wp-content/plugins/sdweddingdirectory-guest-list/sdweddingdirectory-guest-list.php | Text Domain | sdweddingdirectory-guest-list | sdweddingdirectory |
| wp-content/plugins/sdweddingdirectory-venue/sdweddingdirectory-venue.php | Plugin URI | http://sdweddingdirectory.net | https://www.sdmusicdirectory.com |
| wp-content/plugins/sdweddingdirectory-venue/sdweddingdirectory-venue.php | Version | 1.7.8 | 1.0.0 |
| wp-content/plugins/sdweddingdirectory-venue/sdweddingdirectory-venue.php | Author | Hitesh Mahavar | SDWeddingDirectory |
| wp-content/plugins/sdweddingdirectory-venue/sdweddingdirectory-venue.php | Author URI | https://sdweddingdirectory.net/ | https://www.sdmusicdirectory.com |
| wp-content/plugins/sdweddingdirectory-venue/sdweddingdirectory-venue.php | Text Domain | sdweddingdirectory-venue | sdweddingdirectory |
| wp-content/plugins/sdweddingdirectory-real-wedding/sdweddingdirectory-real-wedding.php | Plugin URI | http://sdweddingdirectory.net | https://www.sdmusicdirectory.com |
| wp-content/plugins/sdweddingdirectory-real-wedding/sdweddingdirectory-real-wedding.php | Version | 1.3.7 | 1.0.0 |
| wp-content/plugins/sdweddingdirectory-real-wedding/sdweddingdirectory-real-wedding.php | Author | Hitesh Mahavar | SDWeddingDirectory |
| wp-content/plugins/sdweddingdirectory-real-wedding/sdweddingdirectory-real-wedding.php | Author URI | https://sdweddingdirectory.net/ | https://www.sdmusicdirectory.com |
| wp-content/plugins/sdweddingdirectory-real-wedding/sdweddingdirectory-real-wedding.php | Text Domain | sdweddingdirectory-real-wedding | sdweddingdirectory |
| wp-content/plugins/sdweddingdirectory-request-quote/sdweddingdirectory-request-quote.php | Plugin URI | http://sdweddingdirectory.net | https://www.sdmusicdirectory.com |
| wp-content/plugins/sdweddingdirectory-request-quote/sdweddingdirectory-request-quote.php | Version | 1.4.3 | 1.0.0 |
| wp-content/plugins/sdweddingdirectory-request-quote/sdweddingdirectory-request-quote.php | Author | Hitesh Mahavar | SDWeddingDirectory |
| wp-content/plugins/sdweddingdirectory-request-quote/sdweddingdirectory-request-quote.php | Author URI | https://sdweddingdirectory.net/ | https://www.sdmusicdirectory.com |
| wp-content/plugins/sdweddingdirectory-request-quote/sdweddingdirectory-request-quote.php | Text Domain | sdweddingdirectory-request-quote | sdweddingdirectory |
| wp-content/plugins/sdweddingdirectory-reviews/sdweddingdirectory-reviews.php | Plugin URI | http://sdweddingdirectory.net | https://www.sdmusicdirectory.com |
| wp-content/plugins/sdweddingdirectory-reviews/sdweddingdirectory-reviews.php | Version | 1.4.2 | 1.0.0 |
| wp-content/plugins/sdweddingdirectory-reviews/sdweddingdirectory-reviews.php | Author | Hitesh Mahavar | SDWeddingDirectory |
| wp-content/plugins/sdweddingdirectory-reviews/sdweddingdirectory-reviews.php | Author URI | https://sdweddingdirectory.net/ | https://www.sdmusicdirectory.com |
| wp-content/plugins/sdweddingdirectory-reviews/sdweddingdirectory-reviews.php | Text Domain | sdweddingdirectory-reviews | sdweddingdirectory |
| wp-content/plugins/sdweddingdirectory-rsvp/sdweddingdirectory-rsvp.php | Plugin URI | http://sdweddingdirectory.net | https://www.sdmusicdirectory.com |
| wp-content/plugins/sdweddingdirectory-rsvp/sdweddingdirectory-rsvp.php | Version | 1.1.7 | 1.0.0 |
| wp-content/plugins/sdweddingdirectory-rsvp/sdweddingdirectory-rsvp.php | Author | Hitesh Mahavar | SDWeddingDirectory |
| wp-content/plugins/sdweddingdirectory-rsvp/sdweddingdirectory-rsvp.php | Author URI | https://sdweddingdirectory.net/ | https://www.sdmusicdirectory.com |
| wp-content/plugins/sdweddingdirectory-rsvp/sdweddingdirectory-rsvp.php | Text Domain | sdweddingdirectory-rsvp | sdweddingdirectory |
| wp-content/plugins/sdweddingdirectory-shortcodes/sdweddingdirectory-shortcodes.php | Plugin URI | https://sdweddingdirectory.net/ | https://www.sdmusicdirectory.com |
| wp-content/plugins/sdweddingdirectory-shortcodes/sdweddingdirectory-shortcodes.php | Version | 1.3.7 | 1.0.0 |
| wp-content/plugins/sdweddingdirectory-shortcodes/sdweddingdirectory-shortcodes.php | Author | Hitesh Mahavar | SDWeddingDirectory |
| wp-content/plugins/sdweddingdirectory-shortcodes/sdweddingdirectory-shortcodes.php | Author URI | https://sdweddingdirectory.net/ | https://www.sdmusicdirectory.com |
| wp-content/plugins/sdweddingdirectory-shortcodes/sdweddingdirectory-shortcodes.php | Text Domain | sdweddingdirectory-shortcodes | sdweddingdirectory |
| wp-content/plugins/sdweddingdirectory-todo-list/sdweddingdirectory-todo-list.php | Plugin URI | http://sdweddingdirectory.net | https://www.sdmusicdirectory.com |
| wp-content/plugins/sdweddingdirectory-todo-list/sdweddingdirectory-todo-list.php | Version | 1.2.5 | 1.0.0 |
| wp-content/plugins/sdweddingdirectory-todo-list/sdweddingdirectory-todo-list.php | Author | Hitesh Mahavar | SDWeddingDirectory |
| wp-content/plugins/sdweddingdirectory-todo-list/sdweddingdirectory-todo-list.php | Author URI | https://sdweddingdirectory.net/ | https://www.sdmusicdirectory.com |
| wp-content/plugins/sdweddingdirectory-todo-list/sdweddingdirectory-todo-list.php | Text Domain | sdweddingdirectory-todo-list | sdweddingdirectory |
| wp-content/plugins/sdweddingdirectory-wishlist/sdweddingdirectory-wishlist.php | Plugin URI | http://sdweddingdirectory.net | https://www.sdmusicdirectory.com |
| wp-content/plugins/sdweddingdirectory-wishlist/sdweddingdirectory-wishlist.php | Version | 1.3.8 | 1.0.0 |
| wp-content/plugins/sdweddingdirectory-wishlist/sdweddingdirectory-wishlist.php | Author | Hitesh Mahavar | SDWeddingDirectory |
| wp-content/plugins/sdweddingdirectory-wishlist/sdweddingdirectory-wishlist.php | Author URI | https://sdweddingdirectory.net/ | https://www.sdmusicdirectory.com |
| wp-content/plugins/sdweddingdirectory-wishlist/sdweddingdirectory-wishlist.php | Text Domain | sdweddingdirectory-wishlist | sdweddingdirectory |
| wp-content/themes/sdweddingdirectory/style.css | Author | wp-organic | SDWeddingDirectory |
| wp-content/themes/sdweddingdirectory/style.css | Author URI | https://wporganic.com/ | https://www.sdmusicdirectory.com |

## Verification
- Confirmed headers for each `sdweddingdirectory-*` plugin main file with `rg` field checks.
- Confirmed theme author metadata updates in `wp-content/themes/sdweddingdirectory/style.css`.
- Ran PHP syntax checks (`php -l`) on edited plugin main files; no syntax errors.

---
---

## Phase
14 — Seating Chart Plugin (Couple Dashboard)

## Scope
- Added a new plugin: `sdweddingdirectory-seating-chart`.
- Registered a new couple dashboard tab: `Seating Chart` at menu priority `85`.
- Implemented a drag-and-drop seating chart builder with table creation, seat assignment from Guest Management data, save, print preview, and JSON export.
- Persisted chart data in couple post meta using key `sdweddingdirectory_seating_chart_data`.
- Used jQuery/vanilla JS and Bootstrap-compatible markup; no new framework introduced.

## New Plugin File Manifest
1. `wp-content/plugins/sdweddingdirectory-seating-chart/index.php`
2. `wp-content/plugins/sdweddingdirectory-seating-chart/sdweddingdirectory-seating-chart.php`
3. `wp-content/plugins/sdweddingdirectory-seating-chart/database/index.php`
4. `wp-content/plugins/sdweddingdirectory-seating-chart/filters-hooks/index.php`
5. `wp-content/plugins/sdweddingdirectory-seating-chart/filters-hooks/menu/index.php`
6. `wp-content/plugins/sdweddingdirectory-seating-chart/admin-file/index.php`
7. `wp-content/plugins/sdweddingdirectory-seating-chart/couple-file/index.php`
8. `wp-content/plugins/sdweddingdirectory-seating-chart/couple-file/style.css`
9. `wp-content/plugins/sdweddingdirectory-seating-chart/couple-file/script.js`
10. `wp-content/plugins/sdweddingdirectory-seating-chart/ajax/index.php`
11. `wp-content/plugins/sdweddingdirectory-seating-chart/languages/` (directory)

## Architecture Decisions
1. Plugin bootstrap pattern follows existing `sdweddingdirectory-*` plugins:
   - Main plugin class loads `database`, `filters-hooks`, `admin-file`, `couple-file`, and `ajax` modules.
   - Constants for version/URL/paths are defined in plugin bootstrap.
2. Dashboard menu integration:
   - Hook: `sdweddingdirectory/couple/dashboard/menu`
   - Priority: `85` (after Guest Management group)
   - Menu slug/page key: `seating-chart`
3. Dashboard rendering integration:
   - Hook: `sdweddingdirectory/couple-dashboard`
   - Priority: `85`
   - Renders only when `?dashboard=seating-chart`.
4. Data model choice:
   - Followed Guest Management pattern (couple post-level storage via `post_id()`), not a new CPT.
   - Meta key: `sdweddingdirectory_seating_chart_data`
   - Payload structure:
     - `tables[]` with `id`, `name`, `shape`, `seats`, `x`, `y`, `assignments[]`
     - `updated_at`
5. Guest source integration:
   - Reads guest options from Guest Management meta key `guest_list_data`.
   - Converts each guest into `[id, label]` for assignment dropdowns.
6. Security and validation:
   - AJAX action: `wp_ajax_sdweddingdirectory_seating_chart_save`
   - Nonce: `sdweddingdirectory_seating_chart_nonce`
   - Requires authenticated couple context (`is_couple()` + `post_id()`).
   - Server-side normalization clamps seat counts, sanitizes names/IDs, and bounds positions.
7. UI behavior:
   - Add table form supports round/rectangular shapes and seat count.
   - Drag/drop table placement in floor plan canvas.
   - Seat assignment selectors for each table.
   - Read-only print/export preview block.
   - Print and JSON export controls.
8. Reliability fix applied:
   - Reworked seat-select option rendering in `couple-file/script.js` so selected guests are set deterministically per seat (removed fragile string replacement logic).

## Prompt 14 Verification
- PHP syntax checks passed for all new plugin PHP files:
  - `find wp-content/plugins/sdweddingdirectory-seating-chart -name '*.php' -print0 | xargs -0 -n1 php -l`
- Confirmed menu hook + priority and dashboard hook + priority are wired in plugin files.
- Confirmed plugin header metadata requirements:
  - Author: `SDWeddingDirectory`
  - Author URI: `https://www.sdmusicdirectory.com`
  - Version: `1.0.0`
  - Text Domain: `sdweddingdirectory`

## WP-CLI Commands Run (Prompt 13 & 14)
- None.

---

## Timestamp
2026-02-28 05:24:43 PST

## Prompt
Claude — Image Updates & Banner Tasks (2026-02-28)

## Scope Completed
1. Homepage hero: randomized banner image selection using `b-1` to `b-5` and updated hero height to 400px with homepage overlay disabled.
2. Venue/vendor search banners: split banner image selection by page type and set section height to 200px.
3. Top Notch Entertainment vendor profile: imported banner media and set `profile_banner` meta for vendor post `5118`.

## Files Modified
1. `wp-content/plugins/sdweddingdirectory-shortcodes/shortcodes/home-slider/index.php`
- Added filter registration in constructor:
  - `add_filter( 'sdweddingdirectory/home-slider/background-image', [ $this, 'randomize_homepage_banner' ] );`
- Applied filter inside `sdweddingdirectory_background_image()`:
  - `$media_url = apply_filters( 'sdweddingdirectory/home-slider/background-image', $media_url );`
- Added `randomize_homepage_banner()` method to return one random banner URL on front page requests.

2. `wp-content/themes/sdweddingdirectory/assets/css/theme-style.css`
- Updated `.slider-versin-two .slider-wrap` min-height from `750px` to `400px`.
- Updated `.slider-versin-two .slider-content` alignment for left-side white area:
  - width `70%`, `left: 0`, `padding-left: 5%`, `transform: translateY(-50%)`.
- Added homepage-only overlay removal rule:
  - `body.home .slider-versin-two .slider-wrap::after { display: none; }`
- Updated responsive `@media (max-width: 1400px)` `.slider-versin-two .slider-wrap` min-height from `700px` to `400px`.

3. `wp-content/themes/sdweddingdirectory/inc/page-header-banner.php`
- Replaced shared venue/vendor search banner block with conditional banner routing:
  - Vendor contexts (`/vendors/`, vendor category tax pages, vendor category template) use `vendor-banner_1920x200.png`.
  - Venue contexts (`/venues/`, venue category tax pages) use `venue-banner_1920x200.png`.
- Updated inline heights from `180px` to `200px` for section and row wrappers.

4. `wp-content/themes/sdweddingdirectory/assets/css/global.css`
- Updated responsive search banner min-height at `@media (max-width: 991px)`:
  - from `140px` to `160px`.

## Media Preparation and Import
- Copied/renamed source files from:
  - `/Users/Havok/Documents/Development/WebDevelopment/Images/edited/`
- Destination files created in local uploads path:
  - `wp-content/uploads/2026/02/b-1_1920x400.png`
  - `wp-content/uploads/2026/02/b-2_1920x400.png`
  - `wp-content/uploads/2026/02/b-3_1920x400.png`
  - `wp-content/uploads/2026/02/b-4_1920x400.png`
  - `wp-content/uploads/2026/02/b-5_1920x400.png`
  - `wp-content/uploads/2026/02/venue-banner_1920x200.png`
  - `wp-content/uploads/2026/02/vendor-banner_1920x200.png`
  - `wp-content/uploads/2026/02/top-notch-banner_1920x600.png`

## WP-CLI / Container Commands Run
1. Media import:
- `docker compose exec wp_ssh wp media import ... --allow-root`
- Imported attachment IDs:
  - `5364` => `b-1_1920x400.png`
  - `5365` => `b-2_1920x400.png`
  - `5366` => `b-3_1920x400.png`
  - `5367` => `b-4_1920x400.png`
  - `5368` => `b-5_1920x400.png`
  - `5369` => `venue-banner_1920x200.png`
  - `5370` => `vendor-banner_1920x200.png`
  - `5371` => `top-notch-banner_1920x600.png`

2. Vendor banner meta update:
- `docker compose exec wp_ssh wp post meta update 5118 profile_banner 5371 --allow-root`

## Verification Results
- PHP lint:
  - `php -l wp-content/plugins/sdweddingdirectory-shortcodes/shortcodes/home-slider/index.php` => OK
  - `php -l wp-content/themes/sdweddingdirectory/inc/page-header-banner.php` => OK

- Homepage randomization check (40 requests):
  - `b-1_1920x400.png`: 7
  - `b-2_1920x400.png`: 8
  - `b-3_1920x400.png`: 8
  - `b-4_1920x400.png`: 9
  - `b-5_1920x400.png`: 8

- Venue/vendor banner checks:
  - `/venues/` renders `venue-banner_1920x200.png` with `min-height: 200px`
  - `/vendors/` renders `vendor-banner_1920x200.png` with `min-height: 200px`
  - `/banquet-hall-weddings/` renders `venue-banner_1920x200.png`
  - `/vendors/bands/` renders `vendor-banner_1920x200.png`

- Top Notch profile banner checks:
  - `wp post meta get 5118 profile_banner` => `5371`
  - `/vendor/top-notch-entertainment/` renders `.vendor-bg` with `top-notch-banner_1920x600.png`

## Notes
- This repository was already in a dirty state before these changes; only the files listed above were edited for this prompt.

---

## Timestamp
2026-02-28 06:25:00 PST

## Prompt
11 — Plugin Audit & Cleanup (Rewritten)

## Source of Truth Commands
- `docker compose exec wp_ssh wp plugin list --status=active --fields=name,title,version,author,status --format=table --allow-root`
- `docker compose exec wp_ssh wp plugin list --status=inactive --fields=name,title,version,author,status --format=table --allow-root`

## Additional Evidence Commands
- `docker compose exec wp_ssh wp post list --post_type=wpcf7_contact_form --format=count --allow-root` => 3
- `docker compose exec wp_ssh wp post list --post_type=mc4wp-form --format=count --allow-root` => 1
- `docker compose exec wp_ssh wp db query "SELECT COUNT(*) AS posts_with_elementor FROM wp_postmeta WHERE meta_key='_elementor_data' AND meta_value IS NOT NULL AND meta_value <> '';" --allow-root` => 36
- `docker compose exec wp_ssh wp db query "SELECT COUNT(*) AS cf7_shortcode_refs FROM wp_posts WHERE post_content LIKE '%[contact-form-7%';" --allow-root` => 1
- `docker compose exec wp_ssh wp db query "SELECT post_type, COUNT(*) AS cnt FROM wp_posts WHERE post_status NOT IN ('auto-draft','trash') GROUP BY post_type ORDER BY cnt DESC;" --allow-root`
- `docker compose exec wp_ssh wp db query "SELECT 'claim-venue' AS post_type, COUNT(*) AS cnt FROM wp_posts WHERE post_type='claim-venue' UNION ALL SELECT 'invoice', COUNT(*) FROM wp_posts WHERE post_type='invoice' UNION ALL SELECT 'pricing', COUNT(*) FROM wp_posts WHERE post_type='pricing';" --allow-root` => all 0
- `rg` scans for `get_field`, `OT_Loader`, `bcn_display_list`, map-provider hooks, and dashboard/wishlist/claim/rsvp integrations.

## Plugin Audit Table
| Plugin Name | Status | Category | Reason | Removal Notes |
|---|---|---|---|---|
| advanced-custom-fields-pro | active | ESSENTIAL | ACF term fields power venue/vendor filter definitions (`get_field` usage across search/venue plugins). | N/A |
| tinymce-advanced | active | OPTIONAL | Editor UX enhancement only; not required for frontend runtime. | Deactivate and confirm editor toolbar needs are acceptable in Classic Editor/Gutenberg. |
| breadcrumb-navxt | active | ESSENTIAL | Breadcrumbs rendered via `bcn_display_list()` in theme page-header banner. | N/A |
| classic-editor | active | ESSENTIAL | Required for legacy edit flows in this project and existing admin workflow. | N/A |
| classic-widgets | active | OPTIONAL | Admin widget management helper; frontend can run without it. | Deactivate and verify Widgets screen usage before removal. |
| contact-form-7 | active | ESSENTIAL | 3 CF7 forms exist and shortcode references exist in content. | Remove only after replacing all forms and shortcode refs. |
| elementor | active | ESSENTIAL | 36 posts contain `_elementor_data`; 7 Elementor library templates exist. | N/A |
| mailchimp-for-wp | active | OPTIONAL | One form CPT exists; no direct shortcode refs in post content found. | Remove only if newsletter capture is migrated elsewhere. |
| nextend-facebook-connect | active | OPTIONAL | Social login integration plugin; no direct content refs detected. | Remove if social login is not part of auth strategy. |
| option-tree | active | ESSENTIAL | Theme/plugins rely heavily on `OT_Loader`, `ot_get_option`, and OT meta boxes. | N/A |
| regenerate-thumbnails | active | REMOVE | One-time maintenance utility; no business runtime dependency. | Safe to deactivate/delete after confirming media sizes are generated. |
| sdweddingdirectory | active | ESSENTIAL | Core marketplace framework plugin. | N/A |
| sdweddingdirectory-budget-calculator | active | ESSENTIAL | Couple dashboard planning tool. | N/A |
| sdweddingdirectory-claim-venue | active | OPTIONAL | Claim flow integration exists, but `claim-venue` post count is currently 0. | Remove only if “Claim Now” flow is permanently retired. |
| sdweddingdirectory-couple-website | active | ESSENTIAL | Couple website dashboard/public website feature. | N/A |
| sdweddingdirectory-elementor | active | ESSENTIAL | Custom SDWeddingDirectory Elementor widgets used by current pages. | N/A |
| sdweddingdirectory-guest-list | active | ESSENTIAL | Guest management + RSVP handling; also feeds seating chart assignment. | N/A |
| sdweddingdirectory-venue | active | ESSENTIAL | Core venue/venue logic and filter architecture. | N/A |
| sdweddingdirectory-real-wedding | active | ESSENTIAL | `real-wedding` CPT exists with content and homepage integration. | N/A |
| sdweddingdirectory-request-quote | active | ESSENTIAL | Quote request and dashboard quote functionality. | N/A |
| sdweddingdirectory-reviews | active | ESSENTIAL | Review/rating feature integration. | N/A |
| sdweddingdirectory-rsvp | active | OPTIONAL | Standalone RSVP module in addition to guest-list RSVP workflows. | Remove only if RSVP workflow is consolidated elsewhere. |
| sdweddingdirectory-seating-chart | active | ESSENTIAL | Required couple dashboard seating chart feature (Prompt 14). | N/A |
| sdweddingdirectory-shortcodes | active | ESSENTIAL | Many frontend widgets/layout blocks depend on shortcode outputs. | N/A |
| sdweddingdirectory-todo-list | active | ESSENTIAL | Couple checklist dashboard module. | N/A |
| sdweddingdirectory-wishlist | active | OPTIONAL | Wishlist/vendor-manager enhancement; not required for core venue render. | Remove only after retiring wishlist UX and related AJAX actions. |
| weddingdir-google-map | active | OPTIONAL | Map provider plugin; one of two map engines used by map provider hooks. | Keep one map provider strategy (Google vs Leaflet) before removal. |
| weddingdir-invoice | active | UNUSED | Invoice CPT is registered but currently has 0 posts. | Deactivate/delete if monetization invoices are not being used. |
| weddingdir-leaflet-map | active | ESSENTIAL | Leaflet integration is referenced by frontend map scripts and provider hooks. | N/A until map provider architecture is replaced. |
| weddingdir-pricing | active | UNUSED | Pricing CPT is registered but currently has 0 posts. | Deactivate/delete if package-pricing flow remains unused. |
| white-label-cms | active | OPTIONAL | Admin branding/usability plugin only; no frontend dependency. | Remove if backend white-labeling is not needed. |
| weddingdir-payfast | inactive | UNUSED | Inactive payment gateway module. | Safe to remove unless PayFast will be enabled. |
| weddingdir-paypal | inactive | UNUSED | Inactive payment gateway module. | Safe to remove unless PayPal gateway will be enabled. |
| weddingdir-paystack | inactive | UNUSED | Inactive payment gateway module. | Safe to remove unless Paystack gateway will be enabled. |
| weddingdir-razorpay | inactive | UNUSED | Inactive payment gateway module. | Safe to remove unless Razorpay gateway will be enabled. |
| weddingdir-stripe | inactive | UNUSED | Inactive payment gateway module. | Safe to remove unless Stripe gateway will be enabled. |

## Prompt 11 Notes
- Audit only completed (no plugin activation/deactivation/deletion performed).
- `weddingdir-*` plugins were explicitly flagged for metadata rebrand in Prompt 13.

---

## Timestamp
2026-02-28 06:26:00 PST

## Prompt
12 — Custom Widget Rename & Alignment

## Scope
- Enumerated custom Elementor widgets from the running site using:
  - `docker compose exec wp_ssh wp eval '...Elementor widgets_manager...' --allow-root`
- Confirmed custom widget registrations are in `wp-content/plugins/sdweddingdirectory-elementor/elementor/*/index.php`.
- Updated only `get_title()` values (no `get_name()`, file names, CSS classes, or render logic changed).

## Widget Title Changes
| Old Title | New Title | File Path | Reason |
|---|---|---|---|
| Action Button | CTA Button | wp-content/plugins/sdweddingdirectory-elementor/elementor/button/index.php | Better reflects intended call-to-action usage in page layouts. |
| Venue Search Form | Directory Search Form | wp-content/plugins/sdweddingdirectory-elementor/elementor/find-venue-form/index.php | Widget is used for broader directory search contexts, not only venue-only contexts. |
| Section Heading | Section Title | wp-content/plugins/sdweddingdirectory-elementor/elementor/section-title/index.php | Aligns terminology with Elementor/editor conventions and SDWeddingDirectory content naming. |

## Widget Inventory Snapshot
- Total custom SDWeddingDirectory Elementor widgets detected: 32
- Internal names + class/title inventory captured from live Elementor widget manager (includes all `sdweddingdirectory`-prefixed widget IDs).

---

## Timestamp
2026-02-28 06:27:00 PST

## Prompt
13 — Plugin Metadata Rebrand (Rewritten)

## Scope
- Updated metadata headers in ALL plugin main files under:
  - `wp-content/plugins/sdweddingdirectory-*/`
  - `wp-content/plugins/weddingdir-*/`
- Updated theme header metadata references in:
  - `wp-content/themes/sdweddingdirectory/style.css`
- Modified only header comment metadata fields.

## Header Standard Applied
- `Plugin URI`: `https://www.sdweddingdirectory.com`
- `Author`: `SDWeddingDirectory`
- `Author URI`: `https://www.sdweddingdirectory.com`
- `Version`: `1.0.0` (for all weddingdir-* plugin headers)
- `Plugin Name`: `WeddingDir - ...` -> `SDWeddingDirectory - ...` for weddingdir-* plugin headers
- `Text Domain`: set to `sdweddingdirectory` for weddingdir-* plugin headers
- `Description`: retained existing text; no WeddingDir references remained in changed description lines.

## Metadata Change Table
| File Path | Old Plugin Name | New Plugin Name | Other Fields Changed |
|---|---|---|---|
| wp-content/plugins/sdweddingdirectory-budget-calculator/sdweddingdirectory-budget-calculator.php | SDWeddingDirectory - Budget Calculator | SDWeddingDirectory - Budget Calculator | Plugin URI, Author URI updated to `https://www.sdweddingdirectory.com` |
| wp-content/plugins/sdweddingdirectory-claim-venue/sdweddingdirectory-claim-venue.php | SDWeddingDirectory - Claim Venue | SDWeddingDirectory - Claim Venue | Plugin URI, Author URI updated to `https://www.sdweddingdirectory.com` |
| wp-content/plugins/sdweddingdirectory-couple-website/sdweddingdirectory-couple-website.php | SDWeddingDirectory - Couple Website | SDWeddingDirectory - Couple Website | Plugin URI, Author URI updated to `https://www.sdweddingdirectory.com` |
| wp-content/plugins/sdweddingdirectory-elementor/sdweddingdirectory-elementor.php | SDWeddingDirectory - Elementor Page Builder | SDWeddingDirectory - Elementor Page Builder | Plugin URI, Author URI updated to `https://www.sdweddingdirectory.com` |
| wp-content/plugins/sdweddingdirectory-guest-list/sdweddingdirectory-guest-list.php | SDWeddingDirectory - Guest List | SDWeddingDirectory - Guest List | Plugin URI, Author URI updated to `https://www.sdweddingdirectory.com` |
| wp-content/plugins/sdweddingdirectory-venue/sdweddingdirectory-venue.php | SDWeddingDirectory - Venue | SDWeddingDirectory - Venue | Plugin URI, Author URI updated to `https://www.sdweddingdirectory.com` |
| wp-content/plugins/sdweddingdirectory-real-wedding/sdweddingdirectory-real-wedding.php | SDWeddingDirectory - Real Wedding | SDWeddingDirectory - Real Wedding | Plugin URI, Author URI updated to `https://www.sdweddingdirectory.com` |
| wp-content/plugins/sdweddingdirectory-request-quote/sdweddingdirectory-request-quote.php | SDWeddingDirectory - Request Quote | SDWeddingDirectory - Request Quote | Plugin URI, Author URI updated to `https://www.sdweddingdirectory.com` |
| wp-content/plugins/sdweddingdirectory-reviews/sdweddingdirectory-reviews.php | SDWeddingDirectory - Reviews | SDWeddingDirectory - Reviews | Plugin URI, Author URI updated to `https://www.sdweddingdirectory.com` |
| wp-content/plugins/sdweddingdirectory-rsvp/sdweddingdirectory-rsvp.php | SDWeddingDirectory - RSVP | SDWeddingDirectory - RSVP | Plugin URI, Author URI updated to `https://www.sdweddingdirectory.com` |
| wp-content/plugins/sdweddingdirectory-seating-chart/sdweddingdirectory-seating-chart.php | SDWeddingDirectory - Seating Chart | SDWeddingDirectory - Seating Chart | Plugin URI, Author URI updated to `https://www.sdweddingdirectory.com` |
| wp-content/plugins/sdweddingdirectory-shortcodes/sdweddingdirectory-shortcodes.php | SDWeddingDirectory - Shortcodes | SDWeddingDirectory - Shortcodes | Plugin URI, Author URI updated to `https://www.sdweddingdirectory.com` |
| wp-content/plugins/sdweddingdirectory-todo-list/sdweddingdirectory-todo-list.php | SDWeddingDirectory - Todo List | SDWeddingDirectory - Todo List | Plugin URI, Author URI updated to `https://www.sdweddingdirectory.com` |
| wp-content/plugins/sdweddingdirectory-wishlist/sdweddingdirectory-wishlist.php | SDWeddingDirectory - Wishlist | SDWeddingDirectory - Wishlist | Plugin URI, Author URI updated to `https://www.sdweddingdirectory.com` |
| wp-content/plugins/weddingdir-google-map/weddingdir-google-map.php | WeddingDir - Google Map | SDWeddingDirectory - Google Map | Plugin URI, Version, Author, Author URI, Text Domain updated |
| wp-content/plugins/weddingdir-invoice/weddingdir-invoice.php | WeddingDir - Invoice | SDWeddingDirectory - Invoice | Plugin URI, Version, Author, Author URI, Text Domain updated |
| wp-content/plugins/weddingdir-leaflet-map/weddingdir-leaflet-map.php | WeddingDir - Leaflet Map | SDWeddingDirectory - Leaflet Map | Plugin URI, Version, Author, Author URI, Text Domain updated |
| wp-content/plugins/weddingdir-payfast/weddingdir-payfast.php | WeddingDir - PayFast | SDWeddingDirectory - PayFast | Plugin URI, Version, Author, Author URI, Text Domain updated |
| wp-content/plugins/weddingdir-paypal/weddingdir-paypal.php | WeddingDir - Paypal | SDWeddingDirectory - Paypal | Plugin URI, Version, Author, Author URI, Text Domain updated |
| wp-content/plugins/weddingdir-paystack/weddingdir-paystack.php | WeddingDir - PayStack | SDWeddingDirectory - PayStack | Plugin URI, Version, Author, Author URI, Text Domain updated |
| wp-content/plugins/weddingdir-pricing/weddingdir-pricing.php | WeddingDir - Pricing Table | SDWeddingDirectory - Pricing Table | Plugin URI, Version, Author, Author URI, Text Domain updated |
| wp-content/plugins/weddingdir-razorpay/weddingdir-razorpay.php | WeddingDir - Razorpay | SDWeddingDirectory - Razorpay | Plugin URI, Version, Author, Author URI, Text Domain updated |
| wp-content/plugins/weddingdir-stripe/weddingdir-stripe.php | WeddingDir - Stripe | SDWeddingDirectory - Stripe | Plugin URI, Version, Author, Author URI, Text Domain updated |

## Theme Header Metadata Updates
- `wp-content/themes/sdweddingdirectory/style.css`
  - Theme URI: `https://www.sdweddingdirectory.com`
  - Author URI: `https://www.sdweddingdirectory.com`

## Validation
- Ran `php -l` across all modified plugin PHP files (all passed, no syntax errors).

---

## Phase
Prompt D + Prompt E + Alignment Addendum (A-E continuation)

## Prompt D — Homepage Sections 7-10 (Completion)

### What was fixed
- Confirmed sections 7-10 were already present in Elementor `_elementor_data` for homepage page ID `1915`.
- Root issue was cache/render state; after cache regeneration, sections rendered correctly on frontend.
- Added dedicated CSS for sections 7-10 and homepage title alignment behavior.

### Files Modified
1. `wp-content/themes/sdweddingdirectory/assets/css/global.css`
- Added homepage section styling for:
  - `.sd-home-section`
  - `.sd-home-section-7`
  - `.sd-home-section-8`
  - `.sd-home-section-9`
  - `.sd-home-section-10`
  - link rows, separators, city links, responsive spacing.
- Added homepage title alignment override:
  - `body.home .section-title, body.home .section-title h2, body.home .section-title p { text-align: left !important; }`

### Prompt D Verification
- `/` now includes section markers/content:
  - `Search by category to find the perfect wedding team` (Section 7)
  - `Why SDWeddingDirectory?` (Section 9)
  - `Browse Wedding Venues by City` (Section 10)

## Prompt E — Registration Modal Fix

### What was fixed
- Reworked couple/vendor registration AJAX handlers to support immediate successful registration UX:
  - reliable AJAX action registration (explicit `wp_ajax` + `wp_ajax_nopriv` hooks)
  - explicit `wp_insert_user()` error handling
  - immediate verified status (`sdweddingdirectory_user_verify = yes`)
  - immediate login via `wp_set_current_user()` + `wp_set_auth_cookie()`
  - success response now returns `redirect: true` + `modal_close: true`
- Added fallback post creation to guarantee profile post creation if configuration action does not create it:
  - couple: creates `couple` CPT post with required meta
  - vendor: creates `vendor` CPT post with required meta + assigns `vendor-category`

### Files Modified
1. `wp-content/plugins/sdweddingdirectory/front-file/couple-login-register/ajax.php`
- Replaced dynamic-on-request AJAX registration with explicit action hooks.
- Added `wp_insert_user()` WP_Error handling.
- Changed popup registration branch from email-verification-only flow to immediate verified/login flow.
- Added fallback couple CPT post creation and meta population.

2. `wp-content/plugins/sdweddingdirectory/front-file/vendor-login-register/ajax.php`
- Replaced dynamic-on-request AJAX registration with explicit action hooks.
- Added `wp_insert_user()` WP_Error handling.
- Changed popup registration branch from email-verification-only flow to immediate verified/login flow.
- Added fallback vendor CPT post creation, meta population, and taxonomy assignment.

### Prompt E Verification
- Couple registration AJAX response now:
  - `{"notice":1,"message":"Your account is created successfully!","redirect":true,"modal_close":true,...}`
- Vendor registration AJAX response now:
  - `{"notice":1,"message":"Supplier account create successfully!","redirect":true,"modal_close":true,...}`
- Verified DB inserts for test accounts and posts:
  - users:
    - `codex_couple_1772301365` (ID 49)
    - `codex_vendor_1772301365` (ID 48)
  - posts:
    - `couple` post ID `5374` title `codex_couple_1772301365`
    - `vendor` post ID `5373` title `codex_vendor_1772301365`

## Alignment Addendum — Standardize to 1320px

### Files Modified
1. `wp-content/themes/sdweddingdirectory/header-style-2.php`
- Changed navbar container:
  - from `container-fluid` to `container`

2. `wp-content/themes/sdweddingdirectory/assets/css/theme-style.css`
- Updated hero content container width:
  - `.slider-versin-two .slider-content .container { max-width: 1320px; }`

### WP-CLI / Cache Commands
1. `docker exec wp_ssh wp option update elementor_container_width 1320 --allow-root`
2. `docker exec wp_ssh wp elementor flush-css --allow-root`
3. `docker exec wp_ssh wp cache flush --allow-root`

### Alignment Verification
- Header container class verified on:
  - `/`
  - `/venues/`
  - `/vendors/djs/`
  - `/vendor/top-notch-entertainment/`
  - `/blog/`
- Elementor width option verified:
  - `elementor_container_width = 1320`
- Hero container CSS override verified in source CSS.

## Notes
- Prompt D render issue was cache-related after meta updates; flushing WP/Elementor cache resolved frontend rendering.
- Test users/posts were created during registration verification and remain in local DB.

## Couples Dashboard Stabilization (Post-Launch)

### Root Causes Identified
1. Guest menu visibility regression:
- `guest-management-rsvp`, `guest-management-statistic`, and `guest-management-invitation` were set to visible by default, creating duplicate Guest Management menu entries.

2. Seating chart plugin hook collision:
- `SDWeddingDirectory_Seating_Chart_Database` inherited `SDWeddingDirectory_Config` constructor hooks.
- This registered unintended global core filters (notably `sdweddingdirectory/post/data`) from the seating plugin context and corrupted shared post lookup behavior across dashboard modules.

3. Missing couple-linked module posts:
- Existing couple users could be missing required `couple`, `website`, or `real-wedding` posts, causing module failures and "post query issue found" screens.

4. Seating chart drag math bug:
- Drag logic mixed relative coordinates with page coordinates, causing table jump-to-corner behavior.

### Files Modified
1. `wp-content/plugins/sdweddingdirectory-guest-list/filters-hooks/menu/index.php`
- Reverted submenu defaults to match original behavior:
  - `guest_list_rsvp/menu-show` -> `false`
  - `guest-management-statistic/menu-show` -> `false`
  - `guest-management-invitation/menu-show` -> `false`

2. `wp-content/plugins/sdweddingdirectory/config-file/couple-config/index.php`
- Added init-time auto-repair flow for logged-in couple users:
  - `maybe_repair_logged_in_couple_configuration()`
  - `configuration_post_count()`
  - `repair_couple_configuration()`
- Hooks into `init` (priority 200) and reuses existing `sdweddingdirectory/register/couple/configuration` action to provision missing `couple`, `website`, and `real-wedding` posts safely.

3. `wp-content/plugins/sdweddingdirectory-seating-chart/database/index.php`
- Added explicit empty constructor:
  - `public function __construct() {}`
- Prevents inheriting and registering core plugin constructor hooks from `SDWeddingDirectory_Config`.

4. `wp-content/plugins/sdweddingdirectory-seating-chart/couple-file/script.js`
- Fixed drag offset calculation:
  - changed from `$node.position()` to `$node.offset()`
  - added null offset guard
- Prevents drag coordinate mismatch that pushed tables to top-left.

### Verification
1. Hook collision removed:
- `sdweddingdirectory/post/data` callback list now contains only:
  - `SDWeddingDirectory_Config::post_dropdown`

2. Guest menu visibility corrected:
- Visible couple menu includes only one `Guest Management` item (no duplicate RSVP/statistics/invitation entries in side menu).

3. Couple-module post provisioning validated:
- After running provisioning action for couple users, counts are:
  - `couple: ok=6, missing=0, duplicate=0`
  - `website: ok=6, missing=0, duplicate=0`
  - `real-wedding: ok=6, missing=0, duplicate=0`

4. Syntax checks passed:
- `php -l` passed for all modified PHP files.
- `node --check` passed for seating chart JS.

## Phase 1 Continuation — Tasks 6, 7, 8 (Elementor Cleanup + Validation)

### Task 6 — Elementor-specific CSS remap
- Updated planning page accordion sizing selectors to remove dependency on removed Elementor wrapper class and target new template wrapper.

#### File Modified
1. `wp-content/themes/sdweddingdirectory/assets/css/theme-style.css`
- Replaced:
  - `body.page-id-4180 .elementor-widget-sdweddingdirectory-elementor-accordion`
- With:
  - `body.page-id-4180 .sdwd-plan-wrap-narrow`
- Kept desktop/tablet/mobile sizing/margin behavior equivalent.

### Task 7 — Database scans before final deactivation

#### Queries Run (inside Docker)
1. `SELECT ID, post_title, post_type, post_status FROM wp_posts WHERE post_content LIKE '%[sdweddingdirectory_%' AND post_status IN ('publish','draft','private') ORDER BY post_type, ID;`
- Result: only page `4180` (`Wedding Planning`).

2. `SELECT p.ID, p.post_title, pm2.meta_value AS template FROM wp_posts p INNER JOIN wp_postmeta pm ON p.ID = pm.post_id AND pm.meta_key = '_elementor_edit_mode' AND pm.meta_value = 'builder' LEFT JOIN wp_postmeta pm2 ON p.ID = pm2.post_id AND pm2.meta_key = '_wp_page_template' WHERE p.post_status = 'publish' AND p.post_type = 'page' ORDER BY p.ID;`
- Builder pages still marked in meta: `91, 1915, 1943, 1978, 1987, 2028, 2420, 4180, 5339`.
- Verified active page routes are template-driven (no runtime dependency on Elementor plugin).

### Task 8 — Final cleanup + deactivation

#### Files Modified
1. `wp-content/themes/sdweddingdirectory/inc/tgm-plugin/required-plugin.php`
- Removed Elementor from required plugin list.
- Set non-runtime setup plugins to non-required:
  - WordPress Importer: `required => false`
  - Widget Importer & Exporter: `required => false`
  - Regenerate Thumbnails: `required => false`

2. `wp-content/themes/sdweddingdirectory/single.php`
- Removed Elementor preview/edit-mode branch.
- Single posts now always render via `do_action('sdweddingdirectory_article', ...)`.

3. `wp-content/themes/sdweddingdirectory/template-parts/content-helper.php`
- Simplified `is_elementor_edit_mode()` to always return `false`.

4. `wp-content/themes/sdweddingdirectory/page-icons.php`
- Deleted (launch cleanup).

5. `wp-content/plugins/sdweddingdirectory/config-file/index.php`
- Added defensive checks to prevent vendor single-page fatal when author user data is missing/incomplete:
  - `venue_author_is_vendor()` now safely handles non-array roles and empty post input.
  - `author_info()` now validates `get_user_by()` result and returns safe defaults (`[]` for roles, `''` for scalar fields).

### WP-CLI / Docker commands executed
1. Deactivated Elementor plugins:
- `wp plugin deactivate elementor sdweddingdirectory-elementor --allow-root`

2. Verified plugin status:
- `wp plugin list --fields=name,status --format=csv --allow-root`
  - `elementor,inactive`
  - `sdweddingdirectory-elementor,inactive`

3. Looked up planning child slugs, sample single slugs, and executed validation DB queries.

### Verification / Smoke Test
Executed HTTP smoke tests via Docker on:
- `/`
- `/about/`
- `/our-team/`
- `/faqs/`
- `/contact/`
- `/inspiration/`
- `/wedding-planning/`
- `/wedding-planning/wedding-checklist/`
- `/wedding-planning/wedding-seating-chart/`
- `/wedding-planning/vendor-manager/`
- `/wedding-planning/wedding-guest-list/`
- `/wedding-planning/wedding-budget/`
- `/wedding-planning/wedding-website/`
- `/venues/`
- `/vendors/`
- `/venues/viejas-casino-resort-2/`
- `/vendor/movin-tunes/`
- `/real-wedding/couple/`
- `/dashboard/`
- `/nonexistent-page/`

Final results:
- 200 on all primary pages and singles tested.
- `/real-wedding/couple/` returns expected 301 redirect.
- `/dashboard/` returns expected 302 redirect.
- `/nonexistent-page/` returns expected 404.
- No PHP fatal error patterns in response bodies after plugin fix.

## Codex Move Images Task — uploads to Theme Assets

### Assets copied (backup originals kept in uploads)
- Copied banner files to `wp-content/themes/sdweddingdirectory/assets/images/banners/`:
  - `home-hero.png`, `vendors-search.png`, `venues-search.png`
- Copied category card images to `assets/images/categories/`:
  - `venues.png`, `photographers.png`, `caterers.png`, `attire.png`
- Copied category icon images to `assets/images/icons/categories/`:
  - `venues.png`, `photographers.png`, `caterers.png`, `attire.png`
- Copied planning icons to `assets/images/icons/planning/`:
  - `wedding-website.png`, `checklist.png`
- Copied location slider images to `assets/images/locations/`:
  - san-diego, la-jolla, coronado, del-mar, encinitas, carlsbad, oceanside, solana-beach, escondido, chula-vista, el-cajon, la-mesa, poway, rancho-santa-fe, julian, imperial-beach, san-marcos
- Copied footer logo to `assets/images/logo/logo_flat.png`
- Additional hardcoded plugin random banners moved to `assets/images/banners/`:
  - `home-hero-random-1.png` ... `home-hero-random-5.png`

### Files modified
1. `wp-content/themes/sdweddingdirectory/user-template/front-page.php`
- Replaced hardcoded uploads image URLs with `get_theme_file_uri()` for:
  - city slides, home hero banner, category card images, planning card icons.
- Updated city slide render to use `$city['image']` directly (no `home_url()` wrapper).
- Left featured article image URLs in uploads unchanged (content images).

2. `wp-content/themes/sdweddingdirectory/inc/page-header-banner.php`
- Replaced vendor and venue search banner URLs with `get_theme_file_uri()` asset paths.
- Replaced planning hero banner check/reference from uploads path to theme asset path.

3. `wp-content/themes/sdweddingdirectory/inc/theme-footer.php`
- Replaced footer brand logo source to `get_theme_file_uri('assets/images/logo/logo_flat.png')`.

4. `wp-content/themes/sdweddingdirectory/assets/css/theme-style.css`
- Replaced home category icon background URLs from uploads to relative theme image paths:
  - `url('../images/icons/categories/...')`

5. `wp-content/plugins/sdweddingdirectory-shortcodes/shortcodes/home-slider/index.php`
- Replaced random homepage banner list from uploads URLs to `get_theme_file_uri('assets/images/banners/home-hero-random-*.png')`.

### Verification
- PHP syntax checks passed for modified PHP files.
- Search for hardcoded `/wp-content/uploads/` in theme+plugin PHP/CSS now returns:
  - Front-page featured article image constants (intentionally kept as content images), and
  - A Mailchimp plugin docblock comment path (comment only).

### Commands run
- File copy operations (`cp`) from uploads -> theme assets.
- Cache flush:
  - `docker compose -f /Users/Havok/Documents/Development/WebDevelopment/wp-docker/docker-compose.yml exec -T wp_ssh wp cache flush --allow-root`

## Phase 2 Plugin Cleanup — Task 1/2/3 Progress

### Task 1 — Broken `weddingdir-*` plugins removed
- Confirmed all broken `weddingdir-*` plugin directories are absent from `wp-content/plugins/`.
- Verified active plugin list now only includes `sdweddingdirectory*` plugins.

### Task 2 — Google Maps rebuilt inside `sdweddingdirectory` core

#### Files added
1. `wp-content/plugins/sdweddingdirectory/config-file/google-map/index.php`
- Added new core map module loader.
- Registered map provider on the correct filter:
  - `sdweddingdirectory/map/provider` => `google`.
- Added map enqueue logic in core:
  - Enqueues Google Maps API script.
  - Enqueues new core map compatibility script.
  - Localizes `SDWEDDINGDIRECTORY_GOOGLE_MAP_OBJ` with marker/cluster/default lat-lng settings.
- Added compatibility option lookups for API key and place-service flags.
- Added map context checks for:
  - map-enabled pages via `sdweddingdirectory/enable-script/map`
  - dashboard pages
  - singular `website`, `vendor`, `venue`
  - marker shortcode pages.

2. `wp-content/plugins/sdweddingdirectory/config-file/google-map/script.js`
- Implemented `window.SDWeddingDirectory_Google_Map` object to match existing call sites.
- Implemented required methods:
  - `init()`
  - `marker_show_on_map()`
  - `find_map_location()`
  - `dynamic_search_address_map()`
  - `google_map_load_venues()`
  - `google_map_load_website_event_data()`
- Added defaults object (`default.lat`, `default.lng`, marker, zoom, etc.) for compatibility with existing vendor/website scripts.
- Added defensive guards so map methods safely no-op when Google API is unavailable.
- Kept compatibility with existing form/map ID conventions:
  - `sdweddingdirectory_map_handler`
  - `map_<id>`, `map_latitude_<id>`, `map_longitude_<id>`, `map_address_<id>`.

#### Hotfix during verification
- Updated `config-file/google-map/index.php` to avoid using `SDWeddingDirectory_Config::have_map()` (which calls a broken parent static method path).
- Replaced with direct filter-based check (`map_flag_requested()`) to prevent front-end critical errors.

### Task 3 — Remove vendor pricing/invoice references

#### File modified
1. `wp-content/plugins/sdweddingdirectory-venue/vendor-file/my-venue/index.php`
- Removed fallback link to `vendor-pricing` page from “My Venues” header button.
- Updated fallback button behavior to always route to `add-venue` with “Add New Venue”.
- Eliminates dead navigation path after pricing/invoice plugin cleanup.

### Commands run
- `wp plugin list --format=json --allow-root` (inside Docker) to verify active plugin state.
- `wp eval ...` checks for:
  - `class_exists('SDWeddingDirectory_Google_Map_Config')`
  - provider filter output (`sdweddingdirectory/map/provider`).
- `wp eval ... do_action('wp_enqueue_scripts')` to verify map handles queue:
  - `sdweddingdirectory-google-map-api`
  - `sdweddingdirectory-google-map-script`.
- `curl` checks against local Docker pages to verify map scripts are present in rendered HTML.
- Syntax checks:
  - `php -l` on new/edited PHP files.
  - `node --check` on new map JS file.

### Verification results
- `SDWeddingDirectory_Google_Map_Config` loads successfully at runtime.
- Map provider filter now returns `{"google":"Google Map"}`.
- Map script handles successfully enqueue in runtime check.
- Rendered pages (`/venues/`, `/vendor/movin-tunes/`) include:
  - Google Maps API script tag
  - localized `SDWEDDINGDIRECTORY_GOOGLE_MAP_OBJ`
  - `config-file/google-map/script.js`.
- No remaining `vendor-pricing` or `vendor-invoice` string references in plugin PHP/JS files.

## Phase 2 Plugin Cleanup — Task 4/5 Progress

### Task 4A — Merged `sdweddingdirectory-venue` into core

#### Core integration
- Copied venue plugin module files into:
  - `wp-content/plugins/sdweddingdirectory/venue/`
- Added integrated module loader:
  - `wp-content/plugins/sdweddingdirectory/venue/index.php`
- Loader defines guarded constants:
  - `SDWEDDINGDIRECTORY_VENUE_URL`
  - `SDWEDDINGDIRECTORY_VENUE_DIR`
  - `SDWEDDINGDIRECTORY_VENUE_VERSION`
- Loader requires original venue module file stack:
  - `database/index.php`
  - `filters-hooks/index.php`
  - `admin-file/index.php`
  - `singular-venue/index.php`
  - `venue-layout/index.php`
  - `vendor-file/index.php`
  - `ajax/index.php`
- Updated core bootstrap include list:
  - `wp-content/plugins/sdweddingdirectory/sdweddingdirectory.php`
  - Added: `require_once 'venue/index.php';`

#### Follow-up fix
- `wp-content/plugins/sdweddingdirectory-venue/vendor-file/my-venue/index.php`
  - Removed dead fallback routing to `vendor-pricing`.
  - Fallback now routes to `add-venue` with `Add New Venue` button/icon.

### Task 4B — Merged `sdweddingdirectory-claim-venue` into core

#### Core integration
- Copied claim-venue module files into:
  - `wp-content/plugins/sdweddingdirectory/claim-venue/`
- Added integrated module loader:
  - `wp-content/plugins/sdweddingdirectory/claim-venue/index.php`
- Loader defines guarded constant:
  - `SDWEDDINGDIRECTORY_CLAIM_VENUE_PLUGIN`
- Loader requires original module file stack:
  - `database/index.php`
  - `filters-hooks/index.php`
  - `admin-file/index.php`
  - `ajax/index.php`
- Updated core bootstrap include list:
  - `wp-content/plugins/sdweddingdirectory/sdweddingdirectory.php`
  - Added: `require_once 'claim-venue/index.php';`

### Task 4C — Merged `sdweddingdirectory-real-wedding` into core

#### Core integration
- Copied real-wedding module files into:
  - `wp-content/plugins/sdweddingdirectory/real-wedding/`
- Added integrated module loader:
  - `wp-content/plugins/sdweddingdirectory/real-wedding/index.php`
- Loader defines guarded constants:
  - `SDWEDDINGDIRECTORY_REALWEDDING_PLUGIN`
  - `SDWEDDINGDIRECTORY_REALWEDDING_PLUGIN_URL`
  - `SDWEDDINGDIRECTORY_REALWEDDING_PLUGIN_DIR`
  - `SDWEDDINGDIRECTORY_REALWEDDING_PLUGIN_LIB`
- Loader requires original module file stack:
  - `database/index.php`
  - `filters-hooks/index.php`
  - `admin-file/index.php`
  - `ajax/index.php`
  - `couple-file/index.php`
  - `singular-file/index.php`
  - `realwedding-layout/index.php`
- Updated core bootstrap include list:
  - `wp-content/plugins/sdweddingdirectory/sdweddingdirectory.php`
  - Added: `require_once 'real-wedding/index.php';`

### Task 5 — Created consolidated `sdweddingdirectory-couple` plugin

#### New plugin files
1. `wp-content/plugins/sdweddingdirectory-couple/sdweddingdirectory-couple.php`
- New consolidator plugin entrypoint.
- Defines `SDWEDDINGDIRECTORY_COUPLE_PLUGIN`.
- Loads (via `require_once`) existing satellite plugin entrypoints:
  - `sdweddingdirectory-budget-calculator`
  - `sdweddingdirectory-couple-website`
  - `sdweddingdirectory-guest-list`
  - `sdweddingdirectory-rsvp`
  - `sdweddingdirectory-reviews`
  - `sdweddingdirectory-request-quote`
  - `sdweddingdirectory-seating-chart`
  - `sdweddingdirectory-todo-list`
  - `sdweddingdirectory-wishlist`

2. `wp-content/plugins/sdweddingdirectory-couple/index.php`
- Silence file.

### Plugin activation/deactivation changes

#### Activated
- `sdweddingdirectory-couple`

#### Deactivated
- `sdweddingdirectory-venue`
- `sdweddingdirectory-claim-venue`
- `sdweddingdirectory-real-wedding`
- `sdweddingdirectory-budget-calculator`
- `sdweddingdirectory-couple-website`
- `sdweddingdirectory-guest-list`
- `sdweddingdirectory-rsvp`
- `sdweddingdirectory-reviews`
- `sdweddingdirectory-request-quote`
- `sdweddingdirectory-seating-chart`
- `sdweddingdirectory-todo-list`
- `sdweddingdirectory-wishlist`

### Commands run (Task 4/5)
- Deactivation commands:
  - `wp plugin deactivate sdweddingdirectory-venue --allow-root`
  - `wp plugin deactivate sdweddingdirectory-claim-venue --allow-root`
  - `wp plugin deactivate sdweddingdirectory-real-wedding --allow-root`
  - `wp plugin deactivate sdweddingdirectory-budget-calculator sdweddingdirectory-couple-website sdweddingdirectory-guest-list sdweddingdirectory-rsvp sdweddingdirectory-reviews sdweddingdirectory-request-quote sdweddingdirectory-seating-chart sdweddingdirectory-todo-list sdweddingdirectory-wishlist --allow-root`
- Activation command:
  - `wp plugin activate sdweddingdirectory-couple --allow-root`
- Runtime verification commands:
  - `wp plugin list --allow-root`
  - `wp plugin is-active ... --allow-root`
  - `wp eval 'class_exists(...)' --allow-root`
  - `curl` smoke checks for `/venues/`, `/vendors/djs/`, `/venues/viejas-casino-resort-2/`, `/real-weddings/`, `/wedding-planning/wedding-checklist/`, `/dashboard/`

### Verification results (Task 4/5)
- Route smoke checks render with expected titles and no fatal/critical markers:
  - `/venues/`
  - `/vendors/djs/`
  - `/venues/viejas-casino-resort-2/`
  - `/real-weddings/`
  - `/wedding-planning/wedding-checklist/`
- Venue detail page still renders claim CTA (`Claim Now`).
- `/dashboard/` returns expected `302` redirect when unauthenticated.
- Representative classes confirmed loaded with satellites inactive:
  - `SDWeddingDirectory_Dashboard_Venue`
  - `SDWeddingDirectory_Claim_Venue_Database`
  - `SDWeddingDirectory_Real_Wedding_Database`
- Couple module classes confirmed loaded through consolidated couple plugin while individual couple plugins are inactive.

### Note
- During deactivation operations, temporary "constant already defined" warnings appeared while both old satellite and new integrated loaders were active in the same request. These warnings stopped after satellites were deactivated.

## Phase 2 Plugin Cleanup — Task 6/7/8 Progress (Consolidated Runtime Path)

### Task 6/7 execution approach
- The theme still contains many direct `SDWeddingDirectory_Shortcode_*::page_builder()` calls.
- Instead of a high-risk immediate rewrite of all those templates to new theme template-part files, shortcodes runtime was consolidated into `sd-core` so the standalone shortcodes plugin could be removed safely now.
- This preserves current page output while enabling plugin-count reduction immediately.

### Integrated shortcodes module into `sd-core`

#### Files added/copied
- Copied shortcodes runtime files into:
  - `wp-content/plugins/sdweddingdirectory/shortcodes/`
  - includes copied subfolders: `assets/`, `languages/`, `shortcodes/`

#### File modified
1. `wp-content/plugins/sdweddingdirectory/shortcodes/index.php`
- Replaced silence file with new module loader class:
  - `SDWeddingDirectory_Shortcodes_Module`
- Defines guarded constants previously provided by standalone plugin:
  - `SDWEDDINGDIRECTORY_SHORTCODE_VERSION`
  - `SDWEDDINGDIRECTORY_SHORTCODE_URL`
  - `SDWEDDINGDIRECTORY_SHORTCODE_DIR`
  - `SDWEDDINGDIRECTORY_SHORTCODE_IMAGES`
- Registers filters formerly supplied by standalone plugin:
  - `the_content`
  - `sdweddingdirectory_clean_shortcode`
- Loads copied shortcode class tree via:
  - `require_once .../shortcodes/index.php`

2. `wp-content/plugins/sdweddingdirectory/sdweddingdirectory.php`
- Added module include:
  - `require_once 'shortcodes/index.php';`

### Deactivated standalone shortcodes plugin
- Deactivated:
  - `sdweddingdirectory-shortcodes`
- Verified key shortcode classes still load from core:
  - `SDWeddingDirectory_Shortcode`
  - `SDWeddingDirectory_Shortcode_Slider_Version`
  - `SDWeddingDirectory_Shortcode_Find_Venue_Form`
  - `SDWeddingDirectory_Shortcode_RealWedding_Post`
  - `SDWeddingDirectory_Shortcode_Team`

### Refactor `sdweddingdirectory-couple` for directory cleanup

#### Why
- Initial consolidator pointed to old satellite directories.
- To allow Task 8 physical deletion, module code was moved internal to `sdweddingdirectory-couple`.

#### Files/folders changed
1. Added internal module copies:
- `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-budget-calculator/`
- `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-couple-website/`
- `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-guest-list/`
- `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-rsvp/`
- `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-reviews/`
- `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-request-quote/`
- `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-seating-chart/`
- `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-todo-list/`
- `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-wishlist/`

2. Updated loader path map:
- `wp-content/plugins/sdweddingdirectory-couple/sdweddingdirectory-couple.php`
- Changed module entrypoint paths from sibling plugin dirs to local `modules/...` paths.

### Task 8 — Deleted old plugin directories

#### Deleted old satellite plugin directories
- `wp-content/plugins/sdweddingdirectory-venue/`
- `wp-content/plugins/sdweddingdirectory-claim-venue/`
- `wp-content/plugins/sdweddingdirectory-real-wedding/`

#### Deleted old couple plugin directories
- `wp-content/plugins/sdweddingdirectory-budget-calculator/`
- `wp-content/plugins/sdweddingdirectory-couple-website/`
- `wp-content/plugins/sdweddingdirectory-guest-list/`
- `wp-content/plugins/sdweddingdirectory-rsvp/`
- `wp-content/plugins/sdweddingdirectory-reviews/`
- `wp-content/plugins/sdweddingdirectory-request-quote/`
- `wp-content/plugins/sdweddingdirectory-seating-chart/`
- `wp-content/plugins/sdweddingdirectory-todo-list/`
- `wp-content/plugins/sdweddingdirectory-wishlist/`

#### Deleted standalone shortcodes plugin directory
- `wp-content/plugins/sdweddingdirectory-shortcodes/`

### Current custom plugin footprint
- `wp-content/plugins/sdweddingdirectory/` (sd-core)
- `wp-content/plugins/sdweddingdirectory-couple/` (sd-couple)

### Commands run (Task 6/7/8)
- Deactivated shortcodes plugin:
  - `wp plugin deactivate sdweddingdirectory-shortcodes --allow-root`
- Class/runtime checks via `wp eval` for shortcode/venue/claim/real-wedding/couple classes.
- Final plugin registry checks:
  - `wp plugin list --allow-root`
- Directory cleanup:
  - `rm -rf` old satellite directories listed above.
- Smoke tests via `curl` for key routes after cleanup.

### Verification results after full cleanup
- Plugin list now shows only 2 custom active plugins:
  - `sdweddingdirectory` (active)
  - `sdweddingdirectory-couple` (active)
- Key runtime classes confirmed loaded from consolidated plugins.
- Smoke checks pass with no fatal markers:
  - `/`
  - `/about/`
  - `/our-team/`
  - `/venues/`
  - `/vendors/`
  - `/vendors/djs/`
  - `/real-weddings/`
- `/dashboard/` returns expected unauthenticated `302` redirect.

## Phase 2 Plugin Cleanup — Post-Consolidation Hardening (Follow-up)

### Dead shortcode asset-path cleanup

After deleting `wp-content/plugins/sdweddingdirectory-shortcodes/`, two hardcoded asset URLs still referenced the removed plugin path. These were updated to resolve from the integrated shortcodes module URL (`SDWEDDINGDIRECTORY_SHORTCODE_URL`) with a safe fallback.

#### Files modified
1. `wp-content/plugins/sdweddingdirectory/front-file/find-post-helper/search-venue/index.php`
- Updated venue location placeholder source:
  - From removed path: `/wp-content/plugins/sdweddingdirectory-shortcodes/...`
  - To integrated module path based on `SDWEDDINGDIRECTORY_SHORTCODE_URL`.

2. `wp-content/themes/sdweddingdirectory/user-template/vendor-category.php`
- Updated vendor category placeholder source:
  - From removed path: `/wp-content/plugins/sdweddingdirectory-shortcodes/...`
  - To integrated module path based on `SDWEDDINGDIRECTORY_SHORTCODE_URL`.

### Commands run (follow-up)
- Syntax checks:
  - `php -l wp-content/plugins/sdweddingdirectory/front-file/find-post-helper/search-venue/index.php`
  - `php -l wp-content/themes/sdweddingdirectory/user-template/vendor-category.php`

- Removed-path scan:
  - `rg -n "wp-content/plugins/sdweddingdirectory-shortcodes|sdweddingdirectory-shortcodes/shortcodes" wp-content/themes/sdweddingdirectory wp-content/plugins/sdweddingdirectory wp-content/plugins/sdweddingdirectory-couple -g "*.php" -g "*.js" -g "*.css"`

- Plugin state verification (Docker WP-CLI):
  - `docker compose -f /Users/Havok/Documents/Development/WebDevelopment/wp-docker/docker-compose.yml exec -T wp_ssh wp plugin list --fields=name,status --format=table --allow-root`

- Route smoke snapshots:
  - `docker exec wp_ssh curl -s http://wp_app/ -H 'Host: localhost:8080' > /tmp/phase2-home.html`
  - `docker exec wp_ssh curl -s http://wp_app/venues/ -H 'Host: localhost:8080' > /tmp/phase2-venues.html`
  - `docker exec wp_ssh curl -s http://wp_app/vendors/ -H 'Host: localhost:8080' > /tmp/phase2-vendors.html`
  - `docker exec wp_ssh curl -s http://wp_app/vendors/djs/ -H 'Host: localhost:8080' > /tmp/phase2-vendors-djs.html`
  - `docker exec wp_ssh curl -s http://wp_app/real-weddings/ -H 'Host: localhost:8080' > /tmp/phase2-realweddings.html`

- Fatal marker scan on snapshots:
  - `rg -n 'Fatal error|Parse error|Warning:|Critical error|There has been a critical error' /tmp/phase2-*.html`

### Verification results
- No syntax errors in modified files.
- No remaining code references to `sdweddingdirectory-shortcodes` filesystem URL paths.
- Active custom plugins remain:
  - `sdweddingdirectory`
  - `sdweddingdirectory-couple`
- Smoke snapshots for `/`, `/venues/`, `/vendors/`, `/vendors/djs/`, `/real-weddings/` contain no fatal/critical markers.

## Codex Cleanup & Features — Execution Log (2026-03-04)

### Source Prompt
- `/Users/Havok/Desktop/codex-cleanup-and-features.md`

### Task 1 — Random banner rotation (home + planning pages)

#### Files modified
1. `wp-content/themes/sdweddingdirectory/functions.php`
- Added helper:
  - `sdweddingdirectory_random_banner( $prefix, $count = 5, $ext = 'png' )`

2. `wp-content/themes/sdweddingdirectory/user-template/front-page.php`
- Home hero banner source changed:
  - from static `assets/images/banners/home-hero.png`
  - to `sdweddingdirectory_random_banner( 'home-hero-random', 5 )`

3. `wp-content/themes/sdweddingdirectory/inc/page-header-banner.php`
- Planning hero banner source changed:
  - from static `home-hero.png` + fallback branches
  - to `sdweddingdirectory_random_banner( 'wedding-planning-hero-random', 5 )`
- Removed now-unneeded static/fallback image branch logic for planning hero.

### Task 2 — Blog category image migration + references

#### Files/assets updated
1. Created/filled theme blog image directory:
- `wp-content/themes/sdweddingdirectory/assets/images/blog/`

2. Copied and renamed images from import folder:
- `wedding-planning-how-to.jpg`
- `wedding-ceremony.jpg`
- `wedding-reception.jpg`
- `wedding-services.jpg`
- `wedding-fashion.jpg`
- `hair-makeup.jpg`

3. Added placeholder copies for missing categories:
- `honeymoon-advice.jpg`
- `budget.jpg`
- `legal-paperwork.jpg`
- `trends-and-tips.jpg`

4. `wp-content/themes/sdweddingdirectory/user-template/front-page.php`
- Expanded home Inspiration circle categories from 6 to 10.
- Updated image references from:
  - `/wp-content/sdweddingdirectory-import/blog-category-images/...`
  to:
  - `get_theme_file_uri( 'assets/images/blog/{slug}.jpg' )`

5. Deleted old import directory:
- `wp-content/sdweddingdirectory-import/`

### Task 3 — Missing planning icons

#### Assets added
- Directory: `wp-content/themes/sdweddingdirectory/assets/images/icons/planning/`
- Ensured 6 canonical planning tool icon files exist:
  - `checklist.png`
  - `wedding-website.png`
  - `budget.png`
  - `seating-chart.png`
  - `guest-list.png`
  - `vendor-manager.png`

### Task 4 — Vendor carousel: remove Venues category

#### File modified
- `wp-content/themes/sdweddingdirectory/user-template/vendor-category.php`

#### Change
- Filtered top-level vendor terms to exclude slugs:
  - `venues`, `venue`, `wedding-venues`

### Task 5 — Featured vendor pop-out crop tuning

#### File modified
- `wp-content/themes/sdweddingdirectory/assets/css/global.css`

#### Change
- Added focal-point positioning for featured vendor pop-out images:
  - `.sd-vendors-feature-visual img { object-position: center 30%; }`
  - same rule in mobile block.

### Task 6 — Delete unused plugins

#### WP-CLI cleanup attempt
- Ran deactivation/deletion for:
  - `tinymce-advanced`, `classic-editor`, `classic-widgets`, `breadcrumb-navxt`, `woocommerce`
- Result:
  - First four were already absent.
  - WooCommerce plugin was already non-loadable and then physically removed.

#### Filesystem cleanup
- Removed:
  - `wp-content/plugins/woocommerce/`

### Task 7 — Delete unused theme files + remove references

#### Files/directories deleted
- `wp-content/themes/sdweddingdirectory/inc/tgm-plugin/`
- `wp-content/themes/sdweddingdirectory/languages/`
- `wp-content/themes/sdweddingdirectory/archive-changelog.php`
- `wp-content/themes/sdweddingdirectory/woocommerce.php`
- `wp-content/themes/sdweddingdirectory/inc/woocommerce.php`
- `wp-content/themes/sdweddingdirectory/assets/css/woocommerce-style.css`
- `wp-content/themes/sdweddingdirectory/readme.txt`

#### File modified
- `wp-content/themes/sdweddingdirectory/functions.php`
- Removed TGM includes:
  - `inc/tgm-plugin/class-tgm-plugin-activation.php`
  - `inc/tgm-plugin/required-plugin.php`
- Removed conditional WooCommerce include block:
  - `require_once 'inc/woocommerce.php';`

### Task 8 — Custom admin dashboard

#### Files modified
1. Added:
- `wp-content/plugins/sdweddingdirectory/admin-file/admin-dashboard.php`

2. Updated loader:
- `wp-content/plugins/sdweddingdirectory/admin-file/index.php`
- Added explicit include:
  - `require_once plugin_dir_path( __FILE__ ) . 'admin-dashboard.php';`

#### Behavior
- Removes default WP dashboard widgets and welcome panel.
- Adds custom `SD Wedding Directory - Site Overview` widget with:
  - total venues/vendors
  - couple/vendor account counts
  - new couples (week/month)
  - claimed vs unclaimed bars for venues/vendors
  - vendor category counts (excluding venues)

### Task 9 — Vendor category images to theme assets

#### Assets added
- Added 18 vendor category image files to:
  - `wp-content/themes/sdweddingdirectory/assets/images/categories/`
- Added files:
  - `planners.png`
  - `bands.png`
  - `cakes.png`
  - `ceremony-music.png`
  - `djs.png`
  - `event-rentals.png`
  - `favors-and-gifts.png`
  - `flowers.png`
  - `hair-and-makeup.png`
  - `invitations.png`
  - `jewelry.png`
  - `lighting-and-decor.png`
  - `officiants.png`
  - `photo-booths.png`
  - `photography.png`
  - `transportation.png`
  - `travel-agents.png`
  - `videography.png`

#### File modified
- `wp-content/themes/sdweddingdirectory/user-template/vendor-category.php`

#### Changes
- Switched vendor carousel term image source from taxonomy/ACF upload-driven term image to theme-asset slug map.
- Updated placeholder paths:
  - term placeholder -> `assets/images/categories/venues.png`
  - vendor post placeholder -> `assets/images/banner-bg.jpg`
- Featured vendor block image source now also uses mapped theme category image for known slugs.

### Task 10 — Planning child page icons moved to theme assets

#### Asset copy layout created
- `wp-content/themes/sdweddingdirectory/assets/images/planning/checklist/*.svg`
- `wp-content/themes/sdweddingdirectory/assets/images/planning/seating-chart/*.svg`
- `wp-content/themes/sdweddingdirectory/assets/images/planning/vendor-manager/*.svg`
- `wp-content/themes/sdweddingdirectory/assets/images/planning/guest-management/*.svg`
- `wp-content/themes/sdweddingdirectory/assets/images/planning/budget-calculator/*.svg`
- `wp-content/themes/sdweddingdirectory/assets/images/planning/wedding-website/*.svg`

#### Database content updates (page IDs)
- Updated page content for:
  - `5322` (wedding-checklist)
  - `5324` (wedding-seating-chart)
  - `5326` (vendor-manager)
  - `5328` (wedding-guest-list)
  - `5330` (wedding-budget)
  - `5332` (wedding-website)

#### Replacements made in page HTML
- Replaced all `/wp-content/uploads/icons/planning-tools/*` URLs with theme asset URLs under:
  - `assets/images/icons/planning/*.png` (tool cards)
  - `assets/images/planning/{tool}/*.svg` (top 3 feature icons)

---

## Verification Summary

### Syntax checks
- `php -l` passed for:
  - `user-template/front-page.php`
  - `inc/page-header-banner.php`
  - `user-template/vendor-category.php`
  - `functions.php`
  - `plugins/sdweddingdirectory/admin-file/index.php`
  - `plugins/sdweddingdirectory/admin-file/admin-dashboard.php`

### Required scans/checks
1. `sdweddingdirectory-import` references in code:
- `rg -n "sdweddingdirectory-import" wp-content` (php/css/js/html scope) => no matches.

2. `wp-content/uploads/icons/` references in code:
- `rg -n "wp-content/uploads/icons/" wp-content` (php/css/js/html scope) => no matches.

3. TGM/Woo theme references:
- `rg -n "tgm-plugin|tgmpa|inc/woocommerce.php|woocommerce-style.css|add_theme_support\('woocommerce'" theme` => no matches.

4. Category image count:
- `assets/images/categories/` file count => `22`.

5. Planning icon file count:
- `assets/images/icons/planning/` file count => `6`.

6. Blog image folder:
- `assets/images/blog/` contains 10 files (6 migrated + 4 placeholders).

7. Home/planning random banner sampling:
- Multiple requests showed varying `home-hero-random-{1..5}` and `wedding-planning-hero-random-{1..5}` files.

8. `/vendors` carousel category titles:
- Extracted slide titles show no `Venues` item.

9. Planning child page icon source verification:
- All 6 pages no longer contain `/uploads/icons/planning-tools/` URLs.

10. Frontend smoke checks:
- `/`
- `/vendors/`
- `/wedding-planning/`
- `/wedding-planning/wedding-checklist/`
- No fatal/critical markers found in fetched HTML snapshots.

11. Plugin status after cleanup:
- Active plugins:
  - `advanced-custom-fields-pro`
  - `contact-form-7`
  - `mailchimp-for-wp`
  - `nextend-facebook-connect`
  - `option-tree`
  - `regenerate-thumbnails`
  - `sdweddingdirectory`
  - `sdweddingdirectory-couple`
  - `white-label-cms`

12. Admin dashboard load check:
- `wp eval "echo function_exists('sdwd_render_dashboard_stats') ? 'yes' : 'no';"` => `yes`.

## Follow-up Fix — Admin stats widget hidden by White Label CMS

### Issue
- Custom dashboard stats widget (`sdwd_site_stats`) was registered but not visible in wp-admin dashboard.
- Root cause: `white-label-cms` removes dashboard metaboxes in `wp_dashboard_setup` at priority `999` unless the widget ID is in its exclusion list filter (`wlcms_exclude_dashboard_metaboxes`).

### Fix applied
- File updated:
  - `wp-content/plugins/sdweddingdirectory/admin-file/admin-dashboard.php`
- Added filter:
  - `add_filter( 'wlcms_exclude_dashboard_metaboxes', ... )`
  - Ensures `sdwd_site_stats` is included in the exclusion list and preserved by White Label CMS cleanup.

### Verification
- `php -l` passed for `admin-dashboard.php`.
- WP-CLI filter check:
  - `apply_filters('wlcms_exclude_dashboard_metaboxes', ['wlcms_rss_box'])`
  - Result includes `sdwd_site_stats`.
- Additional hardening: added `get_user_option_metaboxhidden_dashboard` filter in `admin-dashboard.php` to remove `sdwd_site_stats` from hidden dashboard metaboxes.
