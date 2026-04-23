# Phase 1: Close in-progress + cleanup — Context

**Gathered:** 2026-04-22
**Status:** Ready for planning
**Re-synthesized:** 2026-04-22 (terminology reset — milestone renamed to "launch"; P1-FIX-02 added; P1-BUILD-01 struck from requirements; P1-BUILD-02 reworded "build" → "finish")

<domain>
## Phase Boundary

Stabilize existing work before launch prep: finish the 404 page (95% done, touch-ups only), fix the broken couple registration form so it actually creates accounts (supports LG-03), fix the home-page category-search mega-menu dropdown (scoped home-page unlock), and remove dead legacy code (filter hooks, stylesheet dequeues, inert CSS rules) + standardize the text-domain across the theme AND plugin files.

Every cleanup item (P1-CLEAN-01 through 06) has a pre-specified file + line number + "delete/rename" directive in `.planning/codebase/CONCERNS.md` — this phase is HOW not WHAT for those items.

Out of scope for this phase: the Phase 2 security work (password strength / current-password / rate limiting / `SDWD_DEV_MODE` constant), the Phase 2 vendor/venue parity work, any new capability. Home-page edits are out of scope EXCEPT for the `P1-FIX-02` mega-menu dropdown fix (scoped unlock per root `CLAUDE.md` §Locked Pages).

</domain>

<decisions>
## Implementation Decisions

### Global footer — already landed (P1-BUILD-01 struck from REQUIREMENTS)

- **D-01:** Footer is already built. `wp-content/themes/sandiegoweddingdirectory/footer.php` (113 lines) exists and all 4 footer widget areas are registered in `functions.php:65-79` via `for ( $i = 1; $i <= 4; $i++ )`. Widget IDs: `sdwdv2-footer-1` through `sdwdv2-footer-4`. `footer.php` renders 4 `.footer__widget` columns (matches). Moved to `.planning/PROJECT.md` §Validated during the 2026-04-22 re-synthesis; `P1-BUILD-01` struck from `REQUIREMENTS.md`.
- **D-02:** No footer build work in Phase 1 plans. The phase verifier retains a **verification-only checkpoint** — eyeball the footer at phase end to catch any regressions from surrounding Phase 1 work (text-domain sweep, dead-filter deletes). If the verifier finds gaps, surface them for a targeted touch-up via a separate Post-Launch Backlog entry or a decimal-phase insert — do not scope a new build inside Phase 1.

### P1-BUILD-02 — 404 page (finish; 95% done, touch-ups only)

- **D-03:** Replace the current placeholder inline SVG in `404.php` with the real asset at `wp-content/themes/sandiegoweddingdirectory/assets/images/404-error-page/404_error.svg`. Reference via `<?php echo esc_url( get_template_directory_uri() . '/assets/images/404-error-page/404_error.svg' ); ?>`.
- **D-04:** Keep the existing `<h1>Page Not Found</h1>` and the existing description copy as-is. Text-domain on these strings will be normalized by the P1-CLEAN-04 sweep.
- **D-05:** Bottom button row — 5 buttons, all styled `.btn .btn--outline` (currently `Home` is `.btn--primary`; that visual inconsistency is the bug to fix). Left-to-right order and targets:
  - Home → `home_url( '/' )`
  - Planning Tools → `home_url( '/wedding-planning/' )`
  - Venues → `home_url( '/venues/' )`
  - Vendors → `home_url( '/vendors/' )`
  - Real Weddings → `home_url( '/real-weddings/' )`
- **D-06:** Layout — button row right-aligned (currently left-aligned). Implementation: the `.error-404__nav` container switches from default alignment to `justify-content: flex-end` with token-based `gap` between buttons. No hardcoded spacing values — use tokens from `foundation.css :root`.
- **D-07:** CSS lives in `wp-content/themes/sandiegoweddingdirectory/assets/css/pages/static.css` (already conditionally enqueued for 404 via `functions.php:138-140` — no enqueue change needed).

### P1-FIX-01 — Registration form retarget + UX

- **D-08:** Keep the silent auto-password generation pattern (current 2-step UX in `template-parts/planning/planning-hero.php`: Step 1 name + email, Step 2 location + wedding date; username and password auto-derived client-side; AJAX submit; auto-login + redirect to `/couple-dashboard/`). Rationale: lowest friction for couples registering mid-planning-session where conversion matters more than password choice.
- **D-09:** Retarget the form per CONCERNS.md HIGH finding:
  - Hidden `action` input: `sdweddingdirectory_couple_register_form_action` → `sdwd_register`
  - Nonce: legacy `sdweddingdirectory_couple_registration_security` → `sdwd_auth_nonce`
  - Input `name` attributes rename to match `sdwd-core/includes/auth.php` field names: `first_name`, `last_name`, `email`, `password`, `account_type=couple`, `wedding_date`
  - Preserve the client-side split of the full-name field into `first_name`/`last_name`
  - Preserve the client-side auto-generated password (already satisfies the ≥8-char registration minimum at `auth.php:27,51-53`)
