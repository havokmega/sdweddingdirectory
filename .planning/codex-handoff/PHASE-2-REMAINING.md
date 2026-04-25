# Phase 2 Remaining Work — Codex Handoff Plan

**For:** Codex (OpenAI CLI).
**Repo root:** `~/Documents/Development/WebDevelopment/wp-docker`.
**Rules:** MUST read `CLAUDE.md` at repo root before ANY task. Violations session-ending.
**Context docs:**
- `.planning/REQUIREMENTS.md` (what) + `.planning/codebase/CONCERNS.md` (why)
- `.planning/discoveries/P2-DISCO-01-seating-chart-hunt.md` (seating-chart recon)
- `.planning/phases/02-plugin-closeout/NEXT-VENUE-LOCATION-FILTERS.md` (related task spec — different scope, don't overlap)

## Already shipped in Phase 2 (don't redo)

| REQ | Commit |
|---|---|
| P2-DISCO-01 seating-chart fingerprint | `93a9c8c` |
| P2-NONCE-01 5 AJAX nonces localized on dashboard | `1148cf1` |
| P2-CLAIM-01 claim.js + inline script removal | `f36afdc` |
| P2-PARITY-01 has_archive=true both CPTs | `248ec65` |
| P2-PARITY-03 venue-profile.php | `dfedea3` / `ae776a7` |
| P2-PARITY-04 venue description field | verified (already existed) |

## Still to do (this plan covers)

| REQ | Legacy source | New target |
|---|---|---|
| P2-PORT-04 Todo-List | `legacy-sdweddingdirectory/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-todo-list/` | `wp-content/plugins/sdwd-couple/modules/todo-list/` |
| P2-PORT-02 RSVP | `.../sdweddingdirectory-rsvp/` | `.../rsvp/` |
| P2-PORT-03 Guest-List | `.../sdweddingdirectory-guest-list/` | `.../guest-list/` |
| P2-PORT-05 Couple Website | `.../sdweddingdirectory-couple-website/` | `.../website/` |
| P2-PORT-01 Seating-Chart | `.../sdweddingdirectory-seating-chart/` (see discovery doc) | `.../seating-chart/` |
| P2-MIGRATE-01 | run `sdwd-core/includes/migrate.php` | verify |
| P2-PARITY-02 | flatten venue-location taxonomy | DB migration |
| P2-SEC-01..06 | 6 security hardening fixes | theme + sdwd-core |

**Legacy path prefix:** `~/Documents/Development/WebDevelopment/legacy-sdweddingdirectory/plugins/sdweddingdirectory-couple/modules/`
**New plugin path prefix:** `wp-content/plugins/sdwd-couple/modules/`
**Founder's rule:** strip Bootstrap + jQuery on import. No shortcut "port AS-IS with banned deps" — surgery is mandatory. Converting is ~4x faster than rebuilding from scratch, and it preserves the schema + AJAX contracts that already work.

---

## Shared conversion patterns (read once, apply in every port task)

### Pattern A — Backend PHP file port

For every `.php` file copied from legacy to new plugin:

1. Copy the file to the target path.
2. Global rename in the copied file:
   - `sdweddingdirectory_*` → `sdwd_*` in function names, class names, hook names (`add_action`/`add_filter`/`do_action`/`apply_filters`), meta keys, AJAX action names (`wp_ajax_sdweddingdirectory_*` → `wp_ajax_sdwd_*`), nonce action strings.
3. Replace text-domain literals (2nd arg of `__()` / `_e()` / `esc_html__()` etc., 1st arg of `load_plugin_textdomain()`): `'sdweddingdirectory'` / `'sdweddingdirectory-couple'` → `'sdwd-couple'`.
4. If the file echoes HTML markup, keep it for now — frontend stripping is Pattern C (separate task).
5. Preserve `defined( 'ABSPATH' ) || exit;` at top; add if missing.
6. Remove any `register_activation_hook` / `register_deactivation_hook` — those only fire on top-level plugin files, not module files.

### Pattern B — AJAX handler hardening

For every `check_ajax_referer( 'X', 'Y' )` call in a ported handler:

1. 1st arg must be renamed to `sdwd_{feature}_nonce` per the P2-NONCE-01 convention:
   - checklist → `sdwd_checklist_nonce`
   - budget → `sdwd_budget_nonce`
   - wishlist → `sdwd_wishlist_nonce`
   - reviews → `sdwd_review_nonce`
   - quote → `sdwd_quote_nonce`
   - **New nonce actions for modules not in P2-NONCE-01:** `sdwd_todo_nonce`, `sdwd_rsvp_nonce`, `sdwd_guest_nonce`, `sdwd_website_nonce`, `sdwd_seating_nonce`.
2. 2nd arg MUST be the literal string `'nonce'` (matches the POST field name consumer JS uses).
3. Every `$_POST[ $key ]` read inside the handler must be wrapped with `wp_unslash()` BEFORE any sanitize call. Example: `$msg = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );`
4. Every response uses `wp_send_json_success` / `wp_send_json_error` (auto-escapes) — never hand-rolled `json_encode`.

### Pattern C — Frontend PHP stripping (Bootstrap → BEM-lite)

For every `couple-file/index.php` or similar frontend template:

1. Replace every Bootstrap class with BEM-lite equivalent:
   | Bootstrap | Replace with |
   |---|---|
   | `container` | keep (theme provides `.container`) |
   | `row` | `grid` |
   | `col-md-6` / `col-sm-6` | parent: `grid--2col` |
   | `col-md-4` / `col-sm-4` | parent: `grid--3col` |
   | `col-md-3` / `col-sm-3` | parent: `grid--4col` |
   | `col-12` / `col-md-12` | remove (full-width default) |
   | `d-flex` | new named class w/ flex in scoped CSS |
   | `justify-content-between`, `align-items-center` | flex properties in scoped class, not utilities |
   | `gap-2` / `gap-3` | `gap: var(--gutter);` or `var(--sdwd-row-gap)` in scoped class |
   | `mb-1`, `mb-2`, `mb-3`, `mt-*` | margin via scoped class with token |
   | `btn btn-primary` | `btn btn--primary` (add dashes, it's a 1-char swap) |
   | `btn btn-outline-*` | `btn btn--outline` |
   | `btn btn-sm` | `btn btn--small` |
   | `flex-wrap` | `flex-wrap: wrap;` in scoped class |
2. Remove every `style="..."` attribute. Move each declaration into the module's scoped CSS file with a named class. Allowed exception: dynamic `background-image` URLs generated from PHP data.
3. Remove inline `<script>` blocks; they become Pattern D work.
4. Output must use `esc_html__`, `esc_attr__`, `esc_url`, `esc_textarea`, or `wp_kses_post` — never raw echo of user data.

### Pattern D — Frontend JS port (jQuery → vanilla ES6+)

For every `script.js` in a legacy module:

1. Replace jQuery DOM APIs with vanilla:
   | jQuery | Vanilla |
   |---|---|
   | `$('#foo')` | `document.getElementById('foo')` |
   | `$('.foo')` | `document.querySelectorAll('.foo')` (iterate with `.forEach`) |
   | `$(el).find('.x')` | `el.querySelector('.x')` |
   | `$(el).closest('.x')` | `el.closest('.x')` |
   | `$(el).on('click', fn)` | `el.addEventListener('click', fn)` |
   | `$(el).on('click', selector, fn)` (delegation) | `el.addEventListener('click', e => { const t = e.target.closest(selector); if (t) fn.call(t, e); })` |
   | `$(el).val()` / `.val(v)` | `el.value` / `el.value = v` |
   | `$(el).text()` / `.text(v)` | `el.textContent` / `el.textContent = v` |
   | `$(el).html(v)` | **escape v server-side first**, then use safe DOM construction (`createElement`, `append`, `textContent`). Avoid raw HTML assignment with user data — XSS risk. If the legacy code passed trusted server-rendered HTML, refactor to template-side rendering instead. |
   | `$(el).attr(n)` / `.attr(n, v)` | `el.getAttribute(n)` / `el.setAttribute(n, v)` |
   | `$(el).hide()` / `.show()` | `el.hidden = true` / `el.hidden = false` |
   | `$(el).addClass('x')` | `el.classList.add('x')` |
   | `$(el).removeClass('x')` | `el.classList.remove('x')` |
   | `$(el).toggleClass('x')` | `el.classList.toggle('x')` |
   | `$(el).data('x')` | `el.dataset.x` |
   | `$.ajax({...})` / `$.post(url, data)` | `fetch(url, { method: 'POST', credentials: 'same-origin', body: new FormData() }).then(r => r.json())` |
2. Remove all `$(document).ready(fn)` wrappers. Replace with `document.addEventListener('DOMContentLoaded', fn)` OR an IIFE at the end of file if script loads in footer (`wp_enqueue_script(..., true)` — DOM already parsed).
3. Drop `$` as a variable reference entirely. Use descriptive names: `const saveBtn = document.querySelector('#save');`
4. Nonce lookup: `window.sdwd_dash.{module}_nonce`. POST under key `nonce`.
5. AJAX URL: `window.sdwd_dash.url`.

### Pattern E — Module loader + enqueue

Every ported module must have a main loader file:

1. Target: `wp-content/plugins/sdwd-couple/modules/{module}/{module}.php`
2. Responsibilities:
   - `require_once` the module's database.php and ajax.php
   - If the module has frontend JS/CSS, register `add_action('wp_enqueue_scripts', ...)` that conditionally enqueues on dashboard pages. Gate with `function_exists('sdwdv2_is_dashboard_page') && sdwdv2_is_dashboard_page()`.
   - For new feature nonces (todo, rsvp, guest, website, seating): ALSO update `wp-content/themes/sandiegoweddingdirectory/functions.php` — the `sdwd_dash` localize block — to add the new `{feature}_nonce` key with `wp_create_nonce( 'sdwd_{feature}_nonce' )`.
3. Register in `wp-content/plugins/sdwd-couple/sdwd-couple.php` bootstrap:
   ```php
   require_once SDWD_COUPLE_PATH . 'modules/{module}/{module}.php';
   ```
   (Add alongside existing require_once for reviews, request-quote, wishlist, checklist, budget.)

---

# Task-by-task breakdown

Each task is self-contained. Copy its body into a Codex prompt. Run one, verify, commit, then move to the next. Don't batch.

---

## TASK 1 — Scout Todo-List module

**Goal:** produce a scout report so the port tasks know exactly what's being ported.

**Do:**
1. `ls -R ~/Documents/Development/WebDevelopment/legacy-sdweddingdirectory/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-todo-list/`
2. For each `.php` / `.js` / `.css` found, record: path, line count, first 20 lines.
3. Count jQuery usage: `grep -c '\\\$(' legacy-path/couple-file/script.js`
4. Count Bootstrap usage: `grep -oE 'class="[^"]*(btn|col-|row|d-flex|mb-|mt-|gap-)' legacy-path/couple-file/index.php | wc -l`
5. Write to `.planning/discoveries/P2-PORT-04-todo-list-scout.md`.

**Acceptance:** scout file exists with full inventory + counts.
**Commit:** `docs(discoveries): scout todo-list legacy module for port`

---

## TASK 2 — Port Todo-List backend (main + database)

**Prereq:** Task 1 complete.

**Do:**
1. `mkdir -p wp-content/plugins/sdwd-couple/modules/todo-list/`
2. Copy `legacy/sdweddingdirectory-todo-list.php` → `sdwd-couple/modules/todo-list/todo-list.php`
3. Copy `legacy/database/index.php` → `sdwd-couple/modules/todo-list/database.php`
4. Apply Pattern A to BOTH files (renames + text-domain).
5. Update `todo-list.php` plugin header/docblock to reflect new naming.
6. Remove any activation/deactivation hooks.
7. Do NOT touch couple-file/, admin-file/, ajax/, filters-hooks/ yet.

**Acceptance:**
- `grep -r 'sdweddingdirectory' wp-content/plugins/sdwd-couple/modules/todo-list/` returns zero matches.
- PHP syntax check both files: `docker exec -w /var/www/html wp_ssh php -l /var/www/html/wp-content/plugins/sdwd-couple/modules/todo-list/todo-list.php` → "No syntax errors".

**Commit:** `P2-PORT-04 (1/7): port todo-list main + database PHP from legacy`

---

## TASK 3 — Port Todo-List AJAX handlers

**Do:**
1. Copy `legacy/ajax/index.php` → `sdwd-couple/modules/todo-list/ajax.php`
2. Apply Pattern A (renames + text-domain).
3. Apply Pattern B: every `check_ajax_referer` 1st arg → `sdwd_todo_nonce`, 2nd arg → `'nonce'`.
4. Wrap every `$_POST[...]` read with `wp_unslash()` before sanitize.
5. Ensure every response uses `wp_send_json_success` / `wp_send_json_error`.

**Acceptance:**
- `grep -c "sdwd_todo_nonce" wp-content/plugins/sdwd-couple/modules/todo-list/ajax.php` ≥ 1.
- `grep -c "wp_unslash" ajax.php` ≥ (number of `$_POST` reads in file).
- PHP syntax check passes.

**Commit:** `P2-PORT-04 (2/7): port todo-list AJAX handlers with sdwd_todo_nonce`

---

## TASK 4 — Register Todo-List module + add nonce to sdwd_dash

**Do:**
1. In `wp-content/plugins/sdwd-couple/sdwd-couple.php`, find the block that `require_once`'s reviews/request-quote/wishlist/checklist/budget. Add at end of block:
   ```php
   require_once SDWD_COUPLE_PATH . 'modules/todo-list/todo-list.php';
   ```
2. In `todo-list.php`, near top (after docblock):
   ```php
   defined( 'ABSPATH' ) || exit;
   require_once __DIR__ . '/database.php';
   require_once __DIR__ . '/ajax.php';
   ```
3. In `wp-content/themes/sandiegoweddingdirectory/functions.php`, find the `sdwd_dash` localize block (search `wishlist_nonce`). Add before closing `] );`:
   ```php
   'todo_nonce'      => wp_create_nonce( 'sdwd_todo_nonce' ),
   ```
   Preserve existing alignment pattern.

**Acceptance:**
- No fatal errors in `docker exec wp_app tail /var/www/html/wp-content/debug.log`.
- `curl -sI -o /dev/null -w "HTTP %{http_code}\n" http://localhost:8080/` → 200.
- On a dashboard page, `view-source:` shows `sdwd_dash` object contains `todo_nonce`.

**Commit:** `P2-PORT-04 (3/7): register todo-list module + todo_nonce in sdwd_dash`

---

## TASK 5 — Port Todo-List frontend PHP (Pattern C)

**Do:**
1. Copy `legacy/couple-file/index.php` → `sdwd-couple/modules/todo-list/couple-file.php`
2. Apply Pattern A + Pattern C exhaustively.
3. Remove every `style="..."` attribute; track each removal in a top-of-file comment `// MOVED TO CSS: {selector} { {declaration} }`.
4. Remove inline `<script>`; track as `// MOVED TO JS: {description}`.
5. All echoes use `esc_*` / `wp_kses_post`.
6. BEM root class for this module: `.todo-list__*` (e.g. `.todo-list__toolbar`, `.todo-list__item`, `.todo-list__form`).

**Acceptance:**
- `grep -cE 'class="[^"]*(col-[a-z]*-[0-9]|d-flex|btn-primary|btn-sm|mb-[0-9]|mt-[0-9]|row[^-])' couple-file.php` == 0.
- `grep -c 'style="' couple-file.php` == 0 (except a dynamic bg-image exception if any).
- `grep -c '<script' couple-file.php` == 0.
- PHP syntax check passes.

**Commit:** `P2-PORT-04 (4/7): strip Bootstrap + inline scripts from todo-list couple-file`

---

## TASK 6 — Port Todo-List frontend JS (Pattern D)

**Do:**
1. Copy `legacy/couple-file/script.js` → `sdwd-couple/modules/todo-list/script.js`
2. Apply Pattern D exhaustively.
3. Replace every `$.ajax({...})` with `fetch()` + `.then(r => r.json())`. Use `new FormData()` for POST.
4. Nonce: `window.sdwd_dash.todo_nonce`. URL: `window.sdwd_dash.url`.
5. Wrap file body in IIFE `(function () { ... })()` if not already.
6. Top comment: `// Todo-List module — ported from legacy plugin. jQuery-free.`
7. If the legacy script used jQuery `.html()` to inject rendered HTML, refactor to build DOM nodes with `document.createElement` + `.textContent` for text, + `.appendChild`. Do not assign arbitrary user data as raw HTML.

**Acceptance:**
- `grep -cE '\$\(|jQuery\(' sdwd-couple/modules/todo-list/script.js` == 0.
- `grep -c 'fetch(' script.js` ≥ (number of AJAX calls in legacy version).

**Commit:** `P2-PORT-04 (5/7): rewrite todo-list script.js jQuery → vanilla ES6+`

---

## TASK 7 — Port Todo-List CSS (scoped + tokenized)

**Do:**
1. Copy `legacy/couple-file/style.css` → `sdwd-couple/modules/todo-list/style.css`
2. Replace raw hex colors with tokens from `wp-content/themes/sandiegoweddingdirectory/assets/css/foundation.css :root` (grep `--sdwd-*`). If no token fits, pick closest + add `/* TODO: founder to review color token fit */`.
3. Replace raw px margins/paddings/gaps with tokens (`--sdwd-row-gap`, `--sdwd-section-gap-md`, `--gutter`). Values ≤ 4px may stay literal.
4. Remove all `!important` — find specificity instead.
5. Replace any remaining Bootstrap-ish selectors (`.row`, `.col-md-*`, `.btn-primary`) with the BEM-lite equivalent you used in Task 5.
6. Scope under `.todo-list` root.

**Acceptance:**
- `grep -cE '^\s*[^/]*#[0-9a-fA-F]{3,8}[;)\s]' style.css` == 0 (no raw hex).
- `grep -c '!important' style.css` == 0.
- `grep -cE 'col-|btn-primary|d-flex' style.css` == 0.

**Commit:** `P2-PORT-04 (6/7): todo-list CSS scoped + tokenized`

---

## TASK 8 — Wire Todo-List enqueue

**Do:**
1. In `sdwd-couple/modules/todo-list/todo-list.php`, add `add_action( 'wp_enqueue_scripts', ... )`:
   - If `function_exists('sdwdv2_is_dashboard_page') && sdwdv2_is_dashboard_page()`:
     - `wp_enqueue_style( 'sdwd-todo-list', plugins_url( 'style.css', __FILE__ ), [ 'sdwdv2-dashboard' ], filemtime( __DIR__ . '/style.css' ) );`
     - `wp_enqueue_script( 'sdwd-todo-list', plugins_url( 'script.js', __FILE__ ), [], filemtime( __DIR__ . '/script.js' ), true );`

**Acceptance:**
- Load a dashboard page; view source shows `sdwd-todo-list-css` and `sdwd-todo-list-js` handles.
- No duplicate enqueues.

**Commit:** `P2-PORT-04 (7/7): enqueue todo-list assets on dashboard pages`

---

## TASK 9 — E2E verify Todo-List

**Do:**
1. Log in at `http://localhost:8080/wp-login.php` as `couple` / `couple`.
2. Navigate to wherever Todo-List UI is exposed (likely `/couple-dashboard/` — if no nav exists yet, skip UI steps, only verify no fatals).
3. If UI reachable: add a todo, verify AJAX returns `{success: true}`, reload page, verify persistence.
4. Check `docker exec wp_app tail -20 /var/www/html/wp-content/debug.log` — no fatals referencing todo-list.

**Acceptance:**
- No fatals.
- If UI reachable: happy path works E2E.

**Commit:** `P2-PORT-04 (verify): todo-list module loads clean` (empty commit allowed if no file changes).

---

## TASKS 10–17 — Repeat for RSVP (P2-PORT-02)

Follow Tasks 1-9 exactly, substituting:
- legacy source: `sdweddingdirectory-rsvp/`
- new target: `rsvp/`
- nonce action: `sdwd_rsvp_nonce`
- BEM prefix: `.rsvp__*`
- sdwd_dash key: `rsvp_nonce`
- Commit prefix: `P2-PORT-02 (N/7)`

---

## TASKS 18–25 — Repeat for Guest-List (P2-PORT-03)

Same pattern, substituting:
- legacy: `sdweddingdirectory-guest-list/`
- new: `guest-list/`
- nonce: `sdwd_guest_nonce`
- BEM: `.guest-list__*`
- sdwd_dash key: `guest_nonce`
- Commit prefix: `P2-PORT-03 (N/7)`

**Special note:** Guest-List likely has CSV import. If legacy uses `SplFileObject` or a CSV parser, port as-is (server-side, not front-end). Do NOT add new CSV libraries — PHP built-ins only.

---

## TASKS 26–33 — Repeat for Couple Website (P2-PORT-05)

Same pattern, substituting:
- legacy: `sdweddingdirectory-couple-website/`
- new: `website/`
- nonce: `sdwd_website_nonce`
- BEM: `.website__*`
- sdwd_dash key: `website_nonce`
- Commit prefix: `P2-PORT-05 (N/7)`

**Extra scope note:** This module likely includes a template system where couples pick a wedding-website template (the "Website Template One" in PROJECT.md §4). Port whatever ship-ready template exists, but do NOT rewrite the template-selection UI — that's Phase 5 P5-THEME-02. Ship 1 working template, defer 5 more to post-launch per B5 from re-synthesis conflict resolution.

---

## TASKS 34–42 — Seating-Chart port (P2-PORT-01) — hardest, most caveats

**READ FIRST:** `.planning/discoveries/P2-DISCO-01-seating-chart-hunt.md`

Same pattern but with substitutions:
- legacy: `sdweddingdirectory-seating-chart/`
- new: `seating-chart/`
- nonce: `sdwd_seating_nonce`
- BEM: `.seating-chart__*`
- sdwd_dash key: `seating_nonce`
- Commit prefix: `P2-PORT-01 (N/7)`

**Known facts from discovery doc:**
- Backend (database 322 lines, ajax 63 lines, main 128 lines) is CLEAN — straight Pattern A/B port.
- `couple-file/index.php` 200 lines with ~60 Bootstrap class uses (Pattern C scope).
- `couple-file/script.js` 932 lines with 68 jQuery calls. The drag-drop logic uses jQuery UI-style `.draggable()` / `.droppable()` which have no vanilla 1:1. **Rewrite using native PointerEvent API** (`pointerdown` + `pointermove` + `pointerup` + absolute positioning). This is the hardest rewrite in Phase 2 — budget 2-4 hours for just drag-drop.
- `couple-file/style.css` 225 lines including a 23-line print-layout block (`#sdwc-print-layout-card ~ *`, hide dashboard chrome on print) — preserve verbatim, just scope + tokenize.

**Extra sub-task for seating-chart** (between Task 6 and 7 equivalent): Rewrite drag-drop engine. May warrant 2-3 separate commits: (a) pointer-based drag for table shapes, (b) drop zones for floor plan grid, (c) multi-select + snap-to-grid.

---

## TASK 43 — P2-PARITY-02 — Flatten venue-location taxonomy

**Do:**
1. Open `wp-content/plugins/sdwd-core/includes/taxonomies.php`. Find `register_taxonomy( 'venue-location', ...)`. Change `hierarchical` key from `true` to `false`.
2. Flush rewrites: `docker exec -w /var/www/html wp_ssh wp --allow-root rewrite flush`
3. Add a one-off migration function in `sdwd-core/includes/migrate.php`:
   ```php
   function sdwd_flatten_venue_locations() {
       if ( get_option( 'sdwd_venue_location_flat_done' ) ) return;
       $terms = get_terms([ 'taxonomy' => 'venue-location', 'hide_empty' => false, 'fields' => 'ids' ]);
       foreach ( $terms as $tid ) {
           wp_update_term( $tid, 'venue-location', [ 'parent' => 0 ] );
       }
       update_option( 'sdwd_venue_location_flat_done', true );
   }
   ```
4. Run: `docker exec -w /var/www/html wp_ssh wp --allow-root eval 'sdwd_flatten_venue_locations();'`
5. Verify: `docker exec -w /var/www/html wp_ssh wp --allow-root term list venue-location --fields=term_id,name,parent --format=table` — every `parent` == 0.
6. `/venues/{location-slug}/` still returns HTTP 200 post-flatten.

**Commit:** `P2-PARITY-02: flatten venue-location taxonomy to non-hierarchical`

---

## TASK 44 — P2-MIGRATE-01 — Run migrate.php, verify

**Do:**
1. Read `wp-content/plugins/sdwd-core/includes/migrate.php` top-to-bottom. Note what it migrates, rollback path, idempotence guards.
2. Snapshot DB: `docker exec wp_db mysqldump -uroot -proot wordpress > /tmp/pre-migrate.sql`
3. Run: `docker exec -w /var/www/html wp_ssh wp --allow-root eval-file /var/www/html/wp-content/plugins/sdwd-core/includes/migrate.php`
4. Watch for errors in stdout + `/var/www/html/wp-content/debug.log`.
5. If script processes vendors/venues/couples in batches, verify batch size ≤ 50 and `sdwd_migration_version` (or similar) option prevents double-runs.
6. If batch-50 or idempotence guard missing, ADD both. Do NOT remove or change existing business logic.

**Acceptance:**
- No fatal errors.
- `sdwd_migration_version` option set post-run.
- Running migrate.php a SECOND time produces no data changes.

**Commit:** `P2-MIGRATE-01: verify migrate.php round-trip + ensure idempotence`

---

## TASKS 45–50 — P2-SEC-01 through 06 — Security hardening

**Shared gate pattern:** Every enforcement added MUST be wrapped as:
```php
if ( ! defined( 'SDWD_DEV_MODE' ) || ! SDWD_DEV_MODE ) {
    // enforcement here
}
```
Preserves `couple/couple`, `vendor/vendor`, `venue/venue` local dev accounts. See `.planning/CLAUDE-GSD.md` § "Dev environment accounts" for full spec.

### TASK 45 — P2-SEC-01 Password strength ≥8 chars

**Do:**
1. Sites to update:
   - `sdwd-core/includes/admin/couple-meta.php` (admin metabox save)
   - `sdwd-core/includes/admin/vendor-meta.php` (admin metabox save)
   - `sdwd-core/includes/admin/venue-meta.php` (admin metabox save)
   - `sdwd-core/includes/dashboard.php` (frontend password-change handler)
2. In each, BEFORE the `wp_set_password` (or equivalent), gate-wrap:
   ```php
   if ( ! defined( 'SDWD_DEV_MODE' ) || ! SDWD_DEV_MODE ) {
       if ( strlen( $new_password ) < 8 ) {
           // return error: WP_Error in admin, wp_send_json_error in AJAX
       }
   }
   ```
3. Error message: `__( 'Password must be at least 8 characters.', 'sdwd-core' )`.

**Acceptance:** Changing password to `couple` (6 chars) in local dev still works (gate bypasses because SDWD_DEV_MODE=true in local wp-config). In prod, fails with length error.

**Commit:** `P2-SEC-01: password strength ≥8 chars gated by SDWD_DEV_MODE`

### TASK 46 — P2-SEC-02 current_password verification on frontend change

**Do:**
1. Open `sdwd-core/includes/dashboard.php:127-131` (password-change handler).
2. Add POST read for `current_password`.
3. Gate-wrap: `wp_check_password( $current_password, $user->user_pass, $user->ID )`.
4. If wrong, `wp_send_json_error` with `__( 'Current password is incorrect.', 'sdwd-core' )`.
5. Add `current_password` field to the relevant frontend password-change form in theme (search user-template/ or template-parts/ for the UI).

**Acceptance:** Frontend password change requires the old password in prod; bypassed in local dev.
**Commit:** `P2-SEC-02: current_password verification on frontend change, SDWD_DEV_MODE-gated`

### TASK 47 — P2-SEC-03 wp_unslash on admin password metabox saves

**Do:**
1. `sdwd-core/includes/admin/couple-meta.php:137` — find password read.
2. `sdwd-core/includes/admin/vendor-meta.php:234` — same.
3. `sdwd-core/includes/admin/venue-meta.php:291` — same.
4. In each: `$new_password = $_POST['password_field_name'] ?? '';` becomes `$new_password = wp_unslash( $_POST['password_field_name'] ?? '' );`
5. **NOT gated by SDWD_DEV_MODE** — correctness fix. Apply unconditionally.

**Acceptance:** `wp_unslash` appears near the password line in each of the 3 files.
**Commit:** `P2-SEC-03: wp_unslash admin password saves (prevents silent login break)`

### TASK 48 — P2-SEC-04 Rate limit login + password-change

**Do:**
1. In `sdwd-core/includes/auth.php` (login) and `sdwd-core/includes/dashboard.php` (password-change), add rate-limit check BEFORE processing.
2. Use transients. Key = `'sdwd_ratelimit_' . md5( ($_SERVER['REMOTE_ADDR'] ?? '') . $action_name )`. Window 15 min, max 5 attempts.
3. Increment counter each attempt. If > 5, `wp_send_json_error` with `__( 'Too many attempts. Try again in 15 minutes.', 'sdwd-core' )`.
4. Gate with SDWD_DEV_MODE — no limit in local dev.

**Acceptance:** 6 bad login attempts in 5 min return the "Too many" error in prod mode. Unlimited in local dev.
**Commit:** `P2-SEC-04: transient-counter rate limit on login + password-change (dev-mode gated)`

### TASK 49 — P2-SEC-05 Extract inline JS from admin claim metabox

**Do:**
1. Open `sdwd-core/includes/claim.php:128-144`. Has an inline `<script>` with `$post->ID` and `$nonce` interpolated into JS.
2. Move this JS to `sdwd-core/assets/admin.js` (new file if missing).
3. In claim.php, replace inline `<script>` with `wp_enqueue_script` + `wp_localize_script` passing `post_id` and `nonce` as a window object (e.g. `sdwd_admin_claim`).
4. Escape both variables when emitting in any HTML data attributes: `esc_attr( $post->ID )` and `esc_attr( $nonce )`. `wp_localize_script` handles JS object escaping.

**Acceptance:**
- `grep -c '<script' wp-content/plugins/sdwd-core/includes/claim.php` == 0.
- `wp-content/plugins/sdwd-core/assets/admin.js` exists + enqueued on admin claim screens.

**Commit:** `P2-SEC-05: extract inline JS from admin claim metabox into enqueued admin.js`

### TASK 50 — P2-SEC-06 Fix repeater-field slash accumulation

**Do:**
1. Open `sdwd-core/includes/admin/vendor-meta.php:157-205` and `venue-meta.php:206-254`.
2. These blocks handle repeater fields (social links, pricing tiers, business hours). Bug: values stored from `$_POST` as-is, re-displayed next page load where WP re-slashes them → escape accumulation on every save.
3. Fix: on save, apply `wp_unslash()` to every string value in the repeater array before `update_post_meta`. On render (in the frontend profile template), apply `esc_attr` only — no additional `wp_kses` or re-escaping.
4. **NOT gated** — correctness fix. Apply unconditionally.

**Acceptance:** Edit a repeater field containing `'`, save, reload the edit page — value displays exactly once (no escape accumulation).
**Commit:** `P2-SEC-06: wp_unslash repeater-field saves (fix escape accumulation)`

---

## Final verification (after all 50 tasks done)

```bash
cd ~/Documents/Development/WebDevelopment/wp-docker
~/.composer/vendor/bin/phpcs --report=summary     # count should drop from 126 toward near-zero
curl -sI -o /dev/null -w "HTTP %{http_code}\n" http://localhost:8080/     # 200
docker exec wp_app tail -50 /var/www/html/wp-content/debug.log            # no new fatals
```

Then:
- Log in as `couple` / `couple` at `/wp-login.php` — dashboard loads, no 403s
- Log in as `vendor` / `vendor` — vendor-dashboard loads
- Log in as `venue` / `venue` — venue-dashboard loads

---

## Handoff back to founder

After all 50 tasks complete, either:
- Founder runs `/gsd-transition` in the GSD window to formalize state, OR
- Founder manually updates `.planning/STATE.md` + `REQUIREMENTS.md` from the git log:
  ```
  cd ~/Documents/Development/WebDevelopment/wp-docker
  git log --oneline main ^1148cf1^ | head -80
  ```

---

*Plan written by Claude Code 2026-04-24 under context pressure. Total tasks: 50. Each designed to fit in one Codex prompt. Run sequentially, commit after each, verify acceptance before proceeding. Related spec: `.planning/phases/02-plugin-closeout/NEXT-VENUE-LOCATION-FILTERS.md`.*
