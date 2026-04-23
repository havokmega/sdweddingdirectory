# Architecture

**Analysis Date:** 2026-04-23

## Pattern Overview

**Overall:** Docker-hosted WordPress site — single-site install with bind-mounted `wp-content/`. Theme-owned rendering with plugin-owned data/business-logic.

**Key Characteristics:**
- WordPress core is provided by the `wordpress:latest` image (not committed to this repo). Only `wp-content/` is source-controlled via a bind mount.
- Three Docker services: MySQL 8 (`db`), WordPress/Apache+PHP (`wordpress`), and a custom SSH container (`wp_ssh`) that shares the same volumes and bundles WP-CLI.
- Two custom plugins (`sdwd-core`, `sdwd-couple`) own all custom post types, taxonomies, roles, AJAX handlers, and admin meta boxes. The theme (`sandiegoweddingdirectory`) owns all front-end HTML, CSS, and JS.
- Strict separation: no Bootstrap, Font Awesome, jQuery, Google Fonts, page builders, or shortcodes for layout (enforced by `CLAUDE.md`).
- `mu-plugins/` directory does not exist — all custom PHP is in regular plugins.
- Content model (vendor/venue/couple) is explicitly parallel: one shared architecture differentiated by role and taxonomy, not by forked code.

## Layers

**Docker Infrastructure Layer:**
- Purpose: Provides isolated dev environment for WordPress, MySQL, and shell access.
- Location: `docker-compose.yml`, `Dockerfile.ssh`, `php.ini`, `.htaccess`
- Contains: Service definitions, volume mounts, PHP config overrides, custom SSH image definition.
- Depends on: Docker engine; `wordpress:latest` and `mysql:8.0` images.
- Used by: The developer — `docker compose up` boots the site at `http://localhost:8080`.

**WordPress Core Layer:**
- Purpose: Runtime, routing, template hierarchy, REST API, admin UI.
- Location: Inside the `wp_data` named volume at `/var/www/html/` (NOT in this repo).
- Contains: `index.php`, `wp-login.php`, `wp-admin/*`, `wp-includes/*`, core tables.
- Depends on: MySQL `db` service.
- Used by: Every HTTP request — Apache mod_rewrite funnels all non-file requests through `/index.php`.

**Plugin (Data/Logic) Layer:**
- Purpose: Register custom post types, taxonomies, roles; provide AJAX handlers for registration, login, dashboard save, quote requests, claims, reviews, wishlist, checklist, budget; own admin meta boxes.
- Location: `wp-content/plugins/sdwd-core/`, `wp-content/plugins/sdwd-couple/`
- Contains: PHP only — no HTML output for front-end layout.
- Depends on: WordPress core (hooks, WP_User, WP_Query, `wp_mail`, nonces).
- Used by: Theme templates (call `get_post_meta()` directly against `sdwd_*` keys), theme JS (`modals.js`, `dashboard.js` POST to `admin-ajax.php` with `action=sdwd_*`).

**Theme (Presentation) Layer:**
- Purpose: All front-end HTML, CSS, JS. Template hierarchy resolution, conditional asset enqueuing, nav walker, helper includes.
- Location: `wp-content/themes/sandiegoweddingdirectory/`
- Contains: Page templates, template-parts, assets, three `inc/` helpers.
- Depends on: Plugin-registered CPTs/taxonomies/meta, plugin AJAX endpoints.
- Used by: WordPress template hierarchy — WP picks the matching template by URL and CPT.

**Third-Party Plugin Layer:**
- Purpose: SEO, caching, backups, mail, security, image optimization.
- Location: `wp-content/plugins/{seo-by-rank-math,w3-total-cache,updraftplus,wp-mail-smtp,wordfence,shortpixel-image-optimiser}/`
- Contains: Vendor plugin code (not project-authored).
- Depends on: WordPress core.
- Used by: Admin and front-end — activation state is stored in the DB (not verifiable from files alone); `PROJECT.md` lists several as "to be configured."

## Data Flow

**Front-End Request Lifecycle:**