- **D-10:** Cross-phase dependency — P1-FIX-01 is not *truly complete* until LG-04 (Phase 5, transactional email) delivers a welcome email that contains either (a) the auto-generated password in plaintext OR (b) a one-click password-set/reset link. Without this, a couple who logs out before setting their own password is locked out with no recovery path. This constraint is captured here so the Phase 5 email-template work (`P5-EMAIL-02`) includes the auth-material delivery path.
- **D-11:** Success/error UX stays as currently implemented (AJAX response drives the `.planning-hero__form-message` banner + 350ms delayed redirect on `payload.redirect === true`). No rework — just ensure the `sdwd_register` handler returns a compatible payload shape (`{ message, notice, redirect, redirect_link }`) or adjust the theme-side handler to match whatever `auth.php` currently returns.

### P1-FIX-02 — Home-page category-search mega-menu dropdown

- **D-18:** The bug — the category filter on the home-page search bar is supposed to drop down a mega-menu showing selectable vendor/venue categories. Currently it does nothing when clicked. Root `PROJECT.md` §1 notes: "Search doesn't work. Searching by category is supposed to drop down a mega menu. We had this working in some prior version."
- **D-19:** **Home-page scoped unlock is ACTIVE for this requirement only.** Root `CLAUDE.md` §Locked Pages and `.planning/CLAUDE-GSD.md` §Locked both reflect the scope: `front-page.php` and `assets/css/pages/home.css` may be edited **only** for the work required to fix the category-search mega-menu dropdown. Every other change to either file is still gated on explicit founder approval. The lock re-engages automatically the moment `P1-FIX-02` is verified complete.
- **D-20:** Scope of edits is investigation-driven, not pre-committed. Likely files: `front-page.php` (home template markup for the search bar + category filter), `assets/css/pages/home.css` (dropdown visibility + positioning), `assets/js/app.js` (dropdown open/close behavior). **Possibly NOT** `inc/sd-mega-navwalker.php` — that serves the top-nav mega-menu walker, not the home-page search-bar category dropdown; re-check at plan-phase to confirm. If the investigation surfaces a need to edit files outside this set, escalate to the founder before expanding scope.
- **D-21:** Success criterion (mirrors ROADMAP.md Phase 1 §2): "The home-page category-search mega-menu dropdown fires correctly and displays selectable category options." Verifier confirms via manual browser test — click the category filter on `/`, dropdown opens, shows category options, selection passes through to the downstream search behavior.
- **D-22:** If fingerprinting the prior working version of this dropdown is useful, check legacy snapshots under `~/Documents/Development/WebDevelopment/` before rebuilding from scratch — this is a small example of the Core Principle "legacy code is a recovery source, not a scrap heap." Dropdown markup + CSS + JS may be recoverable from a prior attempt. Strip banned deps on import (no Bootstrap, FA, jQuery) and tokenize via `foundation.css :root`.
- **D-23:** No changes to shared tokens (`foundation.css :root`), `components.css`, or `layout.css` under this scope. If the fix appears to require a new token, escalate — do not add one unilaterally; home-page components are downstream of shared tokens and a token change may cascade into other locked pages.

### P1-CLEAN-04 — Text-domain sweep (full)

- **D-12:** Full sweep — normalize every occurrence of `'sdweddingdirectory-v2'` and `'sdweddingdirectory'` in the codebase to the correct text-domain for each file:
  - Theme files (`functions.php`, all `page-*.php`, all `single-*.php`, all `archive-*.php`, `taxonomy-*.php`, `template-parts/**`, `user-template/**`) → `'sandiegoweddingdirectory'`
  - Plugin files (`wp-content/plugins/sdwd-core/**`, `wp-content/plugins/sdwd-couple/**`) → also normalize legacy `'sdweddingdirectory'` strings per founder's explicit instruction. The founder stated "Normalize ALL text-domain strings to sandiegoweddingdirectory" — phpcs is the authority on whether plugin files should end up with `sandiegoweddingdirectory` (literal instruction, breaks plugin .mo loading convention) or the plugin's own slug `sdwd-core` / `sdwd-couple` (WP convention). Phase 1 execution uses whichever phpcs enforces for a zero-error gate. If phpcs accepts `sandiegoweddingdirectory` in plugin files, use it; if phpcs enforces plugin-slug domains, use those.
