# Next session task: Venue Location page filter sidebar

**Goal:** render the 4-section filter sidebar on every `/venues/{location}/` page per founder spec.
**Target URL:** `/venues/{location}/` (taxonomy-venue-location.php → template-parts/venues/listing.php).
**Rendered-only scope:** UI must be visible. Filter LOGIC for Settings + Amenities is deferred (no venue data yet). Venue Type + Capacity filters should actually filter the query (data exists).
**Token estimate:** 30-60k in one clean pass (read 2 files, edit 2 files, verify in browser).

---

## Founder's exact spec (reference — do not deviate)

**Group 1 — Venue Type** (17 options, exact order):
All types, Barns & Farms, Hotels, Wineries & Breweries, Country Clubs, Restaurants, Rooftops & Lofts, Mansions, Churches & Temples, Museums, Boats, Parks, Historic Venues, Banquet Halls, Beaches, Gardens, Waterfronts

**Group 2 — Number of guests** (5 ranges):
0-99, 100-199, 200-299, 300-399, 400+

**Group 3 — Settings** (3 options):
Indoor, Covered Outdoor, Uncovered Outdoor

**Group 4 — Amenities** (11 options):
Accommodations, Bar Services, Catering Services, Clean Up, Event Planner, Event Rentals, Get Ready Rooms, Liability Insurance, Outside Vendors, Pet Friendly, Wifi

---

## Current state (verified 2026-04-24)

**Good news:** sidebar infrastructure already exists at `template-parts/venues/listing.php:188-280`:
- `<aside class="archive-filtered__sidebar">` with a `<form class="venues-filter-form" method="get">`
- Existing filter groups: Price range, Guest count (capacity select), Settings (checkboxes), Amenities (checkboxes), Services, Styles, Sort
- Each group gated on `if ( ! empty( $options['{name}'] ) )` — so empty options = hidden section

**What's MISSING:**
- **Venue Type filter** — not in the sidebar at all. Must be added as a new group ABOVE the existing Price range section.
- Settings/Amenities helpers (`sdwdv2_get_venue_value_options` in `inc/venues.php:367-368`) likely return empty arrays because no venues have been tagged with these values. The sections therefore don't render on the live page.
- Capacity ranges from `sdwdv2_get_venue_range_options` may not match the founder's 5 ranges. Need to override with fallback.

**Good architecture to preserve:**
- The `sdwdv2_get_venue_filter_state()` in `inc/venues.php` already reads GET params for `cat_id`, `location`, `price-filter`, `capacity`, and likely `venue_setting` + `venue_amenities`.
- `sdwdv2_build_venue_query_args()` at `inc/venues.php:~395` already applies a `venue-type` tax_query when `category_id` is set.

---

## Task breakdown

### Task A — Add Venue Type filter section to listing.php

1. Read `template-parts/venues/listing.php` lines 188-220 to find the sidebar form + first filter group (Price range at line 203).
2. INSERT a new filter group BEFORE the Price range section (so Venue Type is first in sidebar):
   - Header label: `Venue type`
   - Render as radio buttons OR a `<select>` — prefer radios for the founder's 17-item spec (one-click select).
   - Field name: `cat_id` (matches existing hidden input behavior; the sidebar form currently emits cat_id as a hidden input when set from elsewhere — this replaces that with an editable selector).
   - Values: term_id of the matching `venue-type` term, resolved via `get_term_by( 'slug', 'barn-farm-weddings', 'venue-type' )` etc. Use the slug mapping below.
   - Default `All types` = empty value (no filter).
