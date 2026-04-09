# Plugin Parity Audit

Last updated: 2026-04-08

## Scope

This audit compares:

1. `SDWD Core` vs legacy `SDWeddingDirectory`
2. `SDWD Couple` vs legacy `SDWeddingDirectory - Couple`

This document is based only on plugin code inside `wp-content/plugins/` in this `wp-docker` repository. Theme-only behavior is out of scope unless a plugin directly depends on it.

## Method

- Only confirmed code paths are documented.
- If a feature is unclear or not directly confirmable from code, it is marked `UNKNOWN`.
- The `Labels` columns summarize the primary singular/plural/menu intent when the full localized label array was not re-listed in this audit.
- The dependency section distinguishes `CONFIRMED` plugin-scope dependencies from `NOT FOUND IN PLUGIN SCOPE`.

## Section 1: Feature Inventory

### 1.1 SDWD Core vs SDWeddingDirectory (legacy)

#### Custom Post Types

| Plugin | CPT | Supports | Rewrite | Labels | Source |
|------|------|------|------|------|------|
| SDWD Core | `couple` | `title`, `thumbnail` | No public rewrite; admin-oriented | Couple / Couples | `wp-content/plugins/sdwd-core/includes/post-types.php:16-109` |
| SDWD Core | `vendor` | `title`, `editor`, `thumbnail` | `vendor` | Vendor / Vendors | `wp-content/plugins/sdwd-core/includes/post-types.php:16-109` |
| SDWD Core | `venue` | `title`, `editor`, `thumbnail` | `venue` | Venue / Venues | `wp-content/plugins/sdwd-core/includes/post-types.php:16-109` |
| Legacy SDWeddingDirectory | `couple` | Public CPT; exact support list not fully re-listed here | Legacy public rewrite in CPT registration | Couple / Couples | `wp-content/plugins/sdweddingdirectory/admin-file/custom-post-type/vendor-post/index.php:120-249` |
| Legacy SDWeddingDirectory | `vendor` | Public CPT with archive support | `vendor` | Vendor / Vendors | `wp-content/plugins/sdweddingdirectory/admin-file/custom-post-type/vendor-post/index.php:120-249` |
| Legacy SDWeddingDirectory | `venue` | Public CPT; legacy venue implementation diverges from vendor | `venues` | Venue / Venues | `wp-content/plugins/sdweddingdirectory/venue/admin-file/custom-post/index.php:1-200` |
| Legacy SDWeddingDirectory | `real-wedding` | `title`, `editor`, `thumbnail`, `author` | `real-weddings` | Real Wedding / Real Weddings | `wp-content/plugins/sdweddingdirectory/real-wedding/admin-file/custom-post/index.php:1-299` |
| Legacy SDWeddingDirectory | `team` | Public CPT with thumbnail support | Legacy team rewrite in CPT registration | Team / Teams | `wp-content/plugins/sdweddingdirectory/admin-file/custom-post-type/team-post/index.php:120-210` |
| Legacy SDWeddingDirectory | `testimonial` | Public CPT with thumbnail support | Legacy testimonial rewrite in CPT registration | Testimonial / Testimonials | `wp-content/plugins/sdweddingdirectory/admin-file/custom-post-type/testimonials/index.php:200-233` |
| Legacy SDWeddingDirectory | `claim-venue` | Admin workflow CPT for pending claims | Admin-only claim workflow | Claim Venue / Claim Venues | `wp-content/plugins/sdweddingdirectory/claim-venue/admin-file/custom-post/index.php:1-200` |

#### Taxonomies

| Plugin | Taxonomy | Hierarchical | Rewrite | Relationships | Source |
|------|------|------|------|------|------|
| SDWD Core | `vendor-category` | Yes | `vendors` | Attached to `vendor` | `wp-content/plugins/sdwd-core/includes/taxonomies.php:18-85` |
| SDWD Core | `venue-type` | Yes | `venues/type` | Attached to `venue` | `wp-content/plugins/sdwd-core/includes/taxonomies.php:18-85` |
| SDWD Core | `venue-location` | Yes | `venues` | Attached to `venue` | `wp-content/plugins/sdwd-core/includes/taxonomies.php:18-85` |
| Legacy SDWeddingDirectory | `vendor-category` | Yes | Legacy vendor category rewrite | Attached to `vendor` | `wp-content/plugins/sdweddingdirectory/admin-file/custom-post-type/vendor-post/index.php:251-325` |
| Legacy SDWeddingDirectory | `venue-type` | Yes | Legacy venue type rewrite | Attached to `venue` | `wp-content/plugins/sdweddingdirectory/venue/admin-file/custom-post/index.php:1-118` |
| Legacy SDWeddingDirectory | `venue-location` | Yes | Legacy venue location rewrite | Attached to `venue` | `wp-content/plugins/sdweddingdirectory/venue/admin-file/custom-post/index.php:1-118` |
| Legacy SDWeddingDirectory | `real-wedding-*` | Yes | Multiple real-wedding rewrites | Attached to `real-wedding` | `wp-content/plugins/sdweddingdirectory/real-wedding/admin-file/custom-post/index.php:259-299` |

#### Meta Fields

| Plugin | Feature group | Confirmed keys | Type | Where used | UI source | Source |
|------|------|------|------|------|------|------|
| SDWD Core | Vendor profile | `sdwd_company_name`, `sdwd_email`, `sdwd_phone`, `sdwd_company_website` | Text / email / URL strings | Vendor admin save and frontend dashboard save | Vendor admin metaboxes; theme dashboard form | `wp-content/plugins/sdwd-core/includes/admin/vendor-meta.php:16-254`, `wp-content/plugins/sdwd-core/includes/dashboard.php:79-124` |
| SDWD Core | Vendor social, hours, pricing | `sdwd_social`, `sdwd_hours`, `sdwd_pricing` | Arrays | Vendor admin and frontend dashboard save | Vendor social/hours/pricing metaboxes; theme dashboard form | `wp-content/plugins/sdwd-core/includes/admin/vendor-meta.php:88-339`, `wp-content/plugins/sdwd-core/includes/dashboard.php:79-124` |
| SDWD Core | Venue profile | Vendor keys plus `sdwd_street_address`, `sdwd_city`, `sdwd_zip_code`, `sdwd_capacity` | Text strings plus integer | Venue admin save and frontend dashboard save | Venue admin metaboxes; theme dashboard form | `wp-content/plugins/sdwd-core/includes/admin/venue-meta.php:76-339`, `wp-content/plugins/sdwd-core/includes/dashboard.php:79-124` |
| SDWD Core | Couple profile | `sdwd_email`, `sdwd_phone`, `sdwd_wedding_date`, `sdwd_social` | Text / date / array | Couple admin save and couple dashboard save | Couple admin metaboxes; theme dashboard form | `wp-content/plugins/sdwd-core/includes/admin/couple-meta.php:48-151`, `wp-content/plugins/sdwd-core/includes/dashboard.php:79-124` |
| SDWD Core | Claim state | `sdwd_claim` | Array | Claim submission, admin review, approval/rejection | Frontend claim form handled by AJAX; admin metabox | `wp-content/plugins/sdwd-core/includes/claim.php:36-210` |
| SDWD Core | User to post link | `sdwd_post_id`; copied user keys include `sdwd_company_name`, `sdwd_company_address`, `sdwd_company_website`, `sdwd_zip_code`, `sdwd_contact_number`, `sdwd_wedding_date`, `sdwd_couple_type` | User meta plus copied strings | Registration bootstrap and post linking | No standalone UI; driven by registration flow | `wp-content/plugins/sdwd-core/includes/user-post-link.php:47-88` |
| SDWD Core | Search/filter | `UNKNOWN` | `UNKNOWN` | No dedicated search meta confirmed in current plugin | `UNKNOWN` | `wp-content/plugins/sdwd-core/includes/*.php` |
| Legacy SDWeddingDirectory | Vendor profile | `company_name`, `company_contact`, `company_email`, `company_website`, `company_address`, `company_location_pincode`, `vendor_category`, `account_type`, `user_id`, `user_email` | Mostly text / relation IDs | Vendor registration, vendor dashboard profile editing, admin metaboxes | Vendor registration modal, vendor dashboard, admin metabox | `wp-content/plugins/sdweddingdirectory/config-file/vendor-config/index.php:145-211`, `wp-content/plugins/sdweddingdirectory/dashboard/vendor-file/profile-page/admin-file/meta-box.php:23-147`, `wp-content/plugins/sdweddingdirectory/dashboard/vendor-file/profile-page/ajax/index.php:204-393` |
| Legacy SDWeddingDirectory | Vendor pricing and filters | `sdwd_vendor_pricing_tiers`, `sdweddingdirectory_vendor_services`, `sdweddingdirectory_vendor_pricing`, `sdweddingdirectory_vendor_style`, `sdweddingdirectory_vendor_specialties` | Arrays / structured values | Vendor pricing sidebar, vendor filter profile save, dashboard widgets | Vendor dashboard pricing and filter panels | `wp-content/plugins/sdweddingdirectory/dashboard/vendor-file/set-pricing/index.php:251-395`, `wp-content/plugins/sdweddingdirectory/dashboard/vendor-file/profile-page/ajax/index.php:450-707`, `wp-content/plugins/sdweddingdirectory/front-file/singular-vendor/left-widgets/pricing/index.php:55-283` |
| Legacy SDWeddingDirectory | Follow and saved vendor state | `sdweddingdirectory_follow_vendors` | Array of vendor IDs | Follow/unfollow actions | Frontend follow UI | `wp-content/plugins/sdweddingdirectory/front-file/follow-vendor/ajax/index.php:12-207` |
| Legacy SDWeddingDirectory | Venue profile | `company_name`, `company_contact`, `company_address`, `company_website`, `company_location_pincode`, `vendor_category` and additional venue-specific fields | Mixed | Venue add/update/publish flows | Venue forms and venue admin flows | `wp-content/plugins/sdweddingdirectory/venue/admin-file/custom-post/index.php:1-200`, `wp-content/plugins/sdweddingdirectory/venue/ajax/index.php` |
| Legacy SDWeddingDirectory | Claim records | `claimant_name`, `claimant_phone`, `claimant_email`, `target_post_id`, `target_post_type`, `target_slug`, `sdwd_claim_approved_welcome` | Text / IDs / status flag | Claim record creation and approval | Claim submission form and claim admin review | `wp-content/plugins/sdweddingdirectory/claim-venue/ajax/index.php:300-343`, `wp-content/plugins/sdweddingdirectory/claim-venue/admin-file/custom-post/index.php:545-565` |
| Legacy SDWeddingDirectory | Password reset helpers | `sdwd_password_reset_token`, `sdwd_password_reset_expires`, `sdwd_password_reset_requested_at` | Text / timestamp | Forgot password flow | Forgot/reset password modal | `wp-content/plugins/sdweddingdirectory/front-file/forgot-password/index.php:461-699` |
| Legacy SDWeddingDirectory | Additional core meta | `UNKNOWN` | `UNKNOWN` | Legacy plugin spans many modules; only directly confirmed keys are listed here | `UNKNOWN` | `wp-content/plugins/sdweddingdirectory/` |

