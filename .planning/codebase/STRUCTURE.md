# Codebase Structure

**Analysis Date:** 2026-04-23

## Directory Layout

```
wp-docker/                              # Repo root = Docker project root, NOT WP root
├── docker-compose.yml                  # 3 services: db, wordpress, wp_ssh
├── Dockerfile.ssh                      # Custom image for wp_ssh (adds SSH + WP-CLI)
├── php.ini                             # PHP overrides (upload_max_filesize 256M, memory 512M)
├── .htaccess                           # Bind-mounted into container; SDWD permalink fix + WP rules
├── PROJECT.md                          # Single source of truth for open tasks
├── CLAUDE.md                           # AI rules, locked pages, hard constraints
├── AGENTS.md                           # Mirror of CLAUDE.md for other agents
├── .gitignore
├── .vscode/
├── .claude/
│   └── settings.local.json
├── .planning/
│   └── codebase/                       # Generated analysis docs (this directory)
├── Documentation/                      # Human-authored reference docs
│   ├── README.md
│   ├── architecture.md                 # System rules, CSS architecture, naming
│   ├── codebase-index.md               # Every theme file with purpose + status
│   ├── component-index.md              # Reusable component catalog
│   ├── page-template-css-map.md
│   ├── parity-responses.md
│   ├── plugin-parity-audit.md
│   ├── route-template-map.md           # URL → template → data flow
│   ├── TASK_LOG.md
│   ├── vendor-dashboard-spec.md
│   ├── vendor-venue-parity-audit.md
│   └── screenshots/                    # Annotated UI targets
└── wp-content/                         # Bind-mounted → /var/www/html/wp-content
    ├── index.php                       # WP stub (from core, committed)
    ├── debug.log                       # WP_DEBUG_LOG output
    ├── plugins/
    │   ├── index.php                   # WP stub
    │   ├── sdwd-core/                  # Custom — data/logic for couple/vendor/venue
    │   ├── sdwd-couple/                # Custom — reviews, quotes, wishlist, checklist, budget
    │   ├── seo-by-rank-math/           # Third-party — SEO
    │   ├── shortpixel-image-optimiser/ # Third-party — image optimization
    │   ├── updraftplus/                # Third-party — backups
    │   ├── w3-total-cache/             # Third-party — caching
    │   ├── wordfence/                  # Third-party — security
    │   └── wp-mail-smtp/               # Third-party — transactional mail
    ├── themes/
    │   ├── index.php                   # WP stub
    │   └── sandiegoweddingdirectory/   # Only theme — the working theme
    ├── upgrade/                        # WP-managed; empty in repo
    └── uploads/                        # Media library (year-flat structure); see note
```

**Note on `wp-content/uploads/`:** Contains site media. Year-based or flat organization (seen: `blog/`, `couples/`, and loose `topnotch*.{png,jpg}` at the top level). Treated as user data; do not enumerate or edit — referenced only by DB `wp_posts` (attachment rows) and by CSS background URLs on live pages.

**Note on WordPress core:** Not present in this repo. `wp-admin/`, `wp-includes/`, `wp-login.php`, `index.php` (root), and `wp-load.php` live inside the `wp_data` named Docker volume at `/var/www/html/` inside the `wp_app` container. Only `wp-content/` is bind-mounted from this repo.

**Note on missing dirs:** No `mu-plugins/` directory exists. No `languages/` directory at `wp-content/` level.

## Directory Purposes

