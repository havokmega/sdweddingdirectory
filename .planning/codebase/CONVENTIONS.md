# Coding Conventions

**Analysis Date:** 2026-04-23

## Scope

Covers the custom code in this workspace:

- `wp-content/plugins/sdwd-core/` — custom plugin (auth, CPTs, taxonomies, claims, quotes, dashboard AJAX, admin meta boxes)
- `wp-content/plugins/sdwd-couple/` — custom plugin (reviews, wishlist, checklist, budget, request-quote)
- `wp-content/themes/sandiegoweddingdirectory/` — custom theme (templates, components, CSS, enqueues)

Third-party plugins (`wordfence`, `updraftplus`, `w3-total-cache`, `seo-by-rank-math`, `wp-mail-smtp`, `shortpixel-image-optimiser`) are **not** covered — their conventions are vendor-defined and out of scope for this project's standards.

## Style Baseline

The custom code loosely follows **WordPress Coding Standards (WPCS)** conventions, but **no linter is configured** (no `.phpcs.xml`, no `phpcs.xml.dist`, no `composer.json`, no `package.json`). Adherence is by convention only — new code must maintain these patterns manually.

**Observed WPCS traits:**

- Space-padded parentheses in function calls: `esc_html( $var )`, `in_array( $needle, $haystack, true )`
- Yoda conditions occasionally used: `'couple' === $account_type`, `0 === strpos(...)` (but not consistently — most comparisons are left-variable)
- Short array syntax `[ ... ]` (not legacy `array()`)
- Tabs/spaces: **4-space indentation** (not tabs — this diverges from strict WPCS which mandates tabs)
- File docblocks at top of every PHP file
- Function docblocks on most but not all functions

## Naming Patterns

**Files (PHP):** `kebab-case.php`
- `post-types.php`, `user-post-link.php`, `sd-mega-navwalker.php`, `vendor-dashboard.php`
- Template parts: `couple-dashboard-sidebar-right-2-task-list.php` (section-numbered for ordering)

**Files (CSS/JS):** `kebab-case.css`, `kebab-case.js`
- `foundation.css`, `components.css`, `layout.css`, `modals.js`, `dashboard.js`

**Functions (plugins):** `sdwd_` prefix, snake_case
- `sdwd_register_post_types()`, `sdwd_handle_register()`, `sdwd_get_dashboard_url()`, `sdwd_create_user_post()`

**Functions (theme):** `sdwdv2_` prefix, snake_case
- `sdwdv2_is_dashboard_page()`, `sdwdv2_get_vendor_company_name()`, `sdwdv2_build_vendor_query_args()`

**Inconsistency:** The theme uses `sdwdv2_` prefix as a relic of an earlier naming scheme even though the theme is now named `sandiegoweddingdirectory`. The two naming systems have not been reconciled. Phase 1 `P1-CLEAN-04` text-domain sweep does not rename these function prefixes; they are a separate future cleanup.

**Constants (plugins):** UPPER_SNAKE with plugin prefix
- `SDWD_CORE_VERSION`, `SDWD_CORE_PATH`, `SDWD_CORE_URL`
- `SDWD_COUPLE_VERSION`, `SDWD_COUPLE_PATH`

**Constants (theme):** `SDWEDDINGDIRECTORY_THEME_*`
- `SDWEDDINGDIRECTORY_THEME_VERSION`, `SDWEDDINGDIRECTORY_THEME_PATH`, `SDWEDDINGDIRECTORY_THEME_DIR`, `SDWEDDINGDIRECTORY_THEME_LIBRARY`, `SDWEDDINGDIRECTORY_THEME_MEDIA`

**Classes:** Almost none. Custom code is **procedural**, not OOP.
- Only custom class detected: `SDSDWeddingDirectoryectory_Navwalker` in `wp-content/themes/sandiegoweddingdirectory/inc/sd-mega-navwalker.php` (note the typo "SDSD...ectory" — legacy carry-over)
- No PSR autoloading, no composer, no `class-*.php` filename pattern

**Meta keys / option keys:** `sdwd_` prefix, snake_case
- Post meta: `sdwd_company_name`, `sdwd_email`, `sdwd_phone`, `sdwd_hours`, `sdwd_pricing`, `sdwd_social`, `sdwd_claim`, `sdwd_wedding_date`
- User meta: `sdwd_post_id`, `sdwd_wishlist`, `sdwd_checklist`, `sdwd_budget_total`, `sdwd_budget_items`
- Options: `sdwdv2_vendor_rewrite_version` (theme) — note `sdwdv2_` not `sdwd_`