#### Admin Pages

| Plugin | Admin area | Purpose | Source |
|------|------|------|------|
| SDWD Core | Tools -> `SDWD Migration` | One-time migration of legacy meta and user/post links into `sdwd_*` structures | `wp-content/plugins/sdwd-core/includes/migrate.php:11-200` |
| SDWD Core | Vendor metaboxes | Business, social, hours, pricing | `wp-content/plugins/sdwd-core/includes/admin/vendor-meta.php:10-256` |
| SDWD Core | Venue metaboxes | Business, location, capacity, social, hours, pricing | `wp-content/plugins/sdwd-core/includes/admin/venue-meta.php:11-257` |
| SDWD Core | Couple metaboxes | Contact, wedding, social | `wp-content/plugins/sdwd-core/includes/admin/couple-meta.php:10-151` |
| SDWD Core | Claim admin metabox | Review and approve/reject pending claims | `wp-content/plugins/sdwd-core/includes/claim.php:118-210` |
| Legacy SDWeddingDirectory | Reordered plugin admin menu | Groups plugin-owned CPTs and settings into a single admin experience | `wp-content/plugins/sdweddingdirectory/admin-file/custom-post-type/index.php:1-120` |
| Legacy SDWeddingDirectory | Vendor dashboard admin and profile tools | Plugin-owned business profile, onboarding, pricing, gallery, filter editing | `wp-content/plugins/sdweddingdirectory/dashboard/index.php:90-160`, `wp-content/plugins/sdweddingdirectory/dashboard/vendor-file/dashboard/vendor-file/index.php:382-624` |
| Legacy SDWeddingDirectory | Vendor profile admin metabox | Vendor profile information inside wp-admin | `wp-content/plugins/sdweddingdirectory/dashboard/vendor-file/profile-page/admin-file/meta-box.php:23-147` |
| Legacy SDWeddingDirectory | Claim management admin | Review submitted venue claims | `wp-content/plugins/sdweddingdirectory/claim-venue/admin-file/custom-post/index.php:1-200` |

#### Frontend Features

| Plugin | Feature | What exists | Rendering model | Source |
|------|------|------|------|------|
| SDWD Core | Registration/login/forgot password | AJAX endpoints only | Theme-owned forms submit to plugin JSON handlers | `wp-content/plugins/sdwd-core/includes/auth.php:18-236` |
| SDWD Core | Vendor, venue, couple dashboards | Save handler only | Theme-owned dashboard templates and JS | `wp-content/plugins/sdwd-core/includes/dashboard.php:10-141` |
| SDWD Core | Claims | Submit, approve, reject | Theme-owned claim form; plugin admin metabox | `wp-content/plugins/sdwd-core/includes/claim.php:11-211` |
| SDWD Core | CPT and taxonomy model | Vendor, venue, couple, taxonomy registration | Theme renders archives and singles | `wp-content/plugins/sdwd-core/includes/post-types.php:16-109`, `wp-content/plugins/sdwd-core/includes/taxonomies.php:18-85` |
| Legacy SDWeddingDirectory | Vendor and couple auth modals | Full login/registration markup and AJAX | Plugin-owned HTML, JS, and AJAX | `wp-content/plugins/sdweddingdirectory/front-file/vendor-login-register/index.php:900-954`, `wp-content/plugins/sdweddingdirectory/front-file/couple-login-register/index.php:600-720`, corresponding `ajax.php` files |
| Legacy SDWeddingDirectory | Forgot/reset password | Full modal flow and reset-token management | Plugin-owned HTML plus AJAX | `wp-content/plugins/sdweddingdirectory/front-file/forgot-password/index.php:90-699` |
| Legacy SDWeddingDirectory | Vendor/venue search and filters | Search helpers, term pages, dropdown AJAX, availability widgets | Plugin-owned widgets, handlers, and helper queries | `wp-content/plugins/sdweddingdirectory/front-file/find-post-helper/search-venue/index.php:960-1005`, `wp-content/plugins/sdweddingdirectory/front-file/find-post-helper/search-vendor/index.php:45-60`, `wp-content/plugins/sdweddingdirectory/front-file/dropdown-box/ajax/` |
| Legacy SDWeddingDirectory | Follow/unfollow vendors | AJAX-driven favorite/follow state | Plugin-owned buttons and handlers | `wp-content/plugins/sdweddingdirectory/front-file/follow-vendor/ajax/index.php:12-207` |
| Legacy SDWeddingDirectory | Vendor dashboard UI | Bootstrap-heavy profile, onboarding, pricing, media, analytics | Plugin-owned templates, scripts, and assets | `wp-content/plugins/sdweddingdirectory/dashboard/vendor-file/dashboard/vendor-file/index.php:382-624` |
| Legacy SDWeddingDirectory | Venue request and claim flows | Venue request creation plus claim system | Plugin-owned forms, AJAX, and admin review | `wp-content/plugins/sdweddingdirectory/venue/ajax/index.php:40-90`, `wp-content/plugins/sdweddingdirectory/claim-venue/ajax/index.php:18-60` |
| Legacy SDWeddingDirectory | Shortcode-driven content rendering | Large shortcode library for listings, layout, sliders, marketing blocks | Plugin-owned shortcodes render HTML | `wp-content/plugins/sdweddingdirectory/shortcodes/index.php:1-70`, `wp-content/plugins/sdweddingdirectory/shortcodes/shortcodes/index.php:1-70` |

#### User Roles and Capabilities

| Plugin | Role | Confirmed capabilities | Source |
|------|------|------|------|
| SDWD Core | `couple` | `read` | `wp-content/plugins/sdwd-core/includes/roles.php:11-35` |
| SDWD Core | `vendor` | `read`, `upload_files`, `edit_posts`, `delete_posts` | `wp-content/plugins/sdwd-core/includes/roles.php:11-35` |
| SDWD Core | `venue` | `read`, `upload_files`, `edit_posts`, `delete_posts` | `wp-content/plugins/sdwd-core/includes/roles.php:11-35` |
| Legacy SDWeddingDirectory | `couple` | `read`, `upload_files`, `edit_posts`, `delete_posts` | `wp-content/plugins/sdweddingdirectory/sdweddingdirectory.php:187-218` |
| Legacy SDWeddingDirectory | `vendor` | `read`, `upload_files`, `edit_posts`, `delete_posts` | `wp-content/plugins/sdweddingdirectory/sdweddingdirectory.php:187-218` |

#### AJAX Endpoints

