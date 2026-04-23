# Requirements: San Diego Wedding Directory — launch

**Defined:** 2026-04-22
**Last updated:** 2026-04-22 (terminology reset + legacy-recovery re-synthesis)
**Core Value:** Ship the launch milestone cleanly — the working theme + plugin stack must replace the prior public site and pass all four hard launch gates.

---

## Launch Gates (4 hard)

The site does NOT ship without these all green. Every launch requirement must trace back to supporting at least one gate (or to baseline launch hygiene).

1. **LG-01**: All 5 `sdwd-couple` AJAX endpoints accept valid nonces and return success responses (quote, wishlist, checklist, budget, reviews)
2. **LG-02**: `/carlsbad/beach-weddings/` (and every other valid location×type combo) returns HTTP 200 with real content, not 404
3. **LG-03**: Dashboards functional end-to-end: register → login → save data → receive email confirmation, for all 3 roles (couple, vendor, venue)
4. **LG-04**: Transactional email actually delivers — quote requests, registrations, claim notifications land in inbox

---

## Launch Requirements

REQ-IDs are phase-prefixed (`P{N}-{CATEGORY}-{NN}`) so phase mapping is self-documenting. Every requirement maps to exactly one of the five phases.

### Phase 1 — Close in-progress + cleanup

- [ ] **P1-QA-01**: Planning child pages pass final QA (intro width confirmed; any remaining items from root `PROJECT.md` §1 resolved)
- [ ] **P1-BUILD-02**: Error 404 page is finished (95% already done — swap placeholder inline SVG for the real asset at `assets/images/404-error-page/404_error.svg`, normalize button row to all `.btn--outline`, right-align the button container with a token-based `gap`)
- [ ] **P1-CLEAN-01**: Dead filter `sdweddingdirectory/post-cap/vendor` removed from `functions.php:240-243`
- [ ] **P1-CLEAN-02**: Dead `sdweddingdirectory_icon_library` filter removed from `functions.php:245-254`
- [ ] **P1-CLEAN-03**: Dead `.modal.fade { display: none; }` rule removed from `assets/css/layout.css:687-690`
- [ ] **P1-CLEAN-04**: Text-domain standardized to `sandiegoweddingdirectory` across the theme; sweep extends to `sdwd-core` / `sdwd-couple` plugin files (plugin-file modification explicitly authorized for this scope). `style.css` `Theme Name:` header verified in sync. Verification gate: phpcs text-domain-sniff error count 1,039+ → 0.
- [ ] **P1-CLEAN-05**: Dead `wp_dequeue_style('sdweddingdirectory-fontello' / '-flaticon')` calls removed from `functions.php:175-181`
- [ ] **P1-CLEAN-06**: `add_image_size('sdweddingdirectory_img_*')` in `functions.php:58-59` renamed to `sdwd_img_*` (or removed if unused)
- [ ] **P1-CLEAN-07**: Root-level macOS `Icon` artifact deleted; `style.css` `Version:` header bumped if stale
- [ ] **P1-FIX-01**: `template-parts/planning/planning-hero.php:62-235` registration form retargeted to `sdwd_register` action, inputs renamed to match `sdwd-core/includes/auth.php` field names, legacy nonce replaced with theme-minted `sdwd_auth_nonce`. Silent auto-password generation UX preserved. **Cross-phase hook:** welcome email (`P5-EMAIL-02`) MUST carry couple password plaintext OR a password-set/reset link — without that, a couple who logs out before setting their own password is locked out. **Verifies:** couple sign-ups from planning page actually create accounts (supports LG-03)
- [ ] **P1-FIX-02**: Home-page category-search mega-menu dropdown fixed — the category filter on the home-page search bar must drop open to show selectable categories and pass the selection through to the downstream search. **Scoped home-page unlock active for this requirement only; home page re-locks on completion.**

### Phase 2 — Plugin closeout + parity + security

