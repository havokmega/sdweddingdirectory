# External Integrations

**Analysis Date:** 2026-04-23

## APIs & External Services

**Mapping / Location:**
- Google Maps — embedded `<iframe>` only (no API key, no JS SDK).
  - `wp-content/themes/sandiegoweddingdirectory/single-venue.php` line 281: `https://maps.google.com/maps?q=<address>&output=embed`
  - `wp-content/themes/sandiegoweddingdirectory/inc/venues.php` line 815: helper `sdwdv2_venue_map_embed_url()` returns `https://www.google.com/maps?q=<location>&z=14&output=embed`
  - SDK/Client: none — plain iframe URL, no authentication.

**SEO:**
- Rank Math SEO — bundled at `wp-content/plugins/seo-by-rank-math/rank-math.php` v1.0.265. Integrates with Google Search Console, Bing Webmaster, Google Analytics per plugin's own settings (no SDWD code references these). Not configured from SDWD source; listed as TODO in `PROJECT.md` Section 6.

**Image optimization:**
- ShortPixel — bundled at `wp-content/plugins/shortpixel-image-optimiser/wp-shortpixel.php` v6.4.3. Sends uploads to ShortPixel API for compression. Not configured from SDWD source; listed as TODO in `PROJECT.md` Section 6. Auth: ShortPixel API key (configured via plugin admin UI, not in repo).

**Security / Firewall:**
- Wordfence — bundled at `wp-content/plugins/wordfence/wordfence.php` v8.1.4. Maintains its own malware-signature and IP-reputation feeds via Wordfence API. Per `PROJECT.md` Section 6, intended for "staging/live only."

**No other external API integrations detected in SDWD theme or plugin code** — no Stripe, PayPal, reCAPTCHA, Mapbox, Mailchimp, Hotjar, Intercom, or analytics SDKs in `wp-content/themes/sandiegoweddingdirectory/`, `wp-content/plugins/sdwd-core/`, or `wp-content/plugins/sdwd-couple/`.

## Data Storage

**Databases:**
- MySQL 8.0 (`mysql:8.0` Docker image, service name `db`, container `wp_db`).
  - Connection from WordPress: `db:3306` (docker-compose.yml line 25).
  - Database name: `wordpress` (docker-compose.yml line 9).
  - Client: WordPress core `$wpdb`. No custom ORM; plugins use `get_post_meta` / `update_post_meta` / `get_user_meta` / `update_user_meta` / `register_post_type` / `register_taxonomy`.
  - Persistent volume: `db_data` (docker-compose.yml lines 14, 57).

**File Storage:**
- Local filesystem only. WordPress uploads live in `wp-content/uploads/` on the container volume `wp_data` (docker-compose.yml line 30) bind-mounted via `./wp-content:/var/www/html/wp-content` (line 31).
- `wp-content/uploads/` is excluded from git (`.gitignore` line 8) but physically present in the workspace.
- No S3, Cloudinary, Backblaze, or external object-storage integration in SDWD code. (UpdraftPlus supports remote backup destinations including S3, Google Drive, Dropbox, Azure, Backblaze, etc. — see `wp-content/plugins/updraftplus/methods/` — but those are UpdraftPlus-managed, not referenced from SDWD code.)

**Caching:**
- W3 Total Cache bundled at `wp-content/plugins/w3-total-cache/w3-total-cache.php` v2.9.2. Not referenced from SDWD code. Listed as TODO in `PROJECT.md` Section 6.
- `.gitignore` lines 14, 54-56 exclude cache artifact paths (`wp-content/cache/`, `wp-content/w3tc-config/`, `wp-content/advanced-cache.php`, `wp-content/speedycache-config/`).

## Authentication & Identity

**Auth Provider:**
- WordPress native users only. No OAuth, SSO, or external identity provider.