**CSS classes:** BEM-lite (per `CLAUDE.md`): `.block__element`, `.block--modifier`
- `.vendor-profile-head__title`, `.card--vendor`, `.sdwd-modal__close`, `.sdwd-repeater__row`
- Single-class selectors, max one level of nesting

**JS data attributes:** `data-sdwd-*` (kebab-case in HTML, `dataset.sdwd*` in JS)
- `data-sdwd-modal-open`, `data-sdwd-repeater`, `data-sdwd-tier`, `data-nav-toggle`

## Namespacing

**PHP namespaces: none.** No `namespace` declarations anywhere in custom code. All functions are in the global namespace, prefixed by convention (`sdwd_`, `sdwdv2_`).

**PSR-4 autoloading: not used.** No `composer.json` exists.

## Hook / Action / Filter Naming

**Two coexisting conventions** — this is inconsistent:

### 1. Snake_case hooks (new sdwd-core / sdwd-couple code)

Used for AJAX actions and custom events emitted by the custom plugins:

```php
add_action( 'wp_ajax_sdwd_register',        'sdwd_handle_register' );
add_action( 'wp_ajax_nopriv_sdwd_login',    'sdwd_handle_login' );
add_action( 'wp_ajax_sdwd_submit_claim',    'sdwd_handle_submit_claim' );
add_action( 'wp_ajax_sdwd_save_dashboard',  'sdwd_save_dashboard' );
do_action(  'sdwd_user_registered', $user_id, $account_type );
```

Custom AJAX action slugs inventoried (all in `sdwd-core` + `sdwd-couple`):
- `sdwd_register`, `sdwd_login`, `sdwd_forgot_password`
- `sdwd_submit_claim`, `sdwd_approve_claim`, `sdwd_reject_claim`
- `sdwd_save_dashboard`, `sdwd_send_quote`, `sdwd_request_quote`
- `sdwd_submit_review`, `sdwd_toggle_wishlist`, `sdwd_save_checklist`, `sdwd_save_budget`

Custom event: `sdwd_user_registered` (fired in `auth.php`, handled in `user-post-link.php`).

### 2. Slash-namespaced filter hooks (legacy theme/plugin contract)

Used where the theme calls into an upstream plugin or filters term data. These match the `sdweddingdirectory/...` namespace of an older plugin the theme was originally written against:

```php
apply_filters( 'sdweddingdirectory/rating/average', '', [ 'venue_id' => $post_id ] );
add_filter(  'sdweddingdirectory/post-cap/vendor', '__return_false' );
add_filter(  'sdweddingdirectory_icon_library', function ( $args = [] ) { ... } );
```

Inventoried slash-namespaced hooks referenced by the theme:
- `sdweddingdirectory/couple/detail-page`
- `sdweddingdirectory/pagination`
- `sdweddingdirectory/post-cap/vendor`
- `sdweddingdirectory/rating/average`
- `sdweddingdirectory/real-wedding/detail-page`
- `sdweddingdirectory/term-box-group`
- `sdweddingdirectory/term/image`
- `sdweddingdirectory/website/detail-page`
- `sdweddingdirectory_icon_library`

**Guidance:** New hooks in sdwd-core / sdwd-couple should use the `sdwd_snake_case` convention. The `sdweddingdirectory/...` hooks exist only for compatibility with templates written against the older plugin API.

## Error Handling

**Pattern:** Return early + `wp_send_json_error()` for AJAX, `is_wp_error()` checks for WP API calls.

```php
// AJAX handler pattern — used uniformly across auth.php, claim.php, quote.php, dashboard.php, reviews.php
function sdwd_handle_register() {
    check_ajax_referer( 'sdwd_auth_nonce', 'nonce' );

    $email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );

    if ( ! is_email( $email ) ) {
        wp_send_json_error( [ 'message' => __( 'Please enter a valid email address.', 'sdwd-core' ) ] );
    }

    $user_id = wp_create_user( $email, $password, $email );
    if ( is_wp_error( $user_id ) ) {
        wp_send_json_error( [ 'message' => $user_id->get_error_message() ] );
    }

    // ... success path ...
    wp_send_json_success( [ 'message' => __( 'Account created successfully!', 'sdwd-core' ) ] );
}
```