| Plugin | Area | Confirmed actions | Source |
|------|------|------|------|
| SDWD Core | Auth | `sdwd_register`, `sdwd_login`, `sdwd_forgot_password` | `wp-content/plugins/sdwd-core/includes/auth.php:18-236` |
| SDWD Core | Dashboard | `sdwd_save_dashboard` | `wp-content/plugins/sdwd-core/includes/dashboard.php:10-141` |
| SDWD Core | Claims | `sdwd_submit_claim`, `sdwd_approve_claim`, `sdwd_reject_claim` | `wp-content/plugins/sdwd-core/includes/claim.php:11-211` |
| Legacy SDWeddingDirectory | Vendor auth | `sdweddingdirectory_vendor_register_form_action`, `sdweddingdirectory_vendor_login_action` | `wp-content/plugins/sdweddingdirectory/front-file/vendor-login-register/ajax.php:38-44` |
| Legacy SDWeddingDirectory | Couple auth | `sdweddingdirectory_couple_register_form_action`, `sdweddingdirectory_couple_login_form_action` | `wp-content/plugins/sdweddingdirectory/front-file/couple-login-register/ajax.php:38-44` |
| Legacy SDWeddingDirectory | Password reset | `sdweddingdirectory_forgot_password_form_action`, `sdweddingdirectory_reset_password_form_action` | `wp-content/plugins/sdweddingdirectory/front-file/forgot-password/index.php:90-144` |
| Legacy SDWeddingDirectory | Vendor dashboard/profile | `sdweddingdirectory_vendor_profile_action`, `sdweddingdirectory_vendor_password_action`, `sdweddingdirectory_vendor_social_action`, `sdweddingdirectory_vendor_business_profile_action`, `sdweddingdirectory_vendor_filter_profile_action`, `sdwd_vendor_onboarding_save_action`, `sdweddingdirectory_vendor_upload_photos_action`, `sdwd_vendor_pricing_save_action` | `wp-content/plugins/sdweddingdirectory/dashboard/vendor-file/profile-page/ajax/index.php:40-134`, `wp-content/plugins/sdweddingdirectory/dashboard/vendor-file/set-pricing/index.php:44-395` |
| Legacy SDWeddingDirectory | Search and filters | `sdweddingdirectory_load_vendor_data`, `sdweddingdirectory_load_venue_data`, `sdweddingdirectory_venue_term_page` and additional dropdown helper AJAX actions under `front-file/dropdown-box/ajax/` | `wp-content/plugins/sdweddingdirectory/front-file/find-post-helper/search-vendor/index.php:45-60`, `wp-content/plugins/sdweddingdirectory/front-file/find-post-helper/search-venue/index.php:960-1005`, `wp-content/plugins/sdweddingdirectory/front-file/find-post-helper/venue-term-page/index.php:62-105` |
| Legacy SDWeddingDirectory | Follow and engagement | `sdweddingdirectory_follow_vendor_action`, `sdweddingdirectory_unfollow_vendor_action`, `sd_get_vendor_availability` | `wp-content/plugins/sdweddingdirectory/front-file/follow-vendor/ajax/index.php:40-55`, `wp-content/plugins/sdweddingdirectory/front-file/singular-vendor/left-widgets/availability/ajax.php:36-56` |
| Legacy SDWeddingDirectory | Claims and venue requests | `sdweddingdirectory_claim_request_action`, `sdwd_profile_claim_submit_action`, `sdweddingdirectory_venue_add_new_request_handler` | `wp-content/plugins/sdweddingdirectory/claim-venue/ajax/index.php:18-60`, `wp-content/plugins/sdweddingdirectory/venue/ajax/index.php:40-90` |
| Legacy SDWeddingDirectory | Dynamic AJAX registries | Dynamic action registries also exist in `ajax/index.php`, `real-wedding/ajax/index.php`, `claim-venue/ajax/index.php`, `venue/ajax/*`, `admin-file/setting-page/ajax.php`, `dashboard/couple-file/profile-page/ajax/index.php`, `dashboard/vendor-file/profile-page/ajax/index.php`, `front-file/dropdown-box/ajax/*`, `front-file/forgot-password/index.php`, `front-file/find-post-helper/*`; this audit documents the confirmed action names and controller locations, but those controllers also register additional feature-local actions through per-file `$action` registries | `wp-content/plugins/sdweddingdirectory/ajax/index.php:93-101`, `wp-content/plugins/sdweddingdirectory/real-wedding/ajax/index.php:141-149`, `wp-content/plugins/sdweddingdirectory/claim-venue/ajax/index.php:57-61`, `wp-content/plugins/sdweddingdirectory/venue/ajax/`, `wp-content/plugins/sdweddingdirectory/admin-file/setting-page/ajax.php:93`, `wp-content/plugins/sdweddingdirectory/front-file/dropdown-box/ajax/` |

#### Shortcodes

| Plugin | Status | Shortcodes | Source |
|------|------|------|------|
| SDWD Core | None confirmed | No `add_shortcode` registrations found in plugin scope | Search basis confirmed by code audit; see explorer summary for `wp-content/plugins/sdwd-core` |
| Legacy SDWeddingDirectory | Confirmed | `sdweddingdirectory_vendor_category`, `sdweddingdirectory_button_group`, `sdweddingdirectory_vendor`, `sdweddingdirectory_call_to_action_one`, `sdweddingdirectory_call_to_action_two`, `sdweddingdirectory_why_choose_us`, `sdweddingdirectory_pricing`, `sdweddingdirectory_venue_location`, `sdweddingdirectory_idea_and_tips`, `sdweddingdirectory_realwedding_location`, `sdweddingdirectory_venue`, `sdweddingdirectory_container`, `sdweddingdirectory_row`, `sdweddingdirectory_grid`, `sdweddingdirectory_section`, `sdweddingdirectory_contact_box`, `sdweddingdirectory_collapsibles`, `sdweddingdirectory_collapse`, `sdweddingdirectory_blog`, `sdweddingdirectory_venue_category_icon`, `sdweddingdirectory_find_venue_form`, `sdweddingdirectory_testimonial`, `sdweddingdirectory_slider_section`, `sdweddingdirectory_slider`, `sdweddingdirectory_slider_image`, `sdweddingdirectory_background_image`, `sdweddingdirectory_featuer_info`, `sdweddingdirectory_social_media`, `sdweddingdirectory_real_wedding_category`, `sdweddingdirectory_realwedding`, `sdweddingdirectory_tabs`, `sdweddingdirectory_tab`, `sdweddingdirectory_venue_category`, `sdweddingdirectory_button`, `sdweddingdirectory_featuer_box`, `sdweddingdirectory_team_section`, `sdweddingdirectory_carousel`, `sdweddingdirectory_about_us_carousel`, `sdweddingdirectory_about_us_image` | `wp-content/plugins/sdweddingdirectory/shortcodes/shortcodes/` |

#### External Dependencies

| Plugin | Dependency | Status | What it does | Source |
|------|------|------|------|------|
| SDWD Core | Plugin-local admin assets only | Confirmed | Uses only `assets/admin.css` and `assets/admin.js`; no third-party bundle confirmed | `wp-content/plugins/sdwd-core/sdwd-core.php:49-62` |
| Legacy SDWeddingDirectory | Bootstrap | CONFIRMED | Dashboard and frontend layout dependency | `wp-content/plugins/sdweddingdirectory/assets/index.php:500-531`, `wp-content/plugins/sdweddingdirectory/dashboard/index.php:90-160` |
| Legacy SDWeddingDirectory | jQuery | CONFIRMED | Base dependency for most frontend/dashboard scripts | `wp-content/plugins/sdweddingdirectory/assets/index.php:278-1118`, `wp-content/plugins/sdweddingdirectory/dashboard/index.php:148` |
| Legacy SDWeddingDirectory | Clipboard.js | CONFIRMED | Copy-to-clipboard helpers | `wp-content/plugins/sdweddingdirectory/assets/index.php:181-247` |
| Legacy SDWeddingDirectory | Fontello icon font | CONFIRMED | Legacy icon system | `wp-content/plugins/sdweddingdirectory/assets/index.php:85-902` |
| Legacy SDWeddingDirectory | Pagination helper | CONFIRMED | Listing pagination UI | `wp-content/plugins/sdweddingdirectory/assets/index.php:146-476` |
| Legacy SDWeddingDirectory | Magnific Popup | CONFIRMED | Modal/lightbox behavior | `wp-content/plugins/sdweddingdirectory/assets/index.php:118-990` |
| Legacy SDWeddingDirectory | Masonry | CONFIRMED | Card/grid layout | `wp-content/plugins/sdweddingdirectory/assets/index.php:139-387` |
| Legacy SDWeddingDirectory | Isotope | CONFIRMED | Filtered layout behavior | `wp-content/plugins/sdweddingdirectory/assets/index.php:305-335` |
| Legacy SDWeddingDirectory | Select2 | CONFIRMED | Enhanced select fields | `wp-content/plugins/sdweddingdirectory/assets/index.php:188-1073` |
| Legacy SDWeddingDirectory | Summernote | CONFIRMED | Rich text editing | `wp-content/plugins/sdweddingdirectory/assets/index.php:594-649` |
| Legacy SDWeddingDirectory | Bootstrap Datepicker | CONFIRMED | Date fields | `wp-content/plugins/sdweddingdirectory/assets/index.php:1158-1213` |
| Legacy SDWeddingDirectory | Bootstrap Slider | CONFIRMED | Slider-based pricing/input controls | `wp-content/plugins/sdweddingdirectory/assets/index.php:1256-1305` |
| Legacy SDWeddingDirectory | Toastr | CONFIRMED | Toast notifications | `wp-content/plugins/sdweddingdirectory/assets/index.php:500-553` |
| Legacy SDWeddingDirectory | Slide Reveal | CONFIRMED | Slide-in panels | `wp-content/plugins/sdweddingdirectory/assets/index.php:1094-1124` |
| Legacy SDWeddingDirectory | ApexCharts | CONFIRMED | Charts and analytics widgets | `wp-content/plugins/sdweddingdirectory/assets/index.php:1341-1359` |
| Legacy SDWeddingDirectory | FullCalendar | CONFIRMED | Calendar UI assets bundled in plugin library | `wp-content/plugins/sdweddingdirectory/assets/library/fullcalendar-4.0.2/` |
| Legacy SDWeddingDirectory | Owl Carousel | NOT FOUND IN PLUGIN SCOPE | Plugin JS references `owlCarousel`, but no library file or enqueue was confirmed in plugin scope | `wp-content/plugins/sdweddingdirectory/assets/script.js:278-951` |
| Legacy SDWeddingDirectory | Slick | NOT FOUND IN PLUGIN SCOPE | No plugin-scope file or enqueue confirmed | Code search result reported by explorer |
| Legacy SDWeddingDirectory | OptionTree | NOT FOUND IN PLUGIN SCOPE | Only historical/comment references remain; framework not bundled or enqueued in plugin scope | `wp-content/plugins/sdweddingdirectory/admin-file/setting-option/index.php:4-196` |
| Legacy SDWeddingDirectory | Font Awesome | NOT FOUND IN PLUGIN SCOPE | Icon mapping references exist, but no plugin-scope FA asset bundle or enqueue was confirmed | `wp-content/plugins/sdweddingdirectory/config-file/icons-list/weddingdir-icons/font-awesome-icon.php:46-70` |