**Implementation:**
- Custom frontend auth AJAX handlers in `wp-content/plugins/sdwd-core/includes/auth.php`:
  - `sdwd_register` (action `wp_ajax_nopriv_sdwd_register`, handler `sdwd_handle_register`, lines 18-121) — creates a WordPress user with role `couple`, `vendor`, or `venue`.
  - `sdwd_login` (action `wp_ajax_nopriv_sdwd_login`, handler `sdwd_handle_login`, lines 125-161) — validates via `get_user_by` + `wp_check_password`, sets auth cookie.
  - `sdwd_forgot_password` (action `wp_ajax_nopriv_sdwd_forgot_password`, handler `sdwd_handle_forgot_password`, lines 165-186) — calls WordPress `retrieve_password()` which sends the reset email via `wp_mail`.
- Three custom roles registered in `wp-content/plugins/sdwd-core/includes/roles.php`: `couple`, `vendor`, `venue`.
- Frontend roles are redirected away from `wp-admin` and have the admin bar hidden (`auth.php` lines 193-222).
- All auth forms hit the front-end via `admin-ajax.php` with `sdwd_auth_nonce` / `sdwd_dashboard_nonce` / `sdwd_claim_nonce` / `sdwd_quote_nonce` / `sdwd_review_nonce` / `sdwd_wishlist_nonce`.

**Claim flow:**
- `wp-content/plugins/sdwd-core/includes/claim.php` — users claim unclaimed (admin-authored) vendor/venue posts. Admin approves/rejects via meta box; on approval, `post_author` is transferred to the claimant (lines 154-189).

## Email

**Transport:**
- WordPress `wp_mail()` is the only email API used by SDWD code:
  - Quote requests — `wp-content/plugins/sdwd-core/includes/quote.php` line 54 (vendor notification).
  - Couple-initiated quote requests — `wp-content/plugins/sdwd-couple/modules/request-quote.php` line 71 (vendor notification).
  - Password reset — `wp-content/plugins/sdwd-core/includes/auth.php` line 180 via `retrieve_password()`.
- Recipient resolution: `get_post_meta( $post_id, 'sdwd_email', true )` on the vendor/venue post, falling back to `get_option( 'admin_email' )` in `quote.php` line 34.

**SMTP / Delivery:**
- WP Mail SMTP plugin bundled at `wp-content/plugins/wp-mail-smtp/wp_mail_smtp.php` v4.7.1. Supported provider adapters shipped in `wp-content/plugins/wp-mail-smtp/src/Providers/`: `AmazonSES/`, `ElasticEmail/`, `Gmail/`, `Mail/`, `MailerSend/`, `Mailgun/`, `Mailjet/`, `Mandrill/`, `Outlook/`, `Pepipost/`, `PepipostAPI/`, `Postmark/`, `Resend/`, `SMTPcom/`, `SendGrid/`, `SendLayer/`, `SendInBlue/` (Brevo), plus generic `SMTP/`.
- No provider is wired up from SDWD code or `wp-config*.php` (wp-config files are gitignored). Listed as TODO in `PROJECT.md` Section 5: "Activate and configure `wp-mail-smtp` plugin."

## Monitoring & Observability

**Error Tracking:**
- None. No Sentry, Rollbar, Bugsnag, or custom error service integration.

**Logs:**
- `wp-content/debug.log` (513 KB, present on disk, ignored via `.gitignore` line 14). Produced by WordPress `WP_DEBUG_LOG` when enabled in `wp-config.php`.
- Apache access/error logs ship to container stdout/stderr (standard `wordpress:latest` image behavior).
- SSH-accessible shell in `wp_ssh` container (port 2222) allows ad-hoc inspection. WP-CLI is available inside that container (`Dockerfile.ssh` lines 12-13).

## CI/CD & Deployment

**Hosting:**
- Not defined in this repo. Docker-compose stack is development-only. Production deployment target is out of scope for the files committed here.

**CI Pipeline:**
- None detected. No `.github/`, `.gitlab-ci.yml`, `bitbucket-pipelines.yml`, or similar in the repo root.

## Environment Configuration

**Required env vars (`docker-compose.yml`):**
- `MYSQL_DATABASE` = `wordpress` (line 9)
- `MYSQL_USER` = `wordpress` (line 10)
- `MYSQL_PASSWORD` = `wordpress` (line 11) — dev-only default
- `MYSQL_ROOT_PASSWORD` = `root` (line 12) — dev-only default
- `WORDPRESS_DB_HOST` = `db:3306` (lines 25, 46)
- `WORDPRESS_DB_USER` = `wordpress` (lines 26, 47)
- `WORDPRESS_DB_PASSWORD` = `wordpress` (lines 27, 48)
- `WORDPRESS_DB_NAME` = `wordpress` (lines 28, 49)