- **D-13:** `style.css` `Text Domain:` header is already `sandiegoweddingdirectory` (verified in scout). Confirm `Version:` header is current during the sweep — bump if stale.
- **D-14:** Verification gate — binary phpcs check with the WP text-domain sniff:
  - Before: ≥1,039 text-domain errors (roughly: 7 in functions.php + ~517 template occurrences of `'sdweddingdirectory'` + plugin-side occurrences)
  - After: 0 errors
- **D-15:** Explicit plugin-file authorization — CLAUDE.md §File boundaries says "Do not modify plugin files unless explicitly instructed." The founder's instruction in the Phase 1 discussion explicitly authorizes plugin-file modification for the text-domain sweep scope only. Capture this authorization in the commit message so the paper trail is clear.

### P1-CLEAN-07 — Bundled LOW-priority cleanup

- **D-16:** Include in P1-CLEAN-07 scope:
  - Delete the root-level macOS `Icon\r` artifact (the file at repo root referenced in CONCERNS.md §LOW, currently sitting in the tree though `.gitignore:51` covers `Icon?`)
  - Bump `style.css` `Version:` header if it's stale relative to the current release state (check `SDWEDDINGDIRECTORY_THEME_VERSION` constant for parity)
- **D-17:** Explicitly EXCLUDE from P1-CLEAN-07 scope:
  - `Documentation/TASK_LOG.md` — founder's personal workspace, not theme code
  - `wp-content/debug.log` truncation — WordPress writes to it freely; truncating now just defers the same state. Skip unless the log is actively blocking debugging.
  - Everything in CONCERNS.md §MEDIUM or §HIGH — those are Phase 2+ work.

### Claude's Discretion

- Commit granularity within Phase 1 plans (atomic per requirement, or grouped where changes touch the same file). Prefer atomic.
- Exact phpcs rule set / config used to run the text-domain verification, as long as the before/after error count proves the sweep landed.
- The specific token names used for the 404 button-row `gap` (any existing token in `foundation.css :root` is acceptable).
- Whether to do the text-domain sweep with `sed`/`find`/an editor script — mechanical execution choice.

</decisions>

<canonical_refs>
## Canonical References

**Downstream agents MUST read these before planning or implementing.**

### Hard rules + project scope
- `CLAUDE.md` — hard rules, banned dependencies, file boundaries, locked home page, git safety. Violation is session-ending.
- `.planning/CLAUDE-GSD.md` — GSD workflow guide + project-specific gotchas (WP specifics, text-domain convention, dev-account preservation via `SDWD_DEV_MODE`).
- `PROJECT.md` (repo root) — authoritative task tracker (pre-GSD, still canonical for task-status truth). Cross-references Phase 7 + Phase 8 rewrites landed 2026-04-22.
- `.planning/PROJECT.md` — milestone scope, launch gates, key decisions, out-of-scope, Core Principles.
- `.planning/codebase/DESIGN-REFERENCES.md` — canonical UI reference (weddingwire.com) + WeddingDir themeforest port targets + strip-third-party rules.
- `.planning/REQUIREMENTS.md` §"Phase 1" — the 11 `P1-*` REQ-IDs this phase delivers.
- `.planning/ROADMAP.md` §"Phase 1" — goal and 4 success criteria.
- `.planning/STATE.md` — current position and next-action handoff.

### Primary source of Phase 1 task detail
- `.planning/codebase/CONCERNS.md` — exact file + line numbers + fix approach for every P1-CLEAN-* and P1-FIX-01 item. Authoritative per `CLAUDE-GSD.md`.
- `.planning/codebase/STRUCTURE.md` — file layout, naming conventions (nonce names, AJAX action names, meta-key prefix).
- `.planning/codebase/CONVENTIONS.md` — code conventions.
- `.planning/codebase/ARCHITECTURE.md` — system rules, CSS architecture, separation of concerns.

### Human-authored reference
- `Documentation/architecture.md` — system rules, CSS architecture, naming, brand palette, icon mapping.
- `Documentation/codebase-index.md` — every theme file with purpose + status.
- `Documentation/component-index.md` — reusable component catalog.
- `Documentation/route-template-map.md` — URL → template → data flow.

### Phase-1-specific files the plan/executor will read
- `wp-content/themes/sandiegoweddingdirectory/functions.php` — lines 32/36/37/58-59/69/71/175-181/229/231/240-243/245-254 (targets for P1-CLEAN-01, -02, -04, -05, -06)
- `wp-content/themes/sandiegoweddingdirectory/assets/css/layout.css` §687-690 (target for P1-CLEAN-03)
- `wp-content/themes/sandiegoweddingdirectory/404.php` (target for P1-BUILD-02)
- `wp-content/themes/sandiegoweddingdirectory/assets/css/pages/static.css` (404 page CSS destination)
- `wp-content/themes/sandiegoweddingdirectory/assets/images/404-error-page/404_error.svg` (real 404 asset, already in repo)
- `wp-content/themes/sandiegoweddingdirectory/footer.php` (P1-BUILD-01 verification target)
- `wp-content/themes/sandiegoweddingdirectory/template-parts/planning/planning-hero.php` §62-67, 79-124, 154, 223-235 (target for P1-FIX-01)
- `wp-content/plugins/sdwd-core/includes/auth.php` §18, 27, 41-59 (registration handler `sdwd_register` — field names are authoritative)
- `wp-content/themes/sandiegoweddingdirectory/style.css` (Text Domain + Version header verification)