### 1.2 SDWD Couple vs SDWeddingDirectory - Couple (legacy)

#### Custom Post Types

| Plugin | CPT | Supports | Rewrite | Labels | Source |
|------|------|------|------|------|------|
| SDWD Couple | `sdwd_review` | `title`, `editor` | Private/admin UI; no public archive | Reviews | `wp-content/plugins/sdwd-couple/modules/reviews.php:11-28` |
| Legacy SDWeddingDirectory - Couple | `venue-review` | `title` | `venue-review` | Reviews | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-reviews/admin-file/custom-post/index.php:34-214` |
| Legacy SDWeddingDirectory - Couple | `venue-request` | Request post workflow | `venue-request` | Requests | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-request-quote/admin-file/custom-post/index.php:20-150` |
| Legacy SDWeddingDirectory - Couple | `website` | `title`, `editor`, `thumbnail`, `author` | `website` | Website / Websites | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-couple-website/admin-file/custom-post/index.php:40-220` |

#### Taxonomies

| Plugin | Taxonomy | Hierarchical | Rewrite | Relationships | Source |
|------|------|------|------|------|------|
| SDWD Couple | None confirmed | No taxonomies registered | N/A | N/A | `wp-content/plugins/sdwd-couple/` |
| Legacy SDWeddingDirectory - Couple | `website-category` | Yes | `website-category` | Attached to `website` | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-couple-website/admin-file/custom-post/index.php:227-263` |
| Legacy SDWeddingDirectory - Couple | `website-location` | Yes | `website-location` | Attached to `website` | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-couple-website/admin-file/custom-post/index.php:227-263` |

#### Meta Fields

| Plugin | Feature group | Confirmed keys | Type | Where used | UI source | Source |
|------|------|------|------|------|------|------|
| SDWD Couple | Budget | `sdwd_budget_total`, `sdwd_budget_items` | Float plus array | Budget AJAX save and budget getters | Theme dashboard UI not bundled in plugin | `wp-content/plugins/sdwd-couple/modules/budget.php:11-82` |
| SDWD Couple | Checklist | `sdwd_checklist` | Array of `{text, completed, due_date}` | Checklist AJAX save and getters | Theme dashboard UI not bundled in plugin | `wp-content/plugins/sdwd-couple/modules/checklist.php:11-69` |
| SDWD Couple | Wishlist | `sdwd_wishlist` | Array of post IDs | Wishlist toggle and helper reads | Theme UI not bundled in plugin | `wp-content/plugins/sdwd-couple/modules/wishlist.php:11-69` |
| SDWD Couple | Request quotes | `sdwd_quote_requests`; reads `sdwd_post_id` and vendor `sdwd_email` | Array plus relation keys | Quote submission and vendor email notification | Theme form UI not bundled in plugin | `wp-content/plugins/sdwd-couple/modules/request-quote.php:11-75` |
| SDWD Couple | Reviews | `sdwd_reviewed_id`, `sdwd_rating` | Post meta on `sdwd_review` | Review submit/update and average rating reads | Theme UI not bundled in plugin | `wp-content/plugins/sdwd-couple/modules/reviews.php:31-118` |
| SDWD Couple | Guest list / RSVP / seating chart / website | `UNKNOWN` | `UNKNOWN` | No module present in current plugin | `UNKNOWN` | `wp-content/plugins/sdwd-couple/modules/` |
| Legacy SDWeddingDirectory - Couple | Budget | `sdweddingdirectory_budget_data` | Array of budget categories and values | Budget module database and chart/dashboard views | Couple dashboard budget module | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-budget-calculator/database/index.php:61-118` |
| Legacy SDWeddingDirectory - Couple | Wishlist | `sdweddingdirectory_wishlist` with nested `wishlist_venue_id`, `wishlist_vendor_id`, `wishlist_note`, `wishlist_estimate_price`, `wishlist_rating` | Array | Wishlist AJAX actions and couple dashboard | Couple dashboard wishlist UI | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-wishlist/ajax/index.php:204-262`, `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-wishlist/couple-file/script.js:21` |
| Legacy SDWeddingDirectory - Couple | Reviews | `couple_id`, `vendor_id`, `venue_id`, `quality_service`, `facilities`, `staff`, `flexibility`, `value_of_money`, `couple_comment`, `vendor_comment` | IDs, rating values, text | Review posts and rating-average calculations | Couple review form and vendor response flow | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-reviews/ajax/index.php:360`, `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-reviews/filters-hooks/rating-average/index.php:297` |
| Legacy SDWeddingDirectory - Couple | Request quotes and lead tracking | `request_quote_*` fields, `vendor_id`, `venue_id`, `sdwd_lead_status`, `sdwd_lead_history`, `user_contact`, `vendor_booked_dates`, `sdwd_daily_booking_capacity` | Mixed structured values | Couple request forms, vendor lead management, booking capacity updates | Couple request modal and vendor lead tools | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-request-quote/ajax/index.php:325-1187`, `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-request-quote/database/index.php:1253` |
| Legacy SDWeddingDirectory - Couple | Guest list | `guest_list_event_group`, `guest_list_group`, `guest_list_menu_group`, `guest_list_data` | Arrays | Guest list CRUD, invitations, event grouping | Couple dashboard guest-list module | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-guest-list/database/index.php:61`, `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-guest-list/ajax/index.php:326-888` |
| Legacy SDWeddingDirectory - Couple | RSVP | Reuses `guest_list_data` and guest RSVP fields | Array | RSVP save and guest response flows | Couple dashboard RSVP tab and website RSVP flow | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-rsvp/ajax/index.php:1508-1570`, `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-guest-list/couple-file/rsvp/index.php:407` |
| Legacy SDWeddingDirectory - Couple | Seating chart | `sdweddingdirectory_seating_chart_data` | Structured array with tables and timestamp | Seating chart save/load | Couple dashboard seating chart module | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-seating-chart/database/index.php:35-77` |
| Legacy SDWeddingDirectory - Couple | Todo list | `todo_list` with nested `todo_title`, `todo_overview`, due date parts, `todo_unique_id`, `todo_status` | Array | Todo CRUD and completion | Couple dashboard todo module | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-todo-list/ajax/index.php:126-168`, `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-todo-list/couple-file/script.js:140` |
| Legacy SDWeddingDirectory - Couple | Couple website | `website_template_layout`, `user_email`, `wedding_date`, `groom_first_name`, `groom_last_name`, `bride_first_name`, `bride_last_name`, `groom_image`, `bride_image`, `header_image`, `couple_info_heading`, `couple_info_description`, `couple_event_heading`, `couple_event_description`, `couple_event`, `when_and_where`, plus arbitrary grouped tab fields | Mixed | Couple website tabs, website rendering, migration helpers | Couple dashboard website builder and public website templates | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-couple-website/filters-hooks/website-config/index.php:197`, `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-couple-website/ajax/index.php:186`, `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-couple-website/couple-file/`, `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-couple-website/database/index.php:1190` |

#### Admin Pages

| Plugin | Admin area | Purpose | Source |
|------|------|------|------|
| SDWD Couple | `sdwd_review` CPT UI | Admin management for review posts | `wp-content/plugins/sdwd-couple/modules/reviews.php:11-28` |
| Legacy SDWeddingDirectory - Couple | Budget admin metabox | Budget defaults and couple budget administration | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-budget-calculator/admin-file/meta-box/index.php:30-110` |
| Legacy SDWeddingDirectory - Couple | Todo admin metabox | Todo planning data and admin visibility | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-todo-list/admin-file/meta-box/index.php:70-145` |
| Legacy SDWeddingDirectory - Couple | Request/review/website CPT admin | Manage request posts, review posts, website posts and taxonomies | Corresponding module `admin-file/` trees under `sdweddingdirectory-request-quote`, `sdweddingdirectory-reviews`, `sdweddingdirectory-couple-website` |
| Legacy SDWeddingDirectory - Couple | Guest list and RSVP admin helpers | Module-owned admin settings and imports | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-guest-list/`, `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-rsvp/` |

#### Frontend Features

| Plugin | Feature | What exists | Rendering model | Source |
|------|------|------|------|------|
| SDWD Couple | Reviews | Review submit/update and read helpers | Theme or another layer must render forms and lists | `wp-content/plugins/sdwd-couple/modules/reviews.php:31-118` |
| SDWD Couple | Request quotes | Quote submit handler and vendor email notification | Theme or another layer must render forms | `wp-content/plugins/sdwd-couple/modules/request-quote.php:11-75` |
| SDWD Couple | Wishlist | Toggle plus helper reads | Theme or another layer must render UI | `wp-content/plugins/sdwd-couple/modules/wishlist.php:11-69` |
| SDWD Couple | Checklist | Save and getter helpers | Theme or another layer must render UI | `wp-content/plugins/sdwd-couple/modules/checklist.php:11-69` |
| SDWD Couple | Budget | Save and getter helpers | Theme or another layer must render UI | `wp-content/plugins/sdwd-couple/modules/budget.php:11-82` |
| Legacy SDWeddingDirectory - Couple | Budget calculator | Full dashboard UI, charting, category management | Plugin-owned dashboard templates and JS | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-budget-calculator/` |
| Legacy SDWeddingDirectory - Couple | Wishlist | Full vendor-manager style dashboard and AJAX | Plugin-owned dashboard templates and JS | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-wishlist/` |
| Legacy SDWeddingDirectory - Couple | Reviews | Couple submission, vendor response, review display | Plugin-owned CPT, templates, and AJAX | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-reviews/` |
| Legacy SDWeddingDirectory - Couple | Request quote | Couple forms, vendor lead management, booking capacity | Plugin-owned forms, scripts, and AJAX | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-request-quote/` |
| Legacy SDWeddingDirectory - Couple | Guest list | Full event, guest, meal, import/export workflow | Plugin-owned dashboard templates, DataTables, AJAX | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-guest-list/` |
| Legacy SDWeddingDirectory - Couple | RSVP | Couple dashboard RSVP and public guest RSVP submission | Plugin-owned dashboard and website templates | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-rsvp/` |
| Legacy SDWeddingDirectory - Couple | Seating chart | Drag/drop seat planning and save/load | Plugin-owned dashboard templates and AJAX | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-seating-chart/` |
| Legacy SDWeddingDirectory - Couple | Todo list | Full task management UI | Plugin-owned dashboard templates and AJAX | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-todo-list/` |
| Legacy SDWeddingDirectory - Couple | Couple website | Dashboard website builder plus public website templates | Plugin-owned dashboard and singular templates | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-couple-website/` |