**Secrets location:**
- Not centralized in the repo. `.env` is gitignored (`.gitignore` line 41) but no `.env` file currently exists at the repo root. Dev credentials are inlined in `docker-compose.yml` in plaintext. Production credentials must be provided via the actual WordPress `wp-config.php` (gitignored, line 17) on the deployment target.

## Webhooks & Callbacks

**Incoming:**
- WordPress `admin-ajax.php` endpoints registered by SDWD plugins (all CSRF-protected via `check_ajax_referer`):
  - `sdwd_register`, `sdwd_login`, `sdwd_forgot_password` — `wp-content/plugins/sdwd-core/includes/auth.php`
  - `sdwd_save_dashboard` — `wp-content/plugins/sdwd-core/includes/dashboard.php`
  - `sdwd_submit_claim`, `sdwd_approve_claim`, `sdwd_reject_claim` — `wp-content/plugins/sdwd-core/includes/claim.php`
  - `sdwd_send_quote` — `wp-content/plugins/sdwd-core/includes/quote.php`
  - `sdwd_submit_review` — `wp-content/plugins/sdwd-couple/modules/reviews.php`
  - `sdwd_request_quote` — `wp-content/plugins/sdwd-couple/modules/request-quote.php`
  - `sdwd_toggle_wishlist` — `wp-content/plugins/sdwd-couple/modules/wishlist.php`
- No REST API controllers registered by SDWD code. All three custom post types set `show_in_rest => false` (`post-types.php` lines 44, 76, 108).

**Outgoing:**
- None from SDWD code. No `wp_remote_get` / `wp_remote_post` / `curl` / `fopen` to external services in theme or custom plugins. Third-party plugins (Rank Math, Wordfence, UpdraftPlus, ShortPixel, WP Mail SMTP) make their own outbound calls when configured.

## Third-Party Plugin Integrations

The following plugins ship in `wp-content/plugins/` but are not referenced from SDWD code. Per `PROJECT.md` Section 6 each one is a pending configuration TODO; activation state is not determinable from code alone:

| Plugin | Directory | Version | Role |
|--------|-----------|---------|------|
| Rank Math SEO | `wp-content/plugins/seo-by-rank-math/` | 1.0.265 | Meta tags, sitemap, schema, SEO audits |
| ShortPixel Image Optimizer | `wp-content/plugins/shortpixel-image-optimiser/` | 6.4.3 | Upload compression |
| UpdraftPlus | `wp-content/plugins/updraftplus/` | 1.26.2 | Scheduled backups to remote destinations |
| W3 Total Cache | `wp-content/plugins/w3-total-cache/` | 2.9.2 | Page / object / opcode caching |
| Wordfence Security | `wp-content/plugins/wordfence/` | 8.1.4 | Firewall + malware scanning (staging/live only) |
| WP Mail SMTP | `wp-content/plugins/wp-mail-smtp/` | 4.7.1 | Reliable email delivery via SMTP or provider API |

## External Assets Loaded by Theme

**None.** Per `CLAUDE.md` ("No external CDN requests") and verified by grep: no references to `fonts.googleapis.com`, `fonts.gstatic.com`, `cdn.jsdelivr.net`, `cdnjs.cloudflare.com`, `unpkg.com`, `googletagmanager.com`, `google-analytics.com`, `hotjar.com`, `intercom.io`, `fullstory.com`, `stripe.com`, or reCAPTCHA endpoints in `wp-content/themes/sandiegoweddingdirectory/` or the SDWD plugins. Fonts, icons, CSS, and JS are all self-hosted under `wp-content/themes/sandiegoweddingdirectory/assets/`.

The only external URL referenced at runtime is the unauthenticated Google Maps embed iframe for venue addresses (see "APIs & External Services" above).

---

*Integration audit: 2026-04-23*
