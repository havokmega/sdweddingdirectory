# Phase 1: Close in-progress + cleanup — Pattern Map

**Mapped:** 2026-04-23
**Files analyzed:** 9 distinct modification targets + 1 deletion + ~80 sweep targets
**Analogs found:** 9 / 9 (every modification target has a strong existing analog inside the theme)

## Overview

Phase 1 is a HOW-not-WHAT phase: every cleanup item has a pre-specified file + line + directive in CONCERNS.md, and every "build" item is a 95%-done touch-up. Pattern mapping confirmed:

- The 404 page already has matching button-row analogs (`home-vendors__buttons`, `home-realweddings__cta`, `home-inspiration__cta`) — copy `display: flex; gap: var(--sdwd-row-gap)`-style markup.
- The home-page category-search dropdown markup AND JS handler ALREADY EXIST in `front-page.php` + `app.js`. The most likely root cause of P1-FIX-02 is a missing icon class (`icon-chevron-down` is referenced in markup but not defined in `sdwd-icons` font — only `icon-chevron-left` and `icon-angle-down` exist) and/or a CSS or scope issue. **The dropdown is not "broken"; it is wired but appears broken.** Investigation must confirm root cause before committing edits.
- The registration form retarget (P1-FIX-01) has an exact analog in `template-parts/modals/couple-registration.php` which already uses the modern `sdwd_register` action via `data-sdwd-form="register"` — the planning-hero form is the legacy outlier and should be updated to match modal field names exactly (`first_name`, `last_name`, `email`, `password`, `account_type=couple`, `wedding_date`). The modal pattern uses a per-form data-attribute that the modals.js handler converts into `action = 'sdwd_' + dataset.sdwdForm`; the planning-hero form is a non-modal one-off and will keep its own inline submit handler, but the field names + nonce + action must match.
- The text-domain sweep is ~80 theme files with `'sdweddingdirectory-v2'` or `'sdweddingdirectory'` — **but ZERO plugin files** carry those legacy strings. Plugin files already correctly use their own slugs (`'sdwd-core'`, `'sdwd-couple'`). Per D-15, the founder authorized plugin-file edits for the sweep; per the verification (`grep -rln "'sdweddingdirectory" wp-content/plugins/sdwd-core/ wp-content/plugins/sdwd-couple/` returns zero), there is nothing to change in plugin files. The sweep is effectively theme-only despite the explicit authorization.

## File Classification

| New/Modified File | Role | Data Flow | Closest Analog | Match Quality |
|-------------------|------|-----------|----------------|---------------|
| `wp-content/themes/sandiegoweddingdirectory/404.php` | template (orchestrator) | request-response | `wp-content/themes/sandiegoweddingdirectory/front-page.php` (button rows) + own current contents | exact (in-place edit) |
| `wp-content/themes/sandiegoweddingdirectory/assets/css/pages/static.css` | CSS (page-scoped) | n/a | `assets/css/pages/home.css` §606-621 `.home-vendors__buttons` | exact |
| `wp-content/themes/sandiegoweddingdirectory/functions.php` | bootstrap (setup, enqueue, filters) | n/a | own current contents (delete-only) | exact (in-place edit) |
| `wp-content/themes/sandiegoweddingdirectory/assets/css/layout.css` §687-690 | CSS (shared) | n/a | n/a (delete-only — no pattern needed) | n/a |
| `wp-content/themes/sandiegoweddingdirectory/template-parts/planning/planning-hero.php` | template part (form) | request-response (AJAX) | `template-parts/modals/couple-registration.php` (field names + action) + `auth.php:18-120` (handler contract) | exact |
| `wp-content/themes/sandiegoweddingdirectory/style.css` | metadata | n/a | own current header (verify-only) | n/a |
| `wp-content/themes/sandiegoweddingdirectory/front-page.php` (P1-FIX-02) | template (orchestrator) | event-driven (UI dropdown) | own current §159-247 markup + `app.js:184-242` handler | exact (in-place edit, scoped to dropdown bug) |
| `wp-content/themes/sandiegoweddingdirectory/assets/css/pages/home.css` (P1-FIX-02) | CSS (page-scoped) | n/a | own current §261-357 `.hero__dropdown-*` rules | exact (in-place edit, scoped to dropdown bug) |
| `wp-content/themes/sandiegoweddingdirectory/assets/js/app.js` (P1-FIX-02) | JS (vanilla) | event-driven | own current §184-242 dropdown handler | exact (in-place edit, scoped to dropdown bug) |
| `Icon\r` (repo root) | macOS artifact | n/a | n/a (rm) | n/a |
| Theme template files (`page-*.php`, `single-*.php`, `archive-*.php`, `taxonomy-*.php`, `template-parts/**`, `user-template/**`) — text-domain sweep | template/template-part | n/a | `template-parts/modals/couple-registration.php` (correct domain examples) | exact pattern |
| Plugin files (`sdwd-core/**/*.php`, `sdwd-couple/**/*.php`) — text-domain sweep | plugin module | n/a | NO-OP (zero legacy strings present) | n/a |

---

## Pattern Assignments

### `404.php` (template, request-response) — P1-BUILD-02