- [ ] **P2-DISCO-01**: **Seating-chart plugin hunt** (first task in Phase 2 — highest risk of permanent loss). Fingerprint every legacy folder under `~/Documents/Development/WebDevelopment/` for a prior-built seating-chart plugin. Candidate folders include SDWD-era snapshots (`legacy-sdweddingdirectory/`, `SDWeddingDirectory BBEdit/`, `sdweddingdirectory-contaminated/`, `sdweddingdirectory-final-backup/`, `sdweddingdirectory-v2-snapshot/`), WeddingDir themeforest variants (`themeforest-Os2C2dOt-.../`, `WeddingDIr/`, `ThemeFilesModified/`), `sdwd-ui/`, `ww_html_clone/`, `backupwp/`, `RECOVERY/`, and any folder with `seating` / `chart` / `layout` / `table-plan` in file names. Deliverable: candidate table (folder + file listings + LoC + first 30 lines) for founder selection. **If found → port intact** to `sdwd-couple/modules/seating-chart/`. **If not found →** open `PL-FEAT-01` in the Post-Launch Backlog for a full rebuild.
- [ ] **P2-NONCE-01**: All 5 `sdwd-couple` module nonces issued via `wp_localize_script` or per-feature `wp_create_nonce`; every `check_ajax_referer` call in `sdwd-couple/modules/*` has a matching issuer. **Verifies:** LG-01
- [ ] **P2-CLAIM-01**: `single-vendor.php` claim button wired via new `claim.js` in `assets/js/`; inline `<script>` blocks deleted from both `single-venue.php:382-408` and `single-vendor.php`; bound to `[data-sdwd-claim]` with `data-*` attributes
- [ ] **P2-PORT-01**: `seating-chart` module ported from prior-built source (gated by `P2-DISCO-01` outcome)
- [ ] **P2-PORT-02**: `rsvp` module ported from prior-built couple plugin
- [ ] **P2-PORT-03**: `guest-list` module ported from prior-built couple plugin
- [ ] **P2-PORT-04**: `todo-list` module ported from prior-built couple plugin
- [ ] **P2-PORT-05**: `website` (couple wedding-website) module ported from prior-built plugin
- [ ] **P2-MIGRATE-01**: `sdwd-core/includes/migrate.php` runs clean on real staging data with round-trip verified; chunked processing (batch 50) + `sdwd_migration_version` idempotence option added
- [ ] **P2-PARITY-01**: Both `vendor` and `venue` CPTs have `has_archive => true`; `page-venues.php` logic folded into `archive-venue.php`
- [ ] **P2-PARITY-02**: `venue-location` taxonomy re-registered with `hierarchical => false`; existing hierarchical terms migrated to flat (city-only)
- [ ] **P2-PARITY-03**: `user-template/venue-profile.php` exists as a parallel of `vendor-profile.php` with address + capacity fields added
- [ ] **P2-PARITY-04**: `venue-dashboard.php` has a description field parallel to `vendor-profile.php:90-96`; venue owners can edit and save their description

> **Dev-account preservation note for P2-SEC-01 / P2-SEC-02 / P2-SEC-04:** Founder's local dev environment has throwaway accounts `couple`/`couple`, `vendor`/`vendor`, `venue`/`venue` that MUST keep working through ongoing dashboard iteration (site is not going online soon). Gate all enforcement below on an `SDWD_DEV_MODE` constant — when defined & truthy in local `wp-config.php`, strength/re-auth/rate-limit checks are bypassed. Pattern: `if ( ! defined( 'SDWD_DEV_MODE' ) || ! SDWD_DEV_MODE ) { enforce(...); }`. Staging + production `wp-config.php` do NOT define the constant so enforcement is live. `P5-DASH-03` verification and `P5-QA-05` codebase audit MUST confirm the constant is absent from production `wp-config.php` before LG-03 can clear. See `.planning/CLAUDE-GSD.md` → "Dev environment accounts" for full spec.

