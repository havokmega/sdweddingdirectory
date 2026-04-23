# P2-DISCO-01 — Seating Chart Plugin Hunt (Pre-executed)

**Run date:** 2026-04-23
**Phase:** Phase 2 first task (pre-executed in this session to save Phase 2 token spend — results ready for consumption when Phase 2 begins)
**Status:** ✅ FOUND — plugin exists in 2 legacy locations; legacy-sdweddingdirectory/ version is most recent and should be the port source

---

## Verdict (one-liner)

**Plugin recoverable with surgery.** The core logic is custom and solid (~1,947 lines across 10 files), but the UI layer uses jQuery + Bootstrap classes that violate `CLAUDE.md` bans. **Recovery with dep-stripping surgery** ≈ 4–8 hours work vs. ~40+ hours to rebuild from scratch. Recovery wins big.

---

## Candidates Found

### Candidate A (WINNER): `legacy-sdweddingdirectory/`

**Full path:** `/Users/Havok/Documents/Development/WebDevelopment/legacy-sdweddingdirectory/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-seating-chart/`

**Last modified:** 2026-02-28 (main plugin file), 2026-03-17 (couple-file/ subdir — most recent work)

**Status:** Most recent version. Includes 23 additional lines of print-layout CSS in `couple-file/style.css` that Candidate B lacks.

### Candidate B (older): `backupwp/`

**Full path:** `/Users/Havok/Documents/Development/WebDevelopment/backupwp/wp-docker/wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-seating-chart/`