#### User Roles and Capabilities

| Plugin | Role behavior | Source |
|------|------|------|
| SDWD Couple | Review submission explicitly requires `couple`; other module handlers require authenticated users | `wp-content/plugins/sdwd-couple/modules/reviews.php:34-69`, `wp-content/plugins/sdwd-couple/modules/budget.php:16-44`, `wp-content/plugins/sdwd-couple/modules/wishlist.php:13-47`, `wp-content/plugins/sdwd-couple/modules/checklist.php:13-40`, `wp-content/plugins/sdwd-couple/modules/request-quote.php:11-75` |
| Legacy SDWeddingDirectory - Couple | Module handlers check couple/vendor context through legacy base classes such as `SDWeddingDirectory_Config` and module-specific guards | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-wishlist/ajax/index.php:134-157`, `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-request-quote/ajax/index.php:207-220`, `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-reviews/ajax/index.php:22-110` |

#### AJAX Endpoints

| Plugin | Area | Confirmed actions | Source |
|------|------|------|------|
| SDWD Couple | Budget | `sdwd_save_budget` | `wp-content/plugins/sdwd-couple/modules/budget.php:11-44` |
| SDWD Couple | Checklist | `sdwd_save_checklist` | `wp-content/plugins/sdwd-couple/modules/checklist.php:11-40` |
| SDWD Couple | Wishlist | `sdwd_toggle_wishlist` | `wp-content/plugins/sdwd-couple/modules/wishlist.php:11-47` |
| SDWD Couple | Request quote | `sdwd_request_quote` | `wp-content/plugins/sdwd-couple/modules/request-quote.php:11-75` |
| SDWD Couple | Reviews | `sdwd_submit_review` | `wp-content/plugins/sdwd-couple/modules/reviews.php:31-88` |
| Legacy SDWeddingDirectory - Couple | Budget | `sdweddingdirectory_budget_category_add`, `sdweddingdirectory_removed_budget_category`, `sdweddingdirectory_budget_data_save`, `sdweddingdirectory_budget_category_edit`, `sdweddingdirectory_estimate_budget_amount`, `budget_calculator_chart_data`, `sdweddingdirectory_couple_reset_budget` | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-budget-calculator/ajax/index.php:80-105` |
| Legacy SDWeddingDirectory - Couple | Wishlist | `sdweddingdirectory_update_notes`, `sdweddingdirectory_remove_wishlist`, `sdweddingdirectory_add_wishlist`, `sdweddingdirectory_update_estimate_price`, `sdweddingdirectory_update_rating`, `sdweddingdirectory_add_hired`, `sdweddingdirectory_remove_hired`, `sdweddingdirectory_wishlist_hire_vendor` | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-wishlist/ajax/index.php:62-110` |
| Legacy SDWeddingDirectory - Couple | Reviews | `sdweddingdirectory_load_review_comment_posts`, `sdweddingdirectory_vendor_review_response_action`, `sdweddingdirectory_submit_venue_review` | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-reviews/ajax/index.php:62-108` |
| Legacy SDWeddingDirectory - Couple | Request quote | `sdweddingdirectory_venue_request_form_action`, `sdweddingdirectory_vendor_remove_request_quote`, `sdweddingdirectory_venue_request_form_fields_action`, `sdwd_update_lead_status`, `sdwd_add_activity_tag`, `sdwd_add_lead_note`, `sdwd_get_lead_history`, `sdwd_update_booking_capacity` | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-request-quote/ajax/index.php:64-110` |
| Legacy SDWeddingDirectory - Couple | RSVP | `sdweddingdirectory_couple_guest_find_name`, `sdweddingdirectory_rsvp_form`, `sdweddingdirectory_rsvp_guest_information`, `sdweddingdirectory_guest_submited_rsvp_info` | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-rsvp/ajax/index.php:64-86` |
| Legacy SDWeddingDirectory - Couple | Seating chart | `sdweddingdirectory_seating_chart_save` | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-seating-chart/ajax/index.php:21-58` |
| Legacy SDWeddingDirectory - Couple | Todo list | `sdweddingdirectory_couple_add_todo_list`, `sdweddingdirectory_couple_complete_todo_task`, `sdweddingdirectory_couple_remove_todo_id`, `sdweddingdirectory_couple_edit_todo_id`, `sdweddingdirectory_couple_reset_todo` | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-todo-list/ajax/index.php:64-107` |
| Legacy SDWeddingDirectory - Couple | Guest list | `sdweddingdirectory_guest_list_menu_removed`, `sdweddingdirectory_guest_list_menu_add`, `sdweddingdirectory_group_item_removed`, `sdweddingdirectory_guest_list_group_add`, `sdweddingdirectory_event_list_removed`, `sdweddingdirectory_event_list_add`, `sdweddingdirectory_create_new_event`, `sdweddingdirectory_create_new_guest_data`, `sdweddingdirectory_guest_event_meal_action`, `sdweddingdirectory_guest_event_attendance_action`, `sdweddingdirectory_get_guest_info_action`, `sdweddingdirectory_remove_guest_info_action`, `sdweddingdirectory_update_guest_data`, `sdweddingdirectory_update_event_form_data`, `sdweddingdirectory_update_event_data`, `sdweddingdirectory_remove_event_data`, `sdweddingdirectory_guest_group_action`, `guest_list_csv_download`, `sdweddingdirectory_event_summary_load`, `sdweddingdirectory_invitation_sent`, `sdweddingdirectory_guest_list_import`, `sdweddingdirectory_guest_email_update`, `sdweddingdirectory_couple_sending_guest_rsvp` | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-guest-list/ajax/index.php:49-186` |

#### Shortcodes

| Plugin | Status | Source |
|------|------|------|
| SDWD Couple | No shortcode registrations confirmed | Search basis confirmed by code audit; no `add_shortcode` under `wp-content/plugins/sdwd-couple` |
| Legacy SDWeddingDirectory - Couple | No shortcode registrations confirmed | Search basis confirmed by code audit; no `add_shortcode` under `wp-content/plugins/sdweddingdirectory-couple` |

#### External Dependencies

| Plugin | Dependency | Status | What it does | Source |
|------|------|------|------|------|
| SDWD Couple | Hard dependency on `sdwd-core` | Confirmed | Loads modules only when `SDWD_CORE_VERSION` exists | `wp-content/plugins/sdwd-couple/sdwd-couple.php:2-27` |
| SDWD Couple | Third-party JS/CSS | None confirmed | No bundled external libraries found in current plugin modules | `wp-content/plugins/sdwd-couple/` |
| Legacy SDWeddingDirectory - Couple | Toastr | CONFIRMED | AJAX feedback and toast messages across quote, wishlist, website, RSVP flows | `wp-content/plugins/sdweddingdirectory/assets/index.php:494`, module references listed in explorer summary |
| Legacy SDWeddingDirectory - Couple | Slide Reveal | CONFIRMED | Slide-in forms and planning panels | `wp-content/plugins/sdweddingdirectory/assets/index.php:1088`, module references in budget, guest-list, todo files |
| Legacy SDWeddingDirectory - Couple | Clipboard.js | CONFIRMED | Invitation link copy interactions | `wp-content/plugins/sdweddingdirectory/assets/index.php:217`, `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-guest-list/forms/missing-info/index.php:233` |
| Legacy SDWeddingDirectory - Couple | Bootstrap Datepicker | CONFIRMED | Due-date and wedding date inputs | `wp-content/plugins/sdweddingdirectory/assets/index.php:1140`, couple module references in todo and website dashboards |
| Legacy SDWeddingDirectory - Couple | ApexCharts | CONFIRMED | Budget and guest-list charts | `wp-content/plugins/sdweddingdirectory/assets/index.php:1323`, module references in budget and guest-list dashboards |
| Legacy SDWeddingDirectory - Couple | DataTables, Buttons, JSZip, pdfmake, `vfs_fonts` | CONFIRMED | Guest list tables and export actions | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-guest-list/couple-file/guest-list/index.php:65` |
| Legacy SDWeddingDirectory - Couple | RateYo | CONFIRMED | Star rating UI for reviews | `wp-content/plugins/sdweddingdirectory-couple/modules/sdweddingdirectory-reviews/assets/index.php:63` |
| Legacy SDWeddingDirectory - Couple | Core-plugin base classes | CONFIRMED | Modules extend `SDWeddingDirectory_Config`, `SDWeddingDirectory_Form_Tabs`, `SDWeddingDirectory_Admin_Settings` and depend on legacy core bootstrapping | Example module files across `wp-content/plugins/sdweddingdirectory-couple/modules/` |