- [ ] **P2-SEC-01**: Password strength check (≥8 chars) enforced on frontend dashboard password change + all 3 admin metabox save callbacks (couple-meta, vendor-meta, venue-meta) — **gated by `SDWD_DEV_MODE`**
- [ ] **P2-SEC-02**: `current_password` verification field required on frontend password-change (`dashboard.php:127-131`); new-password input cleared after save — **gated by `SDWD_DEV_MODE`**
- [ ] **P2-SEC-03**: `wp_unslash()` applied to admin metabox password inputs in couple-meta.php:137, vendor-meta.php:234, venue-meta.php:291 — prevents silent login breakage on passwords containing `'`, `"`, `\` (NOT gated — this is a correctness fix, not an enforcement toggle)
- [ ] **P2-SEC-04**: Transient-counter rate limit (per IP, fail-closed) on password-change and login endpoints — **gated by `SDWD_DEV_MODE`**
- [ ] **P2-SEC-05**: Inline JS extracted from admin claim metabox (`claim.php:128-144`); moved to `sdwd-core/assets/admin.js`; `$post->ID` and `$nonce` escaped via `esc_attr`
- [ ] **P2-SEC-06**: Repeater-field slash accumulation fixed in `vendor-meta.php:157-205` and `venue-meta.php:206-254`; values read raw, escaped on render via `esc_attr` only

### Phase 3 — Missing + ported templates

- [ ] **P3-DISCO-01**: **Port-source discovery** — fingerprint every SDWD-era candidate folder (`legacy-sdweddingdirectory/`, `SDWeddingDirectory BBEdit/`, `sdweddingdirectory-contaminated/`, `sdweddingdirectory-final-backup/`, `sdweddingdirectory-v2-snapshot/`, `wp-content.zip`) against every SDWD-era target page (`page-inspiration.php`, `category.php`, `archive.php`, `single.php`, `page-policy.php` 4-tab, `page-about.php`, `page-contact.php`, `page-faqs.php`, `page-our-team.php`, `single-venue.php`, `single-vendor.php` + its custom calendar) AND every WeddingDir themeforest candidate (`themeforest-Os2C2dOt-...`, `WeddingDIr/`, `ThemeFilesModified/`) against every themeforest target (wedding-websites landing + single, `archive-real-wedding`, `single-real-wedding`, `listing-not-found`, blog `single.php` layout). For each candidate×target cell: present?, last-modified date, line count, first 30 lines. Results surfaced to founder for per-page source selection at execution time.
- [ ] **P3-BUILD-01**: `page-cost.php` + 17 child cost pages built from wireframes, one per vendor category, with original SD-specific pricing content (300-word min intro per page)
- [ ] **P3-BUILD-02**: `page-registry.php` + `page-registry-child.php` built from wireframes
- [ ] **P3-BUILD-03**: Hashtag generator tool page built (scope clarified at plan-phase time)
- [ ] **P3-PORT-01**: `page-inspiration.php` ported from chosen source (`P3-DISCO-01` output); banned deps stripped on import
- [ ] **P3-PORT-02**: Category archive (`category.php`) ported with planning-subcategory sidebar handling
- [ ] **P3-PORT-03**: Blog archive (`archive.php`) ported
- [ ] **P3-PORT-04**: Single blog post (`single.php`) ported from the WeddingDir themeforest example (`https://weddingdir.net/what-does-a-wedding-planner-actually-do/`); article layout + JS content-splitter; strip shortcake/ACF/Elementor/jQuery/Bootstrap/FA on import
- [ ] **P3-PORT-05**: 4-tab policy group ported (`page-policy.php` covering privacy, terms, CA privacy, cookies) from prior-built source
- [ ] **P3-PORT-06**: Wedding-websites landing + templates gallery + single template ported from the WeddingDir themeforest example (`https://weddingdir.net/website/hitesh-and-priyanka/`). Strip shortcake/ACF/Elementor/jQuery/Bootstrap/FA on import. Keep only the CSS the founder approves. Integrate with existing `single-website.php`. **Launch ships one template;** expanding to 6 total templates is deferred to `PL-THEME-01..05`.
- [ ] **P3-PORT-07**: `archive-real-wedding.php` + `single-real-wedding.php` ported from the WeddingDir themeforest example (`https://weddingdir.net/real-wedding/ratna-jacob/`): gallery + story + vendor credits. Strip shortcake/ACF/Elementor/jQuery/Bootstrap/FA on import. Register `real-wedding` CPT + 8 taxonomies (`real-wedding-{category,color,community,location,season,space-preferance,style,tag}`) in `sdwd-core` (currently referenced by theme but not registered — breakage risk). If founder elects not to register: delete orphan files and strip from `functions.php:122` `is_singular()`.
- [ ] **P3-PORT-08**: `listing-not-found` template ported from WeddingDir themeforest source
- [ ] **P3-PORT-09**: `page-about.php` ported from prior-built source (selected via `P3-DISCO-01`) — same strip-on-import rules as other ports
- [ ] **P3-PORT-10**: `page-contact.php` ported from prior-built source — same strip-on-import rules
- [ ] **P3-PORT-11**: `page-faqs.php` ported from prior-built source (tabbed accordion per root `PROJECT.md` §2 Phase 6) — same strip-on-import rules
- [ ] **P3-PORT-12**: `page-our-team.php` ported from prior-built source — same strip-on-import rules
- [ ] **P3-PORT-13**: `single-venue.php` public profile ported from prior-built source — UI/CSS was previously complete in a legacy snapshot; port markup + CSS, strip banned deps on import, tokenize colors/spacing/typography via `foundation.css :root`
- [ ] **P3-PORT-14**: `single-vendor.php` public profile ported from prior-built source — includes the custom vendor calendar. Initial classification: theme-side (template + CSS). If `P3-DISCO-01` fingerprinting reveals AJAX/data glue, spawn `P3-PORT-14a` for the plugin-side piece.
- [ ] **P3-CLEAN-01**: Action-hook stubs in `single-couple.php:12`, `single-website.php:13` resolved (decide per-file: delete orphan or rebuild with sdwd-* native hooks)