**Array normalization after `get_post_meta()` / `get_user_meta()`:**

```php
$social = get_post_meta( $post_id, 'sdwd_social', true );
if ( ! is_array( $social ) || empty( $social ) ) {
    $social = [ [ 'label' => '', 'url' => '' ] ];
}
```

**Template-part argument extraction:**

```php
$post_id    = $args['post_id'] ?? 0;
$image_size = $args['image_size'] ?? 'medium';

if ( ! $post_id ) {
    return;
}
```

**No try/catch.** Exceptions are not used in custom code — the WordPress `WP_Error` object is the error vehicle.

## Logging

**No custom logger. No `error_log()` calls anywhere in custom code.**

A grep for `error_log` / `WP_DEBUG` / `wp_debug` across the three custom code directories returns zero hits. Debugging relies on:

1. `wp-content/debug.log` when `WP_DEBUG_LOG` is enabled in `wp-config.php` (gitignored — see `.gitignore` line 14)
2. `*.log` files — gitignored globally
3. Browser devtools for JS / AJAX response inspection

**Guidance:** If logging is needed, use `error_log()` and rely on `WP_DEBUG_LOG` to route output to `wp-content/debug.log`. Do not introduce a custom logger framework without founder approval.

## Asset Handling (Enqueue Patterns)

**All enqueues live in `wp_enqueue_scripts` and `admin_enqueue_scripts` action callbacks — no inline `<link>` / `<script>` tags.**

**Cache-busting via `filemtime()`** (theme only):

```php
// wp-content/themes/sandiegoweddingdirectory/functions.php
$asset_version = static function ( $relative_path ) use ( $theme_dir, $theme_version ) {
    $asset_path = $theme_dir . $relative_path;
    return file_exists( $asset_path ) ? filemtime( $asset_path ) : $theme_version;
};

wp_enqueue_style( 'sdwdv2-foundation', $theme_uri . '/assets/css/foundation.css', [ 'sdwd-icons' ], $asset_version( '/assets/css/foundation.css' ) );
```

**Plugins use a static `SDWD_CORE_VERSION` constant** (no filemtime):

```php
// wp-content/plugins/sdwd-core/sdwd-core.php
wp_enqueue_style( 'sdwd-admin', SDWD_CORE_URL . 'assets/admin.css', [], SDWD_CORE_VERSION );
wp_enqueue_script( 'sdwd-admin', SDWD_CORE_URL . 'assets/admin.js', [], SDWD_CORE_VERSION, true );
```

**Conditional loading (theme):** Page-specific CSS is gated on template/query conditions:

```php
if ( is_singular( [ 'vendor', 'venue', 'real-wedding', 'couple' ] ) ) {
    wp_enqueue_style( 'sdwdv2-profile', ... );
}

if ( sdwdv2_is_dashboard_page() ) {
    wp_enqueue_style( 'sdwdv2-dashboard', ... );
    wp_enqueue_script( 'sdwd-dashboard', ... );
    wp_localize_script( 'sdwd-dashboard', 'sdwd_dash', [
        'url'   => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce( 'sdwd_dashboard_nonce' ),
    ] );
}
```

**Script handles:**
- Theme handles: `sdwdv2-*` (e.g. `sdwdv2-foundation`, `sdwdv2-home`)
- Plugin handles: `sdwd-*` (e.g. `sdwd-admin`, `sdwd-modals`, `sdwd-dashboard`)

**Asset directory structure (theme):**
- `assets/css/` — `foundation.css`, `components.css`, `layout.css` (loaded on every page)
- `assets/css/pages/` — page-specific CSS (conditionally enqueued)
- `assets/js/` — `app.js`, `modals.js`, `dashboard.js`
- `assets/library/sdwd-icons/` — custom icon font (replaces Font Awesome per `CLAUDE.md`)
- `assets/fonts/` — local WOFF2 (no Google Fonts — banned by `CLAUDE.md`)
- `assets/images/` — all theme imagery

**JavaScript:** vanilla ES6+, no jQuery (banned), no bundler, no build step. Files are shipped as-is.

**Dequeueing legacy assets** (theme `functions.php` lines 175-181):

```php
add_action( 'wp_print_styles', function () {
    wp_dequeue_style( 'sdweddingdirectory-fontello' );
    wp_deregister_style( 'sdweddingdirectory-fontello' );

    wp_dequeue_style( 'sdweddingdirectory-flaticon' );
    wp_deregister_style( 'sdweddingdirectory-flaticon' );
}, 100 );
```