1. Browser hits `http://localhost:8080/vendor/acme-photography/`.
2. Apache inside `wp_app` container applies `.htaccess` at `/var/www/html/.htaccess` (bind-mounted from repo root). Custom rules under `BEGIN SDWD Permalink Conflict Fix` resolve category-vs-post overlap under `/wedding-inspiration/`, then standard WordPress rules route everything non-file to `/index.php`.
3. `/index.php` bootstraps WordPress core, which fires `plugins_loaded` → `sdwd-core` bootstrap (`wp-content/plugins/sdwd-core/sdwd-core.php` line 35), then `sdwd-couple` (if core loaded).
4. On `init` priority 8, `sdwd_register_post_types` and `sdwd_register_taxonomies` fire (`wp-content/plugins/sdwd-core/includes/post-types.php`, `includes/taxonomies.php`).
5. `after_setup_theme` runs theme setup; `wp_enqueue_scripts` runs conditional asset enqueuing in `wp-content/themes/sandiegoweddingdirectory/functions.php` (lines 84–173).
6. The template hierarchy selects `single-vendor.php` for a `vendor` CPT. WordPress renders: `get_header()` → template body → `get_footer()`.
7. `single-vendor.php` (`wp-content/themes/sandiegoweddingdirectory/single-vendor.php`) reads plugin meta directly via `get_post_meta( $post_id, 'sdwd_company_name', true )` etc., emits HTML, and embeds the quote form with a nonce from `wp_create_nonce( 'sdwd_quote_nonce' )`.

**AJAX Lifecycle (e.g., quote request):**

1. User submits `#sdwd-quote-form` in the sidebar of `single-vendor.php`.
2. `assets/js/app.js` (or inline handler) POSTs to `admin-ajax.php` with `action=sdwd_send_quote` and the nonce.
3. WordPress fires `wp_ajax_nopriv_sdwd_send_quote` → `sdwd_handle_send_quote` (`wp-content/plugins/sdwd-core/includes/quote.php` line 9).
4. Handler validates nonce, sanitizes inputs, reads vendor email from `sdwd_email` meta, sends via `wp_mail`, returns JSON.

**Registration → CPT Linking Flow:**

1. Unauthenticated user submits a modal form (couple/vendor/venue) from `template-parts/modals/*`.
2. `assets/js/modals.js` POSTs to `admin-ajax.php` with `action=sdwd_register`.
3. `sdwd_handle_register` (`wp-content/plugins/sdwd-core/includes/auth.php` line 20) creates `WP_User`, sets role, stores `first_name`/`last_name`, stores business-only meta on the user.
4. At the end, `do_action( 'sdwd_user_registered', $user_id, $account_type )` fires.
5. `sdwd_create_user_post` (`wp-content/plugins/sdwd-core/includes/user-post-link.php` line 19) listens on that hook and creates a matching CPT post (`couple`/`vendor`/`venue`) with `post_author = $user_id`, stores `sdwd_post_id` on the user, copies meta keys onto the post.

**Dashboard Save Flow:**

1. Logged-in vendor edits their profile on `user-template/vendor-dashboard.php`.
2. `assets/js/dashboard.js` POSTs to `admin-ajax.php` with `action=sdwd_save_dashboard`.
3. `sdwd_save_dashboard` (`wp-content/plugins/sdwd-core/includes/dashboard.php` line 15) verifies role, locates linked post via `sdwd_post_id` user meta, enforces `post_author == user_id`, updates post meta keys on the allow-list, returns JSON.

**State Management:**
- Canonical state lives in MySQL (`wp_posts`, `wp_postmeta`, `wp_users`, `wp_usermeta`, `wp_options`, `wp_terms`, `wp_term_relationships`).
- DB volume: named Docker volume `db_data` (not in repo). `wp_data` volume holds WP core files.
- User↔CPT linkage: `sdwd_post_id` user meta + `post_author` post column (checked in both directions in `includes/user-post-link.php` and `includes/dashboard.php`).

## Key Abstractions

**Plugin Bootstrap Pattern:**
- Purpose: Central `plugins_loaded` listener that `require_once`s every module in a deterministic order.
- Examples: `wp-content/plugins/sdwd-core/sdwd-core.php` lines 35–52; `wp-content/plugins/sdwd-couple/sdwd-couple.php` lines 16–27.
- Pattern: Define `SDWD_*_VERSION`, `SDWD_*_PATH`, `SDWD_*_URL` constants. Register activation hook for roles. Load admin files only `if ( is_admin() )`. `sdwd-couple` guards its load on `defined( 'SDWD_CORE_VERSION' )`.