## Section 2: Feature Mapping

### 2.1 Core Mapping

| Feature | Legacy implementation | Dependency | Replacement strategy |
|------|------|------|------|
| Roles | Legacy core creates `couple` and `vendor` only | Legacy core bootstrap | Keep role registration in `SDWD Core`. Add `venue` as a first-class role, keep capabilities minimal, and leave frontend rendering in the theme. |
| Vendor CPT | Legacy core registers `vendor` and mixes profile logic into plugin dashboards | Legacy core CPT registration plus dashboard UI | Keep CPT registration in `SDWD Core`; keep markup in theme templates and dashboard forms; retain `vendor-category` taxonomy and `sdwd_*` profile meta. |
| Venue CPT | Legacy core uses a separate venue architecture with divergent admin/dashboard paths | Legacy venue module inside core plugin | Keep CPT registration in `SDWD Core`, but make venue data parallel to vendor data instead of forking the architecture. |
| Couple CPT | Legacy core and legacy couple modules both depend on couple posts | Legacy core plus legacy couple modules | Keep couple post ownership in `SDWD Core`; keep couple feature data in `SDWD Couple` using the linked couple post or user meta as appropriate. |
| Vendor taxonomy | Legacy `vendor-category` taxonomy powers archives and filters | Legacy taxonomy registration plus vendor filters | Keep taxonomy registration in `SDWD Core`; render archive/filter UI in theme and query using taxonomy + `sdwd_*` meta. |
| Venue taxonomies | Legacy `venue-type` and `venue-location` power venue browsing | Legacy taxonomy registration and search helpers | Keep taxonomy registration in `SDWD Core`; move filter UI/query composition into theme templates and plugin query helpers instead of plugin-owned HTML. |
| Vendor meta model | Legacy vendor meta mixes registration data, dashboard data, and pricing into many legacy keys | Legacy vendor dashboard, pricing module, onboarding | Standardize on `sdwd_*` meta keys already used in `SDWD Core`; migrate legacy keys with the migration tool and keep save logic in `includes/dashboard.php` plus admin metaboxes. |
| Venue meta model | Legacy venue meta is inconsistent with vendor meta | Legacy venue AJAX and admin forms | Use the same field families as vendor plus venue-only location/capacity fields in `SDWD Core`; keep venue forms parallel to vendor forms in the theme. |
| Couple profile meta | Legacy couple profile data is split across core and couple modules | Legacy couple dashboard | Keep basic identity/contact/wedding meta in `SDWD Core` and leave planning-tool data in `SDWD Couple`. |
| Auth | Legacy core owns plugin-rendered modals and AJAX registration/login/password reset | Bootstrap/jQuery modal stack | Keep auth handlers in `SDWD Core`; theme renders modal/forms and submits to JSON handlers. |
| Dashboard persistence | Legacy core owns Bootstrap-heavy dashboard templates plus save actions | Legacy dashboard JS and templates | Keep only persistence handlers in `SDWD Core`; theme owns dashboard markup, CSS, and JS. |
| Claims | Legacy core uses dedicated `claim-venue` CPT plus claim admin UI | Claim module and admin workflow | Keep claim submission/approval in `SDWD Core`, but store claim state in a single `sdwd_claim` meta structure instead of a separate claim CPT. |
| Search and filter | Legacy core embeds search widgets, dropdown helpers, and listing filters directly in plugin files | Shortcodes, Bootstrap, JS libraries | Rebuild archive/search UI in theme templates; keep only reusable query logic or request validation in `SDWD Core`. |
| Vendor availability widget | Legacy core exposes availability AJAX from singular vendor widgets | Legacy singular widget HTML plus AJAX | Rebuild the widget in the theme and move only data lookup into `SDWD Core` if the feature is still required. |
| Follow/unfollow vendors | Legacy core stores follow state in user/post meta and exposes plugin UI | Legacy follow module | Rebuild as a theme-owned interaction backed by a small `SDWD Core` persistence endpoint if the feature remains in scope. |
| Real weddings CPT | Legacy core still owns `real-wedding` and related taxonomies | Legacy real-wedding module | This is missing from `SDWD Core`. Rebuild as a separate `SDWD Core` module only if Phase 4 work requires it; keep rendering in theme templates. |
| Team and testimonials CPTs | Legacy core registers both CPTs | Legacy marketing/content module | Do not rebuild inside the core data layer unless the founder explicitly reintroduces those page types. They are not present in the current plugin. |
| Shortcodes | Legacy core renders dozens of layout/content shortcodes | Shortcode loader plus Bootstrap-era HTML | Do not rebuild the shortcode layer. Replace every surviving shortcode use with theme templates or block content. |

### 2.2 Couple Mapping

| Feature | Legacy implementation | Dependency | Replacement strategy |
|------|------|------|------|
| Reviews | Legacy couple plugin uses `venue-review` posts, multiple rating dimensions, vendor responses | Review CPT, RateYo, plugin templates | Keep reviews in `SDWD Couple`, but simplify around `sdwd_review` plus `sdwd_rating`. If multi-dimension scoring is still required, extend the new review schema instead of restoring legacy markup and assets. |
| Request quotes | Legacy couple plugin creates request posts and lead history with booking capacity updates | Request CPT, vendor lead UI, Toastr, plugin forms | Keep request submission/business logic in `SDWD Couple`; if lead management remains necessary, add a dedicated quote-request storage layer rather than restoring `venue-request` as-is. Theme renders the forms. |
| Wishlist | Legacy couple plugin stores structured wishlist entries and vendor-manager tools | Wishlist dashboard UI, Toastr | Keep simple favorite storage in `SDWD Couple`; only re-add notes, estimate price, rating, and hire state if the founder still wants vendor-management features. Theme owns UI. |
| Budget | Legacy couple plugin ships category management, charts, and reset flows | ApexCharts, Slide Reveal, plugin dashboard | Keep budget storage in `SDWD Couple`; rebuild category editing and summaries in theme dashboards with minimal vanilla JS and CSS charts only if charting remains necessary. |
| Todo list | Legacy couple plugin has a dedicated task system | Slide Reveal, plugin dashboard | Current `SDWD Couple` checklist is the replacement path. Extend `sdwd_checklist` rather than restoring a separate `todo_list` subsystem unless advanced task metadata is required. |
| Guest list | Legacy couple plugin has a full guest/event/import/export stack | DataTables, exports, RSVP coupling | Missing in current plugins. Rebuild as a new `SDWD Couple` module only if Phase 4 includes guest management; keep rendering in theme dashboards and separate data schema from RSVP state. |
| RSVP | Legacy couple plugin reuses guest-list data for dashboard and public website RSVP | Guest list module plus couple website | Missing in current plugins. Rebuild only after guest list and website requirements are finalized; store RSVP data as structured guest/event state instead of embedding it in plugin templates. |
| Seating chart | Legacy couple plugin stores table layouts in post meta and drives drag/drop UI | Plugin dashboard JS | Missing in current plugins. Rebuild only after guest list is in place; keep storage in `SDWD Couple` and render the planner in theme dashboards. |
| Couple website | Legacy couple plugin registers `website` CPT, website taxonomies, dashboard tabs, and public templates | Website CPT, plugin templates, Toastr, datepicker | Missing in current plugins. Rebuild as a dedicated `SDWD Couple` website module only if the website product remains in scope; keep templates in theme or a future website-specific rendering layer instead of restoring plugin-owned frontend HTML. |
| Reviews vendor response | Legacy vendors can answer reviews | Legacy review dashboard widgets | Not present in current plugins. Re-add only if review moderation/response is still required; keep persistence in `SDWD Couple` or `SDWD Core`, but render response UI in the theme dashboard. |
| Lead history and booking capacity | Legacy quote module tracks status, history, booked dates, daily capacity | Quote request CPT plus vendor lead dashboard | Missing in current plugins. Rebuild only if vendor CRM behavior is still needed; otherwise keep quote requests as simple messages. |

## Section 3: Dependency Purge Plan