3. Slug mapping (founder's label → existing `venue-type` term slug, derived from site-outline.md + current DB):
   ```
   Barns & Farms          barn-farm-weddings
   Hotels                 hotel-weddings
   Wineries & Breweries   winery-brewery-weddings
   Country Clubs          country-club-weddings
   Restaurants            restaurant-weddings
   Rooftops & Lofts       rooftop-loft-weddings
   Mansions               mansion-weddings
   Churches & Temples     church-temple-weddings
   Museums                museum-weddings
   Boats                  boat-weddings
   Parks                  park-weddings
   Historic Venues        historic-venue-weddings
   Banquet Halls          banquet-hall-weddings
   Beaches                beach-weddings
   Gardens                garden-weddings
   Waterfronts            waterfront-weddings
   ```
   Verify each slug exists via `docker exec -w /var/www/html wp_ssh wp --allow-root term list venue-type --fields=slug,name --format=csv`. If any don't exist, note the mismatch — do NOT silently skip. Ask founder.
4. Render the founder's 17 labels in EXACT order (first is "All types" → value=""). Use hardcoded array in listing.php (not `get_terms`) so the order is preserved.
5. Handle the case where the user is already on `/venues/{location}/` — the form should submit to same URL with `cat_id={term_id}` added. The existing form `action="<?php echo esc_url( $current_url ); ?>"` already handles this; the new field just needs `name="cat_id"` value="{term_id_or_empty}".
6. Remove the existing hidden `<input type="hidden" name="cat_id">` at line 191-193 — it's superseded by the new visible selector.

### Task B — Add capacity fallback (5 founder ranges)

1. Open `inc/venues.php:~367` — `sdwdv2_get_venue_range_options( 'capacity_options', 'capacity_available', $category_id )`.
2. Locate that helper function definition elsewhere in `inc/venues.php`. It probably takes a 4th arg for fallback ranges (like price_ranges does at line 360-364 — it has a hardcoded fallback array).
3. Pass the founder's 5 ranges as the 4th arg for capacity:
   ```php
   [
       [ 'min' => 0,   'max' => 99,    'label' => __( '0 - 99', 'sandiegoweddingdirectory' ) ],
       [ 'min' => 100, 'max' => 199,   'label' => __( '100 - 199', 'sandiegoweddingdirectory' ) ],
       [ 'min' => 200, 'max' => 299,   'label' => __( '200 - 299', 'sandiegoweddingdirectory' ) ],
       [ 'min' => 300, 'max' => 399,   'label' => __( '300 - 399', 'sandiegoweddingdirectory' ) ],
       [ 'min' => 400, 'max' => 99999, 'label' => __( '400+', 'sandiegoweddingdirectory' ) ],
   ]
   ```
4. If the helper's signature doesn't accept a 4th arg yet, add it (same pattern as price_ranges).

### Task C — Add Settings fallback (3 options — render even without data)

1. `sdwdv2_get_venue_value_options( 'setting_options', 'setting_available', $category_id )` in `inc/venues.php:~368`.
2. Make this helper return the founder's 3 options if the DB returns empty:
   ```php
   [
       [ 'value' => 'indoor',            'label' => __( 'Indoor', 'sandiegoweddingdirectory' ) ],
       [ 'value' => 'covered-outdoor',   'label' => __( 'Covered Outdoor', 'sandiegoweddingdirectory' ) ],
       [ 'value' => 'uncovered-outdoor', 'label' => __( 'Uncovered Outdoor', 'sandiegoweddingdirectory' ) ],
   ]
   ```
3. Exact helper-signature change depends on existing implementation — may need a 4th arg or a `|| fallback` at the return site.
4. Render mode in listing.php is already checkboxes (see line 237-260 area for the foreach loop that handles `venue_setting`/`venue_amenities`).

### Task D — Add Amenities fallback (11 options)

Same pattern as Task C but for amenities:
```php
[
    [ 'value' => 'accommodations',      'label' => __( 'Accommodations', 'sandiegoweddingdirectory' ) ],
    [ 'value' => 'bar-services',        'label' => __( 'Bar Services', 'sandiegoweddingdirectory' ) ],
    [ 'value' => 'catering-services',   'label' => __( 'Catering Services', 'sandiegoweddingdirectory' ) ],
    [ 'value' => 'clean-up',            'label' => __( 'Clean Up', 'sandiegoweddingdirectory' ) ],
    [ 'value' => 'event-planner',       'label' => __( 'Event Planner', 'sandiegoweddingdirectory' ) ],
    [ 'value' => 'event-rentals',       'label' => __( 'Event Rentals', 'sandiegoweddingdirectory' ) ],
    [ 'value' => 'get-ready-rooms',     'label' => __( 'Get Ready Rooms', 'sandiegoweddingdirectory' ) ],
    [ 'value' => 'liability-insurance', 'label' => __( 'Liability Insurance', 'sandiegoweddingdirectory' ) ],
    [ 'value' => 'outside-vendors',     'label' => __( 'Outside Vendors', 'sandiegoweddingdirectory' ) ],
    [ 'value' => 'pet-friendly',        'label' => __( 'Pet Friendly', 'sandiegoweddingdirectory' ) ],
    [ 'value' => 'wifi',                'label' => __( 'Wifi', 'sandiegoweddingdirectory' ) ],
]
```

### Task E — Verify query logic handles venue-type filter

1. Check `sdwdv2_build_venue_query_args()` in `inc/venues.php:~395`. It already builds a `venue-type` tax_query from `$filters['category_id']`. Confirm the sidebar's new `cat_id` field flows into that.
2. If `sdwdv2_get_venue_filter_state()` (probably in the same file) reads `$_GET['cat_id']` → `$filters['category_id']`, we're good. If not, add it.
3. For Settings + Amenities: the filter state likely reads `$_GET['venue_setting']` + `$_GET['venue_amenities']` as arrays. The query applies them as `meta_query` on meta keys `venue_setting` / `venue_amenities`. Since no venues have that data yet, submitting with settings/amenities checked will return empty results — expected behavior until data is captured (explicit TODO to capture in venue-profile.php form later).

### Task F — Browser verify

1. Visit `http://localhost:8080/venues/carlsbad/` (or any populated location).
2. Confirm the sidebar shows 4 groups in this order: Venue type → Guest count → Settings → Amenities. (Price / Services / Styles / Sort may also still show — acceptable, don't remove unless founder asks.)
3. Click "Barns & Farms" radio → submit → URL should be `/venues/carlsbad/?cat_id={term_id}` and results should narrow to Barn/Farm venues in Carlsbad.
4. Select a capacity range → submit → URL should include `capacity=[100-199]` → results narrow further.
5. Check Settings + Amenities checkboxes → submit → no error (results may be empty, that's fine — no data yet).

---

## Files to touch

| File | Change |
|---|---|
| `wp-content/themes/sandiegoweddingdirectory/template-parts/venues/listing.php` | Insert new Venue Type filter section at ~line 202 (before Price range). Remove the existing hidden cat_id input at ~191-193. |
| `wp-content/themes/sandiegoweddingdirectory/inc/venues.php` | Add capacity fallback ranges to helper signature (~line 367). Make Settings + Amenities helpers return founder's fallback lists when DB returns empty (~lines 368-369). |

## Files to NOT touch

- `inc/vendors.php` (separate concern — parallel vendor filter is a different task)
- Anything outside `template-parts/venues/` + `inc/venues.php`
- CSS — the sidebar CSS already exists for the existing filter groups; new Venue Type group reuses `.venues-filter-form__group` class so no CSS needed.
- `functions.php` enqueues — already conditionally load `pages/venues.css` on `is_tax('venue-location')`.

---

## Acceptance criteria

1. Visit `/venues/carlsbad/` → sidebar renders 4 groups in the specified order.
2. Venue Type group shows "All types" + 16 venue types in the exact founder order.
3. Guest count group shows the 5 founder ranges (0-99, 100-199, 200-299, 300-399, 400+).
4. Settings group shows 3 checkboxes (Indoor, Covered Outdoor, Uncovered Outdoor).
5. Amenities group shows 11 checkboxes in the founder's order.
6. Selecting "Barns & Farms" + hitting Search → URL has `cat_id=X` → results narrowed to that type.
7. Selecting a capacity range → URL has `capacity=[100-199]` → results narrowed.
8. Selecting Settings/Amenities → form submits cleanly, no PHP errors (results may be empty pending data capture).

---

## Defer to later task (NOT this session)

- **Capturing Settings + Amenities data on venue-profile.php form** — so the filters actually return results. This requires adding checkbox groups to the venue-profile edit form + a save handler that writes to `sdwd_venue_setting` / `sdwd_venue_amenities` post meta. Track as a new REQ, probably P5-DASH-02 addendum or a new P2-PARITY-05.
- **Dynamic counts per filter** (e.g., "Hotels (12)") — nice-to-have, deferred.
- **AJAX/live filtering** — deferred, form submit is fine for launch.

---

## Commit message template

```
P2 feat: venue-location filter sidebar per founder spec

Adds Venue Type + Capacity + Settings + Amenities filter groups to
the sidebar rendered at /venues/{location}/. Matches founder's exact
option list and ordering (see
.planning/phases/02-plugin-closeout/NEXT-VENUE-LOCATION-FILTERS.md).

- template-parts/venues/listing.php: new Venue Type filter section
  inserted before Price range. Renders 17 radio options (All types +
  16 venue types) in founder's order via hardcoded array (term lookup
  by slug). Replaces the previous hidden cat_id input.
- inc/venues.php: capacity helper now accepts founder's 5-range
  fallback (0-99, 100-199, 200-299, 300-399, 400+). Settings helper
  returns 3-option fallback (indoor / covered-outdoor /
  uncovered-outdoor). Amenities helper returns 11-option fallback
  (accommodations, bar-services, ..., wifi).

Filter logic wired for venue-type (tax_query) and capacity
(meta_query on venue_capacity_value). Settings + amenities render
as checkboxes and submit cleanly but return empty results until
venue-profile.php form is extended to capture those meta values
(deferred — flagged in the task spec).

Verified at /venues/carlsbad/ — sidebar renders 4 groups in order,
Barns & Farms radio narrows results correctly, capacity range
narrows further. Settings + amenities submit without errors.
```

---

*Spec written 2026-04-24 with limited context remaining. Next session should read this top-to-bottom before touching any file. Estimated 30-60k tokens to execute cleanly.*