## Translations (Text Domains)

**Four text domains in active use — this is inconsistent and should be rationalized:**

| Text Domain | Defined In | Usage Count (approx.) | Notes |
|---|---|---|---|
| `sdwd-core` | `wp-content/plugins/sdwd-core/sdwd-core.php` header | 64 strings in theme (leakage), all of `sdwd-core` plugin | Correct for sdwd-core plugin |
| `sdwd-couple` | `wp-content/plugins/sdwd-couple/sdwd-couple.php` header | All of sdwd-couple plugin | Correct for sdwd-couple plugin |
| `sdweddingdirectory` | `wp-content/themes/sandiegoweddingdirectory/style.css` line 7 | 801 strings in theme | Current theme domain |
| `sdweddingdirectory-v2` | None declared — legacy leftover | 240 strings in theme | Orphan from previous theme name — no `load_theme_textdomain()` call matches |

**Current theme setup:**

```php
// wp-content/themes/sandiegoweddingdirectory/functions.php line 32
load_theme_textdomain( 'sdweddingdirectory-v2', get_template_directory() . '/languages' );
```

The `load_theme_textdomain()` call registers `sdweddingdirectory-v2` — not `sdweddingdirectory` declared in `style.css`. Neither matches uniformly across the theme. **This is a real bug: many translatable strings will never be translated.** Flag as tech debt.

**Guidance for new strings:**

- In `sdwd-core` plugin files → `__( 'text', 'sdwd-core' )`
- In `sdwd-couple` plugin files → `__( 'text', 'sdwd-couple' )`
- In theme files → `__( 'text', 'sdweddingdirectory' )` (match `style.css`, and separately reconcile `load_theme_textdomain()` as a cleanup task)

**Functions used:** `__()`, `_e()`, `esc_html__()`, `esc_html_e()`, `esc_attr__()`, `esc_attr_e()`, `sprintf( __( '...', ... ) )`, `_n()` (rare).

## Security Patterns

Security hygiene is **consistently applied** across the custom code — this is the strongest convention in the codebase. Key counts from grep:

- ~130 calls to `esc_html` / `esc_attr` / `esc_url` / `wp_kses` / `sanitize_*` across both plugins' `includes/` and `modules/`
- 27 calls to `check_ajax_referer` / `wp_create_nonce` / `wp_nonce_field` / `wp_verify_nonce` / `current_user_can` across both plugins

### ABSPATH guard (top of every PHP file)

```php
defined( 'ABSPATH' ) || exit;
```

Present in every custom PHP file — confirmed across `sdwd-core/`, `sdwd-couple/`, and all theme template parts inspected.

### Nonces

**AJAX nonces — one nonce per feature area:**

| Nonce action | Used by | Created in | Verified in |
|---|---|---|---|
| `sdwd_auth_nonce` | login, register, forgot-password | theme `functions.php` modals enqueue | `sdwd-core/includes/auth.php` |
| `sdwd_dashboard_nonce` | frontend dashboard save | theme `functions.php` dashboard enqueue | `sdwd-core/includes/dashboard.php` |
| `sdwd_claim_nonce` | submit claim (public) | (localized on vendor/venue singular templates) | `sdwd-core/includes/claim.php` |
| `sdwd_claim_action` | admin approve/reject claim | `sdwd_claim_meta_box_cb()` | `sdwd-core/includes/claim.php` |
| `sdwd_quote_nonce` | vendor quote form | `single-vendor.php` | `sdwd-core/includes/quote.php`, `sdwd-couple/modules/request-quote.php` |
| `sdwd_review_nonce` | submit review | (theme template) | `sdwd-couple/modules/reviews.php` |
| `sdwd_wishlist_nonce` | toggle wishlist | (theme template) | `sdwd-couple/modules/wishlist.php` |
| `sdwd_budget_nonce` | save budget | (theme template) | `sdwd-couple/modules/budget.php` |
| `sdwd_migration` | run data migration | `sdwd-core/includes/migrate.php` | `sdwd-core/includes/migrate.php` |

**Meta-box nonces** (in admin save-post flow):

```php
// wp-content/plugins/sdwd-core/includes/admin/vendor-meta.php
function sdwd_save_vendor_meta( $post_id, $post ) {
    if ( ! isset( $_POST['sdwd_vendor_meta_nonce'] )
        || ! wp_verify_nonce( $_POST['sdwd_vendor_meta_nonce'], 'sdwd_vendor_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    // ... save ...
}
```

