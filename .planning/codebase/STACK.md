# Technology Stack

**Analysis Date:** 2026-04-23

## Languages

**Primary:**
- PHP — all WordPress theme and plugin code (`wp-content/themes/sandiegoweddingdirectory/`, `wp-content/plugins/sdwd-core/`, `wp-content/plugins/sdwd-couple/`)
- CSS — custom design-token stylesheets in `wp-content/themes/sandiegoweddingdirectory/assets/css/`
- JavaScript — vanilla JS only, three files: `assets/js/app.js`, `assets/js/modals.js`, `assets/js/dashboard.js`

**Secondary:**
- SQL — MySQL 8.0 database managed by WordPress core (no hand-authored migrations in repo)
- Dockerfile / Compose YAML — `docker-compose.yml`, `Dockerfile.ssh`
- Apache rewrite rules — `.htaccess` (permalink routing)

## Runtime

**Environment:**
- WordPress — `wordpress:latest` official image (Apache + PHP). Declared in `docker-compose.yml` line 17.
- PHP — provided by the `wordpress:latest` image (version pinned by upstream Docker tag, not in-repo).
- MySQL — `mysql:8.0` (docker-compose.yml line 5).
- Apache — shipped with `wordpress:latest`. Rewrite config in `.htaccess`.

**Package Manager:**
- None at the project level. WordPress plugins and themes are committed directly to `wp-content/`. No `composer.json`, `package.json`, or lockfile exists in the repo.

## Frameworks

**Core:**
- WordPress — core CMS. Version not committed to the repo (see `.gitignore` lines 7-8: `wp-admin/` and `wp-includes/` are ignored). Theme target compatibility defined in `wp-content/themes/sandiegoweddingdirectory/style.css` (Theme Version 2.1.0).

**Testing:**
- Not detected. No PHPUnit, no Jest, no testing config files in the repo.

**Build/Dev:**
- No build step. CSS and JS are served directly from `wp-content/themes/sandiegoweddingdirectory/assets/` with no compilation or bundling.
- Asset cache-busting uses `filemtime()` in `wp-content/themes/sandiegoweddingdirectory/functions.php` (lines 88-91).

## Key Dependencies

**Custom plugins (in repo):**
- `wp-content/plugins/sdwd-core/` — SDWD Core 1.0.0. Registers `couple`, `vendor`, `venue` post types, `vendor-category` / `venue-type` / `venue-location` taxonomies, three user roles, auth/AJAX handlers, claim system, dashboard save handler, quote request handler. Entry point: `wp-content/plugins/sdwd-core/sdwd-core.php`.
- `wp-content/plugins/sdwd-couple/` — SDWD Couple 1.0.0. Requires SDWD Core. Registers `sdwd_review` post type plus modules for reviews, quote requests, wishlist, checklist, budget. Entry point: `wp-content/plugins/sdwd-couple/sdwd-couple.php`.

**Third-party plugins (committed in repo, activation state not verifiable from code):**
- `wp-content/plugins/seo-by-rank-math/rank-math.php` — Rank Math SEO 1.0.265
- `wp-content/plugins/wp-mail-smtp/wp_mail_smtp.php` — WP Mail SMTP 4.7.1
- `wp-content/plugins/wordfence/wordfence.php` — Wordfence Security 8.1.4
- `wp-content/plugins/updraftplus/updraftplus.php` — UpdraftPlus Backup/Restore 1.26.2
- `wp-content/plugins/w3-total-cache/w3-total-cache.php` — W3 Total Cache 2.9.2
- `wp-content/plugins/shortpixel-image-optimiser/wp-shortpixel.php` — ShortPixel Image Optimizer 6.4.3

**Deliberately excluded dependencies (per `CLAUDE.md` / `AGENTS.md`):**
- No Bootstrap, Font Awesome, jQuery, Google Fonts, Elementor, page builders, or external CSS/JS frameworks.

**Infrastructure:**
- WP-CLI — installed inside the `wp_ssh` container via `Dockerfile.ssh` lines 12-13 (downloaded from `https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar`).
- OpenSSH server — inside `wp_ssh` container for remote shell access on port 2222 (`Dockerfile.ssh` lines 6, 15-18, 22).
- default-mysql-client / mysql-client — inside `wp_ssh` container for DB access (`Dockerfile.ssh` line 9).

## Configuration

**Environment:**
- Database credentials are set as plaintext environment variables in `docker-compose.yml` lines 8-12 and 25-28 (development defaults: `wordpress` / `wordpress` for user/password, `root` for MySQL root). No `.env` file is committed (`.gitignore` line 41).
- `wp-config.php` and `wp-config-docker.php` are in `.gitignore` (lines 17-18). The `wordpress:latest` image generates these using the `WORDPRESS_DB_*` env vars at container start.