**Analog:** `front-page.php` (button-row pattern) + `static.css` lines 117-143 (404-page page CSS already partially defined under `.error-page__*` BEM block — there is a class-name mismatch between `404.php` markup `.error-404__*` and `static.css` `.error-page__*`; the executor must either rename markup to `.error-page__*` or add `.error-404__*` rules. Recommendation: rename markup to match the existing CSS BEM block so `static.css` doesn't need a parallel set of rules.)

**Real SVG asset (verified):** `wp-content/themes/sandiegoweddingdirectory/assets/images/404-error-page/404_error.svg` exists (4 KiB, dated 2026-04-06).

**SVG inclusion pattern** — current placeholder block at `404.php:11-16` to be replaced:

```php
<?php // BEFORE — placeholder inline SVG ?>
<div class="error-404__graphic">
    <svg class="error-404__svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 160" width="200" height="160" aria-hidden="true">
        <circle cx="100" cy="80" r="70" fill="none" stroke="currentColor" stroke-width="2" opacity="0.2"/>
        <text x="100" y="95" text-anchor="middle" font-size="48" font-weight="700" fill="currentColor" opacity="0.3">404</text>
    </svg>
</div>

<?php // AFTER — real asset, mirrors the home-page image-include pattern at front-page.php:310, 315, 363 ?>
<div class="error-page__graphic">
    <img
        src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/404-error-page/404_error.svg' ); ?>"
        alt=""
        width="280"
        height="200"
        loading="lazy"
        decoding="async"
    >
</div>
```

Why `<img src=...svg>` rather than inline SVG: the home page uses this exact pattern for category-card icons (`front-page.php:315`) and planning-tool icons (`:363`) — `<img loading="lazy" decoding="async" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/...' ); ?>" alt="..." width=... height=...>`. Stay consistent.

**Button-row pattern** (5 buttons, all `.btn .btn--outline`, right-aligned, token-based gap):

The closest analog is `front-page.php:325-341` `.home-vendors__buttons` — a horizontal flex row of `.btn--outline` buttons with token gap:

```php
<?php // ANALOG — front-page.php:325-341 (home-vendors button row) ?>
<div class="home-vendors__buttons">
    <?php foreach ( $vendor_buttons as $vb ) : ?>
        <a class="btn btn--outline home-vendors__button" href="<?php echo esc_url( home_url( $vb['path'] ) ); ?>"><?php echo esc_html( $vb['label'] ); ?></a>
    <?php endforeach; ?>
    <a class="btn btn--outline home-vendors__button" href="<?php echo esc_url( home_url( '/vendors/' ) ); ?>"><?php esc_html_e( 'Show All', 'sdweddingdirectory' ); ?></a>
</div>
```

CSS for that row at `assets/css/pages/home.css:606-613`:

```css
.home-vendors__buttons {
  display: flex;
  flex-wrap: nowrap;
  gap: 10px;             /* hardcoded — note: home.css has hardcoded values; the 404 row should use tokens per D-06 */
  justify-content: flex-start;
  width: 100%;
  overflow: hidden;
}
```

**Apply for the 404:** five `<a class="btn btn--outline">` siblings inside the existing `.error-page__buttons` (or rename to `.error-404__nav` if keeping the markup name) container. Use `justify-content: flex-end` (per D-06) and a token-based gap. Available spacing tokens in `foundation.css :root`:

- `--sdwd-row-gap: 32px` (lines 98) — too big for a 5-button row
- `--sdwd-title-gap: 24px` (line 99) — also too big
- `--sdwd-section-gap-sm: 16px` / `--sdwd-section-gap-md: 24px` / `--sdwd-section-gap-lg: 48px` (lines 94-96)
- `--gutter: 20px` (line 102)

**Recommended token:** `var(--sdwd-section-gap-sm)` (16px) for inter-button gap — smaller than `--sdwd-row-gap` (32px) which is too large for a tight button row. Confirm at execution; if it visually overflows on mobile, use `var(--gutter)` (20px). Use `var(--sdwd-row-gap)` for the gap *above* the button row (separating it from the description copy).

**Final 404 button-row markup:**

```php
<nav class="error-page__buttons" aria-label="<?php esc_attr_e( 'Helpful links', 'sandiegoweddingdirectory' ); ?>">
    <a class="btn btn--outline" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'sandiegoweddingdirectory' ); ?></a>
    <a class="btn btn--outline" href="<?php echo esc_url( home_url( '/wedding-planning/' ) ); ?>"><?php esc_html_e( 'Planning Tools', 'sandiegoweddingdirectory' ); ?></a>
    <a class="btn btn--outline" href="<?php echo esc_url( home_url( '/venues/' ) ); ?>"><?php esc_html_e( 'Venues', 'sandiegoweddingdirectory' ); ?></a>
    <a class="btn btn--outline" href="<?php echo esc_url( home_url( '/vendors/' ) ); ?>"><?php esc_html_e( 'Vendors', 'sandiegoweddingdirectory' ); ?></a>
    <a class="btn btn--outline" href="<?php echo esc_url( home_url( '/real-weddings/' ) ); ?>"><?php esc_html_e( 'Real Weddings', 'sandiegoweddingdirectory' ); ?></a>
</nav>
```

Note: text-domain in the `__()` call updated to `'sandiegoweddingdirectory'` per the P1-CLEAN-04 sweep.

**Conditional CSS enqueue (already wired — no change):** `functions.php:138-141`:

```php
if ( is_page_template( [ 'page-about.php', 'page-contact.php', 'page-faqs.php', 'page-policy.php', 'page-our-team.php' ] )
     || is_404() || is_search() || is_singular( 'team' ) || is_singular( 'changelog' ) ) {
    wp_enqueue_style( 'sdwdv2-static', $theme_uri . '/assets/css/pages/static.css', [ 'sdwdv2-layout' ], $asset_version( '/assets/css/pages/static.css' ) );
}
```

`is_404()` → loads `static.css`. The 404 button-row CSS belongs in `static.css`. No enqueue change.

---

### `static.css` (CSS, page-scoped) — P1-BUILD-02

**Analog:** Existing `.error-page__buttons` block at `static.css:138-143`:

```css
.error-page__buttons {
  display: flex;
  gap: 12px;                    /* hardcoded — replace with token */
  justify-content: center;      /* change to flex-end per D-06 */
  flex-wrap: wrap;
}
```

**Updated rule (token + right-align):**

```css
.error-page__buttons {
  display: flex;
  gap: var(--sdwd-section-gap-sm);   /* 16px — token-only per D-06 */
  justify-content: flex-end;          /* right-align per D-06 */
  flex-wrap: wrap;
  margin-top: var(--sdwd-row-gap);    /* 32px above the button row */
}
```

**Note on class-name parity:** The current `404.php` markup uses `.error-404__nav` / `.error-404__title` / `.error-404__desc` / `.error-404__graphic` / `.error-404__svg`. The existing `static.css` defines `.error-page__buttons` / `.error-page__graphic` / `.error-page__title` / `.error-page__desc`. **They don't match.** Choose one: either rename markup in `404.php` to `.error-page__*` (recommended, since `static.css` already has the partial CSS), or rename CSS in `static.css` to `.error-404__*`. The recommendation is to rename markup to match CSS — fewer lines change.

---

### `functions.php` (bootstrap) — P1-CLEAN-01, -02, -04, -05, -06

All edits are deletes or text-domain renames; no new code. Concrete pre-specified edits per CONCERNS.md:

| Line(s) | Current | Action | REQ |
|---------|---------|--------|-----|
| 32 | `load_theme_textdomain( 'sdweddingdirectory-v2', ... )` | Replace `'sdweddingdirectory-v2'` → `'sandiegoweddingdirectory'` | P1-CLEAN-04 |
| 36, 37 | `__( 'Primary Menu', 'sdweddingdirectory-v2' )`, `__( 'Tiny Footer Menu', 'sdweddingdirectory-v2' )` | Same domain rename | P1-CLEAN-04 |
| 58, 59 | `add_image_size( 'sdweddingdirectory_img_600x470', ... )`, `add_image_size( 'sdweddingdirectory_img_360x480', ... )` | DELETE both lines + the comment at line 57 (`// Match legacy sizes used by plugins/templates.`) | P1-CLEAN-05 |
| 69, 71 | `__( 'Footer Column %d', 'sdweddingdirectory-v2' )`, `__( 'Footer widget area', 'sdweddingdirectory-v2' )` | Domain rename | P1-CLEAN-04 |
| 175-181 | `add_action( 'wp_print_styles', function () { wp_dequeue_style( 'sdweddingdirectory-fontello' ); ... }, 100 );` | DELETE the entire `add_action( 'wp_print_styles', ... )` block (4 dequeue calls + the function wrapper) | P1-CLEAN-02 |
| 229, 231 | `__( 'Blog Sidebar', 'sdweddingdirectory-v2' )`, `__( 'Sidebar for blog and category pages', 'sdweddingdirectory-v2' )` | Domain rename | P1-CLEAN-04 |
| 240-243 | `add_filter( 'sdweddingdirectory/post-cap/vendor', '__return_false' );` (with 3-line comment block above) | DELETE entire block (lines 239-243) | P1-CLEAN-06 |
| 245-254 | `add_filter( 'sdweddingdirectory_icon_library', function ( $args = [] ) { ... } );` | DELETE entire block (lines 245-254 + the comment at 245-247) | P1-CLEAN-01 |

**Text-domain sweep micro-pattern (mechanical):**

```php
// BEFORE (every theme file, all variants)
__( 'String', 'sdweddingdirectory-v2' )
esc_html__( 'String', 'sdweddingdirectory-v2' )
esc_attr__( 'String', 'sdweddingdirectory-v2' )
esc_html_e( 'String', 'sdweddingdirectory-v2' )
esc_attr_e( 'String', 'sdweddingdirectory-v2' )
_e( 'String', 'sdweddingdirectory-v2' )
__( 'String', 'sdweddingdirectory' )            // also legacy — same fix
esc_html_e( 'String', 'sdweddingdirectory' )    // also legacy — same fix

// AFTER (every theme file, every variant)
__( 'String', 'sandiegoweddingdirectory' )
esc_html__( 'String', 'sandiegoweddingdirectory' )
esc_attr__( 'String', 'sandiegoweddingdirectory' )
esc_html_e( 'String', 'sandiegoweddingdirectory' )
esc_attr_e( 'String', 'sandiegoweddingdirectory' )
_e( 'String', 'sandiegoweddingdirectory' )
```

**Mechanical sweep command sketch** (the planner can express this as either two `find … -exec sed` invocations or two PHP-aware editor passes — both work since the text-domain string is unique enough that no false positives are possible):

```bash
find wp-content/themes/sandiegoweddingdirectory -type f -name '*.php' \
  -exec sed -i.bak "s/'sdweddingdirectory-v2'/'sandiegoweddingdirectory'/g" {} \;
find wp-content/themes/sandiegoweddingdirectory -type f -name '*.php' \
  -exec sed -i.bak "s/'sdweddingdirectory'/'sandiegoweddingdirectory'/g" {} \;
# Then delete .bak files:
find wp-content/themes/sandiegoweddingdirectory -name '*.php.bak' -delete
```

**Plugin sweep:** Verified zero hits — `grep -rln "'sdweddingdirectory" wp-content/plugins/sdwd-core/ wp-content/plugins/sdwd-couple/` returns 0 files. Plugin files already use `'sdwd-core'` / `'sdwd-couple'` correctly per their `Text Domain:` headers. **The plugin sweep portion of P1-CLEAN-04 is a no-op.** D-15 authorization remains valid (founder approved plugin edits if needed); the commit message should note "verified plugin files have zero legacy text-domain occurrences; no plugin edits required."

---

### `layout.css` §687-690 (CSS, shared) — P1-CLEAN-03

**Analog:** N/A (delete-only).

**Current rule** (verified at `layout.css:687-690`):

```css
/* Hide plugin-injected Bootstrap modals until v2 modal system is built */
.modal.fade {
  display: none;
}
```

**Action:** Delete the comment + selector + braces (4 lines). Verify the surrounding `.back-to-top` rule at line 692 stays intact and the file's blank-line structure remains clean.

---

### `template-parts/planning/planning-hero.php` (template-part, request-response) — P1-FIX-01

**Analog 1 (field names + action contract):** `template-parts/modals/couple-registration.php` (the canonical modern registration form that already targets `sdwd_register`).

**Analog 2 (handler contract):** `wp-content/plugins/sdwd-core/includes/auth.php:18-121` — the `sdwd_handle_register` function. Field names that the handler reads:

```php
// From auth.php:23-27 — these are AUTHORITATIVE field names
$account_type = sanitize_text_field( wp_unslash( $_POST['account_type'] ?? '' ) );
$first_name   = sanitize_text_field( wp_unslash( $_POST['first_name'] ?? '' ) );
$last_name    = sanitize_text_field( wp_unslash( $_POST['last_name'] ?? '' ) );
$email        = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
$password     = $_POST['password'] ?? '';
// Couple-specific (auth.php:93-100):
$wedding_date = sanitize_text_field( wp_unslash( $_POST['wedding_date'] ?? '' ) );
$couple_type  = sanitize_text_field( wp_unslash( $_POST['couple_type'] ?? '' ) );
```

**Auth response contract** (from `auth.php:117-120`):

```php
wp_send_json_success( [
    'message'  => __( 'Account created successfully!', 'sdwd-core' ),
    'redirect' => $redirects[ $account_type ] ?? home_url(),
] );
```

This returns `{success: true, data: {message, redirect}}` (standard WP `wp_send_json_success` envelope). The current planning-hero JS (`planning-hero.php:334-345`) expects a flat `{message, notice, redirect, redirect_link}` payload. **This is a contract mismatch the executor MUST resolve at implementation time.** Two options:

- **Option A (preferred — match the modals.js pattern):** Update `planning-hero.php`'s inline JS to read `payload.success` / `payload.data.message` / `payload.data.redirect` (mirror `modals.js:99-112`). Smaller blast radius — keeps `auth.php` untouched.
- **Option B:** Add a payload-shape adapter to `auth.php`. Larger blast radius — affects modal flows too.

Choose Option A unless something else forces B.

**Hidden input retarget pattern — exact field-by-field changes** (concrete excerpts):

```php
<?php // BEFORE — planning-hero.php:62-67 (legacy field names) ?>
<input type="hidden" name="action" value="sdweddingdirectory_couple_register_form_action" />
<input type="hidden" name="sdweddingdirectory_couple_registration_security" value="<?php echo esc_attr( wp_create_nonce( 'sdweddingdirectory_couple_registration_security' ) ); ?>" />
<input type="hidden" name="redirect_link" value="<?php echo esc_url( home_url( '/couple-dashboard/' ) ); ?>" />
<input type="hidden" name="sdweddingdirectory_couple_register_first_name" value="" />
<input type="hidden" name="sdweddingdirectory_couple_register_last_name" value="" />
<input type="hidden" name="sdweddingdirectory_couple_register_username" value="" />
<input type="hidden" name="sdweddingdirectory_couple_register_password" value="" />
<input type="hidden" name="sdweddingdirectory_register_couple_person" value="Planning my wedding" />

<?php // AFTER — match auth.php field names exactly ?>
<input type="hidden" name="action" value="sdwd_register" />
<input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'sdwd_auth_nonce' ) ); ?>" />
<input type="hidden" name="account_type" value="couple" />
<input type="hidden" name="first_name" value="" />
<input type="hidden" name="last_name" value="" />
<input type="hidden" name="password" value="" />
<input type="hidden" name="couple_type" value="planning_wedding" />
```

Notes:
- `wedding_date` is provided by the visible `<input type="date" name="planning-hero...">` field — rename its `name` attribute (currently `sdweddingdirectory_couple_register_wedding_date`) to `wedding_date`.
- `email` is provided by the visible Step-1 email input — rename its `name` from `sdweddingdirectory_couple_register_email` to `email`.
- `redirect_link` hidden input is no longer needed; `auth.php:111-115` returns the right redirect server-side. Keep it only if Option A's JS reads it as a fallback.
- `sdweddingdirectory_couple_register_full_name` (the visible name input at line 79) stays as-is — it's a client-side UX field that gets split by `populateHiddenFields()` into the new `first_name` / `last_name` hidden inputs. No need to rename it; the JS must still read it by its current name.
- The legacy `username` hidden input is dropped — `auth.php:56` uses email as username (`wp_create_user( $email, $password, $email )`).

**Visible input renames** (lines 79, 87, 112, 124):

```php
// Line 79 (full name — client-side only, JS-read; can keep name as-is or rename for clarity)
<input ... name="sdweddingdirectory_couple_register_full_name" ... />
// Optional rename to:
<input ... name="full_name" ... />

// Line 87 (email — server-read)
<input ... name="sdweddingdirectory_couple_register_email" ... />
// Rename to:
<input ... name="email" ... />

// Line 112 (location — currently client-side only, NOT in auth.php; keep as-is or rename)
<input ... name="sdweddingdirectory_couple_register_location" ... />
// Optional rename to:
<input ... name="wedding_location" ... />
// Note: auth.php does NOT consume location — it's a UX-collection-only field.
// If wedding location should persist, that's a Phase 2+ decision; do NOT add server-side handling here.

// Line 124 (wedding date — server-read)
<input ... name="sdweddingdirectory_couple_register_wedding_date" ... />
// Rename to:
<input ... name="wedding_date" ... />
```

**JS adjustments — `populateHiddenFields()` rewrite** (planning-hero.php:222-241):

```javascript
// BEFORE (lines 222-241) — reads legacy hidden field names
form.querySelector('[name="sdweddingdirectory_couple_register_first_name"]').value = firstName;
form.querySelector('[name="sdweddingdirectory_couple_register_last_name"]').value = lastName || firstName;
// ... + username generation (drop) + password generation
form.querySelector('[name="sdweddingdirectory_couple_register_password"]').value = pass;

// AFTER — matches new hidden field names
form.querySelector('[name="first_name"]').value = firstName;
form.querySelector('[name="last_name"]').value = lastName || firstName;
form.querySelector('[name="password"]').value = pass;
// DROP the username block entirely — auth.php uses email as username.
```

**JS submit handler — fix nonce field name + payload shape** (planning-hero.php:316-353):

```javascript
// BEFORE (line 319-320) — duplicates nonce as 'security'
var nonceValue = formData.get('sdweddingdirectory_couple_registration_security');
formData.append('security', nonceValue ? nonceValue : '');

// AFTER — auth.php expects the nonce in field 'nonce' (set via the hidden input rename above);
// no JS-side nonce duplication needed. Drop these two lines.

// BEFORE (line 335-342) — expects flat {message, notice, redirect, redirect_link}
.then(function (payload) {
    if (payload && payload.message) {
        setMessage(payload.message, payload.notice === 1 ? 'is-success' : 'is-error');
    }
    if (payload && payload.redirect === true) {
        var redirectUrl = payload.redirect_link ? payload.redirect_link : '<?php echo esc_url( home_url( '/couple-dashboard/' ) ); ?>';
        window.setTimeout(function () { window.location.href = redirectUrl; }, 350);
    }
})

// AFTER — match WP wp_send_json_success / wp_send_json_error envelope (mirrors modals.js:99-112)
.then(function (payload) {
    if (payload && payload.success) {
        setMessage((payload.data && payload.data.message) || '', 'is-success');
        if (payload.data && payload.data.redirect) {
            window.setTimeout(function () { window.location.href = payload.data.redirect; }, 350);
        }
    } else {
        setMessage((payload && payload.data && payload.data.message) || '<?php echo esc_js( __( 'Something went wrong.', 'sandiegoweddingdirectory' ) ); ?>', 'is-error');
    }
})
```

**Banner pattern preserved:** The `.planning-hero__form-message` element at line 71 stays. Its CSS at `assets/css/pages/planning.css:137-158` is unchanged; the JS continues to call `setMessage(msg, type)` where `type ∈ {'is-error', 'is-success', 'is-info'}` — those exact CSS modifier classes already exist:

```css
/* assets/css/pages/planning.css:148-158 — already in place */
.planning-hero__form-message.is-error   { color: var(--sdwd-alert); }
.planning-hero__form-message.is-success { color: var(--sdwd-accent-dark); }
.planning-hero__form-message.is-info    { color: var(--sdwd-muted); }
```

---

### `style.css` (theme metadata) — P1-CLEAN-04, P1-CLEAN-07

**Current header (verified):**

```css
/*
Theme Name: San Diego Wedding Directory
Theme URI: https://sdweddingdirectory.com/
Description: Custom WordPress theme for SD Wedding Directory — clean semantic markup, design tokens, no frameworks.
Author: SDWeddingDirectory
Version: 2.1.0
Text Domain: sandiegoweddingdirectory
*/
```

**Action:**
- `Text Domain: sandiegoweddingdirectory` — already correct; verify no change needed (P1-CLEAN-04).
- `Version: 2.1.0` — compare against `SDWEDDINGDIRECTORY_THEME_VERSION` constant (which `functions.php:9` sets via `wp_get_theme()->get( 'Version' )` — they're the same source). Bump if Phase 1 work warrants a minor bump (e.g., `2.1.1`). Per D-16, "bump if stale relative to the current release state" — Phase 1 closes legacy artifacts, so a `2.1.1` bump is appropriate.

---

### `front-page.php` + `home.css` + `app.js` (P1-FIX-02 — home-page category-search dropdown)

**Investigation summary (THIS IS WHAT THE PLANNER MUST KNOW):**

The dropdown markup AND JS handler ALREADY EXIST and appear functional on first read. CONCERNS.md does not flag this dropdown as broken; the bug surfaces in `PROJECT.md` §1 ("Searching by category is supposed to drop down a mega menu") and is captured here as P1-FIX-02. **The most likely root causes are NOT a missing handler but rather:**

1. **Missing icon class:** `front-page.php:168, 186, 206, 234` reference `<span class="hero__dropdown-arrow icon-chevron-down">`. The `sdwd-icons` font defines `icon-chevron-left` and `icon-angle-down` but **NOT `icon-chevron-down`** (verified: `grep -E "chevron|caret|arrow-down|angle-down" assets/library/sdwd-icons/style.css` returns only `icon-angle-down` and `icon-chevron-left`). Replace with `icon-angle-down` or add a new `icon-chevron-down` to the font's `style.css`. This is a markup-only fix in `front-page.php` (rename the class on 4 spans), assuming the font already includes a chevron-down glyph that is just missing its CSS rule. **Verify in the icon font at investigation time.**

2. **CSS clipping or z-index:** `assets/css/pages/home.css:308-321` `.hero__dropdown-panel` uses `position: absolute; top: 100%; left: -16px; right: -16px; z-index: 100`. If the parent `.hero__field--dropdown` has `overflow: hidden` upstream (e.g., from `.hero__form` or `.hero__form-fields`), the panel never escapes its container. Inspect with devtools at investigation time.

3. **CSS `[hidden]` overrides:** Line 323-325: `.hero__dropdown-panel[hidden] { display: none; }`. The JS at `app.js:208` toggles `panel.hidden = isOpen`. If a Bootstrap-era reset (or any inherited `display: block !important`) overrides `[hidden] { display: none }`, the panel won't show/hide correctly. Unlikely in this theme (`!important` is banned), but verify.

**Existing handler analog (already correct, no rewrite needed):**

```javascript
// app.js:184-242 — the dropdown handler IS in place and IS correct
document.querySelectorAll('.hero__dropdown-trigger').forEach(trigger => {
    const field = trigger.closest('.hero__field');
    if (!field) return;

    const panel = field.querySelector('.hero__dropdown-panel');
    const hiddenInput = field.querySelector('input[type="hidden"]');
    const textEl = trigger.querySelector('.hero__dropdown-text');

    if (!panel) return;

    trigger.addEventListener('click', () => {
        const isOpen = trigger.getAttribute('aria-expanded') === 'true';

        // Close all other open panels first
        document.querySelectorAll('.hero__dropdown-trigger[aria-expanded="true"]').forEach(other => {
            if (other !== trigger) {
                other.setAttribute('aria-expanded', 'false');
                const otherPanel = other.closest('.hero__field').querySelector('.hero__dropdown-panel');
                if (otherPanel) otherPanel.hidden = true;
            }
        });

        trigger.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
        panel.hidden = isOpen;
    });

    panel.querySelectorAll('.hero__dropdown-item').forEach(item => {
        item.addEventListener('click', () => {
            // Remove active state from siblings
            panel.querySelectorAll('.hero__dropdown-item').forEach(i => i.classList.remove('hero__dropdown-item--active'));
            item.classList.add('hero__dropdown-item--active');

            // Set hidden input value
            if (hiddenInput) hiddenInput.value = item.dataset.value || '';

            // Update trigger text
            if (textEl) {
                textEl.textContent = item.textContent.trim();
                textEl.classList.add('hero__dropdown-text--selected');
            }

            // Close panel
            trigger.setAttribute('aria-expanded', 'false');
            panel.hidden = true;
        });
    });
});

// Close dropdown panels on outside click (line 234-242)
document.addEventListener('click', (e) => {
    if (!e.target.closest('.hero__field--dropdown')) {
        document.querySelectorAll('.hero__dropdown-trigger[aria-expanded="true"]').forEach(trigger => {
            trigger.setAttribute('aria-expanded', 'false');
            const panel = trigger.closest('.hero__field').querySelector('.hero__dropdown-panel');
            if (panel) panel.hidden = true;
        });
    }
});
```

**Existing markup analog (already correct):**

```php
<?php // front-page.php:202-227 — the vendor-category dropdown ?>
<div class="hero__field hero__field--type hero__field--dropdown">
    <span class="hero__field-icon icon-search" aria-hidden="true"></span>
    <button class="hero__dropdown-trigger" type="button" aria-expanded="false" aria-haspopup="true" data-dropdown="vendor-category">
        <span class="hero__dropdown-text"><?php esc_html_e( 'Search by category', 'sdweddingdirectory' ); ?></span>
        <span class="hero__dropdown-arrow icon-chevron-down" aria-hidden="true"></span>
    </button>
    <input type="hidden" name="cat_id" value="">
    <div class="hero__dropdown-panel" data-panel="vendor-category" hidden>
        <div class="hero__dropdown-grid">
            <?php
            $vendor_cats = get_terms( [ 'taxonomy' => 'vendor-category', 'hide_empty' => false, 'orderby' => 'name', 'order' => 'ASC' ] );
            if ( ! is_wp_error( $vendor_cats ) ) :
                foreach ( $vendor_cats as $vcat ) : ?>
                    <button class="hero__dropdown-item" type="button" data-value="<?php echo esc_attr( $vcat->term_id ); ?>">
                        <?php echo esc_html( $vcat->name ); ?>
                    </button>
                <?php endforeach;
            endif;
            ?>
        </div>
    </div>
</div>
```

**Pattern guidance for the executor:**
- Do NOT rewrite the handler from scratch. The handler is the analog.
- DO investigate root cause at execution time (icon class? CSS clipping? z-index? specific browser?). The fix is likely a **single-line change** (`icon-chevron-down` → `icon-angle-down`) plus possibly one CSS adjustment.
- The home-page scoped unlock per D-19 is active for this single bug. Do NOT touch any other `front-page.php` / `home.css` / `app.js` content while inside the unlock.
- DO NOT touch `inc/sd-mega-navwalker.php` — per D-20, that file serves the top-nav mega-menu, NOT the home-page search-bar dropdown. The handler lives in `app.js`.

---

### `Icon\r` (repo root, macOS artifact) — P1-CLEAN-07

**Action:** Delete with `rm -- $'Icon\r'` (the filename ends in a literal carriage-return byte; quoting required to handle the byte). Verify with `ls -la /Icon*` before/after to catch any related artifacts.

`.gitignore:51` already covers `Icon?` so no `.gitignore` changes needed. The file is 0 bytes (per CONCERNS.md) and is the macOS Finder's per-folder custom-icon metadata file.

---

## Shared Patterns

### Text-domain (across every theme PHP file Phase 1 touches)

**Source of truth:** `style.css:7` declares `Text Domain: sandiegoweddingdirectory`.

**Apply to:** Every theme `__()`/`_e()`/`esc_html__()`/`esc_html_e()`/`esc_attr__()`/`esc_attr_e()` call.

**Pattern (mechanical):**

```php
// All these forms are correct post-sweep:
__( 'Text', 'sandiegoweddingdirectory' )
_e( 'Text', 'sandiegoweddingdirectory' )
esc_html__( 'Text', 'sandiegoweddingdirectory' )
esc_html_e( 'Text', 'sandiegoweddingdirectory' )
esc_attr__( 'Text', 'sandiegoweddingdirectory' )
esc_attr_e( 'Text', 'sandiegoweddingdirectory' )
sprintf( __( 'New quote request from %1$s — %2$s', 'sandiegoweddingdirectory' ), $name, get_the_title( $post_id ) );

// Plugin files (DO NOT TOUCH — already correct):
__( 'Text', 'sdwd-core' )      // sdwd-core/**/*.php — keep as-is
__( 'Text', 'sdwd-couple' )    // sdwd-couple/**/*.php — keep as-is
```

**Concrete file count for the sweep** (verified at 2026-04-23):
- 80 theme files contain at least one `'sdweddingdirectory-v2'` or `'sdweddingdirectory'` string.
- 0 plugin files contain those legacy strings.
- Per-target file occurrence counts (sample): `404.php` = 7, `functions.php` = 7, `template-parts/planning/planning-hero.php` = 27, `style.css` = 0 (header is already correct).

### Output escaping (every PHP file, including the 404 changes and planning-hero retarget)

**Source:** Theme-wide convention enforced by `CLAUDE.md` "Always use" §.

**Apply to:** Every dynamic value that lands in HTML.

```php
// Text content
<?php echo esc_html( $title ); ?>
<?php esc_html_e( 'Static text', 'sandiegoweddingdirectory' ); ?>

// HTML attributes
<?php echo esc_attr( $class ); ?>
<?php esc_attr_e( 'Aria label text', 'sandiegoweddingdirectory' ); ?>

// URLs (href, src)
<?php echo esc_url( $url ); ?>
<?php echo esc_url( get_template_directory_uri() . '/assets/images/...' ); ?>
<?php echo esc_url( home_url( '/path/' ) ); ?>

// JS string interpolation in inline <script>
<?php echo esc_js( __( 'Wait a moment...', 'sandiegoweddingdirectory' ) ); ?>

// Trusted HTML (rarely needed)
<?php echo wp_kses_post( $description ); ?>
```

### Asset URL pattern (404 SVG inclusion + every other theme image)

**Source:** Used uniformly across `front-page.php`, `template-parts/components/*.php`, etc.

```php
// PREFERRED — for assets relative to the theme root
<?php echo esc_url( get_template_directory_uri() . '/assets/images/path/to/file.ext' ); ?>

// Equivalent shorthand (same result, used in some files):
<?php echo esc_url( get_theme_file_uri( 'assets/images/path/to/file.ext' ) ); ?>
```

Use the long form (`get_template_directory_uri() . '/...'`) per CONCERNS.md style consistency in the 404 button row, but either is acceptable.

### CSS token-only rule (404 button-row gap, any new spacing in Phase 1)

**Source:** `foundation.css :root` — the only place tokens are defined.

**Apply to:** Every new CSS rule that needs a spacing/color/typography value. No raw hex, no hardcoded `gap`/`padding` magic numbers.

```css
/* Spacing tokens available */
gap: var(--sdwd-section-gap-sm);   /* 16px */
gap: var(--sdwd-section-gap-md);   /* 24px */
gap: var(--sdwd-section-gap-lg);   /* 48px */
gap: var(--sdwd-row-gap);           /* 32px */
gap: var(--sdwd-title-gap);         /* 24px */
margin: var(--gutter);              /* 20px */
border-radius: var(--sdwd-radius);  /* 4px */
border-radius: var(--sdwd-radius-lg); /* 8px */

/* Color tokens (use existing — do not introduce raw hex) */
color: var(--sdwd-body-text);
color: var(--sdwd-muted);
color: var(--sdwd-accent);
border-color: var(--sdwd-border);
background: var(--sdwd-bg);
```

### Conditional CSS enqueue (already wired for 404, no change needed)

**Source:** `functions.php:138-141` — `is_404()` already gates `static.css`.

```php
// ALREADY IN PLACE — do not duplicate or modify
if ( is_page_template( [ 'page-about.php', 'page-contact.php', 'page-faqs.php', 'page-policy.php', 'page-our-team.php' ] )
     || is_404() || is_search() || is_singular( 'team' ) || is_singular( 'changelog' ) ) {
    wp_enqueue_style( 'sdwdv2-static', $theme_uri . '/assets/css/pages/static.css', [ 'sdwdv2-layout' ], $asset_version( '/assets/css/pages/static.css' ) );
}
```

**Cache-busting via `filemtime()`:** Every theme stylesheet/script enqueue uses `$asset_version( '/relative/path.css' )` which falls back to `filemtime()` for live cache-busting on file edits. The 404 + planning-hero changes will auto-bust without manual version bumps.

### File header (every PHP file the sweep touches)

**Source:** Convention shown in every existing PHP file.

```php
<?php
/**
 * [Page/Component Name]
 *
 * [Short description.]
 */

defined( 'ABSPATH' ) || exit;
```

The ABSPATH guard appears at the top of every theme PHP file. Do not remove it during the text-domain sweep.

---

## No Analog Found

| File | Role | Data Flow | Reason |
|------|------|-----------|--------|
| (none) | — | — | All Phase 1 modification targets have a strong existing analog inside the theme. |

---

## Metadata

**Analog search scope:**
- `wp-content/themes/sandiegoweddingdirectory/**` (full theme tree)
- `wp-content/plugins/sdwd-core/**` (read-only — handler contract)
- `wp-content/plugins/sdwd-couple/**` (sweep verification — zero hits)

**Files read for pattern extraction:**
- `404.php`
- `functions.php`
- `style.css`
- `front-page.php`
- `template-parts/planning/planning-hero.php`
- `template-parts/modals/couple-registration.php`
- `assets/css/foundation.css` (tokens — partial via grep)
- `assets/css/components.css` (verified btn rules NOT here)
- `assets/css/layout.css` (btn rules + dead `.modal.fade` rule)
- `assets/css/pages/static.css`
- `assets/css/pages/home.css` (button-row CSS + dropdown CSS)
- `assets/css/pages/planning.css` (form-message banner CSS)
- `assets/js/app.js` (dropdown handler)
- `assets/js/modals.js` (AJAX submit pattern)
- `assets/library/sdwd-icons/style.css` (icon class verification)
- `wp-content/plugins/sdwd-core/sdwd-core.php` (plugin header)
- `wp-content/plugins/sdwd-core/includes/auth.php` (handler contract for P1-FIX-01)
- `wp-content/plugins/sdwd-couple/sdwd-couple.php` (plugin header)

**Key facts surfaced during pattern mapping (planner should propagate):**
1. The `static.css` BEM block is `.error-page__*`, the `404.php` markup is `.error-404__*` — they don't match. Recommend renaming markup to `.error-page__*` so existing CSS applies.
2. `icon-chevron-down` is referenced in `front-page.php` 4× but is NOT defined in `sdwd-icons/style.css`. Likely root cause for P1-FIX-02 dropdown bug.
3. Plugin files have ZERO legacy text-domain strings — the P1-CLEAN-04 sweep is theme-only despite D-15's plugin-edit authorization.
4. `auth.php` uses `wp_send_json_success([...])` (WP envelope), but the planning-hero JS expects a flat payload (`{message, notice, redirect, redirect_link}`). Contract mismatch — fix on the JS side per P1-FIX-01 (smaller blast radius).
5. The home-page dropdown handler (`app.js:184-242`) is already complete and correct. P1-FIX-02 is an investigation task, not a rewrite task.
6. `style.css` `Text Domain:` header is already `sandiegoweddingdirectory` — verification per D-13 confirms no change needed there.

**Pattern extraction date:** 2026-04-23
