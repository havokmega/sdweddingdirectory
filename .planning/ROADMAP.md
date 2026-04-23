# Roadmap: San Diego Wedding Directory — launch

**Milestone:** launch
**Granularity:** coarse
**Coverage:** 75/75 launch requirements mapped ✓
**Launch Gates:** 4/4 covered ✓

---

## Phases

- [ ] **Phase 1: Close in-progress + cleanup** — Finish already-in-flight work: polish the 404 page, fix the broken couple registration form, fix the home-page category-search mega-menu dropdown (scoped home-page unlock), and remove dead legacy hooks / standardize the text-domain across the codebase
- [ ] **Phase 2: Plugin closeout + parity + security** — Hunt for and port the custom seating-chart plugin before anything else; complete `sdwd-couple` modules + nonces (LG-01); achieve vendor/venue architectural parity; harden password and input security
- [ ] **Phase 3: Missing + ported templates** — Port every content page previously built but not yet in the working theme — About, Contact, FAQs, Our Team, 4-tab policy group, Inspiration, blog, single vendor/venue profiles (with custom calendar), Real Weddings, Wedding Websites — plus build cost/registry/hashtag-tool pages from wireframes
- [ ] **Phase 4: Combo venue SEO pages** — Build `/{location}/{type}/` URL routing and combo templates (LG-02)
- [ ] **Phase 5: Launch prep** — Wire dashboards end-to-end (LG-03), deliver transactional email including the couple welcome-email auth-material delivery (LG-04), SEO configuration, performance optimization, and cross-browser QA

---

## Phase Details

### Phase 1: Close in-progress + cleanup
**Goal**: All existing in-progress work is finished and the codebase is clean — broken legacy hooks removed, text-domain standardized across theme + plugins, the couple registration form actually creates accounts, and the home-page category-search mega-menu dropdown works.
**Depends on**: Nothing (first phase)
**Requirements**: P1-QA-01, P1-BUILD-02, P1-CLEAN-01, P1-CLEAN-02, P1-CLEAN-03, P1-CLEAN-04, P1-CLEAN-05, P1-CLEAN-06, P1-CLEAN-07, P1-FIX-01, P1-FIX-02
**Success Criteria** (what must be TRUE):
  1. A visitor submitting the planning hero registration form successfully creates a couple account (P1-FIX-01 — supports LG-03)
  2. The home-page category-search mega-menu dropdown fires correctly and displays selectable category options (P1-FIX-02 — scoped home-page unlock closes on completion)
  3. The 404 page renders the real SVG asset with a right-aligned button row of all-`.btn--outline` buttons — no `btn--primary` outlier
  4. `functions.php` contains no references to dead legacy filter hooks or dequeue handles for handles that were never enqueued
  5. Every `__()` / `esc_html__()` call in the theme AND in `sdwd-core` / `sdwd-couple` plugin files uses the correct text-domain (phpcs text-domain-sniff error count 1,039+ → 0); `style.css` header matches
**Plans**: TBD
**UI hint**: yes

---

### Phase 2: Plugin closeout + parity + security
**Goal**: The custom seating-chart plugin is hunted for and either ported intact or deferred to Post-Launch Backlog; `sdwd-couple` is fully functional with nonces wired (LG-01 cleared); vendor and venue are architecturally parallel; all HIGH security issues are closed
**Depends on**: Phase 1
**Requirements**: P2-DISCO-01, P2-NONCE-01, P2-CLAIM-01, P2-PORT-01, P2-PORT-02, P2-PORT-03, P2-PORT-04, P2-PORT-05, P2-MIGRATE-01, P2-PARITY-01, P2-PARITY-02, P2-PARITY-03, P2-PARITY-04, P2-SEC-01, P2-SEC-02, P2-SEC-03, P2-SEC-04, P2-SEC-05, P2-SEC-06
**Success Criteria** (what must be TRUE):
  1. `P2-DISCO-01` has produced a fingerprint table of every candidate folder for the seating-chart plugin; either `P2-PORT-01` lands a ported module OR `PL-FEAT-01` is filed in the Post-Launch Backlog (no silent loss)
  2. **LG-01**: All 5 `sdwd-couple` AJAX endpoints (checklist, budget, wishlist, reviews, request-quote) accept valid nonces and return success responses — no 403s
  3. The vendor claim button on `single-vendor.php` fires the claim AJAX handler (no longer a dead click); inline `<script>` blocks are gone from both single templates
  4. `venue-profile.php` exists parallel to `vendor-profile.php`; `venue-dashboard.php` has a description field; `venue-location` taxonomy is flat (city-only); both CPTs have `has_archive => true`
  5. Password-change on the frontend dashboard requires current-password confirmation and enforces ≥8-char minimum; admin metabox password saves use `wp_unslash()`; rate limiting is active on login and password-change endpoints (all three gated by `SDWD_DEV_MODE`)
  6. Migration tool runs idempotently on staging data with batch-50 chunking and completes without PHP timeout