**Key configs required:**
- `MYSQL_DATABASE`, `MYSQL_USER`, `MYSQL_PASSWORD`, `MYSQL_ROOT_PASSWORD` — `db` service (docker-compose.yml lines 9-12)
- `WORDPRESS_DB_HOST`, `WORDPRESS_DB_USER`, `WORDPRESS_DB_PASSWORD`, `WORDPRESS_DB_NAME` — `wordpress` and `wp_ssh` services (docker-compose.yml lines 25-28, 46-49)

**Build:**
- `docker-compose.yml` — three services: `db` (MySQL), `wordpress` (public site on port 8080), `wp_ssh` (build from `Dockerfile.ssh`, SSH on port 2222, shares `wp_data` and `wp-content` volumes with `wordpress`).
- `Dockerfile.ssh` — extends `wordpress:latest`, adds bash, OpenSSH server, curl, less, MySQL client, and WP-CLI.
- `php.ini` — mounted into container at `/usr/local/etc/php/conf.d/uploads.ini` (docker-compose.yml line 33). Sets `upload_max_filesize = 256M`, `post_max_size = 256M`, `memory_limit = 512M`, `max_execution_time = 300`.
- `.htaccess` — mounted into container at `/var/www/html/.htaccess`. Contains the standard WordPress rewrite block plus an SDWD permalink-conflict fix for `/wedding-inspiration/<slug>/` routes (category vs single post disambiguation).

## Platform Requirements

**Development:**
- Docker + Docker Compose — the only supported local runtime. Run `docker compose up -d` from the repo root to start the three services.
- Ports exposed on host: `8080` (WordPress HTTP), `2222` (SSH to `wp_ssh`).
- Root login on the SSH container uses `root` / `root` (defined in `Dockerfile.ssh` line 16) — dev-only, never ship to production.

**Production:**
- Not defined in this repo. `.gitignore` excludes `docker-compose.yml`, `Dockerfile.ssh`, `.htaccess`, `php.ini`, and WordPress core directories — production infra lives outside this repo. Per `PROJECT.md` Section 6, Wordfence is flagged as "staging/live only."

**Theme / plugin targets:**
- Theme `wp-content/themes/sandiegoweddingdirectory/style.css` — Version 2.1.0, Text Domain `sandiegoweddingdirectory`.
- Custom plugins use modern PHP (closures, short array syntax, arrow function-less closures, `wp_send_json_*`). `wp-mail-smtp` header (`wp-content/plugins/wp-mail-smtp/wp_mail_smtp.php` lines 5-6) declares `Requires at least: 5.5`, `Requires PHP: 7.4` — compatible with the stack.

## Theme Assets

**Fonts (self-hosted, `wp-content/themes/sandiegoweddingdirectory/assets/fonts/`):**
- `Inter-VariableFont_slnt,wght.woff2` — headings
- `WorkSans-VariableFont_wght.woff2` — body

**Icon font (`wp-content/themes/sandiegoweddingdirectory/assets/library/sdwd-icons/`):**
- Custom icomoon build — `style.css`, `fonts/icomoon.woff`, `fonts/icomoon.ttf`, `fonts/icomoon.eot`, `fonts/icomoon.svg`. Replaces Font Awesome per `CLAUDE.md` rules.

**Stylesheets (`wp-content/themes/sandiegoweddingdirectory/assets/css/`):**
- Foundation tier: `foundation.css`, `components.css`, `layout.css` (loaded on every page in this order — see `functions.php` lines 96-99).
- Page tier (conditionally enqueued in `functions.php` lines 102-158): `pages/home.css`, `pages/archive.css`, `pages/venues.css`, `pages/venues-landing.css`, `pages/vendors.css`, `pages/profile.css`, `pages/planning.css`, `pages/blog.css`, `pages/static.css`, `pages/dashboard.css`, `pages/vendor-dashboard.css`, `pages/couple-dashboard.css`, `pages/modals.css`, `pages/inspiration.css`.

**Scripts (`wp-content/themes/sandiegoweddingdirectory/assets/js/`):**
- `app.js` — nav toggles, header hide-on-scroll, mega menu hover debounce, carousels (enqueued globally).
- `modals.js` — login/registration/forgot-password AJAX, enqueued only for logged-out users (`functions.php` lines 161-168).
- `dashboard.js` — AJAX profile save, social row repeater, enqueued only on dashboard templates (`functions.php` lines 143-150).

## Active Theme

- **Theme slug:** `sandiegoweddingdirectory`
- **Theme path:** `wp-content/themes/sandiegoweddingdirectory/`
- **Version:** 2.1.0 (from `style.css`)
- **Author declared:** SDWeddingDirectory
- **Per `CLAUDE.md`:** this is the ONLY theme directory; creating, renaming, or importing other themes is forbidden.

## Editor / IDE

- `.vscode/settings.json` — configures a Python interpreter path and env-file path for a local VS Code AI extension. No project-specific lint, formatter, or build tasks defined.

---

*Stack analysis: 2026-04-23*