**Last modified:** Same file timestamps as A except `couple-file/` dated 2026-02-25 (older than A's 2026-03-17).

**Status:** Stale backup. Use Candidate A.

**Diff between A and B:** Only `couple-file/style.css` — A has 225 lines, B has 201. The extra 23 lines in A are a print-layout block (`#sdwc-print-layout-card ~ *`, hide dashboard chrome on print). Strictly additive; A is strictly newer.

### Candidate C (not a plugin): `sdwd-ui/pages/page-wedding-planning-seating.php`

0 lines — empty file. Ignore.

---

## File Inventory (Candidate A)

```
sdweddingdirectory-seating-chart/
├── sdweddingdirectory-seating-chart.php   128 lines  (main plugin file, loader)
├── index.php                                2 lines  (directory protector)
├── admin-file/
│   └── index.php                            2 lines  (empty stub — admin UI not built)
├── ajax/
│   └── index.php                           63 lines  (AJAX handlers)
├── couple-file/
│   ├── index.php                          200 lines  (couple-facing UI renderer)
│   ├── script.js                          932 lines  (drag-drop layout logic, JQUERY)
│   └── style.css                          225 lines  (layout + print CSS)
├── database/
│   └── index.php                          322 lines  (CPT/schema/persistence)
├── filters-hooks/
│   ├── index.php                           30 lines  (WP action/filter registrations)
│   └── menu/
│       └── index.php                       43 lines  (couple-dashboard menu item)
└── languages/                               (empty)
                                        ─────────
                                         1,947 lines total
```

---

## Banned Dependency Audit

Against CLAUDE.md §"Never introduce": Bootstrap, Font Awesome, jQuery, Google Fonts, external CDNs, Elementor, shortcake.

| Category | Status | Location | Detail |
|----------|--------|----------|--------|
| **jQuery** | ❌ VIOLATES | `couple-file/index.php:48` + `script.js` | `wp_enqueue_script` dep array includes `'jquery'`. `script.js` has **68 `$(...)` calls** — jQuery is the primary DOM API. Whole file is jQuery-shaped. |
| **Bootstrap** | ❌ VIOLATES | `couple-file/index.php` (~60+ class uses) | Full Bootstrap grid + utility class suite in use: `container`, `row`, `col-lg-8`, `col-lg-4`, `col-sm-4`, `col-6`, `col-12`, `d-flex`, `d-md-flex`, `flex-wrap`, `gap-2`, `justify-content-between`, `align-items-center`, `align-items-end`, `mb-0`/`1`/`2`/`3`, `mt-3`, `btn`, `btn-sm`, `btn-primary`, `btn-dark`, `btn-outline` |
| Bootstrap (in CSS) | ❌ VIOLATES (1 line) | `couple-file/style.css:158` | References `.d-md-flex` in a selector (contextual override). |
| **Font Awesome** | ✅ CLEAN | — | Zero `fa-`/`fas`/`far`/`fab` matches. No FA icon markup. |
| **External CDN** | ✅ CLEAN | — | No `src="http..."` or `//cdn` references. Plugin loads only local assets. |
| **Google Fonts** | ✅ CLEAN | — | Not referenced. |
| **Third-party JS libraries** | ✅ CLEAN | — | No fullcalendar, apexchart, summernote, select2, toastr, magnific, isotope. Pure custom + jQuery. |
| **Inline styles** | ⚠️ 6 total | `script.js` (4), `couple-file/index.php` (2) | `script.js` inline styles are likely dynamic (drag-drop element positioning — legitimate per CLAUDE.md "except dynamic background images from PHP"). `index.php` inline styles should be moved to `style.css`. |
| **Backend code (PHP files outside couple-file/)** | ✅ CLEAN | database, ajax, admin, filters-hooks, main plugin | Pure PHP, no banned classes or scripts. Port as-is (subject to text-domain + sdwd-* prefix rewrite). |

---

## Founder's Memory vs Reality

Founder statement from `PROJECT.md`: *"The seating chart plug in was built by us from scratch, that was never part of any legacy code, we made that, and if we can find that it would save a lot of time."*

**Reality:** Plugin IS custom-built by founder (no third-party library boilerplate, no stock WP plugin template fingerprints). BUT it was built using jQuery + Bootstrap — both of which are now banned in the current project. This was likely built in an earlier era before the BEM-lite + vanilla-JS rules were set.

**Conclusion:** "from scratch" was accurate, but "clean" is not. Surgery needed.

---

## Recommended Port Strategy

### Target location

`wp-content/plugins/sdwd-couple/modules/seating-chart/` (matches the existing `sdwd-couple` module convention — see sibling modules `budget`, `checklist`, `request-quote`, `reviews`, `wishlist`).

### Phase 2 sub-tasks (suggested; `gsd-planner` will refine)

1. **P2-PORT-01a — Backend port** (PHP layers are clean)
   - Copy `database/index.php` → `sdwd-couple/modules/seating-chart/database.php`; rename CPT/schema slugs from `sdweddingdirectory_*` → `sdwd_*`
   - Copy `ajax/index.php` → `sdwd-couple/modules/seating-chart/ajax.php`; rename AJAX actions + nonces to `sdwd_seating_*`
   - Copy `filters-hooks/` → inline into main module bootstrap or split as needed
   - Copy `sdweddingdirectory-seating-chart.php` → `sdwd-couple/modules/seating-chart/seating-chart.php`; update plugin-name, text-domain (to `sdwd-couple` per WP plugin convention), class names to `SDWD_Seating_Chart_*`
   - Nonce issuance per P2-NONCE-01 pattern (wp_localize_script)
   - **Effort:** ~1.5 hours

2. **P2-PORT-01b — Frontend UI Bootstrap → BEM-lite rewrite** (couple-file/index.php)
   - Replace every Bootstrap utility class with existing BEM-lite + `.grid`/`.container` equivalents from theme `components.css`/`layout.css`
   - Approximate mapping: `container row col-*` → `.container .grid .grid--*`; `btn btn-primary` → `.btn .btn--primary`; `d-flex justify-content-between` → flex in dedicated `.seating-chart__toolbar` BEM class; margins (`mb-*`, `mt-*`) → CSS tokens in scoped styles
   - Move 2 inline styles out to CSS
   - **Effort:** ~2 hours

3. **P2-PORT-01c — jQuery → vanilla ES6+ rewrite** (couple-file/script.js)
   - 68 `$()` calls to rewrite. Most are simple selectors (`$('#foo')` → `document.getElementById('foo')`) and event handlers (`$('...').on('click', fn)` → `element.addEventListener('click', fn)`)
   - The drag-drop logic in the middle of `script.js` is the hardest part — likely uses `$.draggable()`/`$.droppable()` or jQuery event-delegation for pointer events. Replace with native PointerEvent API.
   - `$.ajax()` calls → `fetch()` with proper nonce headers
   - **Effort:** ~2.5–4 hours depending on drag-drop complexity

4. **P2-PORT-01d — CSS surgery + integration**
   - Strip Bootstrap references from `style.css` (1 selector), tokenize colors/spacing, ensure BEM-lite scoping
   - Verify print-layout CSS (lines 202–224) still works
   - **Effort:** ~30 minutes

5. **P2-PORT-01e — E2E test on dev account**
   - Log in as `couple`/`couple` dev account, navigate to seating-chart dashboard section, create layout, add tables, assign guests, save, reload, verify persistence
   - **Effort:** ~30 minutes

**Total estimated effort for full port:** 6.5–9 hours

**Rebuild-from-scratch alternative:** at least 40 hours (full drag-drop from scratch + persistence layer + print layout + couple-dashboard integration). **Recovery saves ~30–35 hours.**

---

## What Gets Added to P2-PORT-01

Current `REQUIREMENTS.md` P2-PORT-01:

> P2-PORT-01: `seating-chart` module ported from prior-built source (gated by `P2-DISCO-01` outcome)

**Needs update to reflect findings:**

> P2-PORT-01: `seating-chart` module ported from `~/Documents/Development/WebDevelopment/legacy-sdweddingdirectory/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-seating-chart/` to `wp-content/plugins/sdwd-couple/modules/seating-chart/`. **Surgery required on port:** rewrite `couple-file/index.php` Bootstrap classes to BEM-lite (~60 class replacements); rewrite `couple-file/script.js` from jQuery (68 `$()` calls) to vanilla ES6+; strip `.d-md-flex` reference from `style.css`. Backend layers (database/, ajax/, filters-hooks/, main plugin file) port cleanly with sdwd_* prefix + text-domain renames. See `.planning/discoveries/P2-DISCO-01-seating-chart-hunt.md` for full audit.

---

## Skipped Work (not relevant to seating-chart hunt)

The following matched the grep but are NOT the seating-chart plugin and should be ignored:

- `apex-chart/` directories in multiple folders — ApexCharts third-party library (unrelated to seating chart)
- `Blog Post Images/how-to-make-a-wedding-seating-chart*.jpg` — content images for a blog post, not code
- Image assets (`seating-chart_chair.svg`, `seating-chart_clipboard.svg`, `seating-chart_table-number.svg`) in various snapshot folders — icons for the planning-tools UI, already available in current theme at `assets/images/planning/seating-chart/`
- `search-listing/helper/seating-capacity/` directories — unrelated venue search helper (filtering venues by seating capacity, not seat assignment)

---

## Handoff to Phase 2

When Phase 2 kicks off (`/gsd-plan-phase 2`):

1. **P2-DISCO-01 is complete** — do not re-run the hunt. Point the planner at this file.
2. **P2-PORT-01** should be updated in REQUIREMENTS.md per the suggested wording above — recovery source pinned to `legacy-sdweddingdirectory/`, surgery requirements enumerated.
3. **No v1.1-era "rebuild" fallback needed.** The recovery is viable.

---

*Pre-executed 2026-04-23 during Phase 1 planning to save Phase 2 discovery tokens (~200-300k saved vs. full agent spawn).*