**CPT Parallel Triad:**
- Purpose: Couple / Vendor / Venue share the same registration structure, same meta prefix (`sdwd_*`), same role name matching the CPT slug.
- Examples: `wp-content/plugins/sdwd-core/includes/post-types.php`, `includes/roles.php`, `includes/user-post-link.php`.
- Pattern: Couple is admin-only (`public => false`); Vendor and Venue are public with `rewrite => [ 'slug' => 'vendor'/'venue' ]`. All three use `capability_type => 'post'`, `supports => [ 'title', 'editor', 'thumbnail' ]` (couple drops editor), `show_in_rest => false`.

**Orchestrator Template + Template Parts:**
- Purpose: Page templates assemble sections by calling `get_template_part()` with `$args` arrays. Template parts extract args with null coalescing.
- Examples: `wp-content/themes/sandiegoweddingdirectory/archive-vendor.php`, `archive-venue.php`, `front-page.php`.
- Pattern: `get_template_part( 'template-parts/components/page-header', null, [ 'title' => ..., 'breadcrumbs' => [...] ] );` — parts read `$args['title'] ?? ''`.

**Conditional Asset Enqueue:**
- Purpose: Load page-specific CSS/JS only where needed for performance.
- Location: `wp-content/themes/sandiegoweddingdirectory/functions.php` lines 84–173.
- Pattern: `is_front_page()`, `is_singular( [ 'vendor', 'venue', ... ] )`, `is_post_type_archive( 'venue' )`, `is_tax( 'venue-type' )`, custom `sdwdv2_is_dashboard_page()`, `sdwdv2_is_planning_category()` gate each enqueue. Version strings use `filemtime()` for cache-busting.

**Role ↔ CPT ↔ Post Author Triangle:**
- Purpose: Every business user has exactly one linked CPT post; `post_author` enforces ownership on dashboard saves.
- Files: `wp-content/plugins/sdwd-core/includes/roles.php`, `includes/user-post-link.php`, `includes/dashboard.php`, `includes/claim.php`.
- Rule: `sdwd_is_role( $role )` helper checks `user->roles` contains `$role`. Unclaimed posts have `post_author` = admin; claim flow re-parents to the claiming user.

**Helpers in `inc/`:**
- Purpose: Small, focused files outside the orchestrator/template-part dichotomy. Hard-capped at 3 files by `CLAUDE.md`.
- Files: `inc/sd-mega-navwalker.php` (nav walker class), `inc/vendors.php` (vendor URL + taxonomy rewrite helpers), `inc/venues.php` (venue URL + term helpers).

## Entry Points

**HTTP Request Entry (front-end):**
- Location: `/var/www/html/index.php` inside container (WordPress core, not in this repo).
- Triggers: Apache via `.htaccess` `RewriteRule . /index.php [L]` when request is not a file/dir.
- Responsibilities: Bootstraps WordPress, loads plugins, resolves template hierarchy.

**Admin Entry:**
- Location: `/var/www/html/wp-admin/*` inside container (not in this repo).
- Triggers: Direct browser navigation to `/wp-admin/` or `/wp-login.php`.
- Responsibilities: Admin UI, login, role-gated access to CPT edit screens where `sdwd-core` registers meta boxes.

**AJAX Endpoint:**
- Location: `/var/www/html/wp-admin/admin-ajax.php` inside container.
- Triggers: `fetch`/`XMLHttpRequest` POSTs from `assets/js/modals.js`, `assets/js/dashboard.js`, inline form submits.
- Registered actions (all in `sdwd-core` and `sdwd-couple`):
  - `sdwd_register`, `sdwd_login`, `sdwd_forgot_password` (`includes/auth.php`)
  - `sdwd_save_dashboard` (`includes/dashboard.php`)
  - `sdwd_send_quote` (`includes/quote.php`)
  - `sdwd_submit_claim`, `sdwd_approve_claim`, `sdwd_reject_claim` (`includes/claim.php`)
  - `sdwd_submit_review` (`sdwd-couple/modules/reviews.php`)
  - `sdwd_toggle_wishlist` (`sdwd-couple/modules/wishlist.php`)
  - `sdwd_save_checklist` (`sdwd-couple/modules/checklist.php`)
  - `sdwd_save_budget` (`sdwd-couple/modules/budget.php`)
  - `sdwd_request_quote` (`sdwd-couple/modules/request-quote.php`)