**`wp-content/plugins/sdwd-core/`:**
- Purpose: Data + business logic. Registers CPTs, taxonomies, roles, AJAX endpoints, admin meta boxes.
- Contains: One bootstrap file, module PHP files, a tiny `assets/` with admin CSS/JS.
- Key files:
  - `sdwd-core.php` — plugin header + bootstrap (`plugins_loaded` prio 5).
  - `includes/post-types.php` — registers `couple`, `vendor`, `venue`.
  - `includes/taxonomies.php` — registers `vendor-category`, `venue-type`, `venue-location`.
  - `includes/roles.php` — registers `couple`, `vendor`, `venue` roles.
  - `includes/auth.php` — registration, login, forgot-password AJAX.
  - `includes/user-post-link.php` — auto-creates CPT post on `sdwd_user_registered`.
  - `includes/dashboard.php` — `sdwd_save_dashboard` AJAX.
  - `includes/claim.php` — claim unclaimed vendor/venue.
  - `includes/quote.php` — quote-request AJAX.
  - `includes/migrate.php` — one-time meta-key migration admin page.
  - `includes/admin/{couple,vendor,venue}-meta.php` — admin meta boxes.
  - `assets/admin.css`, `assets/admin.js` — admin-only assets.

**`wp-content/plugins/sdwd-couple/`:**
- Purpose: Couple-facing features. Requires sdwd-core (header declaration + runtime guard).
- Contains: One bootstrap file and five module files.
- Key files:
  - `sdwd-couple.php` — plugin header + bootstrap.
  - `modules/reviews.php` — registers `sdwd_review` CPT + submit-review AJAX.
  - `modules/request-quote.php` — couple-initiated quote requests.
  - `modules/wishlist.php` — toggle wishlist AJAX (user meta `sdwd_wishlist`).
  - `modules/checklist.php` — save checklist AJAX.
  - `modules/budget.php` — save budget AJAX.

**`wp-content/themes/sandiegoweddingdirectory/`:**
- Purpose: All front-end rendering, CSS, JS, template hierarchy.
- Contains: Page templates, archive templates, single templates, taxonomy templates, template parts, assets, 3 helper includes.
- Key files: See "Key File Locations" below.

**`wp-content/themes/sandiegoweddingdirectory/assets/css/`:**
- Purpose: Four-file CSS system per `CLAUDE.md`.
- Contains: `foundation.css`, `components.css`, `layout.css`, `pages/*.css`.
- Order: foundation → components → layout → page-specific (enforced by enqueue dependencies in `functions.php`).

**`wp-content/themes/sandiegoweddingdirectory/assets/css/pages/`:**
- Purpose: Page-specific stylesheets, conditionally enqueued.
- Contains: `home.css`, `archive.css`, `venues.css`, `venues-landing.css`, `vendors.css`, `profile.css`, `planning.css`, `blog.css`, `inspiration.css`, `static.css`, `dashboard.css`, `vendor-dashboard.css`, `couple-dashboard.css`, `modals.css`.

**`wp-content/themes/sandiegoweddingdirectory/assets/js/`:**
- Purpose: Vanilla JS only — no jQuery, no libraries.
- Contains: `app.js` (global interactions), `modals.js` (auth modals + AJAX), `dashboard.js` (dashboard save + social row management).

**`wp-content/themes/sandiegoweddingdirectory/assets/library/sdwd-icons/`:**
- Purpose: Custom icomoon icon font — replaces Font Awesome.
- Contains: `style.css` (icon classes `icon-{name}`), font files.
- Usage: `<span class="icon-search"></span>`.

**`wp-content/themes/sandiegoweddingdirectory/assets/fonts/`:**
- Purpose: Local WOFF2 variable fonts — no Google Fonts allowed.
- Files: `Inter-VariableFont_slnt,wght.woff2`, `WorkSans-VariableFont_wght.woff2`.

**`wp-content/themes/sandiegoweddingdirectory/assets/images/`:**
- Purpose: Theme-shipped imagery (not user uploads).
- Subdirs: `banners/`, `blog/`, `categories/`, `icons/`, `locations/`, `logo/`, `map/`, `pages/`, `placeholders/`, `planning/`, `real-wedding/`, `social-media/`, `team/`, `404-error-page/`, plus `_inbox icons and images/` (staging area).

**`wp-content/themes/sandiegoweddingdirectory/inc/`:**
- Purpose: Helper includes. **HARD-CAPPED AT 3 FILES** by `CLAUDE.md`. Do NOT add files here.
- Files: `sd-mega-navwalker.php`, `vendors.php`, `venues.php`.

