# Claim + Slug Refactor Phase Log

## Phase 1 — Shared Slug Validator (Complete)
- Added shared validator in `wp-content/plugins/sdweddingdirectory/config-file/index.php`:
  - `slug_target_post()`
  - `validate_business_slug()`
  - `apply_validated_slug()`
- Vendor slug update now uses shared validator in `dashboard/vendor-file/profile-page/ajax/index.php`.
- Venue update flow now applies slug through shared validator in `venue/ajax/update-venue/index.php`.
- Removed direct `post_name` updates from real-wedding update flow (`real-wedding/ajax/index.php`).

## Phase 2 — Clean Claim Workflow (Complete)
- Reworked claim AJAX in `claim-venue/ajax/index.php`:
  - New endpoint: `sdwd_profile_claim_submit_action`
  - Legacy action now routes through clean claim processor: `sdweddingdirectory_claim_request_action`
  - Enforced nonce + capability checks
  - Claim metadata written exactly as:
    - `claimant_name`
    - `claimant_phone`
    - `claimant_email`
    - `target_post_id`
    - `target_post_type`
    - `target_slug`
- Added duplicate pending claim guard by target+claimant email.

## Phase 3 — Registration + Dashboard Slug Claim Triggers (Complete)
- Vendor registration now relies on shared validator via `config-file/vendor-config/index.php`:
  - Slug source is always `sanitize_title(company_name)`
  - Username is not used as slug source
  - No silent `-2`, `-3` slug fallback in taken cases
- If slug is taken, registration returns `claim_required` payload (no duplicate vendor profile creation).
- Vendor profile slug edit now:
  - validates via shared validator
  - updates immediately if available
  - returns claim trigger response if taken

## Phase 4 — Admin Claims Approval + One-Time Onboarding Trigger (Complete)
- Updated admin claim list + schema display in `claim-venue/admin-file/custom-post/index.php`.
- Updated claim metabox fields to clean metadata contract in `claim-venue/admin-file/meta-box/index.php`.
- Approval action now performs only:
  1. set target post author to claimant user
  2. set user meta `sdwd_claim_approved_welcome = 1`

## Phase 5 — One-Time Onboarding Flow + Helper Save (Complete)
- Added one-time welcome + profile helper modals to vendor dashboard in:
  - `dashboard/vendor-file/dashboard/vendor-file/index.php`
- Helper modal includes dropdown-only inputs for:
  - category-dependent vendor searchable filters
  - business open days/hours
- Added AJAX save endpoint with nonce + capability checks:
  - `sdwd_vendor_onboarding_save_action`
  - file: `dashboard/vendor-file/profile-page/ajax/index.php`
- Onboarding flow is shown once after approval and flag is consumed.

## Frontend Claim Modal Wiring (Complete)
- Added reusable claim modal markup in:
  - `front-file/vendor-login-register/index.php`
- Wired claim modal open + submit handlers in:
  - `front-file/vendor-login-register/script.js`
  - `dashboard/vendor-file/profile-page/vendor-file/script.js`