### Phase 4 — Combo venue SEO pages

- [ ] **P4-TPL-01**: `combo-venue.php` template created with H1 pattern `"San Diego {Type} Weddings in {Location}"`, 300-word min intro, Schema.org LocalBusiness + ItemList JSON-LD. **Verifies:** LG-02
- [ ] **P4-ROUTE-01**: `sdwd-core/includes/routing/rewrite-rules.php` registers `/{location}/{type}/` for every valid combination via `add_rewrite_rule()`; wired into plugin bootstrap. **Verifies:** LG-02
- [ ] **P4-ROUTE-02**: `inc/venues.php` has flush_rewrite_rules + `sdwd_*_rewrite_version` option guard parallel to `inc/vendors.php:31-46` (or shared helper); prevents rewrite collision after cache purges
- [ ] **P4-PERF-01**: Taxonomy queries in `inc/vendors.php` (851 lines) and `inc/venues.php` (816 lines) wrapped in `get_transient`/`set_transient` with 1h TTL; invalidated on `edited_term`/`created_term` hooks
- [ ] **P4-QA-01**: Every valid location×type combo returns HTTP 200 with real content verified via spot-check on `/carlsbad/beach-weddings/`, `/la-jolla/estate-weddings/`, and a representative sample. **Verifies:** LG-02

### Phase 5 — Launch prep