**`wp-content/themes/sandiegoweddingdirectory/template-parts/`:**
- Purpose: Reusable partials invoked via `get_template_part()`.
- Subdirs:
  - `components/` — reusable on 2+ page types (cards, section-title, breadcrumbs, pagination, faq-accordion, page-header, popouts).
  - `planning/` — planning-page-specific sections.
  - `blog/` — blog-page-specific layouts.
  - `modals/` — login/registration/forgot-password modals.
  - `couple-dashboard/` — couple dashboard sections.
  - `vendor-dashboard/` — vendor dashboard sections.
  - `vendors/` — vendors archive listing partial.
  - `venues/` — venues archive listing partial.
- Top-level: `why-use-sdwd.php` (standalone shared partial).

**`wp-content/themes/sandiegoweddingdirectory/user-template/`:**
- Purpose: Page-assignable "Template Name" wrappers for dashboards and an alternate homepage.
- Files: `vendor-dashboard.php`, `venue-dashboard.php`, `couple-dashboard.php`, `vendor-profile.php`, `front-page.php`.

**`Documentation/`:**
- Purpose: Human-authored project reference. Not regenerated automatically.
- Canonical docs: `architecture.md` (system rules), `codebase-index.md` (file status), `component-index.md` (component catalog), `route-template-map.md` (URL mapping).
- `screenshots/` holds annotated target images — the visual source of truth per `CLAUDE.md`.

## Key File Locations

**Docker/Infrastructure:**
- `docker-compose.yml` — 3 services (db, wordpress, wp_ssh), 2 named volumes.
- `Dockerfile.ssh` — custom image extending `wordpress:latest` with SSH + WP-CLI.
- `php.ini` — uploads, memory, execution-time overrides (bind-mounted to `/usr/local/etc/php/conf.d/uploads.ini`).
- `.htaccess` — SDWD permalink conflict fix + standard WP rules.

**Top-level reference:**
- `PROJECT.md` — open task tracker (single source of truth).
- `CLAUDE.md` — hard rules, locked pages, banned dependencies.
- `AGENTS.md` — mirror of CLAUDE.md.

**Plugin entry points:**
- `wp-content/plugins/sdwd-core/sdwd-core.php`
- `wp-content/plugins/sdwd-couple/sdwd-couple.php`

**Theme entry points (WordPress template hierarchy):**
- `wp-content/themes/sandiegoweddingdirectory/style.css` — theme metadata only.
- `wp-content/themes/sandiegoweddingdirectory/functions.php` — setup, enqueues, nav menus, widgets.
- `wp-content/themes/sandiegoweddingdirectory/header.php`, `footer.php`, `index.php`.

**Page orchestrators:**
- `front-page.php` — homepage (LOCKED per `CLAUDE.md`; do not edit without founder approval).
- `page-wedding-planning.php`, `page-venues.php`, `page-vendors.php`, `page-inspiration.php`.
- `page-about.php`, `page-contact.php`, `page-faqs.php`, `page-policy.php`, `page-our-team.php`, `page-style-guide.php`.
- `page.php` — default page (routes planning children to `planning-child-page` template part via parent-ID 4180 check).

**Archive templates:**
- `archive-vendor.php`, `archive-venue.php`, `archive-real-wedding.php`, `archive-couple.php`, `archive-website.php`.
- `archive.php`, `category.php` — blog fallback.

**Taxonomy templates:**
- `taxonomy-vendor-category.php` (not yet implemented per `Documentation/codebase-index.md`).
- `taxonomy-venue-type.php`, `taxonomy-venue-location.php`.
- `taxonomy-real-wedding-{category,color,community,location,season,space-preferance,style,tag}.php`.

**Single templates:**
- `single-vendor.php` (active, ~370 lines, reads plugin meta directly).
- `single-venue.php`, `single-real-wedding.php`, `single-couple.php`, `single-website.php`, `single-team.php`, `single-changelog.php`, `single.php`.

**Other templates:**
- `404.php`, `search.php`, `searchform.php`, `sidebar.php`.

