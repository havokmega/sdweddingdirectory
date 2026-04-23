# San Diego Wedding Directory — v1-launch

## What This Is

A Docker-hosted WordPress wedding directory platform for San Diego, rebuilt from the ground up as a v2 theme (`sandiegoweddingdirectory`) with two custom plugins (`sdwd-core`, `sdwd-couple`) replacing the legacy v1 directory that's been limping for months. It serves three audiences — couples planning San Diego weddings, vendors selling services to them, and venues hosting them — with public directory pages, three role-based dashboards, a claim flow for unclaimed listings, and a transactional email surface.

## Core Value

**Ship `v1-launch` without regressing the v2 foundation** — the first public cut of the v2 stack must replace the v1 site cleanly and pass all four hard launch gates (couple AJAX works, combo SEO pages return 200, all three dashboards are E2E-functional, transactional email delivers). Everything else is negotiable.

## Requirements

### Validated

<!-- Inferred from existing v2 code that's already working and depended on. -->

- ✓ Docker stack: MySQL 8 + WordPress/Apache + custom SSH container with WP-CLI, all wired against bind-mounted `wp-content/` — existing
- ✓ `sdwd-core` plugin owns data/logic: registers 3 CPTs (couple, vendor, venue), 3 taxonomies (vendor-category, venue-type, venue-location), 3 matching roles, 11+ AJAX handlers (auth, dashboard, quote, claim), admin meta boxes, migration tool — existing
- ✓ `sdwd-couple` plugin owns couple-side modules: reviews CPT, wishlist, checklist, budget, request-quote (hard-dependent on `sdwd-core` via `Requires Plugins` header + runtime guard) — existing
- ✓ Theme `sandiegoweddingdirectory` owns all front-end HTML/CSS/JS with strict separation (no Bootstrap/FA/jQuery/Google Fonts/page builders/inline styles/hex/!important) — existing
- ✓ Home page (`front-page.php`) shipped and locked; design-token system in `foundation.css` — existing
- ✓ Wedding Planning page + child pages — existing (child pages at 90%, final QA pending)
- ✓ Navigation mega-menu via `inc/sd-mega-navwalker.php` — existing (missing user-o icon on "Join as Couple")
- ✓ Venues landing page (`page-venues.php`) + `archive-venue.php` + `single-venue.php` shell — existing (80%)
- ✓ Vendors landing `page-vendors.php` + `archive-vendor.php` + `single-vendor.php` shell — existing
- ✓ Registration/login/forgot-password AJAX + modals + user↔CPT linkage on `sdwd_user_registered` hook — existing
- ✓ Claim flow AJAX (submit/approve/reject) registered — existing (frontend submit handler wired on `single-venue.php`; `single-vendor.php` button exists but handler NOT wired — see Active)
- ✓ Conditional CSS/JS enqueuing in `functions.php` gated by `is_singular`, `is_post_type_archive`, `is_tax`, dashboard/planning helpers — existing
- ✓ Codebase map in `.planning/codebase/` (ARCHITECTURE.md, CONCERNS.md, CONVENTIONS.md, INTEGRATIONS.md, STACK.md, STRUCTURE.md, TESTING.md) dated 2026-04-23 — existing

### Active

<!-- v1-launch milestone. Five phases shipped in order. -->

#### Phase 1 — Close in-progress + cleanup