| Dependency | Where it is used | What it does | PHP replacement | CSS replacement | Minimal vanilla JS replacement |
|------|------|------|------|------|------|
| Bootstrap | Legacy core dashboards, auth modals, shortcodes, vendor tools | Layout grid, modal styling, utility classes, JS components | Keep layout decisions in theme templates and plugin data handlers only | Theme `foundation.css`, `components.css`, `layout.css`, page CSS | Native class toggles, small modal/menu scripts in theme JS |
| jQuery | Legacy core and couple scripts | Base DOM/event/AJAX dependency | Use WordPress AJAX endpoints and server-side validation exactly as now | No CSS dependency | Use `fetch`, `FormData`, `classList`, `closest`, `addEventListener` |
| Magnific Popup | Legacy modals/lightboxes | Overlay and lightbox behavior | No PHP replacement needed beyond existing handlers | Theme modal/lightbox styles | Small modal/lightbox controller in `assets/js` |
| Masonry | Legacy listing/card layouts | Uneven-card layout | Render content in deterministic template order | CSS grid or flex layouts in theme | None unless reflow is required |
| Isotope | Legacy filters | Client-side filtering/layout transitions | Query/filter on the server and send structured responses | Theme filter state styles | Minimal AJAX filter script plus CSS transitions |
| Select2 | Legacy enhanced selects | Searchable selects and multi-selects | Server-side request parsing unchanged | Native select styling in theme CSS | Progressive enhancement only where native select is insufficient |
| Summernote | Legacy rich text editing | WYSIWYG textareas | Use WordPress editor/admin fields where rich text is required | Native WP admin styling | None on frontend |
| Bootstrap Datepicker | Legacy budget/todo/website date fields | Calendar picker UI | Store ISO-like date strings in post/user meta | Theme date input styles | Native `<input type="date">` plus formatting helpers if needed |
| Bootstrap Slider | Legacy pricing and range controls | Slider inputs | Store numeric values in meta | Theme range-input styling | Native `<input type="range">` |
| Toastr | Legacy success/error popups | Non-blocking notifications | Keep server responses JSON-based | Theme toast styles or inline alert components | Small toast helper or inline status region |
| Slide Reveal | Legacy slide-out forms | Off-canvas forms/panels | No PHP change | Theme panel styles | `classList`-driven drawer toggle |
| ApexCharts | Legacy budget and guest-list charts | Donut/radial charts | Precompute totals server-side when necessary | Theme card/stat styles | Prefer simple totals/progress bars; only add a tiny chart helper if absolutely required |
| FullCalendar | Legacy calendar UI | Availability and calendar displays | Keep date data in plugin storage/query functions | Theme calendar/list styles | Rebuild only the needed interactions, or use list/calendar views built from native JS |
| Clipboard.js | Legacy copy invitation links | Copy-to-clipboard utility | No PHP change | Theme button states | `navigator.clipboard.writeText` |
| DataTables stack | Legacy guest-list tables and exports | Sorting, paging, CSV/PDF export | Export generation can move server-side when needed | Theme table styles | Native table sorting/filtering for small datasets; custom export endpoints when needed |
| RateYo | Legacy review stars | Star rating input/display | Keep rating storage in plugin meta | Theme star display styles | Minimal star-input widget in vanilla JS |
| Fontello icon font | Legacy iconography | Icon display | No PHP change | Replace with `sdwd-icons` and CSS tokens | None |
| Pagination helper | Legacy listing pagination | Pager UI | Use WordPress pagination helpers already available in PHP | Theme pagination styles | None |
| Owl Carousel | Plugin JS references only; library not confirmed in plugin scope | Legacy carousel API assumption | No plugin rebuild based on Owl-specific behavior | Theme carousel styles | Build only required carousel behavior in vanilla JS if the theme still needs it |
| Slick | Not found in plugin scope | None confirmed | None | None | None |
| OptionTree | Not found in plugin scope | Historical options framework reference only | Use WordPress options/settings APIs if a plugin settings page is needed | WP admin styles | None |
| Font Awesome | Not found in plugin scope | Legacy icon names only | None in plugin layer | Use `sdwd-icons` in theme | None |

## Section 4: Implementation Strategy (no code)

### 4.1 Core Features

| Feature | File location | Function names | Hooks used | Data storage | Rendering method |
|------|------|------|------|------|------|
| Roles | Plugin: `wp-content/plugins/sdwd-core/includes/roles.php` | Existing: `sdwd_register_roles` | `register_activation_hook`, bootstrap on `plugins_loaded` | WordPress roles/cap tables | No rendering |
| CPT registration | Plugin: `wp-content/plugins/sdwd-core/includes/post-types.php` | Existing: `sdwd_register_post_types` | `init` | `wp_posts` | Theme templates render archive/single UI |
| Taxonomy registration | Plugin: `wp-content/plugins/sdwd-core/includes/taxonomies.php` | Existing: `sdwd_register_taxonomies` | `init` | WordPress taxonomy tables | Theme templates render archive/filter UI |
| Auth | Plugin: `wp-content/plugins/sdwd-core/includes/auth.php`; Theme: modal templates and JS | Existing: `sdwd_handle_register`, `sdwd_handle_login`, `sdwd_handle_forgot_password`, `sdwd_get_dashboard_url` | `wp_ajax_nopriv_*`, `admin_init`, `show_admin_bar`, `login_redirect` | `wp_users`, user meta, password reset state | Theme form submits to plugin JSON handlers |
| Dashboard save | Plugin: `wp-content/plugins/sdwd-core/includes/dashboard.php`; Theme: dashboard templates | Existing: `sdwd_save_dashboard` | `wp_ajax_sdwd_save_dashboard` | Post meta and selected user account updates | Theme dashboard form, plugin persistence only |
| Claims | Plugin: `wp-content/plugins/sdwd-core/includes/claim.php`; Theme: claim submission form | Existing: `sdwd_handle_submit_claim`, `sdwd_claim_meta_box`, `sdwd_claim_meta_box_cb`, `sdwd_handle_approve_claim`, `sdwd_handle_reject_claim` | `wp_ajax_sdwd_submit_claim`, `wp_ajax_nopriv_sdwd_submit_claim`, `wp_ajax_sdwd_approve_claim`, `wp_ajax_sdwd_reject_claim`, `add_meta_boxes` | Post meta `sdwd_claim`, user meta `sdwd_post_id` on approval | Theme handles frontend form; plugin renders admin metabox |
| Migration tool | Plugin: `wp-content/plugins/sdwd-core/includes/migrate.php` | Existing: `sdwd_migration_page`, `sdwd_run_migration` | `admin_menu` | Legacy-to-current meta copy and user/post link sync | Plugin admin page only |
| Vendor admin editing | Plugin: `wp-content/plugins/sdwd-core/includes/admin/vendor-meta.php` | Existing: `sdwd_vendor_meta_boxes`, `sdwd_vendor_business_cb`, `sdwd_vendor_social_cb`, `sdwd_vendor_hours_cb`, `sdwd_vendor_pricing_cb`, `sdwd_save_vendor_meta` | `add_meta_boxes`, `save_post_vendor` | Vendor `sdwd_*` post meta | Plugin renders wp-admin metaboxes |
| Venue admin editing | Plugin: `wp-content/plugins/sdwd-core/includes/admin/venue-meta.php` | Existing: `sdwd_venue_meta_boxes`, `sdwd_venue_business_cb`, `sdwd_venue_location_cb`, `sdwd_venue_capacity_cb`, `sdwd_venue_social_cb`, `sdwd_venue_hours_cb`, `sdwd_venue_pricing_cb`, `sdwd_save_venue_meta` | `add_meta_boxes`, `save_post_venue` | Venue `sdwd_*` post meta | Plugin renders wp-admin metaboxes |
| Couple admin editing | Plugin: `wp-content/plugins/sdwd-core/includes/admin/couple-meta.php` | Existing: `sdwd_couple_meta_boxes`, `sdwd_couple_contact_cb`, `sdwd_couple_wedding_cb`, `sdwd_couple_social_cb`, `sdwd_save_couple_meta` | `add_meta_boxes`, `save_post_couple` | Couple `sdwd_*` post meta | Plugin renders wp-admin metaboxes |
| Vendor search/filter rebuild | Plugin: new `sdwd-core` query helper file; Theme: archive/filter templates | Proposed: `sdwd_register_vendor_query_vars`, `sdwd_build_vendor_filter_query`, `sdwd_get_vendor_filter_options` | `init`, `pre_get_posts`, optionally `wp_ajax_nopriv_sdwd_filter_vendors` if async filtering is retained | Taxonomies plus vendor `sdwd_*` post meta | Theme archive and filter sidebar |
| Venue search/filter rebuild | Plugin: new `sdwd-core` query helper file; Theme: archive/filter templates | Proposed: `sdwd_register_venue_query_vars`, `sdwd_build_venue_filter_query`, `sdwd_get_venue_filter_options` | `init`, `pre_get_posts`, optionally `wp_ajax_nopriv_sdwd_filter_venues` | Taxonomies plus venue `sdwd_*` post meta | Theme archive and filter UI |
| Follow/favorite rebuild if retained | Plugin: `sdwd-core` small persistence module; Theme: buttons | Proposed: `sdwd_toggle_follow_vendor`, `sdwd_get_followed_vendors` | `wp_ajax_sdwd_toggle_follow_vendor`, `wp_ajax_nopriv_sdwd_toggle_follow_vendor` only if guests are allowed | User meta or couple-post meta | Theme button and saved-state UI |
| Real-wedding rebuild if retained | Plugin: new `sdwd-core` module; Theme: archive/single templates | Proposed: `sdwd_register_real_wedding_post_type`, `sdwd_register_real_wedding_taxonomies` | `init`, `save_post_real_wedding` if custom meta is added | `wp_posts`, taxonomies, post meta | Theme archive/single templates |

### 4.2 Couple Features