**Plugin CPT registration:**
- `wp-content/plugins/sdwd-core/includes/post-types.php` — couple, vendor, venue.
- `wp-content/plugins/sdwd-couple/modules/reviews.php` — sdwd_review.

**Plugin taxonomy registration:**
- `wp-content/plugins/sdwd-core/includes/taxonomies.php` — vendor-category, venue-type, venue-location.

**Plugin role registration:**
- `wp-content/plugins/sdwd-core/includes/roles.php` — couple, vendor, venue.

**Plugin admin meta boxes:**
- `wp-content/plugins/sdwd-core/includes/admin/couple-meta.php` (~152 lines).
- `wp-content/plugins/sdwd-core/includes/admin/vendor-meta.php` (~284 lines).
- `wp-content/plugins/sdwd-core/includes/admin/venue-meta.php` (~341 lines).

**Generated/Runtime:**
- `wp-content/debug.log` — WordPress error log (not committed; may be present locally).

## Custom Plugins (Project-Authored)

| Plugin Slug | Main File | Active Expected | Purpose |
|---|---|---|---|
| `sdwd-core` | `wp-content/plugins/sdwd-core/sdwd-core.php` | Yes (required) | CPTs, taxonomies, roles, AJAX, admin meta |
| `sdwd-couple` | `wp-content/plugins/sdwd-couple/sdwd-couple.php` | Yes (depends on sdwd-core) | Reviews, wishlist, checklist, budget, quote requests |

## Third-Party Plugins (Vendor Code)

Activation state lives in the DB — cannot be confirmed from files alone. `PROJECT.md §6` lists these as pending setup/configuration, implying most are installed but not yet fully activated.

| Plugin Slug | Main File | Version | Purpose |
|---|---|---|---|
| `seo-by-rank-math` | `rank-math.php` | 1.0.265 | SEO |
| `shortpixel-image-optimiser` | `wp-shortpixel.php` | 6.4.3 | Image optimization |
| `updraftplus` | `updraftplus.php` | 1.26.2 | Backups |
| `w3-total-cache` | `w3-total-cache.php` | 2.9.2 | Page/object/browser cache |
| `wordfence` | `wordfence.php` | 8.1.4 | Security / WAF |
| `wp-mail-smtp` | `wp_mail_smtp.php` | 4.7.1 | Transactional email |

## Themes

| Theme Slug | Active | Purpose |
|---|---|---|
| `sandiegoweddingdirectory` | Yes (only theme) | The working theme — owns all front-end rendering |

**Hard rule from `CLAUDE.md`:** This is the ONLY theme directory. Do not create, rename, copy, or restore other theme directories. The legacy `sdweddingdirectory` theme has been removed.

## Naming Conventions

**Plugin slugs:**
- Custom plugins: kebab-case prefixed with `sdwd-` (`sdwd-core`, `sdwd-couple`).
- Main plugin file matches directory name (`sdwd-core/sdwd-core.php`).
- Third-party plugins keep their upstream slugs.

**Custom Post Type slugs:**
- Lowercase singular, kebab-case where multi-word.
- Core set (`sdwd-core`): `couple`, `vendor`, `venue`.
- Couple-owned CPT: `sdwd_review` (underscore prefix — the outlier, used only internally).
- Documented future CPTs (from `PROJECT.md` + `Documentation/architecture.md`): `real-wedding`, `website`, `team`, `changelog`, `testimonials`, `claim-venue`.

**Taxonomy slugs:**
- Kebab-case, `{parent-cpt}-{dimension}`.
- `vendor-category` (vendors).
- `venue-type`, `venue-location` (venues).
- Documented future: `real-wedding-{category,color,community,location,season,space-preferance,style,tag}`.

**Role slugs:**
- Lowercase singular matching CPT slug where applicable: `couple`, `vendor`, `venue`.
- Exactly one of these three per user; no overlap.