- [ ] **P5-DASH-01**: Vendor dashboard `$quotes` placeholder in `user-template/vendor-dashboard.php:44-45` replaced with real `sdwd_get_vendor_quote_requests($vendor_id)` helper. **Verifies:** vendors see incoming quotes (supports LG-03)
- [ ] **P5-DASH-02**: Dashboard CSS **ported** from the commercial theme the dashboards originally shipped with; strip third-party framework markup (no Bootstrap, FA, jQuery, page builders); tokenize colors/spacing/typography via `foundation.css :root`. Every dashboard tab tested for vendor, venue, couple roles. Plain-language: CSS to style the dashboard interface visually.
- [ ] **P5-DASH-03**: Full E2E QA completes successfully: register → login → save data → receive email confirmation, for all 3 roles. **Verifies:** LG-03
- [ ] **P5-EMAIL-01**: `wp-mail-smtp` activated and configured (SMTP provider selected, credentials in wp-config or env, test-send passes)
- [ ] **P5-EMAIL-02**: Transactional email templates built and wired: quote request, registration confirmation, welcome (**MUST carry couple password plaintext OR password-set/reset link** — closes the `P1-FIX-01` cross-phase hook so couples who auto-register via the planning page aren't locked out after they log out), claim approval, claim rejection. **Verifies:** LG-04
- [ ] **P5-EMAIL-03**: Real-inbox delivery verified for every transactional email. **Verifies:** LG-04
- [ ] **P5-SEO-01**: Rank Math configured: sitemap generated, canonicals set, robots directives correct, schema defaults applied, per-page meta populated
- [ ] **P5-SEO-02**: Internal linking sweep complete — Blog → vendors+venues, Venues → vendors, Cost → vendors, Real Weddings → both
- [ ] **P5-SEO-03**: 300-word minimum intros live on every archive (vendors, venues, taxonomy archives, inspiration, real-weddings)
- [ ] **P5-SEO-04**: H1 geo-targeting audit complete — every vendor archive H1 = `"San Diego Wedding {Category}"`; venue archive H1 = `"San Diego {Type} Wedding Venues"`; combo pages covered by P4-TPL-01
- [ ] **P5-STYLE-01**: `page-style-guide.php` rewritten using `.grid`/`.container`, `icon-*` from sdwd-icons, BEM-only classes, no inline styles (or moved to a separate plugin deactivated in prod). No Bootstrap, no FA markup
- [ ] **P5-PERF-01**: Image optimization pass complete — theme `real-wedding/*.png` and other >1MB PNGs converted to WebP at 1920px max, <200KB each. `assets/images/_inbox icons and images/` moved out of theme. `Documentation/screenshots/` bloat strategy documented
- [ ] **P5-PERF-02**: W3 Total Cache + ShortPixel Image Optimizer configured
- [ ] **P5-QA-01**: Cross-browser testing complete on Chrome, Safari, Firefox, and mobile browsers
- [ ] **P5-QA-02**: Lighthouse audit complete; blocking performance/accessibility/SEO issues resolved
- [ ] **P5-QA-03**: Conditional CSS/JS enqueuing verified post-Phase-1-through-4 changes (no regressions)
- [ ] **P5-QA-04**: `loading="lazy"` + `get_template_directory_uri()` verified on all images across the theme
- [ ] **P5-QA-05**: Codebase audit passes — no hardcoded URLs, no inline styles (except dynamic bg images from PHP data), no raw hex, no `!important`; `SDWD_DEV_MODE` confirmed NOT defined in production `wp-config.php`
- [ ] **P5-QA-06**: Typo sweep complete including `venue-dashbaord`/`vendor-dashbaord` and any matching typos in code/docs
- [ ] **P5-QA-07**: UpdraftPlus (backups) + Wordfence (staging/live) activated and configured
- [ ] **P5-LAUNCH-01**: Pre-launch + post-launch checklists executed — full-site crawl for 404s/redirect chains/missing H1s/duplicate titles/thin pages; Search Console + Bing + analytics + mail-deliverability checks all green

---

## Post-Launch Backlog (Deferred)

Tracked but NOT in the launch roadmap.

### Permalink reorganization
- **PL-PERM-01**: Move CPT archives out from under `/wedding-inspiration/`
- **PL-PERM-02**: Register `vendor-category` base as `/vendors/` (theme currently overrides; lift into plugin)
- **PL-PERM-03**: Register `venue-location` base as `/venues/`
- **PL-PERM-04**: 301 redirects from old permalink paths; verify no hardcoded URL patterns in theme

### Testing
- **PL-TEST-01**: `wp-env` or `wp-browser` set up with minimal `phpunit.xml.dist`
- **PL-TEST-02**: ~15 WP_UnitTestCase tests against `sdwd-core` handlers (auth, claim, dashboard) and `sdwd-couple` module AJAX smoke tests
- **PL-TEST-03**: Rewrite-rule snapshot test for vendor/venue taxonomy conflicts
- **PL-TEST-04**: Migration idempotence integration test

### Additional wedding-website themes (launch ships 1)
- **PL-THEME-01**: Wedding-website theme — modern minimal
- **PL-THEME-02**: Wedding-website theme — romantic classic
- **PL-THEME-03**: Wedding-website theme — rustic
- **PL-THEME-04**: Wedding-website theme — bold / colorful
- **PL-THEME-05**: Wedding-website theme — elegant dark OR garden/floral (final set confirmed with founder)

### Scale / performance
- **PL-SCALE-01**: Full Wedding Wire vendor/venue import at volume (500+) with chunked resume
- **PL-SCALE-02**: N+1 query fix in `sdwd_get_average_rating()` — denormalize `sdwd_rating_avg` + `sdwd_rating_count` onto reviewed post
- **PL-SCALE-03**: Reviews duplicate-submission race guard (re-query + delete newer duplicate OR unique constraint)

### Admin recovery tools
- **PL-ADMIN-01**: Venue claim "reassign owner" admin metabox with read-only claim history

### Misc fixes deferred
- **PL-FIX-01**: Email-as-username divergence fix (`auth.php` vs `dashboard.php` email-change flow) — update `wp_update_user` alongside post meta OR treat `user_login` as immutable
- **PL-FIX-02**: Two-parallel-profile-save-path deduplication — extract `sdwd_persist_profile_fields()` helper used by admin metabox + AJAX dashboard
- **PL-FIX-03**: Duplicate repeater-field JS consolidated into shared `repeater.js`

### Conditional (opens only if discovery fails)
- **PL-FEAT-01**: Seating-chart plugin rebuild from scratch — opens ONLY if `P2-DISCO-01` finds no prior-built source to port

### Dev-stack hardening
- **PL-DEV-01**: Replace hardcoded `docker-compose.yml` + `Dockerfile.ssh` credentials with env-file interpolation before any non-local deployment

---

## Out of Scope

Explicitly excluded from both launch and Post-Launch Backlog tracking. Documented to prevent scope creep.

| Feature | Reason |
|---------|--------|
| Home page modifications beyond `P1-FIX-02` | LOCKED per root `CLAUDE.md`; `front-page.php` + `home.css` scoped-unlocked only for `P1-FIX-02`; re-locks on completion |
| `inc/` fourth file | Root `CLAUDE.md` hard caps at 3 (`sd-mega-navwalker.php`, `vendors.php`, `venues.php`); not relaxing |
| Bootstrap / Font Awesome / jQuery / Google Fonts / external CSS-JS frameworks / shortcake / ACF-layout / Elementor | Banned per root `CLAUDE.md`; any plugin requiring them is a STOP-and-ask. Legacy ports MUST strip these on import. |
| Page builders / shortcodes for layout | Banned per root `CLAUDE.md` |
| Theme-directory rename or copy | LOCKED; only `sandiegoweddingdirectory` exists |
| Custom post types / taxonomies registered in theme | Plugin (`sdwd-core`) owns all CPT/taxonomy registration; theme never registers |
| Raw hex colors in CSS | All colors use tokens from `foundation.css :root` |
| `!important` in CSS | Banned |
| Utility classes (`.mt-4`, `.text-center`, etc.) | Banned — single-class BEM-lite selectors only |
| Force-pushes / history rewrites | No `git init` on existing repo, no `git reset --hard`, no force push without explicit founder approval |

---

## Traceability

Every launch requirement maps to exactly one phase (1:1 via REQ-ID prefix).

| Requirement | Phase | Status |
|-------------|-------|--------|
| P1-QA-01 | Phase 1 | Pending |
| P1-BUILD-02 | Phase 1 | Pending |
| P1-CLEAN-01 | Phase 1 | Pending |
| P1-CLEAN-02 | Phase 1 | Pending |
| P1-CLEAN-03 | Phase 1 | Pending |
| P1-CLEAN-04 | Phase 1 | Pending |
| P1-CLEAN-05 | Phase 1 | Pending |
| P1-CLEAN-06 | Phase 1 | Pending |
| P1-CLEAN-07 | Phase 1 | Pending |
| P1-FIX-01 | Phase 1 | Pending |
| P1-FIX-02 | Phase 1 | Pending |
| P2-DISCO-01 | Phase 2 | Pending |
| P2-NONCE-01 | Phase 2 | Pending |
| P2-CLAIM-01 | Phase 2 | Pending |
| P2-PORT-01 | Phase 2 | Pending |
| P2-PORT-02 | Phase 2 | Pending |
| P2-PORT-03 | Phase 2 | Pending |
| P2-PORT-04 | Phase 2 | Pending |
| P2-PORT-05 | Phase 2 | Pending |
| P2-MIGRATE-01 | Phase 2 | Pending |
| P2-PARITY-01 | Phase 2 | Pending |
| P2-PARITY-02 | Phase 2 | Pending |
| P2-PARITY-03 | Phase 2 | Pending |
| P2-PARITY-04 | Phase 2 | Pending |
| P2-SEC-01 | Phase 2 | Pending |
| P2-SEC-02 | Phase 2 | Pending |
| P2-SEC-03 | Phase 2 | Pending |
| P2-SEC-04 | Phase 2 | Pending |
| P2-SEC-05 | Phase 2 | Pending |
| P2-SEC-06 | Phase 2 | Pending |
| P3-DISCO-01 | Phase 3 | Pending |
| P3-BUILD-01 | Phase 3 | Pending |
| P3-BUILD-02 | Phase 3 | Pending |
| P3-BUILD-03 | Phase 3 | Pending |
| P3-PORT-01 | Phase 3 | Pending |
| P3-PORT-02 | Phase 3 | Pending |
| P3-PORT-03 | Phase 3 | Pending |
| P3-PORT-04 | Phase 3 | Pending |
| P3-PORT-05 | Phase 3 | Pending |
| P3-PORT-06 | Phase 3 | Pending |
| P3-PORT-07 | Phase 3 | Pending |
| P3-PORT-08 | Phase 3 | Pending |
| P3-PORT-09 | Phase 3 | Pending |
| P3-PORT-10 | Phase 3 | Pending |
| P3-PORT-11 | Phase 3 | Pending |
| P3-PORT-12 | Phase 3 | Pending |
| P3-PORT-13 | Phase 3 | Pending |
| P3-PORT-14 | Phase 3 | Pending |
| P3-CLEAN-01 | Phase 3 | Pending |
| P4-TPL-01 | Phase 4 | Pending |
| P4-ROUTE-01 | Phase 4 | Pending |
| P4-ROUTE-02 | Phase 4 | Pending |
| P4-PERF-01 | Phase 4 | Pending |
| P4-QA-01 | Phase 4 | Pending |
| P5-DASH-01 | Phase 5 | Pending |
| P5-DASH-02 | Phase 5 | Pending |
| P5-DASH-03 | Phase 5 | Pending |
| P5-EMAIL-01 | Phase 5 | Pending |
| P5-EMAIL-02 | Phase 5 | Pending |
| P5-EMAIL-03 | Phase 5 | Pending |
| P5-SEO-01 | Phase 5 | Pending |
| P5-SEO-02 | Phase 5 | Pending |
| P5-SEO-03 | Phase 5 | Pending |
| P5-SEO-04 | Phase 5 | Pending |
| P5-STYLE-01 | Phase 5 | Pending |
| P5-PERF-01 | Phase 5 | Pending |
| P5-PERF-02 | Phase 5 | Pending |
| P5-QA-01 | Phase 5 | Pending |
| P5-QA-02 | Phase 5 | Pending |
| P5-QA-03 | Phase 5 | Pending |
| P5-QA-04 | Phase 5 | Pending |
| P5-QA-05 | Phase 5 | Pending |
| P5-QA-06 | Phase 5 | Pending |
| P5-QA-07 | Phase 5 | Pending |
| P5-LAUNCH-01 | Phase 5 | Pending |

**Coverage:**
- Launch requirements: 75 total (+ 5 struck as already validated: P1-BUILD-01 footer; Navigation / Venues Landing / Vendors Landing / registration modals / responsive-polish items consolidated to Validated section of `.planning/PROJECT.md`)
- Mapped to phases: 75
- Unmapped: 0 ✓

**Distribution:**
- Phase 1: 11 requirements (was 11; struck P1-BUILD-01; added P1-FIX-02)
- Phase 2: 19 requirements (was 18; added P2-DISCO-01)
- Phase 3: 19 requirements (was 13; added P3-PORT-09..14)
- Phase 4: 5 requirements (unchanged)
- Phase 5: 21 requirements (was 23; `P5-THEME-01/02` moved to Post-Launch Backlog as `PL-THEME-01..05`)

---