**Plans**: TBD
**UI hint**: no

---

### Phase 3: Missing + ported templates
**Goal**: Every page previously built in a legacy snapshot (About, Contact, FAQs, Our Team, 4-tab policy group, Inspiration, blog archive/single/category, single vendor/venue profiles + custom vendor calendar) is ported into the working theme with banned deps stripped; every page previously built in the WeddingDir themeforest (Real Weddings archive/single, Wedding Website template, blog single layout) is ported with banned deps stripped; cost/registry/hashtag-generator tool pages are built fresh from wireframes. Launch ships ONE wedding-website template — additional templates defer to Post-Launch Backlog.
**Depends on**: Phase 2
**Requirements**: P3-DISCO-01, P3-BUILD-01, P3-BUILD-02, P3-BUILD-03, P3-PORT-01, P3-PORT-02, P3-PORT-03, P3-PORT-04, P3-PORT-05, P3-PORT-06, P3-PORT-07, P3-PORT-08, P3-PORT-09, P3-PORT-10, P3-PORT-11, P3-PORT-12, P3-PORT-13, P3-PORT-14, P3-CLEAN-01
**Success Criteria** (what must be TRUE):
  1. Port-source fingerprint table is delivered to the founder before any porting begins; per-page source selection is confirmed
  2. `/cost/`, `/registry/`, and the hashtag-generator tool pages are accessible with token-based styling and SD-specific content (300-word min on cost pages)
  3. Blog archive, single post, category archive, inspiration, and 4-tab policy pages render correctly using the theme's CSS conventions (no Bootstrap, no FA, no inline styles); banned deps stripped on import
  4. About, Contact, FAQs, Our Team pages are ported and live with banned deps stripped
  5. Single vendor and single venue public profiles ported from legacy snapshots with banned deps stripped; custom vendor calendar carried forward (theme-side; plugin-side follow-up opened if `P3-DISCO-01` fingerprinting surfaces data glue)
  6. Wedding-websites landing + templates gallery + single template (one template shipped) and real-wedding archive + single are live; `real-wedding` CPT and its 8 taxonomies are registered in `sdwd-core` (or orphan files are deleted)
  7. No template file calls a legacy `sdweddingdirectory/...` action hook that has no registered handler
**Plans**: TBD
**UI hint**: yes

---

### Phase 4: Combo venue SEO pages
**Goal**: Every valid `/{location}/{type}/` URL combination returns HTTP 200 with a populated combo template — clearing LG-02 and establishing the new SEO URL structure
**Depends on**: Phase 2 (requires flat `venue-location` taxonomy from P2-PARITY-02; requires CPT archive routing from P2-PARITY-01)
**Requirements**: P4-TPL-01, P4-ROUTE-01, P4-ROUTE-02, P4-PERF-01, P4-QA-01
**Success Criteria** (what must be TRUE):
  1. **LG-02**: `/carlsbad/beach-weddings/`, `/la-jolla/estate-weddings/`, and a representative sample of valid location×type combos all return HTTP 200 with real venue content — not 404
  2. The `combo-venue.php` template renders an H1 matching `"San Diego {Type} Weddings in {Location}"`, a 300-word minimum intro, and valid Schema.org LocalBusiness + ItemList JSON-LD
  3. `inc/venues.php` has the same `flush_rewrite_rules` + version-option guard as `inc/vendors.php`; rewrite collisions after cache purges are prevented
  4. Taxonomy queries in `inc/vendors.php` and `inc/venues.php` are wrapped in transients (1h TTL) with invalidation on term edit/create hooks
**Plans**: TBD
**UI hint**: no

---