- [ ] **P1-QA-01** Planning child pages — final QA pass (intro width confirmed, remaining items in PROJECT.md root §1)
- [ ] **P1-BUILD-01** Global footer built (currently "Not started")
- [ ] **P1-BUILD-02** Error 404 page built (currently "Not started")
- [ ] **P1-CLEAN-01** Delete dead filter `sdweddingdirectory/post-cap/vendor` in `functions.php:240-243` (no consumer exists in `sdwd-core`/`sdwd-couple`)
- [ ] **P1-CLEAN-02** Delete dead `sdweddingdirectory_icon_library` filter in `functions.php:245-254` (same reason)
- [ ] **P1-CLEAN-03** Delete dead `.modal.fade { display: none; }` rule in `assets/css/layout.css:687-690` (v2 modal system has its own classes; rule will swallow legitimate third-party modals)
- [ ] **P1-CLEAN-04** Standardize text-domain to `sandiegoweddingdirectory` across theme (`functions.php` + all templates using `'sdweddingdirectory-v2'` or `'sdweddingdirectory'`) and verify `style.css` `Theme Name:` header matches
- [ ] **P1-FIX-01** Fix `template-parts/planning/planning-hero.php:62-235` — registration form submits to removed legacy action `sdweddingdirectory_couple_register_form_action`. Retarget to `sdwd_register`, rename inputs to match `sdwd-core/includes/auth.php` (first_name, last_name, email, password, account_type=couple, wedding_date), replace legacy nonce with theme-minted `sdwd_auth_nonce`
- [ ] **P1-CLEAN-05** Delete dead `wp_dequeue_style('sdweddingdirectory-fontello' / '-flaticon')` calls in `functions.php:175-181`
- [ ] **P1-CLEAN-06** Rename `add_image_size('sdweddingdirectory_img_*'`) to `sdwd_img_*` in `functions.php:58-59` (or remove if unused after audit)
- [ ] **P1-CLEAN-07** Delete root-level macOS `Icon` artifact and any other low-priority LOW items from CONCERNS.md that are in-scope while we're in the file

#### Phase 2 — Plugin closeout + parity + security