The triple-guard `nonce → autosave → capability check` is applied in every `save_post_*` handler: `sdwd_save_vendor_meta`, `sdwd_save_couple_meta`, `sdwd_save_venue_meta`.

### Capability Checks

```php
// Admin-only operations
if ( ! current_user_can( 'manage_options' ) ) {
    wp_send_json_error( [ 'message' => __( 'Unauthorized.', 'sdwd-core' ) ] );
}

// Per-post capability
if ( ! current_user_can( 'edit_post', $post_id ) ) {
    return;
}

// Role check (frontend AJAX — claim.php, reviews.php)
if ( ! in_array( 'couple', $user->roles, true ) ) {
    wp_send_json_error( [ 'message' => __( 'Only couples can leave reviews.', 'sdwd-couple' ) ] );
}
```

### Input Sanitization

**Applied at the boundary**, immediately on reading `$_POST` / `$_GET`:

| Sanitizer | Used for |
|---|---|
| `sanitize_text_field( wp_unslash( ... ) )` | plain text inputs — dominant pattern |
| `sanitize_textarea_field( wp_unslash( ... ) )` | multi-line text (messages, reviews) |
| `sanitize_email( wp_unslash( ... ) )` | email addresses |
| `sanitize_key( ... )` | array keys like weekday slugs |
| `esc_url_raw( wp_unslash( ... ) )` | URLs before DB save (e.g. social links) |
| `absint( ... )` | IDs, counts |
| `floatval( ... )` | budget figures |
| `wp_kses_post( wp_unslash( ... ) )` | WYSIWYG / description fields saved to `post_content` |

Every `$_POST` access in custom code is wrapped in `wp_unslash()` before sanitizing — confirmed across all AJAX handlers.

**Defensive `?? ''` / `?? 0` null-coalescing** is used on every `$_POST` read:

```php
$email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
$post_id = absint( $_POST['post_id'] ?? 0 );
```

### Output Escaping

**Applied on output, never at storage.** Enforced by the `CLAUDE.md` rules and visible in every theme template:

| Escaper | When |
|---|---|
| `esc_html()` / `esc_html__()` / `esc_html_e()` | text content |
| `esc_attr()` / `esc_attr__()` / `esc_attr_e()` | HTML attributes |
| `esc_url()` | URL attributes (`href`, `src`) |
| `wp_kses_post()` | trusted HTML descriptions (11 call sites, mostly in `template-parts/components/*.php`) |

Example from `template-parts/components/breadcrumbs.php`:

```php
<a class="breadcrumb__link" href="<?php echo esc_url( $item['url'] ); ?>">
    <?php echo esc_html( $item['label'] ); ?>
</a>
```

### Gaps / Inconsistencies

- `sdwd_handle_register()` and `sdwd_handle_login()` pass the raw `$_POST['password']` into `wp_create_user()` / `wp_check_password()` **without** `wp_unslash()`. This is consistent with how WordPress itself handles passwords (unslashing would corrupt passwords containing backslashes), so it is correct — but the pattern differs from every other field and is undocumented.
- `sdwd_claim_meta_box_cb()` in `claim.php` echoes `<?php echo $nonce; ?>` into an inline `onclick` handler. The nonce is safe content, but bypassing `esc_js()` here is a minor hygiene issue.
- `claim.php` emits an inline `<script>` block with `onclick="sdwdClaimAction(...)"` — inline JS is tolerated in plugin admin UI but would violate the theme's "no inline JS" rule if copied into templates.

## Code Style Details

### Formatting