**Meta keys:**
- All post/user meta prefixed `sdwd_`: `sdwd_company_name`, `sdwd_email`, `sdwd_phone`, `sdwd_company_website`, `sdwd_street_address`, `sdwd_city`, `sdwd_zip_code`, `sdwd_capacity`, `sdwd_social`, `sdwd_hours`, `sdwd_pricing`, `sdwd_post_id`, `sdwd_wishlist`, `sdwd_budget`, `sdwd_checklist`, `sdwd_wedding_date`, `sdwd_couple_type`, `sdwd_claim`.

**Nonce names:**
- `sdwd_{feature}_nonce`: `sdwd_auth_nonce`, `sdwd_dashboard_nonce`, `sdwd_quote_nonce`, `sdwd_review_nonce`, `sdwd_wishlist_nonce`, `sdwd_checklist_nonce`, `sdwd_budget_nonce`, `sdwd_claim_approve_nonce`, etc.

**AJAX actions:**
- `sdwd_{verb}_{object}`: `sdwd_register`, `sdwd_login`, `sdwd_forgot_password`, `sdwd_save_dashboard`, `sdwd_send_quote`, `sdwd_submit_claim`, `sdwd_approve_claim`, `sdwd_reject_claim`, `sdwd_submit_review`, `sdwd_toggle_wishlist`, `sdwd_save_checklist`, `sdwd_save_budget`, `sdwd_request_quote`.

**Template file naming:**
- Follows WordPress hierarchy exactly: `single-{post_type}.php`, `archive-{post_type}.php`, `taxonomy-{slug}.php`, `page-{slug}.php`, `category.php`.
- Page templates with `Template Name:` header can live anywhere; dashboards live in `user-template/`.

**Template parts:**
- Components (reusable on 2+ page types): noun — `vendor-card.php`, `pagination.php`, `breadcrumbs.php`.
- Sections (page-scoped): `{page}-{section}.php` — `planning-hero.php`, `planning-faq.php`.
- Dashboard sections: `{role}-dashboard-{section-n}-{name}.php` — `couple-dashboard-section-1-vendor-team.php`, `vendor-dashboard-sidebar-right-2-tips.php`.
- No generic names (`part-1.php`, `block.php`) — banned by `Documentation/architecture.md`.

**CSS class naming (BEM-lite per `CLAUDE.md`):**
- Block / Element: `.block__element` — `.card__title`, `.hero__search`.
- Modifier: `.block--modifier` — `.btn--primary`, `.grid--4col`.
- Single class per styled element; max one level of nesting.
- No IDs for styling, no element selectors, no utility classes.

**CSS file names:**
- Shared: `foundation.css`, `components.css`, `layout.css` (load on every page).
- Page-specific: `pages/{page}.css` — loaded conditionally from `functions.php`.

**JS file names:**
- `app.js`, `modals.js`, `dashboard.js` — scoped by feature, vanilla only.

**Theme text domains:**
- Theme: `sandiegoweddingdirectory` (the literal text-domain string `sdweddingdirectory-v2` is still in `functions.php` pre-`P1-CLEAN-04` sweep; it is a code-identifier artifact, not a project versioning framing).
- Plugins: `sdwd-core`, `sdwd-couple`.

**Icon classes:**
- Prefix `icon-` from the sdwd-icons font. `icon-search`, `icon-building`, `icon-camera1`, `icon-check`, `icon-user`.
- Font Awesome classes (`fa`, `fa-*`) are BANNED.

## Where to Add New Code

**New custom post type or taxonomy:**
- Register in `wp-content/plugins/sdwd-core/includes/post-types.php` or `includes/taxonomies.php`. Do NOT register CPTs in the theme.
- If tightly coupled to couple features (like reviews), register in an `sdwd-couple/modules/*.php` module.

**New AJAX endpoint:**
- If it relates to vendor/venue/couple core flow: add a new file under `wp-content/plugins/sdwd-core/includes/` and `require_once` it from `sdwd-core.php`.
- If couple-only: add a module under `wp-content/plugins/sdwd-couple/modules/` and `require_once` it from `sdwd-couple.php`.
- Use unique nonce + `check_ajax_referer` as first line. Always return `wp_send_json_success` / `wp_send_json_error`.