| Feature | File location | Function names | Hooks used | Data storage | Rendering method |
|------|------|------|------|------|------|
| Reviews | Plugin: `wp-content/plugins/sdwd-couple/modules/reviews.php`; Theme: review forms/lists | Existing: `sdwd_handle_submit_review`, `sdwd_get_reviews`, `sdwd_get_average_rating` | `init`, `wp_ajax_sdwd_submit_review` | `sdwd_review` CPT plus `sdwd_reviewed_id` and `sdwd_rating` post meta | Theme renders forms and review summaries |
| Request quote | Plugin: `wp-content/plugins/sdwd-couple/modules/request-quote.php`; Theme: quote forms and vendor dashboard UI | Existing: `sdwd_handle_request_quote` | `wp_ajax_sdwd_request_quote` | Couple-post meta `sdwd_quote_requests`, linked via `sdwd_post_id` | Theme form submits to plugin handler |
| Wishlist | Plugin: `wp-content/plugins/sdwd-couple/modules/wishlist.php`; Theme: saved/favorite UI | Existing: `sdwd_handle_toggle_wishlist`, `sdwd_is_in_wishlist`, `sdwd_get_wishlist` | `wp_ajax_sdwd_toggle_wishlist` | User meta `sdwd_wishlist` | Theme buttons, cards, and saved list UI |
| Checklist | Plugin: `wp-content/plugins/sdwd-couple/modules/checklist.php`; Theme: planning dashboard | Existing: `sdwd_handle_save_checklist`, `sdwd_get_checklist`, `sdwd_get_default_checklist` | `wp_ajax_sdwd_save_checklist` | User meta `sdwd_checklist` | Theme dashboard widgets and forms |
| Budget | Plugin: `wp-content/plugins/sdwd-couple/modules/budget.php`; Theme: planning dashboard | Existing: `sdwd_handle_save_budget`, `sdwd_get_budget`, `sdwd_get_default_budget_categories` | `wp_ajax_sdwd_save_budget` | User meta `sdwd_budget_total`, `sdwd_budget_items` | Theme dashboard widgets and forms |
| Advanced wishlist notes/hire state if restored | Plugin: new `sdwd-couple` module; Theme: couple dashboard | Proposed: `sdwd_save_wishlist_note`, `sdwd_set_wishlist_hire_state`, `sdwd_get_wishlist_item_meta` | `wp_ajax_sdwd_save_wishlist_note`, `wp_ajax_sdwd_set_wishlist_hire_state` | Expand `sdwd_wishlist` structure or introduce dedicated couple-post meta | Theme dashboard panels |
| Guest list rebuild | Plugin: new `sdwd-couple` module; Theme: dashboard tables/forms | Proposed: `sdwd_save_guest_list`, `sdwd_add_guest_group`, `sdwd_import_guest_csv`, `sdwd_get_guest_list_summary` | `wp_ajax_sdwd_save_guest_list`, `wp_ajax_sdwd_add_guest_group`, `wp_ajax_sdwd_import_guest_csv` | Couple-post meta or dedicated custom table if guest volume becomes large | Theme dashboard tables and forms |
| RSVP rebuild | Plugin: new `sdwd-couple` module; Theme: dashboard and public RSVP form | Proposed: `sdwd_submit_rsvp`, `sdwd_update_guest_rsvp`, `sdwd_get_rsvp_summary` | `wp_ajax_nopriv_sdwd_submit_rsvp`, `wp_ajax_sdwd_update_guest_rsvp` | Couple-post meta or guest-list storage layer | Theme public form and dashboard summaries |
| Seating chart rebuild | Plugin: new `sdwd-couple` module; Theme: dashboard planner | Proposed: `sdwd_save_seating_chart`, `sdwd_get_seating_chart` | `wp_ajax_sdwd_save_seating_chart` | Couple-post meta `sdwd_seating_chart_data` or dedicated table | Theme planner UI |
| Couple website rebuild | Plugin: new `sdwd-couple` website module; Theme: public templates | Proposed: `sdwd_register_website_post_type`, `sdwd_register_website_taxonomies`, `sdwd_save_website_section` | `init`, `wp_ajax_sdwd_save_website_section`, `save_post_website` for admin editing if added | `website` CPT plus website-specific post meta | Theme or website-specific templates render the public site |
| Review vendor response rebuild if retained | Plugin: extend `sdwd-couple` or `sdwd-core`; Theme: vendor dashboard | Proposed: `sdwd_submit_review_response`, `sdwd_get_review_response` | `wp_ajax_sdwd_submit_review_response` | Review post meta such as `sdwd_vendor_response` | Theme vendor dashboard UI |

## Section 5: Gap Analysis

### 5.1 Legacy features not present in the new plugins

| Plugin area | Missing from current state | Notes |
|------|------|------|
| Core | `real-wedding` CPT and related taxonomies | Present in legacy core, absent from `SDWD Core` |
| Core | `team` and `testimonial` CPTs | Present in legacy core, absent from `SDWD Core` |
| Core | Plugin-owned vendor/venue search widgets and dropdown helpers | Legacy core owns them; current core does not |
| Core | Follow/unfollow vendor feature | Present in legacy core, absent from current core |
| Core | Availability widget and related AJAX | Present in legacy core, absent from current core |
| Core | Plugin shortcode library | Entire shortcode system removed in current core |
| Core | Plugin-rendered auth and dashboard markup | Intentionally removed; theme now owns rendering |
| Couple | Guest list | Present in legacy couple plugin, absent from current couple plugin |
| Couple | RSVP | Present in legacy couple plugin, absent from current couple plugin |
| Couple | Seating chart | Present in legacy couple plugin, absent from current couple plugin |
| Couple | Couple website builder and public website CPT | Present in legacy couple plugin, absent from current couple plugin |
| Couple | Vendor lead CRM history/capacity tools | Present in legacy couple plugin, absent from current couple plugin |
| Couple | Multi-dimension review schema and vendor review responses | Legacy feature set is broader than current `sdwd_review` implementation |

### 5.2 Features partially implemented

| Feature | Current status | Gap |
|------|------|------|
| Vendor profile | Core data model and save handlers exist | Legacy dashboard UX, advanced filters, and pricing UI still need theme-side rebuild |
| Venue profile | Core data model and save handlers exist | Legacy venue parity and filter/search surface still incomplete |
| Couple profile | Core contact/wedding meta exists | Legacy planning modules are mostly gone or split |
| Reviews | Basic review submit/read exists in `SDWD Couple` | Legacy vendor responses and multi-score dimensions are missing |
| Request quote | Basic quote submission exists | Legacy request post workflow, status history, and vendor lead tooling are missing |
| Wishlist | Basic favorite toggle exists | Legacy notes, estimate price, rating, and hire-state tools are missing |
| Budget | Storage and defaults exist | Legacy category CRUD, charts, and reset flow are not rebuilt |
| Claims | Simplified claim state exists | Legacy dedicated claim CPT/reporting workflow has been collapsed |

### 5.3 Dead features that should not be rebuilt

| Legacy feature | Why it should not be rebuilt as-is |
|------|------|
| Layout/content shortcodes | The repo architecture puts rendering in the theme, not in plugin shortcodes |
| Bootstrap-based plugin dashboards | The repo explicitly bans Bootstrap and wants theme-owned rendering |
| Plugin-owned auth modal HTML | Current architecture already moves frontend rendering into theme templates |
| Duplicate vendor vs venue architectures | Legacy split caused parity problems and should stay collapsed into one data model |
| Slide Reveal and Toastr dependency patterns | These are implementation conveniences, not product requirements |
| Dedicated `claim-venue` CPT | Current simplified claim-meta flow covers the same business process with less structure |
| `venue-request` CPT as the exact quote-request implementation | The product may still need quote requests, but not necessarily as a dedicated post type with legacy UI baggage |

## Section 6: Priority Roadmap

### Phase 1: Core data model (CPTs, taxonomies)

- Confirm whether `real-wedding`, `website`, guest-list, RSVP, and seating-chart data models are still in scope.
- Keep `vendor`, `venue`, `couple`, `vendor-category`, `venue-type`, and `venue-location` in `SDWD Core`.
- Decide whether missing legacy CPTs should return as plugin modules or remain retired.
- Finish migration coverage from legacy keys into `sdwd_*` keys before adding new UI.

### Phase 2: Meta + forms

- Complete theme-side vendor, venue, and couple dashboard forms against `SDWD Core`.
- Extend `SDWD Couple` forms for budget, checklist, wishlist, reviews, and quote requests.
- Only reintroduce missing couple modules after the founder confirms they are still product requirements.
- Replace legacy plugin-owned form markup with theme templates and minimal JS.

### Phase 3: Frontend filtering/search

- Rebuild vendor and venue archive filters in theme templates.
- Move query composition into small plugin-side helpers instead of plugin-owned HTML.
- Remove all reliance on legacy shortcodes and widget wrappers.
- Rebuild only the search/filter behavior that still maps to current information architecture.

### Phase 4: Dashboards (vendor/couple)

- Finish vendor and venue dashboard parity in the theme against current `SDWD Core` handlers.
- Finish couple dashboard parity for the features that already exist in `SDWD Couple`.
- Sequence missing couple features in this order if they remain in scope: guest list, RSVP, seating chart, couple website, lead history.
- Keep rendering in theme templates; keep storage and business rules in plugins.

### Phase 5: Polish + performance

- Remove any remaining plugin-owned legacy assets not needed by current modules.
- Eliminate Bootstrap/jQuery-era UI assumptions from theme and plugin docs.
- Re-test migration paths for profile meta, reviews, quotes, and claims.
- Confirm that every surviving frontend feature can render without legacy shortcode or library dependencies.

## Section 7: Hard Rules

- No assumptions.
- Do not invent features.
- Only extract what exists in code.
- If something is unclear, mark it `UNKNOWN`.
- No shortcuts.
