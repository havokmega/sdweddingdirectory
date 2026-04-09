# Vendor vs Venue Parity Audit

Last updated: 2026-04-06

## Purpose

Document every structural difference between how vendors and venues are handled in `SDWD Core` (`wp-content/plugins/sdwd-core/`), so we can plan the work to make them parallel.

---

## The Core Problem

The plugin was built vendor-first. Venues were converted from a "listings" module with a completely different architecture. The result:

- **Vendors** = first-class citizens with their own dashboard, menu system, profile tabs, admin metaboxes
- **Venues** = second-class features managed through a form buried inside the vendor dashboard

The founder's intent: vendors and venues should be structurally identical in the backend, differing only in their taxonomy assignments and data fields.

---

## Feature-by-Feature Comparison

### 1. CPT Registration

| Aspect | Vendors | Venues | Gap |
|--------|---------|--------|-----|
| File | `admin-file/custom-post-type/vendor-post/index.php` | `venue/admin-file/custom-post/index.php` | Different locations |
| Post type slug | `vendor` | `venue` | OK |
| Supports | title, editor, thumbnail | title, editor, thumbnail, author, excerpt | Venues have more (fine) |
| Archive | Enabled | **Disabled** | Venues need archive |
| Rewrite slug | `/vendor` (singular) | `/venue` (singular) | By design — singular for profiles, plural for archives/landing |
| Taxonomies | `vendor-category` (1) | `venue-type` + `venue-location` (2) | By design |
| Admin creation | Allowed via metabox | Only via vendor dashboard form | **Gap — venues need admin creation** |

### 2. Dashboard

| Aspect | Vendors | Venues | Gap |
|--------|---------|--------|-----|
| Dashboard directory | `dashboard/vendor-file/` | **None** — uses `venue/vendor-file/` | **Major gap** |
| Menu system | `vendor-menu.php` with filter-based tabs | **None** | **Major gap** |
| Dashboard overview | `dashboard/vendor-file/dashboard/` | **None** | **Major gap** |
| Profile editing | 7 tabbed pages in `profile-page/default-tabs/` | Single multi-section form in `add-update-venue/` | **Different UX pattern** |

**Vendor dashboard tabs:**
1. My Profile (personal/business info)
2. Business Profile (company details)
3. Social Media
4. Business Hours
5. Vendor Filters (custom attributes)
6. Upload Photos
7. Password Change

**Venue editing sections (no tabs, single form):**
1. Venue Info
2. Location Info
3. Category Info
4. Media Info
5. Pricing
6. Working Hours
7. My Team
8. Room/Facility
9. Venue Menu
10. Venue FAQ
11. Venue Video
12. Related Vendor

### 3. User Roles

| Aspect | Vendors | Venues | Gap |
|--------|---------|--------|-----|
| Role | `vendor` | Uses `vendor` role | No separate venue role |
| Ownership | Post author = vendor user | Post author = vendor user who created it | Venues are owned by vendors |

**Decision needed:** Should venues have their own user role, or should the `vendor` role manage both? Current: vendors own venues. Founder intent: venues should be independently manageable.

### 4. AJAX Handlers

| Aspect | Vendors | Venues |
|--------|---------|--------|
| Dedicated handlers | Minimal (uses core) | 8 dedicated (assign-badges, category-data, publish, deactivate, remove, restore, duplicate, update) |
| Repeater fields | None | 4 (FAQs, menus, team, facilities) |

Venues actually have MORE AJAX infrastructure than vendors. This is because the venue form is more complex.

### 5. Admin Metaboxes

| Aspect | Vendors | Venues | Gap |
|--------|---------|--------|-----|
| WordPress admin metabox | Yes — `sdwd_vendor_profile_info` | Limited — `sdweddingdirectory-venue-data` | Venue admin editing is weak |
| Fields in metabox | first_name, last_name, company_name, website, contact, email, user_id | Badge assignment, map location | Venue metabox missing most fields |

### 6. Taxonomy

| Aspect | Vendors | Venues |
|--------|---------|--------|
| Taxonomies | `vendor-category` (flat) | `venue-type` (hierarchical) + `venue-location` (hierarchical) |
| Term meta | header, media, icon, marker | image, icon, marker |
| Admin UI | Standard WP taxonomy admin | State/region/city cascade UI |

**Simplification needed:** `venue-location` should be flat (just San Diego city names). Remove state/region/city hierarchy code.

### 7. Claim System

| Aspect | Vendors | Venues |
|--------|---------|--------|
| Claim module | None needed | `claim-venue/` with dedicated CPT (`claim-request`) |

Claim system is venue-only. This makes sense — vendors register directly; venues may be pre-populated and claimed later.

### 8. Search / Frontend

| Aspect | Vendors | Venues |
|--------|---------|--------|
| Archive/search page | Yes (has_archive: true) | **No** (has_archive: false) |
| Filter UI | Shared dropdown framework | None (single venue pages only) |
| Singular page | `front-file/singular-vendor/` | `venue/singular-venue/` (14+ widget sections) |

**Gap:** Venues need an archive page with search/filter, parallel to vendors.

### 9. Map / Location

| Aspect | Vendors | Venues |
|--------|---------|--------|
| Map markers | Has marker icons in plugin | Has marker icons + Google Maps embed |
| Location data | **Should not have any** | Coordinates, address, map embed |

**Action needed:** Remove vendor map/marker functionality. Only venues have locations.

---

## Summary of Gaps to Close

### Must fix (structural parity)

1. **Create `dashboard/venue-file/`** — parallel to `dashboard/vendor-file/` with:
   - `venue-menu.php`
   - `dashboard/` (overview page)
   - `profile-page/` with tabbed editing (migrate from `venue/vendor-file/add-update-venue/`)

2. **Enable venue archive** — set `has_archive: true` so venues have a searchable listing page

3. **Add venue admin metabox** — allow admin to create/edit venues from WordPress admin, not just frontend

### Should fix (cleanup)

4. **Flatten venue-location** — remove state/region/city hierarchy, use flat city terms only

5. **Remove vendor map/marker code** — vendors don't have locations

6. ~~Consistent rewrite slugs~~ — Not a gap. Singular for single profiles (`/vendor/`, `/venue/`), plural for landing/archives (`/vendors/`, `/venues/`) is intentional

### Nice to have (future)

7. **Venue user role** — if venues should be independently managed (not owned by vendors), they need their own role

8. **Venue search/filter UI** — parallel to vendor search with type/location/price filtering

---

## What NOT to Touch

- Theme `/inc/` files (vendors.php, venues.php, navwalker) — these are clean template helpers
- Theme templates that are currently working
- Plugin files without explicit founder approval
- Any framework imports (Bootstrap, jQuery, etc.)
