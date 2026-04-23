# Roadmap: San Diego Wedding Directory — v1-launch

**Milestone:** v1-launch
**Granularity:** coarse
**Coverage:** 70/70 v1 requirements mapped ✓
**Launch Gates:** 4/4 covered ✓

---

## Phases

- [ ] **Phase 1: Close in-progress + cleanup** — Stabilize existing work: build missing shell templates (footer, 404), fix the broken couple registration form, and remove dead legacy code that pollutes the codebase
- [ ] **Phase 2: Plugin closeout + parity + security** — Complete `sdwd-couple` modules + nonces (LG-01), achieve vendor/venue architectural parity, harden password and input security
- [ ] **Phase 3: Missing + ported templates** — Add all content pages absent from the v2 theme: cost, registry, hashtag tool, inspiration, blog, policy, wedding websites, real weddings
- [ ] **Phase 4: Combo venue SEO pages** — Build `/{location}/{type}/` URL routing and combo templates (LG-02)
- [ ] **Phase 5: Launch prep** — Wire dashboards end-to-end (LG-03), deliver transactional email (LG-04), SEO configuration, performance optimization, and cross-browser QA

---

## Phase Details

### Phase 1: Close in-progress + cleanup
**Goal**: All existing in-progress work is finished and the codebase is clean — broken legacy hooks removed, text-domain standardized, and the couple registration form actually creates accounts
**Depends on**: Nothing (first phase)
**Requirements**: P1-QA-01, P1-BUILD-01, P1-BUILD-02, P1-CLEAN-01, P1-CLEAN-02, P1-CLEAN-03, P1-CLEAN-04, P1-CLEAN-05, P1-CLEAN-06, P1-CLEAN-07, P1-FIX-01
**Success Criteria** (what must be TRUE):
  1. A global footer renders on every non-front-page template, and a styled 404 page returns for unknown URLs
  2. A visitor submitting the planning hero registration form successfully creates a couple account (P1-FIX-01 — supports LG-03)
  3. `functions.php` contains no references to dead legacy filter hooks or dequeue handles for handles that were never enqueued
  4. Every `__()` / `esc_html__()` call in the theme uses the `sandiegoweddingdirectory` text-domain; `style.css` header matches
**Plans**: TBD
**UI hint**: yes

---

### Phase 2: Plugin closeout + parity + security
**Goal**: `sdwd-couple` is fully functional with nonces wired (LG-01 cleared), vendor and venue are architecturally parallel, and all HIGH security issues are closed
**Depends on**: Phase 1
**Requirements**: P2-NONCE-01, P2-CLAIM-01, P2-PORT-01, P2-PORT-02, P2-PORT-03, P2-PORT-04, P2-PORT-05, P2-MIGRATE-01, P2-PARITY-01, P2-PARITY-02, P2-PARITY-03, P2-PARITY-04, P2-SEC-01, P2-SEC-02, P2-SEC-03, P2-SEC-04, P2-SEC-05, P2-SEC-06
**Success Criteria** (what must be TRUE):
  1. **LG-01**: All 5 `sdwd-couple` AJAX endpoints (checklist, budget, wishlist, reviews, request-quote) accept valid nonces and return success responses — no 403s
  2. The vendor claim button on `single-vendor.php` fires the claim AJAX handler (no longer a dead click); inline `<script>` blocks are gone from both single templates
  3. `venue-profile.php` exists parallel to `vendor-profile.php`; `venue-dashboard.php` has a description field; `venue-location` taxonomy is flat (city-only); both CPTs have `has_archive => true`
  4. Password-change on the frontend dashboard requires current-password confirmation and enforces ≥8-char minimum; admin metabox password saves use `wp_unslash()`; rate limiting is active on login and password-change endpoints
  5. Migration tool runs idempotently on staging data with batch-50 chunking and completes without PHP timeout
**Plans**: TBD
**UI hint**: no

---