**Plugin Bootstrap Entry:**
- Location: `wp-content/plugins/sdwd-core/sdwd-core.php`, `wp-content/plugins/sdwd-couple/sdwd-couple.php`.
- Triggers: WordPress `plugins_loaded` action at priority 5 (core) and 10 (couple).

**Theme Bootstrap Entry:**
- Location: `wp-content/themes/sandiegoweddingdirectory/functions.php`.
- Triggers: WordPress `after_setup_theme`, `widgets_init`, `wp_enqueue_scripts`, `wp_print_styles` (dequeues legacy fontello/flaticon).

**SSH Entry (dev-only):**
- Location: `wp_ssh` container exposes port 22 on host `2222`. Credentials `root:root` (dev only).
- Image: built from `Dockerfile.ssh` — extends `wordpress:latest`, adds `openssh-server`, `mysql-client`, and `wp-cli` at `/usr/local/bin/wp`.
- Responsibilities: Exec `wp` commands against the same DB + bind-mounted `wp-content/`.

## Custom Plugin/Theme Boundaries

**Plugin owns:**
- CPT registration: `couple`, `vendor`, `venue` (`sdwd-core/includes/post-types.php`), plus `sdwd_review` (`sdwd-couple/modules/reviews.php`).
- Taxonomy registration: `vendor-category`, `venue-type`, `venue-location` (`sdwd-core/includes/taxonomies.php`).
- Role registration: `couple`, `vendor`, `venue` (`sdwd-core/includes/roles.php`).
- Admin meta boxes: `sdwd-core/includes/admin/{couple,vendor,venue}-meta.php`.
- All `wp_ajax_*` handlers (see Entry Points).
- Migration tool at `Tools → SDWD Migration` (`sdwd-core/includes/migrate.php`).

**Theme owns:**
- All front-end HTML, CSS, JS.
- Template hierarchy files (`single-vendor.php`, `archive-venue.php`, `taxonomy-vendor-category.php`, etc.).
- Conditional asset enqueues (`functions.php`).
- Nav walker (`inc/sd-mega-navwalker.php`).
- Taxonomy rewrite override for `vendor-category` → `/vendors/` base (`inc/vendors.php`).
- Dashboard markup in `user-template/{vendor,venue,couple}-dashboard.php` and `template-parts/{vendor,couple}-dashboard/`.
- Auth modal markup in `template-parts/modals/`.

**Crossover points:**
- Theme reads plugin-owned meta directly: `single-vendor.php` calls `get_post_meta( $post_id, 'sdwd_company_name', true )` and similar. The plugin defines the keys; the theme renders.
- Theme enqueues scripts and localizes `sdwd_ajax` / `sdwd_dash` globals with `admin-ajax.php` URL and nonces (`functions.php` lines 146–149, 162–167) — the plugin consumes these via `wp_verify_nonce` / `check_ajax_referer`.
- Theme calls `sdwd_is_unclaimed()` (plugin helper from `includes/claim.php`) and `sdwd_is_role()` / `sdwd_get_user_post_id()` (from `includes/user-post-link.php`) directly.
- Theme filters plugin behavior: `functions.php` line 243 applies `add_filter( 'sdweddingdirectory/post-cap/vendor', '__return_false' )` so admins can create vendors from `wp-admin` (documented in memory as `project_vendor_cpt_fix.md`).
- Theme filters `sdweddingdirectory_icon_library` (lines 248–254) to register the `sdwd-icons` icon set for legacy plugin code.

## Plugin ↔ Plugin Interaction

**sdwd-couple depends on sdwd-core:**
- Enforced by the header `Requires Plugins: sdwd-core` in `sdwd-couple.php` line 8.
- Enforced at runtime by the guard at `sdwd-couple.php` line 18: `if ( ! defined( 'SDWD_CORE_VERSION' ) ) { return; }`.
- Consequence: Deactivating `sdwd-core` silently no-ops all couple features.

**Shared data surface:**
- Reviews (`sdwd_review` CPT) reference vendor/venue by post ID and are keyed off the couple user's ID.
- Wishlist stores `array<int>` of vendor/venue post IDs in `user_meta['sdwd_wishlist']`.
- Budget, checklist stored in user meta (`sdwd_budget_*`, `sdwd_checklist`).
- Couples must hold the `couple` role to submit reviews (`sdwd-couple/modules/reviews.php` line 38).

## Hook & Filter Registration Points