- [ ] **P2-NONCE-01** Issue 5 missing `sdwd-couple` nonces (`sdwd_checklist_nonce`, `sdwd_budget_nonce`, `sdwd_wishlist_nonce`, `sdwd_review_nonce`, and per-feature split of `sdwd_quote_nonce`) via `wp_localize_script('sdwd-couple', 'sdwd_couple', [ 'nonce' => [...] ])` or single couple-wide nonce; verify every `check_ajax_referer` call in `sdwd-couple/modules/*` has a matching issuer
- [ ] **P2-CLAIM-01** Wire `single-vendor.php` claim button handler (currently button exists but nothing fires on click); extract `claim.js` to `assets/js/`, bind to `[data-sdwd-claim]`, fetch nonce via localized bundle, delete inline `<script>` from both `single-venue.php:382-408` and `single-vendor.php`
- [ ] **P2-PORT-01** Port module `seating-chart` from legacy couple plugin to `sdwd-couple/modules/`
- [ ] **P2-PORT-02** Port module `rsvp` from legacy couple plugin
- [ ] **P2-PORT-03** Port module `guest-list` from legacy couple plugin
- [ ] **P2-PORT-04** Port module `todo-list` from legacy couple plugin
- [ ] **P2-PORT-05** Port module `website` (couple wedding-website module) from legacy plugin
- [ ] **P2-MIGRATE-01** Run `sdwd-core/includes/migrate.php` on real data in staging, verify round-trip, add chunked processing (batch 50) + `sdwd_migration_version` option for idempotence (CONCERNS `Migration tool can run more than once`)
- [ ] **P2-PARITY-01** Flip `has_archive` to `true` on both `vendor` and `venue` CPTs in `sdwd-core/includes/post-types.php:69,101`; fold `page-venues.php` logic into `archive-venue.php`
- [ ] **P2-PARITY-02** Re-register `venue-location` taxonomy with `hierarchical => false` in `sdwd-core/includes/taxonomies.php:78-85`; include migration step for existing hierarchical terms → flat (city only)
- [ ] **P2-PARITY-03** Clone `user-template/vendor-profile.php` → `venue-profile.php` with address/capacity fields added
- [ ] **P2-PARITY-04** Add missing venue description field to `venue-dashboard.php` so venue owners can edit their description (parallel to `vendor-profile.php:90-96`)
- [ ] **P2-SEC-01** Password strength check (≥8 chars, reuse registration rule) in `sdwd_save_dashboard()` + 3 admin metabox save callbacks (couple-meta, vendor-meta, venue-meta)
- [ ] **P2-SEC-02** Add `current_password` verification field on frontend password-change path (`dashboard.php:127-131`); clear the new-password input after save
- [ ] **P2-SEC-03** Add `wp_unslash()` on admin metabox password inputs (`couple-meta.php:137`, `vendor-meta.php:234`, `venue-meta.php:291`) to prevent silent login breakage on `'`, `"`, `\` in passwords
- [ ] **P2-SEC-04** Basic rate-limit on password-change and login endpoints (transient-counter per IP; simple fail-closed)
- [ ] **P2-SEC-05** Extract inline JS from admin claim metabox (`claim.php:128-144`); move to `sdwd-core/assets/admin.js`, pass data via `data-*` attrs, escape `$post->ID` and `$nonce` with `esc_attr`
- [ ] **P2-SEC-06** Fix repeater-field slash accumulation: read values raw, escape only via `esc_attr` on render (not re-unslash); single helper or standardize via `update_post_meta` (`vendor-meta.php:157-205`, `venue-meta.php:206-254`)

#### Phase 3 — Missing + ported templates

- [ ] **P3-DISCO-01** **Port-source discovery** (first task): fingerprint every candidate source folder for every target page. For SDWD-v1 candidates (`legacy-sdweddingdirectory/`, `SDWeddingDirectory BBEdit/`, `sdweddingdirectory-contaminated/`, `sdweddingdirectory-final-backup/`, `sdweddingdirectory-v2-snapshot/`, `wp-content.zip`) × targets (`page-inspiration.php`, `category.php`, `archive.php`, `single.php`, `page-policy.php` with 4-tab): for each folder × target, report (a) present?, (b) last modified date, (c) line count, (d) first 30 lines. For WeddingDir themeforest candidates (`themeforest-Os2C2dOt-.../Untouched Original Theme Source WeddingDir/`, `themeforest-Os2C2dOt-.../weddingdir-child/`, `WeddingDIr/`, `ThemeFilesModified/weddingdir/`, `ThemeFilesModified/weddingdir-child/`) × targets (`page-wedding-websites`-style landing, templates gallery, `archive-real-wedding`, `single-real-wedding`, `listing-not-found`): same fingerprint table. Surface results to founder at execution time for per-page source selection. **Pins the process, not a folder.**
- [ ] **P3-BUILD-01** Build `page-cost.php` + 17 child cost pages (one per vendor category) from wireframes; original SD-specific pricing content; 300-word min intro per cost page
- [ ] **P3-BUILD-02** Build `page-registry.php` + `page-registry-child.php` from wireframes
- [ ] **P3-BUILD-03** Build hashtag generator (tool page — scope at plan-phase time)
- [ ] **P3-PORT-01** Port `page-inspiration.php` (source decided from P3-DISCO-01)
- [ ] **P3-PORT-02** Port category archive (`category.php`) including planning-subcategory sidebar handling
- [ ] **P3-PORT-03** Port blog archive (`archive.php`)
- [ ] **P3-PORT-04** Port single blog post (`single.php`) with article layout + JS content splitter
- [ ] **P3-PORT-05** Port 4-tab policy group (`page-policy.php` with privacy, terms, CA privacy, cookies)
- [ ] **P3-PORT-06** Port wedding-websites landing + templates gallery (source from themeforest candidates in P3-DISCO-01). **Strip all Bootstrap/FA/jQuery/Google Fonts on import**; keep only the CSS styles the founder approves. `single-website.php` already exists — integrate with landing/gallery
- [ ] **P3-PORT-07** Port `archive-real-wedding.php` + `single-real-wedding.php` (gallery + story + vendor credits) from themeforest. Register `real-wedding` CPT + the 8 taxonomies (`real-wedding-{category,color,community,location,season,space-preferance,style,tag}`) in `sdwd-core/includes/post-types.php` + `taxonomies.php` (CONCERNS: currently referenced by theme but not registered). If not registering: delete orphan theme files + strip `real-wedding` from `functions.php:122` `is_singular()`
- [ ] **P3-PORT-08** Port `listing-not-found` template from themeforest
- [ ] **P3-CLEAN-01** Clean up action-hook stubs: decide per-file whether to delete or rebuild `single-couple.php:12`, `single-website.php:13` (legacy `do_action('sdweddingdirectory/...')` calls into handlers that do not exist)

#### Phase 4 — Combo venue SEO pages

- [ ] **P4-TPL-01** Create `combo-venue.php` template; H1 pattern `"San Diego {Type} Weddings in {Location}"`; 300-word min intro per combo; Schema.org LocalBusiness + ItemList JSON-LD
- [ ] **P4-ROUTE-01** Create `sdwd-core/includes/routing/rewrite-rules.php` registering `/{location}/{type}/` for every valid combination via `add_rewrite_rule()`; wire into plugin bootstrap
- [ ] **P4-ROUTE-02** Fix rewrite-flush parity gap — `inc/venues.php` needs the same `flush_rewrite_rules` + `sdwd_*_rewrite_version` option guard that `inc/vendors.php:31-46` has; or extract shared helper so both use one mechanism. Prevents collision after cache purges
- [ ] **P4-PERF-01** Wrap taxonomy queries in `inc/vendors.php` (851 lines) and `inc/venues.php` (816 lines) in `get_transient`/`set_transient` with 1h TTL; invalidate on `edited_term`/`created_term` hooks for relevant taxonomies
- [ ] **P4-QA-01** Verify every valid location×type combo resolves HTTP 200 with real content (not 404); spot-check `/carlsbad/beach-weddings/`, `/la-jolla/estate-weddings/`, a handful more

#### Phase 5 — Launch prep

- [ ] **P5-DASH-01** `user-template/vendor-dashboard.php` — verify `$quotes = []` placeholder is replaced with a real helper (`sdwd_get_vendor_quote_requests($vendor_id)` scanning couple posts for `sdwd_quote_requests` entries matching vendor)
- [ ] **P5-DASH-02** `dashboard.css` — CSS overrides for plugin dashboard markup; test every dashboard tab for vendor, venue, couple roles
- [ ] **P5-DASH-03** Full E2E QA: register → login → save data → receive email confirmation, for all 3 roles
- [ ] **P5-THEME-01** Build 5 additional wedding-website themes sharing the same data model as the existing "Website Template One": modern minimal, romantic classic, rustic, bold/colorful, elegant dark, garden/floral (finalize concepts with founder)
- [ ] **P5-THEME-02** Update template-selector UI with previews in couple dashboard; add theme thumbnails
- [ ] **P5-EMAIL-01** Activate + configure `wp-mail-smtp`
- [ ] **P5-EMAIL-02** Build transactional email templates: quote requests, registration confirmations, welcome, claim notifications (approval + rejection). Wire into existing handlers
- [ ] **P5-EMAIL-03** Verify delivery to real inbox (launch gate)
- [ ] **P5-SEO-01** Configure SEO by Rank Math: sitemap, canonicals, robots, schema defaults, per-page meta
- [ ] **P5-SEO-02** Internal linking sweep: Blog → vendors+venues, Venues → vendors, Cost → vendors, Real Weddings → both
- [ ] **P5-SEO-03** 300-word intros on every archive page (vendors, venues, taxonomy archives, inspiration, real-weddings)
- [ ] **P5-SEO-04** H1 geo-targeting audit — every vendor archive H1 = `"San Diego Wedding {Category}"`; venue archive H1 = `"San Diego {Type} Wedding Venues"`; combo pages covered in Phase 4
- [ ] **P5-STYLE-01** Rewrite `page-style-guide.php` — currently uses banned Bootstrap classes (`row`, `col-md-*`), Font Awesome markup, 394 inline styles; rewrite using `.grid`/`.container`, `icon-*` from sdwd-icons, BEM-only, no inline styles (or move to a separate plugin and deactivate in prod)
- [ ] **P5-PERF-01** Image optimization pass: convert theme `real-wedding/*.png` and other >1MB PNGs to WebP at 1920px max (<200KB each); move `assets/images/_inbox icons and images/` out of theme; audit git-tracked screenshot bloat (155MB in `Documentation/screenshots/`)
- [ ] **P5-PERF-02** Configure W3 Total Cache + ShortPixel Image Optimizer
- [ ] **P5-QA-01** Cross-browser testing: Chrome, Safari, Firefox, mobile
- [ ] **P5-QA-02** Lighthouse audit — performance, accessibility, SEO; fix blocking issues
- [ ] **P5-QA-03** Verify conditional CSS/JS enqueuing still works after all Phase 1–4 changes
- [ ] **P5-QA-04** Verify `loading="lazy"` + `get_template_directory_uri()` on all images
- [ ] **P5-QA-05** Verify no hardcoded URLs, inline styles (except dynamic bg images from PHP data), raw hex colors, `!important` across codebase
- [ ] **P5-QA-06** Typo sweep including `venue-dashbaord`/`vendor-dashbaord` in PROJECT.md root §1 outline and any matching typos in code/docs
- [ ] **P5-QA-07** Third-party plugin setup: UpdraftPlus (backups), Wordfence (staging/live), verify activation state
- [ ] **P5-LAUNCH-01** Pre-launch + post-launch checklists — full site crawl for 404s, redirect chains, missing H1s, duplicate titles, thin pages; Search Console + Bing + analytics + mail-deliverability checks

### Out of Scope

<!-- Explicit exclusions. v1.1+. -->

- **Phase 8 "Permalink Cleanup"** from PROJECT.md root §2 (moving CPT archives out from under `/wedding-inspiration/`, 301 redirects from old paths) — **deferred to v1.1**. Combo SEO pages in Phase 4 cover the new URL structure; wholesale permalink reorg is a separate initiative that risks the launch window.
- **Automated test suite** (WP_UnitTestCase / wp-env / Playwright) — deferred to v1.1. CONCERNS `Test coverage gaps` section lists 15 target tests; start post-launch once the foundation stops moving.
- **Wedding Wire vendor/venue data import at scale** — deferred. `migrate.php` will be exercised on real but limited staging data in Phase 2. Full-volume (500+ vendors) import and chunked resume is v1.1.
- **N+1 query fix in rating aggregation** (`reviews.php:109-119`) — deferred to v1.1 unless reviews ship at scale before launch. Low real-world review volume at launch.
- **Reviews duplicate-submission race condition** (`reviews.php:58-70`) — deferred to v1.1. Edge case at launch volumes.
- **Dev-stack secrets rework** (`docker-compose.yml` + `Dockerfile.ssh` hardcoded `root`/`wordpress` credentials) — deferred. Files are gitignored; local-only stack. Fix before any shared-host or staging deploy.
- **Venue claim "reassign owner" admin UI** (recovery from partial claim approvals) — deferred. Recovery possible via direct DB edit at launch volumes; rare.
- **Email-as-username divergence fix** (`auth.php` vs `dashboard.php` email-change flow) — deferred unless it becomes a support issue pre-launch. Workaround: treat `user_login` as immutable, surface read-only.
- **Two-parallel-profile-save-path deduplication** (admin metabox vs AJAX dashboard) — deferred. Known drift risk; live with it for v1 and refactor when adding the next field.
- **Duplicate repeater-field JS** across admin + frontend — deferred. Document, don't deduplicate yet.
- **Third-party plugin advanced config** beyond launch essentials (Rank Math advanced modules, Wordfence custom rules, UpdraftPlus remote storage) — baseline config only for v1; tuning is ongoing.
- **`inc/` fourth file** — CLAUDE.md hard caps at 3. Not relaxing for v1.
- **Home page modifications** — `front-page.php` + `assets/css/pages/home.css` are LOCKED per CLAUDE.md. No changes unless the founder explicitly requests one.

## Context

**Brownfield — v2 in progress, replacing v1:**
- v1 site (`legacy-sdweddingdirectory/`) was built on Bootstrap/FA/jQuery + 1-2 heavy ThemeForest themes; v2 is a clean-slate rewrite using vanilla CSS + PHP + vanilla JS
- Legacy plugins already physically removed from `wp-content/plugins/` (moved to `~/Documents/Development/WebDevelopment/legacy-sdweddingdirectory/`); their AJAX actions and filter hooks leave behind the breakage listed in Phase 1 cleanup
- Complete codebase analysis in `.planning/codebase/` (2026-04-23) — ARCHITECTURE.md, CONCERNS.md (HIGH/MEDIUM/LOW prioritized), CONVENTIONS.md, INTEGRATIONS.md, STACK.md, STRUCTURE.md, TESTING.md. CONCERNS.md findings are the primary source of Phase 1 + Phase 2 task detail
- Existing root `PROJECT.md` (task tracker) and `CLAUDE.md` (hard rules) remain authoritative; GSD PROJECT.md is additive planning context

**Stack:**
- Docker Compose (MySQL 8 + wordpress:latest + custom SSH image bundling WP-CLI)
- WordPress core lives inside the `wp_data` named volume (not in repo); only `wp-content/` is bind-mounted and source-controlled
- PHP 8.x, MySQL 8, Apache via wordpress image, mod_rewrite routing via bind-mounted `.htaccess`
- No build pipeline — pure CSS + vanilla JS + PHP

**Port sources** (for Phase 3 discovery task):
- SDWD-v1 candidates in `~/Documents/Development/WebDevelopment/`: `legacy-sdweddingdirectory/`, `SDWeddingDirectory BBEdit/`, `sdweddingdirectory-contaminated/`, `sdweddingdirectory-final-backup/`, `sdweddingdirectory-v2-snapshot/`, `wp-content.zip`
- WeddingDir themeforest candidates: `themeforest-Os2C2dOt-weddingdir-directory-listing-wordpress-theme/` (contains `Untouched Original Theme Source WeddingDir/` and `weddingdir-child/`), `WeddingDIr/`, `ThemeFilesModified/` (contains `weddingdir/` + `weddingdir-child/`)

**Work pattern:**
- Founder works on this in bursts between other income-generating work; multi-day gaps between sessions are normal
- Primary GSD adoption motivation: `STATE.md` + `/gsd-resume-work` must keep the project resumable cold — no grep spelunking to figure out where work stopped
- Implication: **PROJECT.md / STATE.md / ROADMAP.md must be updated at every phase transition** (via `/gsd-transition`) so a cold start finds current state

## Constraints

- **Tech stack (locked):** Pure CSS + PHP + vanilla JS. No Bootstrap, no Font Awesome, no jQuery, no Google Fonts, no external CSS/JS frameworks, no page builders, no shortcodes for layout. Enforced in CLAUDE.md — violation is session-ending.
- **Theme directory (locked):** Only `wp-content/themes/sandiegoweddingdirectory/`. Do not create/rename/copy/move theme directories. Do not restore legacy theme files.
- **File boundaries:** `inc/` capped at 3 files (current: `sd-mega-navwalker.php`, `vendors.php`, `venues.php`). Do not modify plugin files or files outside the theme unless explicitly instructed.
- **Home page (locked):** `front-page.php` + `assets/css/pages/home.css` do not change without explicit founder approval. Shared tokens in `foundation.css`/`components.css` may cascade into the home page — verify before modifying shared tokens.
- **CSS rules:** All colors/spacing/typography via tokens in `foundation.css :root`. No raw hex. No `!important`. No inline styles (except dynamic background images from PHP data). Single-class selectors, max one level of nesting. BEM-lite naming.
- **Escaping:** `esc_html`, `esc_attr`, `esc_url`, `wp_kses_post` for all output. `wp_unslash` + appropriate sanitizer on all input.
- **Assets:** `get_template_directory_uri()` for paths (no hardcoded URLs). `loading="lazy"` on images. Conditional enqueue in `functions.php` gated by page-type conditionals. `filemtime()` for cache-busting version strings.
- **Icons:** `sdwd-icons` icon font only (at `assets/library/sdwd-icons/`). No Font Awesome classes.
- **Content model:** Theme does NOT register post types or taxonomies. Plugin (`sdwd-core`) owns all CPT/taxonomy/role registration. Theme provides templates that call plugin AJAX endpoints and read plugin-registered meta keys.
- **Git:** No `git init`, no `git reset --hard`, no force push, no history rewrite without explicit founder approval. Commit frequently.
- **Concurrent sessions:** May not be the only agent running. Do not rename/move directories mid-session; unknown structures → STOP and ask.
- **Text-domain (standardizing to):** `sandiegoweddingdirectory` — matches the theme folder slug. Phase 1 audit normalizes.
- **Timeline:** No fixed deadline, but the v1 site is hurting revenue; `v1-launch` is the top priority. Multi-day gaps between working sessions are the norm — cold-resumability is non-negotiable.

## Key Decisions

| Decision | Rationale | Outcome |
|----------|-----------|---------|
| Run full v1-launch milestone through GSD (not one phase at a time) | Founder loses context across multi-day gaps — STATE.md + `/gsd-resume-work` solves this only if the whole milestone is tracked | — Pending |
| Fold all HIGH security items from CONCERNS.md into v1-launch Phase 2 | Password-change surface + admin metabox wp_unslash are exploitable / data-corrupting in production; not safe to defer past launch | — Pending |
| Vendor/venue parity architecture lands in Phase 2 alongside plugin closeout | PROJECT.md root §3 calls this "the central architecture goal of the rebuild" — shipping v1 with forked vendor/venue code would re-introduce the v1 problem | — Pending |
| Phase 3 port-source discovery is a dedicated task, not a pre-committed folder | 6+ SDWD-v1 snapshots + 3+ WeddingDir themeforest variants exist; founder can't pre-select without a fingerprint of each candidate per target page | — Pending |
| Text-domain standardizes on `sandiegoweddingdirectory` (theme folder slug) | Three variants in use today (`sdweddingdirectory-v2`, `sdweddingdirectory`, `sandiegoweddingdirectory`); translation loading silently no-ops on mismatches. Pick the most descriptive and normalize once | — Pending |
| Phase 8 "Permalink Cleanup" from PROJECT.md root §2 explicitly deferred to v1.1 | Phase 4 combo-SEO pages already introduce new URL structure; wholesale permalink reorg + 301 sweep is a separate initiative that risks the launch window | — Pending |
| No automated test suite in v1-launch | CONCERNS flagged ~15 target tests; founder prefers to land the foundation first and add tests post-launch once code stops moving | — Pending |
| Full-volume Wedding Wire import deferred to v1.1 | `migrate.php` is tested in Phase 2 on real-but-limited staging data; 500+ vendor import needs chunking + resume which isn't launch-blocking | — Pending |

## Evolution

This document evolves at phase transitions and milestone boundaries.

**After each phase transition** (via `/gsd-transition`):
1. Requirements invalidated? → Move to Out of Scope with reason
2. Requirements validated? → Move to Validated with phase reference
3. New requirements emerged? → Add to Active
4. Decisions to log? → Add to Key Decisions
5. "What This Is" still accurate? → Update if drifted

**After each milestone** (via `/gsd-complete-milestone`):
1. Full review of all sections
2. Core Value check — still the right priority?
3. Audit Out of Scope — reasons still valid?
4. Update Context with current state

---
*Last updated: 2026-04-22 after initialization*
