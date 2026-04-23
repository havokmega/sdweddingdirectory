# Testing Patterns

**Analysis Date:** 2026-04-23

## Summary

**There are no automated tests in this workspace.**

No PHPUnit, no Pest, no Jest, no Playwright, no Cypress, no WP-CLI test suites. No CI pipeline. All testing is manual, performed in a local Docker WordPress environment.

## Evidence Gathered

**Test directories:** none detected.
- `find wp-content/{plugins/sdwd-core,plugins/sdwd-couple,themes/sandiegoweddingdirectory} -type d -name tests -o -name test -o -name __tests__ -o -name phpunit` → no results

**Test runner config:** none detected.
- No `phpunit.xml` or `phpunit.xml.dist` in repo root, custom plugins, or theme
- No `composer.json` / `composer.lock` (so no dev-dependency for PHPUnit, Brain Monkey, WP_Mock, or similar)
- No `package.json` / `package-lock.json` / `yarn.lock` in custom code
- No `.phpcs.xml` / `phpcs.xml.dist` / `.php-cs-fixer.php` — no static-analysis config either

**Test files:** none detected.
- `find -name "*.test.php" -o -name "*Test.php" -o -name "test-*.php"` (custom code only) → no results

**CI directories:** none detected.
- No `.github/workflows/` directory
- No `.gitlab-ci.yml`, no `.circleci/`, no `bitbucket-pipelines.yml`
- No `.travis.yml`, no `Jenkinsfile`

**Editor config:**
- `.vscode/settings.json` exists but contains only `vscode-aiconfig` Python interpreter settings — nothing related to PHP testing or linting

## Third-Party Plugin Tests (Out of Scope)

The bundled third-party plugins (`wordfence`, `updraftplus`, `w3-total-cache`, `seo-by-rank-math`, `wp-mail-smtp`, `shortpixel-image-optimiser`) ship with their own vendor code under `wp-content/plugins/<plugin>/vendor/` which may contain test files from upstream. These are:

- **Not** part of this project's test suite
- **Not** executed, maintained, or reviewed here
- Excluded from every exploration command in this analysis

## Manual Testing — How It Works Today

### Local Environment

The project runs in Docker via `docker-compose.yml` at the workspace root (file is gitignored per `.gitignore` line 21-22 but tracked in this checkout). Testing is done by:

1. Bringing up WordPress + MariaDB + optional SSH container via `docker compose up`
2. Loading `localhost:<port>` in a browser
3. Exercising flows manually: registration, login, claim, quote, dashboard save, wishlist, budget, checklist, reviews

### Debug Surface

- **WP_DEBUG:** toggled in `wp-config.php` (gitignored)
- **Debug log:** `wp-content/debug.log` (gitignored via `.gitignore` line 14). Populated only when `WP_DEBUG_LOG` is enabled
- **Browser devtools:** primary way to inspect AJAX requests (`admin-ajax.php`) and `wp_send_json_success` / `wp_send_json_error` responses
- **Wordfence logs:** the active security plugin keeps its own logs under `wp-content/wflogs/`

### No `error_log()` in Custom Code

A grep across the three custom code directories returns **zero** `error_log()` calls. There is no custom log channel to tail.

## What Would Need to Exist for Automated Testing

**Currently missing — document as future work, not tech debt unless founder prioritizes:**

### To add PHPUnit for the plugins

1. Add `composer.json` at workspace root or per-plugin with:
   - `"require-dev": { "phpunit/phpunit": "^9", "brain/monkey": "^2" }` (or `yoast/wp-test-utils`)
2. Add `phpunit.xml.dist` pointing at a `tests/` directory
3. Create `tests/bootstrap.php` that either:
   - Uses **Brain Monkey** / **WP_Mock** for pure-unit tests that mock WP functions, or
   - Uses **wp-phpunit/wp-phpunit** + `wp-tests-config.php` for integration tests against a real WP test DB
4. Structure tests mirroring source:
   - `tests/Unit/Core/AuthTest.php` for `sdwd-core/includes/auth.php`
   - `tests/Integration/ClaimFlowTest.php` for end-to-end claim approval
5. Run locally: `vendor/bin/phpunit` (from workspace or plugin root)

### To add JS tests (theme)

- Not currently practical — no build step, no bundler, no `package.json`. The JS surface is small (`assets/js/app.js` ~19KB, `modals.js` ~5KB, `dashboard.js` ~2KB) and tightly coupled to the DOM produced by PHP templates
- End-to-end with Playwright would be more valuable than unit-testing individual JS files

### To add CI

- `.github/workflows/ci.yml` would be the natural location (the repo is a `git` repo per the workspace listing, though remote provider wasn't inspected)
- A minimal pipeline would: spin up WP + MariaDB, run PHPUnit, run PHPCS against WPCS, run a smoke E2E

## Coverage Status

**Coverage: 0%.** There is no measurement because there are no tests.

Code-coverage tooling (Xdebug, PCOV) is not configured in `Dockerfile.ssh` or `php.ini`.

## Run Commands

**There are no test commands.** There is nothing to run.

For manual verification, the following ad-hoc commands are used:

```bash
# Bring environment up
docker compose up -d

# Tail debug log if WP_DEBUG_LOG is enabled
docker compose exec wordpress tail -f /var/www/html/wp-content/debug.log

# Run the data migration (admin UI action, not a CLI command)
# → wp-admin → Tools → SDWD Migration → "Run Migration"
```

## Test Types

**Unit tests:** none.

**Integration tests:** none.

**E2E tests:** none.

**Smoke-test checklists:** not tracked in-repo. Any QA runbook lives outside the codebase (see `Documentation/` folder for founder-facing notes, but no test checklists were observed).

## Common Patterns (Would Apply If Tests Existed)

The custom code is reasonably testable:

- **AJAX handlers** (`sdwd_handle_register`, `sdwd_handle_submit_claim`, etc.) are pure procedural functions that read `$_POST`, call WP core functions, and call `wp_send_json_*()`. With Brain Monkey mocking `check_ajax_referer`, `wp_create_user`, `wp_send_json_error`, `wp_send_json_success`, these could be unit-tested directly.
- **Helpers** (`sdwd_is_unclaimed`, `sdwd_get_pending_claim`, `sdwd_get_dashboard_url`, `sdwdv2_get_vendor_company_name`, `sdwdv2_build_vendor_query_args`) are small, deterministic, and would unit-test cleanly.
- **Migration** (`sdwd_run_migration` in `sdwd-core/includes/migrate.php`) returns a log array — ideal for assertion-based integration tests against a fresh WP test DB.

**No rewriting is required to add tests** — the existing code is test-friendly even without a DI container or class structure. The gap is purely tooling.

## Honesty Note

Per the instruction to be honest about code quality: **this project ships to production with zero automated test coverage on custom code.** The mitigating factors are:

1. Small surface area (2 custom plugins + 1 theme)
2. High-security WP primitives (nonces, capability checks, sanitization) applied consistently — see `CONVENTIONS.md` → Security Patterns
3. Wordfence in front of the site at runtime
4. Founder performs manual QA before releases

The unmitigated risks are:

1. Refactors have no safety net — regressions land in production
2. The text-domain inconsistency documented in `CONVENTIONS.md` (e.g. `sdweddingdirectory-v2` loaded but `sdweddingdirectory` used in `style.css`) is exactly the kind of defect an integration test would catch
3. Data migrations (`sdwd_run_migration`) are only safe because they run in admin with manual oversight — no dry-run, no rollback, no test-DB rehearsal

**This belongs in `CONCERNS.md`** if the project ever commissions a tech-debt audit.

---

*Testing analysis: 2026-04-23*