### Legacy artifact
- `/Icon` (repo root, macOS artifact — target for deletion via P1-CLEAN-07)

</canonical_refs>

<code_context>
## Existing Code Insights

### Reusable Assets
- `.btn` / `.btn--outline` / `.btn--primary` — existing button component classes used on the 404 button row. Defined in the components layer.
- `foundation.css :root` — design tokens for spacing/color/typography. The 404 button-row `gap` uses these; no hardcoded values.
- 4 footer widget areas — already registered in `functions.php:65-79` as `sdwdv2-footer-1` through `sdwdv2-footer-4` (founder updated from 5 → 4 columns prior to Phase 1 kickoff).
- `page-style-guide.php` — still uses banned Bootstrap/FA (HIGH concern in CONCERNS.md, fixed in Phase 5 P5-STYLE-01); not relevant to Phase 1 except as an anti-pattern reference.

### Established Patterns
- **Text-domain conflict** — three variants currently in use: `sdweddingdirectory-v2` (functions.php, 7 occurrences), `sdweddingdirectory` (templates + user-template + plugin legacy, ~517+ occurrences), `sandiegoweddingdirectory` (style.css header, target). Phase 1 normalizes.
- **Conditional CSS enqueue** — `functions.php:138-140` already gates `static.css` to the 404 template. No enqueue change needed for the 404 work.
- **Nonce issuance** — per-feature `sdwd_{feature}_nonce` pattern. Theme mints nonces at form render; plugin handler verifies (`check_ajax_referer`). Registration uses `sdwd_auth_nonce`.
- **AJAX response shape** — theme-side handler in `planning-hero.php` expects `{ message, notice, redirect, redirect_link }`. Verify `sdwd_register` in `auth.php` returns a compatible shape during P1-FIX-01 implementation.
- **CSS token-only rule** — no raw hex, no `!important`, no inline styles (except dynamic PHP bg images). Token-based `gap` for the 404 button row.
- **Auto-derived registration fields** — the current form splits full name into first/last client-side and generates a random password client-side. These derivations are preserved post-retarget; only the action / nonce / input-name-attribute layer changes.

### Integration Points
- `P1-FIX-01` → `sdwd_register` handler in `sdwd-core/includes/auth.php` (plugin boundary). Theme supplies form; plugin handles submission.
- `P1-FIX-01` ↔ `P5-EMAIL-02` (Phase 5) — welcome email must carry password plaintext or password-set/reset link. This cross-phase dependency is documented in D-10 so `P5-EMAIL-02` scope includes the auth-material delivery path.
- `P1-CLEAN-04` ↔ plugin files (explicitly authorized per D-15) — the only Phase 1 task that touches plugin code.

</code_context>

<specifics>
## Specific Ideas

- **404 visual fix insight:** The "Home button looks different" bug is specifically because `Home` is styled `.btn--primary` while the other 4 are `.btn--outline`. Make all 5 `.btn--outline`. No need to introduce a new variant.
- **Binary verification gate for text-domain sweep:** phpcs text-domain sniff count = 1,039+ before, 0 after. Not a subjective review — a mechanical count.
- **Welcome-email gate language:** "P1-FIX-01 is not truly complete until LG-04 delivers a welcome email containing password plaintext or reset link" — this phrasing goes into the Phase 5 plan for `P5-EMAIL-02`.

</specifics>

<deferred>
## Deferred Ideas

- **debug.log truncation** — skipped for Phase 1 per D-17. WordPress writes to it freely; truncating is not a durable fix. Revisit only if log noise blocks debugging.
- **TASK_LOG.md population** — not theme code, not in Phase 1 scope. Founder's personal workspace.
- **Plugin text-domain architectural call** (whether plugins should use `sandiegoweddingdirectory` vs. `sdwd-core` / `sdwd-couple`) — decided mechanically at execution time by phpcs. If the architectural question deserves a first-class decision, raise at plan-phase; otherwise, phpcs is the authority.
- **Footer touch-ups** — only surface if phase-verification finds gaps. No planned build work on the footer in Phase 1.

</deferred>

---

*Phase: 01-close-in-progress-cleanup*
*Context gathered: 2026-04-22*