**Actions registered by sdwd-core:**
- `register_activation_hook` — registers roles on activate.
- `plugins_loaded` (prio 5) — loads all modules.
- `admin_enqueue_scripts` — admin CSS/JS on CPT edit screens only.
- `init` (prio 8) — registers post types and taxonomies.
- `add_meta_boxes` — couple, vendor, venue, and claim meta boxes.
- `save_post_{couple,vendor,venue}` — persists meta.
- `admin_menu` — registers `Tools → SDWD Migration`.
- `wp_ajax_*` / `wp_ajax_nopriv_*` — all AJAX endpoints listed above.
- `sdwd_user_registered` (custom) — fired in `auth.php`, consumed in `user-post-link.php`.

**Filters registered by sdwd-core:**
- `wp_insert_post_data` — strips `post_password` from vendor/venue posts (`post-types.php` line 116).

**Actions registered by theme:**
- `after_setup_theme` — nav menus, theme supports, image sizes.
- `widgets_init` — 4 footer widget areas + blog sidebar.
- `wp_enqueue_scripts` — all front-end asset enqueues with conditionals.
- `wp_print_styles` (prio 100) — dequeues legacy `sdweddingdirectory-fontello` and `sdweddingdirectory-flaticon` stylesheets.
- `init` (prio 99) — one-shot rewrite flush for vendor taxonomy base (`inc/vendors.php`).

**Filters registered by theme:**
- `register_taxonomy_args` (prio 20) — overrides `vendor-category` rewrite to `/vendors/` (`inc/vendors.php`).
- `sdweddingdirectory/post-cap/vendor` — returns false to allow admin vendor creation (`functions.php` line 243).
- `sdweddingdirectory_icon_library` — registers `sdwd-icons` stylesheet URL (`functions.php` line 248).

## Error Handling

**Strategy:** WordPress idiomatic — AJAX handlers return `wp_send_json_error( [ 'message' => ... ] )` / `wp_send_json_success(...)`. Nonce failures short-circuit via `check_ajax_referer`. `WP_Error` is checked from `wp_insert_post` / `wp_create_user`.

**Patterns:**
- Nonce-first pattern: every AJAX handler opens with `check_ajax_referer( 'sdwd_*_nonce', 'nonce' )`.
- Role gating: dashboard save re-reads `$user->roles` and rejects unknown roles with `Invalid role.` message.
- Ownership check: dashboard save rejects when `post_author != user->ID` even for a matching `sdwd_post_id`.
- Input sanitization: `sanitize_text_field( wp_unslash( ... ) )`, `sanitize_email`, `sanitize_textarea_field`, `absint`, `wp_kses_post` for rich text.
- Output escaping: `esc_html`, `esc_attr`, `esc_url`, `wp_kses_post` (mandated by `CLAUDE.md`).
- Logging: `wp-content/debug.log` is present (512KB) — WordPress `WP_DEBUG_LOG` is the de facto error log.

## Cross-Cutting Concerns

**Logging:** WordPress `debug.log` at `wp-content/debug.log`. No custom logger; plugins and theme rely on WP's built-in error handling.

**Validation:** Inline in each AJAX handler; no central validator. Pattern: check required fields, validate email with `is_email`, enforce length/range, return localized error message.

**Authentication:** WordPress core (`wp_create_user`, `wp_signon`, `wp_set_current_user`, role check via `WP_User::roles`). Email is used as username (`auth.php` line 56: `wp_create_user( $email, $password, $email )`).

**Nonces:** Per-feature scoped (`sdwd_auth_nonce`, `sdwd_dashboard_nonce`, `sdwd_quote_nonce`, `sdwd_review_nonce`, `sdwd_wishlist_nonce`, `sdwd_checklist_nonce`, `sdwd_budget_nonce`, `sdwd_claim_*_nonce`). Generated in the theme where the form renders; verified in the plugin handler.

**Rewrite rules:** Custom `.htaccess` block (at repo root, bind-mounted) resolves `/wedding-inspiration/{slug}/` overlap between category archives and posts. Plugin registers `vendor` and `venue` singular slugs; theme overrides `vendor-category` taxonomy to use the `/vendors/` base.

**i18n:** Text domains `sdwd-core`, `sdwd-couple`, `sdweddingdirectory-v2`, `sdweddingdirectory`. No `.mo` files present; `load_theme_textdomain` called in `functions.php` line 32.

---

*Architecture analysis: 2026-04-23*