### Phase 3: Missing + ported templates
**Goal**: Every content page missing from the v2 theme is built or ported — cost pages, registry, tools, blog, inspiration, policy, wedding websites, and real weddings — giving the site a complete public URL surface
**Depends on**: Phase 2
**Requirements**: P3-DISCO-01, P3-BUILD-01, P3-BUILD-02, P3-BUILD-03, P3-PORT-01, P3-PORT-02, P3-PORT-03, P3-PORT-04, P3-PORT-05, P3-PORT-06, P3-PORT-07, P3-PORT-08, P3-CLEAN-01
**Success Criteria** (what must be TRUE):
  1. Port-source fingerprint table is delivered to the founder before any porting begins; per-page source selection is confirmed
  2. `/cost/`, `/registry/`, and the hashtag-generator tool pages are accessible with v2 styling and SD-specific content (300-word min on cost pages)
  3. Blog archive, single post, category archive, inspiration, and 4-tab policy pages render correctly using v2 CSS conventions (no Bootstrap, no FA, no inline styles)
  4. Wedding-websites landing + templates gallery and real-wedding archive + single are live; `real-wedding` CPT and its 8 taxonomies are registered in `sdwd-core` (or orphan files are deleted)
  5. No template file calls a legacy `sdweddingdirectory/...` action hook that has no registered handler
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
**Goal**: All three dashboards are E2E-functional (LG-03), transactional email delivers to real inboxes (LG-04), SEO is configured, performance is optimized, and QA is complete — the site is ready to replace v1
**Depends on**: Phases 1, 2, 3, 4 (launch prep requires the full site to be complete)
**Requirements**: P5-DASH-01, P5-DASH-02, P5-DASH-03, P5-THEME-01, P5-THEME-02, P5-EMAIL-01, P5-EMAIL-02, P5-EMAIL-03, P5-SEO-01, P5-SEO-02, P5-SEO-03, P5-SEO-04, P5-STYLE-01, P5-PERF-01, P5-PERF-02, P5-QA-01, P5-QA-02, P5-QA-03, P5-QA-04, P5-QA-05, P5-QA-06, P5-QA-07, P5-LAUNCH-01
**Success Criteria** (what must be TRUE):
  1. **LG-03**: A new couple, vendor, and venue can each complete the full flow — register → login → save profile data → receive email confirmation — without error
  2. **LG-04**: Quote request, registration confirmation, welcome, and claim notification emails land in a real inbox (not spam) when triggered from staging
  3. Rank Math sitemap, canonicals, robots, and schema defaults are configured; every vendor/venue archive has a geo-targeted H1 and 300-word intro
  4. Lighthouse audit passes with no blocking performance, accessibility, or SEO issues; cross-browser testing passes on Chrome, Safari, Firefox, and mobile
  5. Codebase audit finds no hardcoded URLs, raw hex values, `!important`, or inline styles (except dynamic PHP background images); `page-style-guide.php` uses only `.grid`/`.container`, `icon-*` BEM classes with no Bootstrap or Font Awesome
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
| Phase 1 | P1-QA-01, P1-BUILD-01, P1-BUILD-02, P1-CLEAN-01 through P1-CLEAN-07, P1-FIX-01 | 11 |
| Phase 2 | P2-NONCE-01, P2-CLAIM-01, P2-PORT-01 through P2-PORT-05, P2-MIGRATE-01, P2-PARITY-01 through P2-PARITY-04, P2-SEC-01 through P2-SEC-06 | 18 |
| Phase 3 | P3-DISCO-01, P3-BUILD-01 through P3-BUILD-03, P3-PORT-01 through P3-PORT-08, P3-CLEAN-01 | 13 |
| Phase 4 | P4-TPL-01, P4-ROUTE-01, P4-ROUTE-02, P4-PERF-01, P4-QA-01 | 5 |
| Phase 5 | P5-DASH-01 through P5-DASH-03, P5-THEME-01, P5-THEME-02, P5-EMAIL-01 through P5-EMAIL-03, P5-SEO-01 through P5-SEO-04, P5-STYLE-01, P5-PERF-01, P5-PERF-02, P5-QA-01 through P5-QA-07, P5-LAUNCH-01 | 23 |
| **Total** | | **70** |

**All 70 v1 requirements mapped. No orphans. All 4 launch gates covered.**

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

Within each phase, `parallelization = true` — independent plans (e.g., building the footer while also cleaning dead filters) can run simultaneously.

---

*Roadmap created: 2026-04-22*
*Last updated: 2026-04-22 after initialization*