- **Indentation: 4 spaces** (not tabs). Consistent across all custom PHP, CSS, JS.
- **Line endings:** LF (Unix).
- **Trailing comma in arrays:** not used (PHP 7.3+ supports it but the codebase doesn't).
- **Block braces:** same line (`function foo() {`) — K&R style.
- **Spaces inside parentheses:** yes, in function calls (`func( $arg )`) and control flow (`if ( ... )`) — WPCS style.

### Array Syntax

Always `[]`, never `array()`. No mixed usage found.

### String Quoting

- Single quotes for plain strings
- Double quotes for strings with interpolation (`"Vendor: {$name}\n"`)
- `sprintf()` preferred for translatable formatted strings:

```php
sprintf( __( 'New quote request from %1$s — %2$s', 'sdwd-core' ), $name, get_the_title( $post_id ) );
```

### Import Organization

Not applicable — no `use` statements (no namespaces, no classes to import). All files use global functions directly.

### Function Design

- **Size:** Small. Most functions 5-40 lines. The outliers are the few "register_post_type" blocks (~30 lines of config) and `sdwd_save_dashboard()` (~125 lines — the kitchen-sink AJAX handler).
- **Parameters:** Positional, no argument structs. Default values via `= []`, `= 0`, `= ''`.
- **Return values:** Plain scalars/arrays. `WP_Error` from WP API calls is checked via `is_wp_error()`, never returned from custom functions.
- **Early returns** preferred over nested conditionals.

### Module Design

**No exports, no barrel files, no autoloading.** Each `require_once` in the plugin bootstrap file loads a module file directly:

```php
// wp-content/plugins/sdwd-core/sdwd-core.php
add_action( 'plugins_loaded', function () {
    require_once SDWD_CORE_PATH . 'includes/roles.php';
    require_once SDWD_CORE_PATH . 'includes/post-types.php';
    require_once SDWD_CORE_PATH . 'includes/taxonomies.php';
    // ...
}, 5 );
```

Modules register their own hooks at load time. No explicit init function per module.

## Comments & Docblocks

**File docblock** at the top of every PHP file — required:

```php
<?php
/**
 * SDWD Core — Authentication
 *
 * AJAX handlers for registration, login, and forgot password.
 * All three account types (couple, vendor, venue) use these endpoints.
 */

defined( 'ABSPATH' ) || exit;
```

**Function docblocks** — present on most public/hook-called functions, absent on small helpers:

```php
/**
 * Create a CPT post for a newly registered user.
 *
 * @param int    $user_id      The new user's ID.
 * @param string $account_type 'couple', 'vendor', or 'venue'.
 */
function sdwd_create_user_post( $user_id, $account_type ) {
```

**Section dividers** in longer files — use box-drawing characters:

```php
// ── Registration ────────────────────────────────────────────
// ── Login ───────────────────────────────────────────────────
// ── Forgot Password ─────────────────────────────────────────
```

**Inline comments** — full sentences, capital letter, trailing period.

Per `CLAUDE.md`: **do not add comments to code you did not change.** Don't add type annotations or JSDoc to existing functions.

## JS Conventions

- **Vanilla ES6+**, no jQuery, no bundler
- IIFE via `DOMContentLoaded`: `document.addEventListener('DOMContentLoaded', () => { ... });`
- Event delegation from `document` for dynamic UI (see `assets/admin.js` lines 7-50 and `assets/js/modals.js`)
- Indent: 2 spaces (JS only — PHP uses 4)
- Arrow functions for callbacks, `const` / `let` (no `var`)
- `fetch()` for AJAX, not XHR:

```js
fetch(ajaxurl, { method: 'POST', body: data })
    .then(r => r.json())
    .then(res => { ... });
```

- AJAX endpoint + nonce passed via `wp_localize_script()` as `sdwd_ajax` / `sdwd_dash` globals

## CSS Conventions (per CLAUDE.md, verified in code)

- **Four core files in order:** `foundation.css` → `components.css` → `layout.css` → page CSS
- **No `!important`**
- **No raw hex colors** — all colors via `--sdwd-*` tokens in `:root` of `foundation.css`
- **BEM-lite** class naming, single-class selectors
- **Mobile-first** — base styles mobile, `@media (min-width: ...)` for larger
- **No utility classes** (no `.mt-4`, no `.text-center`)
- **No Bootstrap, Font Awesome, Google Fonts** (banned by `CLAUDE.md`)

## Template Pattern

Templates are **orchestrators** that call `get_template_part()` with `$args` arrays:

```php
// wp-content/themes/sandiegoweddingdirectory/user-template/couple-dashboard.php
get_template_part( 'template-parts/couple-dashboard/couple-dashboard-hero', null, [
    'first_name'          => $user->first_name,
    'wedding_date'        => $wedding_date,
    'checklist_total'     => $checklist_total,
    'checklist_completed' => $checklist_completed,
] );
```

Template parts receive `$args` and extract with null-coalescing:

```php
$first_name = $args['first_name'] ?? '';
$wedding_date = $args['wedding_date'] ?? '';
```

---

*Convention analysis: 2026-04-23*
