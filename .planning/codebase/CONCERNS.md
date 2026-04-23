# Codebase Concerns

**Analysis Date:** 2026-04-23

> Scope: only first-party code (`wp-content/themes/sandiegoweddingdirectory/`, `wp-content/plugins/sdwd-core/`, `wp-content/plugins/sdwd-couple/`) and repo infrastructure. Third-party plugins (`w3-total-cache`, `wordfence`, `updraftplus`, `shortpixel-image-optimiser`, `seo-by-rank-math`, `wp-mail-smtp`) are out of scope — TODOs/FIXMEs inside their vendored libraries (e.g. `w3-total-cache/lib/Nusoap/`) are not actionable.
>
> Priority key: **HIGH** = security exposure, data loss, or production-blocking runtime errors · **MEDIUM** = stability, broken features, maintainability drag · **LOW** = cleanup, cosmetic, or deferred polish.

---

## HIGH — Security

### Passwords written to the database without strength or re-auth checks

- Issue: `wp_set_password()` is called directly from three admin metaboxes and one frontend AJAX save handler with no password-strength check, no current-password confirmation, and no rate limiting. Registration has a minimum-length check (8 chars), but the change paths do not.
- Files:
  - `wp-content/plugins/sdwd-core/includes/dashboard.php:127-131` — frontend AJAX: `wp_set_password( $_POST['sdwd_new_password'], $user->ID );` then re-issues auth cookie. No unslash, no length check.
  - `wp-content/plugins/sdwd-core/includes/admin/couple-meta.php:136-138` — `wp_set_password( $_POST['sdwd_password'], $post->post_author );` (couple post's linked user).
  - `wp-content/plugins/sdwd-core/includes/admin/vendor-meta.php:233-235` — same pattern for vendor.
  - `wp-content/plugins/sdwd-core/includes/admin/venue-meta.php:290-292` — same pattern for venue.
  - `wp-content/plugins/sdwd-core/includes/auth.php:27,51-53` — registration accepts `$_POST['password']` raw (correctly — WP hashes it) but no upper bound and no complexity rule.
- Impact: any vendor/venue/couple can set a 1-character password via the frontend dashboard. An admin password-reset from the metabox has no confirm step, so an accidental keystroke in the "New Password" field silently resets the owner's login.
- Fix approach: reuse the registration min-length rule (≥8) in `sdwd_save_dashboard()` and the three metabox save callbacks, add a `current_password` verification field for the frontend change path, and clear the field after save instead of leaving a populated input.

### Admin metabox password field accepts input without `wp_unslash()`

- Issue: the admin save handlers pass `$_POST['sdwd_password']` straight into `wp_set_password()` with no `wp_unslash()` and no sanitization check.
- Files:
  - `wp-content/plugins/sdwd-core/includes/admin/couple-meta.php:137`
  - `wp-content/plugins/sdwd-core/includes/admin/vendor-meta.php:234`
  - `wp-content/plugins/sdwd-core/includes/admin/venue-meta.php:291`
- Impact: magic-quotes-era escaping will leak backslashes into the stored hash. Users who set a password containing `'`, `"`, or `\` in wp-admin will be locked out at login because the login path uses `wp_unslash()` on `$_POST['password']` and the stored hash was computed against a different string.
- Fix approach: `wp_set_password( wp_unslash( $_POST['sdwd_password'] ), ... );`.

### Repeater-field repopulation does not use `wp_unslash()` on saved values

- Issue: saved text values are stored via `sanitize_text_field( wp_unslash( $f ) )` but re-rendered in the edit form using `esc_attr( $feature )` without an unslash on read — values that round-trip through `magic_quotes_gpc` emulation accumulate slashes on repeated saves.
- Files:
  - `wp-content/plugins/sdwd-core/includes/admin/vendor-meta.php:157-205` — pricing tier feature rows.
  - `wp-content/plugins/sdwd-core/includes/admin/venue-meta.php:206-254` — same pattern.
- Impact: each save doubles backslashes in stored feature strings, eventually breaking display.
- Fix approach: normalize read/write via a single helper or use `update_post_meta` which already stores unslashed data; re-render using the raw stored value (no escape-on-read other than `esc_attr`).

### Admin claim metabox uses un-sanitized numeric output inside HTML event handlers

- Issue: `onclick="sdwdClaimAction('approve', <?php echo $post->ID; ?>, '<?php echo $nonce; ?>')"` emits `$post->ID` and a nonce as raw values inside a JS string.
- Files: `wp-content/plugins/sdwd-core/includes/claim.php:128-129` (approve/reject buttons) and `:132-144` (inline `<script>` block).
- Impact: low (IDs are ints, nonce is alphanumeric) but violates the "always escape output" rule in `CLAUDE.md` and forces an inline `<script>` into an admin screen.
- Fix approach: `esc_attr( $post->ID )`, `esc_attr( $nonce )`; better, move the JS into `assets/admin.js` and pass data through `data-*` attributes.

### Inline JS in public templates with `echo $post_id` in URL context

- Issue: `single-venue.php` embeds a `<script>` block that reads `<?php echo $post_id; ?>` as a plain numeric token and `<?php echo wp_create_nonce(...); ?>` as a JS string. The parallel `single-vendor.php` does NOT have the script and relies on a missing handler.
- Files:
  - `wp-content/themes/sandiegoweddingdirectory/single-venue.php:382-408` — full inline handler for the claim flow.
  - `wp-content/themes/sandiegoweddingdirectory/single-vendor.php:352-361` — claim UI with NO submit handler wired up.
- Impact:
  1. Inline JS violates the theme's own `CLAUDE.md` rule "No inline JS or CSS" (see `CLAUDE.md:204` and `architecture.md:56`).
  2. Vendor profiles cannot be claimed from the frontend — the button exists but does nothing when clicked.
  3. `$post_id` is unescaped; currently safe (always int) but brittle.
- Fix approach: extract a `claim.js` controller in `assets/js/`, bind to `[data-sdwd-claim]` elements, fetch nonce from a `wp_localize_script` bundle, and delete the inline scripts from both templates.

### Frontend registration submits to a removed legacy AJAX action

- Issue: the planning hero registration form still targets `sdweddingdirectory_couple_register_form_action`, an action that only existed in the retired legacy plugin. The current handler is `sdwd_register` (in `sdwd-core/includes/auth.php:18`).
- Files: `wp-content/themes/sandiegoweddingdirectory/template-parts/planning/planning-hero.php:62-67,79-124,154,223-235`.
- Impact: every couple sign-up submission from the planning page returns a WordPress admin-ajax `0` response (no handler). No account is created.
- Fix approach: change the hidden `action` to `sdwd_register`, rename the input `name` attributes to match `sdwd-core/includes/auth.php` (`first_name`, `last_name`, `email`, `password`, `account_type=couple`, `wedding_date`), and replace the legacy nonce field with a `sdwd_auth_nonce` nonce localized by the theme.

### `docker-compose.yml` and `Dockerfile.ssh` ship hardcoded dev credentials

- Issue: the repo's Docker stack uses `wordpress/wordpress`/`root/root` as DB credentials and `Dockerfile.ssh` hardcodes SSH root login as `root:root` with `PermitRootLogin yes` and `PasswordAuthentication yes`.
- Files:
  - `docker-compose.yml:9-12,25-28,46-49` — `MYSQL_ROOT_PASSWORD: root`, `WORDPRESS_DB_PASSWORD: wordpress`.
  - `Dockerfile.ssh:15-18` — `echo 'root:root' | chpasswd`, `PermitRootLogin yes`.
- Impact: files are listed in `.gitignore:21-22,47` so they are not tracked (verified — `git ls-files docker-compose.yml Dockerfile.ssh` returns empty), but if the ignore entries are removed during any future "tidy" pass the secrets will land in git history. The SSH container also exposes port `2222:22` with a guessable root password — fine locally, catastrophic if this stack is ever run on a public host.
- Fix approach: replace hardcoded values with env-file interpolation (`${MYSQL_PASSWORD}`, `${SSH_ROOT_PASSWORD}`) and add a commit-hook check that refuses to track `.env`, `docker-compose.yml`, and `Dockerfile.ssh`.

### Style-guide showcase imports Bootstrap classes and Font Awesome markup

- Issue: the style-guide page renders `class="row"`, `class="col-md-6"`, and `<i class="fa fa-info-circle">` / `<i class="fa fa-star">` icon elements — the exact classes explicitly banned in `CLAUDE.md:85-87`.
- Files:
  - `wp-content/themes/sandiegoweddingdirectory/page-style-guide.php:301-323` — Bootstrap row + FA info/star icons in "Card Shadow Pattern".
  - `wp-content/themes/sandiegoweddingdirectory/page-style-guide.php:333,357,550` — more `col-md-6` / `col-md-4`.
  - `wp-content/themes/sandiegoweddingdirectory/page-style-guide.php:43-46,71-72,89-90,109-110` — direct `echo $var` output into `style="background: ..."` inline attributes (also >394 inline `style=` occurrences in this one file).
- Impact: the file meant to be the source of truth for "what we use" actively demonstrates the banned patterns. New contributors copy-paste from here and reintroduce the forbidden classes.
- Fix approach: rewrite `page-style-guide.php` using `.grid`/`.container` (`layout.css`), `icon-*` classes from `sdwd-icons`, and BEM-only classes. No inline styles — the file can use its own `style-guide.css` since it's a one-off.

---

## HIGH — Broken features (runtime failures)

### Five `sdwd-couple` AJAX endpoints will always reject — no nonce is ever issued

- Issue: `sdwd-couple` module handlers call `check_ajax_referer()` for nonces that are never created via `wp_create_nonce()` nor localized to JS anywhere in the repo.
- Files:
  - `wp-content/plugins/sdwd-couple/modules/checklist.php:14` — `sdwd_checklist_nonce`
  - `wp-content/plugins/sdwd-couple/modules/budget.php:14` — `sdwd_budget_nonce`
  - `wp-content/plugins/sdwd-couple/modules/wishlist.php:14` — `sdwd_wishlist_nonce`
  - `wp-content/plugins/sdwd-couple/modules/reviews.php:35` — `sdwd_review_nonce`
  - `wp-content/plugins/sdwd-couple/modules/request-quote.php:14` — `sdwd_quote_nonce` (this one IS issued by theme for the vendor-profile quote form at `wp-content/themes/sandiegoweddingdirectory/single-vendor.php:46`, so it shares but there's no separate review/wishlist/etc. issuance).
- Verification: `grep -rn "sdwd_(review|wishlist|checklist|budget)_nonce" wp-content/themes wp-content/plugins` returns ONLY the `check_ajax_referer` lines — zero issuers.
- Impact: every couple-side feature (wishlist toggle, checklist save, budget save, review submission) hard-fails with a 403 from `check_ajax_referer` on first request. No couple dashboard save has ever worked.
- Fix approach: either (a) add a single couple-wide nonce localized alongside `sdwd_dash` in `functions.php`, or (b) mint per-feature nonces server-side and inject via `wp_localize_script( 'sdwd-couple', 'sdwd_couple', [ 'nonce' => [...] ] )`.

### Four template action hooks fire into handlers that do not exist

- Issue: `single-couple.php`, `single-real-wedding.php`, `single-website.php`, and `page-inspiration.php` fire legacy action hooks whose handlers were deleted when the plugin was rewritten.
- Files + hooks:
  - `wp-content/themes/sandiegoweddingdirectory/single-couple.php:12` — `do_action( 'sdweddingdirectory/couple/detail-page' );`
  - `wp-content/themes/sandiegoweddingdirectory/single-real-wedding.php:12` — `do_action( 'sdweddingdirectory/real-wedding/detail-page' );`
  - `wp-content/themes/sandiegoweddingdirectory/single-website.php:13` — `do_action( 'sdweddingdirectory/website/detail-page' );`
  - `wp-content/themes/sandiegoweddingdirectory/page-inspiration.php:12,41,44` — `sdweddingdirectory_main_container`, `sdweddingdirectory_empty_article`, `sdweddingdirectory_main_container_end`.
  - Also used in `inc/venues.php:303,515` — `sdweddingdirectory/term-box-group`, `sdweddingdirectory/term/image`.
- Impact: `codebase-index.md:93-95` lists these single templates as `stub`, matching reality — they render an empty `<main>`. Any real wedding or couple URL serves a blank page. Note: `real-wedding` is not even registered as a post type in `sdwd-core/includes/post-types.php`, so the single template will never fire; but the 8 `taxonomy-real-wedding-*.php` files (registered at `taxonomies.php` — wait, they're NOT registered either) are also dead.
- Fix approach: decide per PROJECT.md which of these pages are still in scope (Phase 4 real weddings, couple website); delete the ones that aren't; rebuild the ones that are using the current `sdwd-*` naming and native theme rendering (no plugin action hooks).

### `real-wedding` and `website` CPTs are referenced by the theme but not registered

- Issue: `functions.php:122` calls `is_singular( [ 'vendor', 'venue', 'real-wedding', 'couple' ] )` but `sdwd-core/includes/post-types.php` only registers `couple`, `vendor`, `venue`. There are 8 `taxonomy-real-wedding-*.php` files and a `single-real-wedding.php` that will never route, plus `archive-real-wedding.php` and `archive-website.php`.
- Files:
  - `wp-content/plugins/sdwd-core/includes/post-types.php:14-110` — only 3 CPTs registered.
  - `wp-content/themes/sandiegoweddingdirectory/functions.php:122` — conditional references `real-wedding` and `couple` for profile.css enqueue.
  - `wp-content/themes/sandiegoweddingdirectory/archive-real-wedding.php`, `archive-website.php`, `single-real-wedding.php`, `single-website.php`, `taxonomy-real-wedding-{category,color,community,location,season,space-preferance,style,tag}.php`.
- Impact: dead template files confuse future devs; conditional CSS enqueue fails silently for these post types; documentation (`architecture.md:86-108`, `codebase-index.md:56-81`) describes an information architecture that does not match code.
- Fix approach: either (a) register `real-wedding` + `website` + their taxonomies in `sdwd-core` if PROJECT.md Phase 4/5 is confirmed in scope, or (b) delete the orphan theme files and strip the `real-wedding` / `couple` entries from the `functions.php:122` `is_singular()` call.

### Theme filter hook targets a plugin that no longer exists

- Issue: `functions.php:243` overrides the filter `sdweddingdirectory/post-cap/vendor` to `__return_false` — this hook was defined by the LEGACY `sdweddingdirectory` plugin (no longer in the plugin directory). The new `sdwd-core` does not use this filter at all.
- Files: `wp-content/themes/sandiegoweddingdirectory/functions.php:240-243`.
- Verification: `grep -rn "sdweddingdirectory/post-cap" wp-content/plugins/sdwd-core wp-content/plugins/sdwd-couple` returns zero matches.
- Impact: the comment says "Plugin defaults this to true which sets `create_posts => do_not_allow`" — but no plugin does that anymore. The filter is dead code. Memory references a "vendor CPT fix" that enables Add New Vendor in wp-admin; the actual vendor CPT in `sdwd-core/includes/post-types.php:49-77` uses default capabilities and `map_meta_cap => true`, so the filter is not needed.
- Fix approach: delete `functions.php:240-243` and the adjacent `sdweddingdirectory_icon_library` filter at `:245-254` (same problem — no consumer exists).

### `wp_print_styles` dequeues two stylesheet handles that are never enqueued

- Issue: `functions.php:175-181` calls `wp_dequeue_style('sdweddingdirectory-fontello')` and `'sdweddingdirectory-flaticon'` on `wp_print_styles`. Both handles were registered by the retired legacy plugin.
- Files: `wp-content/themes/sandiegoweddingdirectory/functions.php:175-181`.
- Impact: no-op; 4 wasted hook invocations per page load.
- Fix approach: delete.

---

## MEDIUM — Dead/fragile infrastructure

### Bootstrap-modal hide rule without a corresponding modal system

- Issue: `layout.css:687-690` has `.modal.fade { display: none; }` with a stale comment from an earlier attempt (`/* Hide plugin-injected Bootstrap modals until v2 modal system is built */`). There is no plugin injecting Bootstrap modals anymore (retired legacy plugin is gone), and the current theme's modal system DOES exist (`assets/js/modals.js`, `template-parts/modals/*.php`).
- Files: `wp-content/themes/sandiegoweddingdirectory/assets/css/layout.css:687-690`.
- Impact: the rule will silently hide any future legitimate `.modal.fade` element that ships from a third-party plugin (e.g., a WooCommerce notification, Rank Math survey).
- Fix approach: delete the rule; the current modal system uses its own class names.

### Vendor/venue architecture parity gap still unaddressed in code

- Issue: the `Documentation/vendor-venue-parity-audit.md:22-131` and founder's `parity-responses.md:3-17` (2026-04-06) commit to making vendors and venues structurally identical with separate user roles, flattened `venue-location` taxonomy, enabled venue archive, and a venue admin metabox. None of these commitments are in code yet.
- Files:
  - `wp-content/plugins/sdwd-core/includes/post-types.php:69,101` — both `vendor` AND `venue` have `has_archive => false`; founder wants both enabled with the rewrite structure `/vendors/<cat>/` and `/venues/<city>/`.
  - `wp-content/plugins/sdwd-core/includes/taxonomies.php:78-85` — `venue-location` is `hierarchical => true`; founder explicitly says location should be flat (city only).
  - `wp-content/plugins/sdwd-core/includes/roles.php:11-36` — `venue` role exists and has the same caps as `vendor`; no differentiation in capabilities and no venue-only features yet.
  - `wp-content/themes/sandiegoweddingdirectory/user-template/venue-dashboard.php` exists (203 lines) but there is no `venue-profile.php` parallel to `user-template/vendor-profile.php` — edit-form parity is incomplete.
- Impact: `PROJECT.md:194-206` flags this as the central architecture goal of the rebuild. Every week without this fix deepens the fork between the two profile flows.
- Fix approach: flip `has_archive` to true on both CPTs, re-register `venue-location` with `hierarchical => false`, clone `vendor-profile.php` → `venue-profile.php` with address/capacity fields added, and run the migration tool against any existing hierarchical location terms.

### No venue admin metabox for claim workflow owners

- Issue: `admin/venue-meta.php` covers business/location/capacity/social/hours/pricing but the venue-specific claim flow (`includes/claim.php:94-148`) relies on `post_author` being set. There is no admin UI to assign a venue to a user once a claim is approved outside the AJAX path — if the AJAX call fails, the only recovery is direct DB editing.
- Files:
  - `wp-content/plugins/sdwd-core/includes/claim.php:152-189` — approve flow calls `wp_update_post()` with new `post_author`.
  - `wp-content/plugins/sdwd-core/includes/admin/venue-meta.php` — no field for `post_author`.
- Impact: recovery from a partial approval (author transferred but `sdwd_post_id` user meta missing) requires raw SQL. No audit trail for claim history on the post beyond the single `sdwd_claim` meta blob.
- Fix approach: add a "Claim history" metabox that shows `sdwd_claim` in read-only form plus a "reassign owner" dropdown for admins.

### N+1 query in rating aggregation

- Issue: `sdwd_get_average_rating()` calls `sdwd_get_reviews( $post_id, -1 )` which runs a `get_posts` query returning every review, then iterates calling `get_post_meta( $review->ID, 'sdwd_rating', true )` for each — a separate DB round-trip per review unless the object cache preloads it (WordPress does preload post meta for post lists, but only when `update_post_meta_cache` is true and the posts are fetched via `WP_Query`).
- Files: `wp-content/plugins/sdwd-couple/modules/reviews.php:109-119`, calls at `:94-104`.
- Impact: at 50 reviews a request makes ~51 queries just to compute one star average. Every time a vendor card renders the rating, this runs.
- Fix approach: store a denormalized `sdwd_rating_avg` + `sdwd_rating_count` on the reviewed post, updated inside `sdwd_handle_submit_review()` after each insert/update. Reads become one `get_post_meta` call.

### Migration tool can run more than once without guardrails

- Issue: `sdwd_run_migration()` re-reads every vendor/venue/user on every submission of the "Run Migration" button. The tool is nonce-protected but not idempotent-logged — repeated runs quietly do work because each `if ( ! get_post_meta(..., $new_key, true ) )` check gates the write. However the bulk fetches use `numberposts => -1`, so with any real data volume this becomes a slow admin action.
- Files: `wp-content/plugins/sdwd-core/includes/migrate.php:60-225` (specifically `:67,132` — `'numberposts' => -1`).
- Impact: with 500+ imported wedding-wire vendors/venues the page will exceed PHP max_execution_time. No batching, no resume support.
- Fix approach: add chunked processing (batch of 50, return "run again" link) and record a `sdwd_migration_version` option so completed migrations are skipped.

### Reviews ordering and uniqueness race

- Issue: duplicate-review detection uses `author` + `meta_key`/`meta_value` `get_posts` — no `SELECT ... FOR UPDATE` and no unique index, so two near-simultaneous review submissions from the same couple for the same vendor both pass the check and both insert.
- Files: `wp-content/plugins/sdwd-couple/modules/reviews.php:58-70`.
- Impact: edge-case duplicate reviews leak past the "one review per business" rule.
- Fix approach: after the insert, re-query and delete the newer of any duplicates, OR add a composite unique constraint via custom-table. Low priority unless the feature ships at scale.

---

## MEDIUM — Tech debt

### Naming churn: plugin rename is complete, theme rename is NOT

- Issue: the plugin folder was renamed legacy `sdweddingdirectory` → `sdwd-core` and `sdweddingdirectory-couple` → `sdwd-couple`. The theme is named `sandiegoweddingdirectory` but still uses the text-domain `sdweddingdirectory-v2` throughout `functions.php` and `sdweddingdirectory` in template strings.
- Files:
  - `wp-content/themes/sandiegoweddingdirectory/functions.php:32,36,37,69,71,229,231` — `load_theme_textdomain( 'sdweddingdirectory-v2', ... )` and 6 other `'sdweddingdirectory-v2'` domains.
  - Any template using `esc_html__( 'X', 'sdweddingdirectory' )` — the domain string is inconsistent (some files use `sdweddingdirectory`, some `sdweddingdirectory-v2`). Example: `user-template/vendor-profile.php:47,54,57,...` uses `sdweddingdirectory`; `functions.php:69` uses `sdweddingdirectory-v2`.
  - `style.css` — worth auditing for `Theme Name:` mismatch.
- Impact: translation loading will silently no-op for every string in the wrong domain. No visible break until a translation is added.
- Fix approach: pick ONE text-domain (likely `sandiegoweddingdirectory`), global find-replace, and keep `style.css` header in sync.

### Theme still has `add_image_size` entries named for the retired plugin

- Issue: `functions.php:58-59` registers `sdweddingdirectory_img_600x470` and `sdweddingdirectory_img_360x480` with comment `Match legacy sizes used by plugins/templates`. No current template fetches those sizes.
- Files: `wp-content/themes/sandiegoweddingdirectory/functions.php:57-59`.
- Impact: WP regenerates these sizes on every upload, doubling storage. Low severity since `uploads/` is empty in dev.
- Fix approach: audit usage; remove or rename to `sdwd_*`.

### Theme's `inc/` already at the 3-file cap — but 2 of 3 are stub-ish

- Issue: `CLAUDE.md:28` says "Do NOT add files to /inc/ — it contains exactly 3 files and that is final." The 3 files are `sd-mega-navwalker.php` (485 lines, active), `venues.php` (816 lines), `vendors.php` (851 lines). Two of those three fire `sdweddingdirectory/...` filter hooks (`inc/venues.php:303,515`) that no consumer exists for.
- Files: `wp-content/themes/sandiegoweddingdirectory/inc/venues.php:303,515`.
- Impact: the filters are inert — they return their `$default` argument directly because no handler is attached. Fine functionally, but the code is unnecessary.
- Fix approach: strip the `apply_filters()` wrappers or document them as intentional extension points.

### `page-style-guide.php` is the largest template at 48KB with 394 inline styles

- Issue: `page-style-guide.php` contains 394 occurrences of `style="..."`, 300+ lines of Bootstrap and Font Awesome demo markup, and lives in the theme root as a page template.
- Files: `wp-content/themes/sandiegoweddingdirectory/page-style-guide.php` (entire file).
- Impact: the file meant to be "the rules" demonstrates breaking them. Larger than most actual page templates.
- Fix approach: rewrite using tokens only, remove banned class examples, or move it to a separate `style-guide` plugin that is de-activated in prod.

### Historical error spam in `wp-content/debug.log` (3,891 lines) reveals churn

- Issue: `wp-content/debug.log` has 1,939 PHP errors spanning Mar 16 – Apr 14 2026 from paths like `wp-content/themes/sdweddingdirectory/`, `sdweddingdirectory-v2/`, `sdweddingdirectory-final/`, `sdweddingdirectory-couple/...` — none of which exist in the current code. The log proves 3+ theme directories and the full legacy plugin stack were active at various points in recent history.
- Files: `wp-content/debug.log` (ignored by git per `.gitignore:14` but still on disk).
- Impact: debug log is so noisy that any new error from current code is a needle in a haystack.
- Fix approach: truncate `debug.log` at a known-clean commit, set `WP_DEBUG_LOG` to a date-rotated file, and delete references to non-existent theme paths in any WP admin configs.

### Dead debug markers left in theme enqueue

- Issue: `functions.php` (previous revisions, per `debug.log` grep) emitted `SDWD DEBUG enqueue: template= is_dash=...` lines; 3 such lines remain in `debug.log` dated 2026-04-14. Current `functions.php` appears clean, but worth verifying no `error_log()` is left behind.
- Files: `wp-content/debug.log` trailing entries; current `functions.php` — no grep hit for `error_log`.
- Fix approach: already resolved; monitoring only.

### Couple dashboard wires `$quotes = []` as a placeholder

- Issue: `user-template/vendor-dashboard.php:44-45` contains `// Quote requests list — wire to plugin data when the module lands.` followed by `$quotes = [];`.
- Files: `wp-content/themes/sandiegoweddingdirectory/user-template/vendor-dashboard.php:44-45`.
- Impact: vendor dashboards always show zero quote requests, even though `sdwd-couple/modules/request-quote.php:46-54` writes requests to couple-side meta. The vendor has no way to see incoming quotes.
- Fix approach: add a `sdwd_get_vendor_quote_requests( $vendor_id )` helper to `sdwd-core/includes/quote.php` that scans couple posts for `sdwd_quote_requests` entries matching the vendor, then wire it into the dashboard.

### Frontend dashboard cannot update the description (it can — but the form doesn't expose it everywhere)

- Issue: `sdwd_save_dashboard()` handles `$_POST['sdwd_description']` and updates `post_content` (`dashboard.php:71-76`), but `user-template/venue-dashboard.php` is missing a description field the way `vendor-profile.php:90-96` has one. Venue owners cannot edit their description.
- Files:
  - `wp-content/plugins/sdwd-core/includes/dashboard.php:71-76`
  - `wp-content/themes/sandiegoweddingdirectory/user-template/vendor-profile.php:90-96` (has field)
  - `wp-content/themes/sandiegoweddingdirectory/user-template/venue-dashboard.php` (verify — no `sdwd_description` textarea)
- Fix approach: add the description fieldset to venue-dashboard parallel to vendor-profile.

### `sdwd_vendor_rewrite_version` option never set for venues

- Issue: `inc/vendors.php:31-46` forces vendor-category rewrite base to `/vendors/` and flushes rewrites once via an options-versioned guard. `inc/venues.php` has no parallel mechanism — venue taxonomy rewrites can collide with the vendor one after cache purges.
- Files:
  - `wp-content/themes/sandiegoweddingdirectory/inc/vendors.php:31-46`
  - `wp-content/themes/sandiegoweddingdirectory/inc/venues.php` (no equivalent function)
- Fix approach: mirror the same `flush_rewrite_rules` + version-option pattern for venues; or move both into a single helper.

---

## MEDIUM — Performance

### Theme ships 155 MB of images

- Issue: `wp-content/themes/sandiegoweddingdirectory/assets/images/` is 152 MB, with 60 MB in `real-wedding/` (14 PNG files, several 4-5 MB each) and 13 MB in `_inbox icons and images/` (staging folder).
- Files:
  - `wp-content/themes/sandiegoweddingdirectory/assets/images/real-wedding/rw_jessica-david-{2,3,4}.png` — 5.7 MB, 5.9 MB, 4.4 MB.
  - `wp-content/themes/sandiegoweddingdirectory/assets/images/real-wedding/rw_emily-james-{1,2,3,4}.png` — 3.9-5.5 MB.
  - `wp-content/themes/sandiegoweddingdirectory/assets/images/_inbox icons and images/` — 13 MB, name prefixed with `_` suggests "not production".
  - `wp-content/themes/sandiegoweddingdirectory/assets/images/categories/*.png` — 11 MB total for category thumbnails.
- Impact: (1) theme clone/deploy slow; (2) git repo now 250 MB `.git` directory with 481 PNG and 182 JPG files tracked; (3) PROJECT.md `Phase 9` mentions ShortPixel optimization but PNGs this big on a wedding site kill LCP.
- Fix approach: convert real-wedding PNGs to WebP at 1920px max, reduce to <200 KB each; move `_inbox icons and images/` outside the theme (it's staging, not production); re-run `add_theme_support( 'post-thumbnails' )` with explicit smaller image sizes.

### `Documentation/screenshots/` is 155 MB with a 20 MB PNG

- Issue: `Documentation/screenshots/v1 theme screenshots/about.png` is 20.7 MB (verified via `git ls-files`). Total `Documentation/screenshots/` is 155 MB. These are tracked in git.
- Files: `Documentation/screenshots/v1 theme screenshots/about.png`, `Documentation/screenshots/Venues Page/footer.png` (10 MB), plus 200+ other tracked screenshots.
- Impact: git history bloat — new clones pay the full 250 MB download cost. CLAUDE.md and architecture.md both reference "screenshots/" as a source of truth, so they can't just be deleted.
- Fix approach: move reference screenshots to a separate repo (`sdwd-design-reference`) submoduled in, or commit compressed JPG/WebP versions only.

### `page-about.php` and `page-our-team.php` use `posts_per_page => -1`

- Issue: both stubs fetch all posts of some type without limit.
- Files:
  - `wp-content/themes/sandiegoweddingdirectory/page-about.php:28` — `'posts_per_page' => -1`
  - `wp-content/themes/sandiegoweddingdirectory/page-our-team.php:22` — `'posts_per_page' => -1`
- Impact: these pages are stubs (per `codebase-index.md:47-51`) so impact is zero today. Once populated, the team CPT and any dynamic about-page query will load everything.
- Fix approach: set explicit `posts_per_page` (e.g., 50) or add pagination.

### No transient/object caching for frequently queried filters

- Issue: `inc/vendors.php` and `inc/venues.php` run taxonomy queries on archive/landing pages (816 + 851 lines of helpers that include `get_terms`, `wp_get_post_terms`, and custom `WP_Query` calls). None of these results are wrapped in transients.
- Files: `wp-content/themes/sandiegoweddingdirectory/inc/vendors.php`, `inc/venues.php` (throughout).
- Impact: every vendors landing or venue landing hit rebuilds term trees. W3 Total Cache's object cache helps but isn't a substitute for explicit cache keys.
- Fix approach: wrap the term queries in `get_transient`/`set_transient` with a 1-hour TTL, and invalidate on `edited_term` / `created_term` hooks for the relevant taxonomies.

---

## MEDIUM — Fragile/tangled areas

### Email-as-username coupling

- Issue: registration uses the submitted email as BOTH `user_login` and `user_email` (`auth.php:56`). The frontend "change email" flow does not update `user_login` — only `sdwd_email` post meta (`dashboard.php:52-63`). After one email change, the displayed contact email and the login username diverge.
- Files:
  - `wp-content/plugins/sdwd-core/includes/auth.php:56` — `wp_create_user( $email, $password, $email );`
  - `wp-content/plugins/sdwd-core/includes/dashboard.php:52-63` — only updates post meta, not `wp_update_user` with a new email.
- Impact: vendor changes contact email to `new@biz.com`, then can no longer log in as `old@biz.com` (still valid) unless they remember. Support burden.
- Fix approach: either treat `user_login` as immutable and surface it read-only in the dashboard, OR update `wp_update_user(['ID' => $user->ID, 'user_email' => $new_email])` alongside the post meta.

### Two parallel profile save code paths (admin metabox + AJAX dashboard) diverge subtly

- Issue: `admin/vendor-meta.php:213-284`, `admin/venue-meta.php:262-341`, `admin/couple-meta.php:116-152` and `dashboard.php:15-141` all sanitize and save the same `sdwd_*` meta keys but with slightly different rules (dashboard limits pricing to 3 tiers and features to 10; admin metabox uses same limits; but dashboard ALSO updates `post_content`, `first_name`/`last_name` on the user, and password — admin metabox only updates password). A change to the field list in one path must be replicated manually in the other.
- Files: the four files above, all four save paths.
- Impact: every new field requires editing two files. Over time they drift.
- Fix approach: extract a shared `sdwd_persist_profile_fields( $post_id, $user_id, $input, $role )` helper in `sdwd-core/includes/` and call it from both admin and AJAX save.

### Duplicate repeater-field JS across admin and frontend dashboards

- Issue: `sdwd-core/assets/admin.js` handles repeater add/remove for admin metaboxes; `themes/.../assets/js/dashboard.js:42-78` implements the same logic for the frontend. Two copies, two places to fix when the pattern changes.
- Files:
  - `wp-content/plugins/sdwd-core/assets/admin.js`
  - `wp-content/themes/sandiegoweddingdirectory/assets/js/dashboard.js:42-78`
- Fix approach: extract a `repeater.js` loaded by both contexts, or live with duplication and document it.

### `email_exists` is the only duplicate-registration guard

- Issue: `auth.php:46` calls `email_exists( $email )` but not `username_exists($email)`. Because `wp_create_user($email, ..., $email)` uses the same string for both, this is usually fine — but `wp_create_user` silently fails if the username is invalid (contains spaces, too long, etc.) without a clear user-facing message.
- Files: `wp-content/plugins/sdwd-core/includes/auth.php:41-59`.
- Impact: a user with an unusual email (`user+tag@example.com`, `very.long.email.address.with.more.than.sixty.characters.here@example.com`) gets an opaque error.
- Fix approach: validate `sanitize_user($email, true)` length and content before calling `wp_create_user`, and surface the specific failure reason.

### Venue CPT supports `has_archive => false` but the theme has `archive-venue.php`

- Issue: `post-types.php:101` sets `has_archive => false` for venues yet `archive-venue.php` exists (listed in `architecture.md:86`). WordPress will never route to it.
- Files: `wp-content/plugins/sdwd-core/includes/post-types.php:101`, `wp-content/themes/sandiegoweddingdirectory/archive-venue.php`.
- Impact: developers edit `archive-venue.php` and the changes never render. The page at `/venues/` is served by `page-venues.php` (template assigned to a WP page), which is different behavior.
- Fix approach: per the founder's parity direction in `parity-responses.md:15`, flip `has_archive` to true and fold `page-venues.php` logic into `archive-venue.php`.

---

## LOW — Cleanup

### `.vscode/settings.json` committed but folder is in `.gitignore`

- Issue: `.gitignore:30` excludes `.vscode/` but the folder exists with `settings.json`. If the ignore ever loses the entry, the settings leak.
- Files: `.vscode/settings.json`.
- Fix approach: verify not tracked (`git ls-files .vscode/settings.json`), leave it in ignore, or commit an `editorconfig` equivalent instead.

### `Icon?` file at repo root (macOS artifact)

- Issue: root-level `Icon` file (0 bytes). `.gitignore:51` mentions `Icon?` but the file itself sits in the tree.
- Files: `/Icon`
- Fix approach: delete.

### `TASK_LOG.md` is empty

- Issue: `Documentation/TASK_LOG.md` is 0-1 bytes. If it's meant as a running log it should be populated or deleted; the file is referenced implicitly by the Documentation folder listing.
- Files: `Documentation/TASK_LOG.md`.
- Fix approach: delete or populate with recent commit summaries.

### Two style.css files may exist — `style.css` at theme root (metadata only)

- Issue: `style.css` is listed as "Theme metadata only (name, version). No actual styles." (`codebase-index.md:15`). Worth verifying the `Version:` header matches `SDWEDDINGDIRECTORY_THEME_VERSION`.
- Files: `wp-content/themes/sandiegoweddingdirectory/style.css`.
- Fix approach: spot-check on next version bump.

### `debug-menu.php` referenced in debug.log

- Issue: `debug.log` shows `PHP Warning: foreach() argument must be of type array|object, null given in /var/www/html/wp-content/debug-menu.php on line 5`. This file is not tracked and appears to be a dev-only helper dropped into wp-content.
- Files: (not in repo) — user has a local `wp-content/debug-menu.php`.
- Fix approach: delete local artifact; ensure `.gitignore` covers `debug-menu.php`.

### `debug.log` size — 3,891 lines, 90% noise

- Issue: same debug log concern as above; low priority because the file is gitignored.
- Files: `wp-content/debug.log`.
- Fix approach: truncate when convenient.

---

## Test coverage gaps

### There are no automated tests — at all

- Issue: no `tests/`, no `phpunit.xml`, no `package.json`, no Jest, no Playwright. No dev dependency declarations. Every change is manual-verified in the browser.
- Files: repo root — no `tests/`, no `*.test.php`, no `package.json`.
- Risk areas that silently break unnoticed:
  - The 5 `sdwd-couple` nonce-less endpoints (see HIGH above) — would be caught by a one-line AJAX smoke test.
  - Claim approval transfer of `post_author` — would be caught by an integration test.
  - Migration idempotence — would be caught by running the tool twice in a test DB.
  - Vendor/venue taxonomy rewrite collisions — would be caught by a rewrite-rule snapshot test.
- Priority: Medium. Start with a WP_UnitTestCase suite for `sdwd-core/includes/auth.php`, `claim.php`, `dashboard.php` handlers plus a smoke test for each `sdwd-couple` module's AJAX endpoint — these are the data-mutating paths and the ones most likely to silently regress during the parity rebuild.
- Fix approach: add `wp-env` or `wp-browser`, a minimal `phpunit.xml.dist`, and ~15 tests against `sdwd-core` handlers. Skip theme visual testing for now.

### Plugin parity doc lists features "MISSING" with no detection mechanism

- Issue: `Documentation/plugin-parity-audit.md:382-398` enumerates 14 legacy features missing from the new plugins (guest list, RSVP, seating chart, couple website, review responses, lead history, etc.). Nothing in the code tells you what's missing — a new contributor must read this doc.
- Priority: Low. The parity audit doc IS the tracker; just keep it updated.

---

*Concerns audit: 2026-04-23*