**New admin meta box:**
- Create `wp-content/plugins/sdwd-core/includes/admin/{cpt}-meta.php`. Load it inside the `is_admin()` branch of `sdwd-core.php`.

**New page template (orchestrator):**
- Add `page-{slug}.php` at `wp-content/themes/sandiegoweddingdirectory/` for a URL-specific page.
- Add `Template Name:` header at top if it should be user-selectable.
- Compose by calling `get_template_part( 'template-parts/{group}/{part}', null, [...] )`.

**New archive/single/taxonomy:**
- Use exact WordPress hierarchy naming: `archive-{type}.php`, `single-{type}.php`, `taxonomy-{slug}.php`.
- Prefer hybrid pattern (thin orchestrator calling a listing partial) — see `archive-venue.php` → `template-parts/venues/listing.php`.

**New reusable component:**
- Path: `wp-content/themes/sandiegoweddingdirectory/template-parts/components/{noun}.php`.
- Accept inputs via `$args` extracted with `??`. Do not rely on globals.
- Document in `Documentation/component-index.md`.

**New page-specific section:**
- If tied to one page type: `template-parts/{page}/{page}-{section}.php`.
- If reusable on 2+ page types: move to `template-parts/components/`.

**New CSS for a new page type:**
- Create `assets/css/pages/{name}.css`.
- Add a conditional `wp_enqueue_style` block in `functions.php` keyed on `is_page_template( ... )` or `is_singular( ... )`.
- Never add styles to `foundation.css`, `components.css`, or `layout.css` unless they belong to that file's domain.
- Use only CSS tokens from `foundation.css :root`. No raw hex colors, no `!important`, no utility classes.

**New JS:**
- Vanilla JS only. Add to `assets/js/` as a new file or extend `app.js`.
- Enqueue in `functions.php`; localize with `wp_localize_script( ..., 'sdwd_*', [ 'url' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( ... ) ] )`.

**New helper function:**
- Plugin-scoped: add to the relevant `includes/*.php`.
- Theme-scoped: **do NOT add files to `inc/`** (hard-capped at 3). Add functions to the bottom of `functions.php`, or into an existing `inc/` file if they fit its domain.

**New image asset:**
- Theme-shipped: `assets/images/{category}/` (e.g., `banners/`, `icons/`, `placeholders/`).
- User-uploaded: WordPress media library → `wp-content/uploads/` (not manually managed).

**New third-party plugin:**
- Drop into `wp-content/plugins/{slug}/`. Do not commit sensitive credentials.
- Activate via wp-admin or WP-CLI inside the `wp_ssh` container.
- Confirm it does not pull in any banned dependency (Bootstrap/FA/jQuery/Google Fonts); if it does, STOP per `CLAUDE.md`.

## Special Directories

**`.planning/codebase/`:**
- Purpose: Generated codebase analysis documents (ARCHITECTURE.md, STRUCTURE.md, etc.).
- Generated: Yes.
- Committed: Per repo convention (no explicit exclusion in `.gitignore`).

**`Documentation/`:**
- Purpose: Human-authored project reference.
- Generated: No.
- Committed: Yes.

**`Documentation/screenshots/`:**
- Purpose: Annotated UI targets — visual source of truth per `CLAUDE.md`.
- Generated: No.
- Committed: Yes.

**`wp-content/uploads/`:**
- Purpose: WordPress media library (user-uploaded images).
- Generated: Yes (by wp-admin uploads).
- Committed: Typically excluded but this repo's `.gitignore` should be inspected to confirm.

**`wp-content/upgrade/`:**
- Purpose: WordPress scratch space during core/plugin upgrades.
- Generated: Yes.
- Committed: No (should be empty in repo).

**`assets/images/_inbox icons and images/`:**
- Purpose: Staging area for unsorted assets — likely transient.
- Committed: Present in repo; treat contents as in-progress.

**Named Docker volumes (NOT in repo):**
- `db_data` — MySQL data directory.
- `wp_data` — WordPress core files (`/var/www/html/` minus `wp-content/`).

---

*Structure analysis: 2026-04-23*