### Phase 5: Launch prep
**Goal**: All three dashboards are E2E-functional (LG-03), transactional email delivers to real inboxes — including the couple welcome-email carrying auth material that closes the `P1-FIX-01` cross-phase hook (LG-04), SEO is configured, performance is optimized, and QA is complete — the site is ready to replace the prior public site
**Depends on**: Phases 1, 2, 3, 4 (launch prep requires the full site to be complete)
**Requirements**: P5-DASH-01, P5-DASH-02, P5-DASH-03, P5-EMAIL-01, P5-EMAIL-02, P5-EMAIL-03, P5-SEO-01, P5-SEO-02, P5-SEO-03, P5-SEO-04, P5-STYLE-01, P5-PERF-01, P5-PERF-02, P5-QA-01, P5-QA-02, P5-QA-03, P5-QA-04, P5-QA-05, P5-QA-06, P5-QA-07, P5-LAUNCH-01
**Success Criteria** (what must be TRUE):
  1. **LG-03**: A new couple, vendor, and venue can each complete the full flow — register → login → save profile data → receive email confirmation — without error
  2. **LG-04**: Quote request, registration confirmation, welcome (carrying password plaintext OR password-set/reset link per `P1-FIX-01` hook), and claim notification emails land in a real inbox (not spam) when triggered from staging
  3. Dashboard CSS is ported from the original commercial theme + stripped of framework markup + tokenized — no Bootstrap, FA, or jQuery in the dashboard surface
  4. Rank Math sitemap, canonicals, robots, and schema defaults are configured; every vendor/venue archive has a geo-targeted H1 and 300-word intro
  5. Lighthouse audit passes with no blocking performance, accessibility, or SEO issues; cross-browser testing passes on Chrome, Safari, Firefox, and mobile
  6. Codebase audit finds no hardcoded URLs, raw hex values, `!important`, or inline styles (except dynamic PHP background images); `SDWD_DEV_MODE` is NOT defined in production `wp-config.php`; `page-style-guide.php` uses only `.grid`/`.container`, `icon-*` BEM classes with no Bootstrap or Font Awesome
**Plans**: TBD
**UI hint**: yes

---

## Coverage

### Launch Gate Mapping

| Launch Gate | Covered By |
|-------------|-----------|
| LG-01: sdwd-couple AJAX nonces | P2-NONCE-01 (Phase 2) |
| LG-02: /{location}/{type}/ returns HTTP 200 | P4-TPL-01, P4-ROUTE-01, P4-QA-01 (Phase 4) |
| LG-03: Dashboards E2E functional | P1-FIX-01 (Phase 1) + P5-DASH-01, P5-DASH-02, P5-DASH-03 (Phase 5) |
| LG-04: Transactional email delivers | P5-EMAIL-01, P5-EMAIL-02, P5-EMAIL-03 (Phase 5) |

### Requirement Coverage

| Phase | Requirements | Count |
|-------|-------------|-------|
| Phase 1 | P1-QA-01, P1-BUILD-02, P1-CLEAN-01..07, P1-FIX-01, P1-FIX-02 | 11 |
| Phase 2 | P2-DISCO-01, P2-NONCE-01, P2-CLAIM-01, P2-PORT-01..05, P2-MIGRATE-01, P2-PARITY-01..04, P2-SEC-01..06 | 19 |
| Phase 3 | P3-DISCO-01, P3-BUILD-01..03, P3-PORT-01..14, P3-CLEAN-01 | 19 |
| Phase 4 | P4-TPL-01, P4-ROUTE-01, P4-ROUTE-02, P4-PERF-01, P4-QA-01 | 5 |
| Phase 5 | P5-DASH-01..03, P5-EMAIL-01..03, P5-SEO-01..04, P5-STYLE-01, P5-PERF-01..02, P5-QA-01..07, P5-LAUNCH-01 | 21 |
| **Total** | | **75** |

**All 75 launch requirements mapped. No orphans. All 4 launch gates covered.**

**Validated (pre-existing, pulled out of launch REQ scope):**
- P1-BUILD-01 global footer (already built — verification-only check retained in Phase 1 CONTEXT)
- Navigation bar, Venues Landing, Venue Location Archive, Venue Type Archive, Vendors Landing, Vendor Category Page, 3 registration modals, 14 responsive-polish items — all pre-existing, captured in `.planning/PROJECT.md` §Validated

---

## Progress Table

| Phase | Plans Complete | Status | Completed |
|-------|----------------|--------|-----------|
| 1. Close in-progress + cleanup | 0/? | Not started | - |
| 2. Plugin closeout + parity + security | 0/? | Not started | - |
| 3. Missing + ported templates | 0/? | Not started | - |
| 4. Combo venue SEO pages | 0/? | Not started | - |
| 5. Launch prep | 0/? | Not started | - |

---

## Parallelization Note

Phases run strictly sequentially — each phase's validated state is a precondition for the next:
- Phase 2 fixes the broken registration and nonce surface that Phase 3 templates depend on
- Phase 4 combo pages require Phase 2's flat `venue-location` taxonomy and CPT archive routing
- Phase 5 launch prep requires the complete public URL surface (Phase 3) and SEO routes (Phase 4)
- `P5-EMAIL-02` closes the `P1-FIX-01` cross-phase hook — couple welcome email must carry password material

Within each phase, `parallelization = true` — independent plans can run simultaneously. Inside Phase 2, `P2-DISCO-01` (seating-chart hunt) runs first; `P2-PORT-01` is gated on its outcome. Inside Phase 3, `P3-DISCO-01` runs first; all other Phase 3 ports are gated on its fingerprint table.

---

*Roadmap created: 2026-04-22*
*Last updated: 2026-04-22 after terminology reset + legacy-recovery re-synthesis*
