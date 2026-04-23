# Site Outline — San Diego Wedding Directory

**Status:** FINAL — this is the goal for the launch milestone.
**Authoritative for:** complete URL inventory, page grouping, per-archive content rules, internal-linking plan, H1 geo-targeting pattern.
**Complements:** `route-template-map.md` (per-URL template + rendering details). If the two conflict on a URL, this file wins on the *existence and grouping* of URLs; `route-template-map.md` wins on *which template and components render a given URL*.

---

## Home page

- `https://www.sdweddingdirectory.com/`

---

## Wedding Planning

### Parent page

- `https://www.sdweddingdirectory.com/wedding-planning`

### Child pages

- `https://www.sdweddingdirectory.com/wedding-planning/wedding-checklists`
- `https://www.sdweddingdirectory.com/wedding-planning/wedding-guests-list`
- `https://www.sdweddingdirectory.com/wedding-planning/wedding-seating-tables`
- `https://www.sdweddingdirectory.com/wedding-planning/wedding-budget`
- `https://www.sdweddingdirectory.com/wedding-planning/wedding-website`
- `https://www.sdweddingdirectory.com/wedding-planning/vendor-manager`

---

## Venues

### Directory landing

- `https://www.sdweddingdirectory.com/venues` — page (directory template)

### Per-location pages (CPT taxonomy — `venue-location`)

**Bottom-of-page sections on every `/venues/{location}/`:**

1. Carousel with 4 cards → *See reviews from couples at Wedding Venues in {location}*
2. 4-card row → *Wedding Venues in {location} with Real Weddings published*
3. The last two sections from `/venues` (repeated for internal linking + SEO)

**URLs:**

- `/venues/alpine`
- `/venues/bonita`
- `/venues/bonsall`
- `/venues/cardiff-by-the-sea`
- `/venues/carlsbad`
- `/venues/chula-vista`
- `/venues/coronado`
- `/venues/del-mar`
- `/venues/descanso`
- `/venues/el-cajon`
- `/venues/encinitas`
- `/venues/escondido`
- `/venues/fallbrook`
- `/venues/imperial-beach`
- `/venues/jamul`
- `/venues/julian`
- `/venues/la-jolla`
- `/venues/national-city`
- `/venues/oceanside`
- `/venues/pala`
- `/venues/palomar-mountain`
- `/venues/poway`
- `/venues/ramona`
- `/venues/rancho-santa-fe`
- `/venues/san-diego`
- `/venues/san-marcos`
- `/venues/santa-ysabel`
- `/venues/santee`
- `/venues/solana-beach`
- `/venues/spring-valley`
- `/venues/valley-center`
- `/venues/vista`

### Per-type pages (CPT taxonomy — `venue-type`)

- `/venues/barn-farm-weddings`
- `/venues/outdoor-weddings`
- `/venues/garden-weddings`
- `/venues/beach-weddings`
- `/venues/rooftop-loft-weddings`
- `/venues/hotel-weddings`
- `/venues/waterfront-weddings`
- `/venues/winery-brewery-weddings`
- `/venues/park-weddings`
- `/venues/country-club-weddings`
- `/venues/mansion-weddings`
- `/venues/historic-venue-weddings`
- `/venues/boat-weddings`
- `/venues/restaurant-weddings`
- `/venues/museum-weddings`
- `/venues/banquet-hall-weddings`
- `/venues/church-temple-weddings`

### Combo pages (location × type — the primary SEO surface)

- `https://www.sdweddingdirectory.com/{location}/{type}` — one URL per valid combination based on the venue types available in each location.

> **This is LG-02.** Delivered by Phase 4 (`combo-venue.php` + rewrite rules in `sdwd-core/includes/routing/rewrite-rules.php`). Every combo page must return HTTP 200 with real content, not 404.

### Single venue pages

- `https://www.sdweddingdirectory.com/venue/{venue-name}`

---

## Vendors

### Directory landing

- `https://www.sdweddingdirectory.com/vendors` — page (directory template)

### Per-category pages (CPT taxonomy — `vendor-category`)

**Bottom-of-page sections on every `/vendors/{category}/`:**

1. Carousel — 4 cards — title: *Featured Wedding {category} Vendors*
2. Heading + full-width body copy + 3-column body text
3. Wedding vendor inline link section (same as the 2nd-to-last section on `/vendors`)

**URLs:**

- `/vendors/wedding-photographers`
- `/vendors/wedding-djs`
- `/vendors/wedding-beauty-health`
- `/vendors/wedding-planners`
- `/vendors/wedding-caterers`
- `/vendors/wedding-florists`
- `/vendors/wedding-videographers`
- `/vendors/wedding-officiants`
- `/vendors/wedding-event-rentals`
- `/vendors/photo-booths`
- `/vendors/wedding-bands`
- `/vendors/wedding-dresses`
- `/vendors/wedding-cakes`
- `/vendors/wedding-limos`
- `/vendors/wedding-ceremony-music`
- `/vendors/lighting-decor`
- `/vendors/wedding-invitations`
- `/vendors/travel-agents`
- `/vendors/wedding-jewelers`
- `/vendors/wedding-favors`

### Single vendor pages

- `https://www.sdweddingdirectory.com/vendor/{vendor-name}`

---

## Wedding Inspiration (Blog)

### Blog index

- `https://www.sdweddingdirectory.com/wedding-inspiration` — page (blog index)

### Blog categories + subcategories

- `/wedding-inspiration/planning-basics/`
  - `/wedding-inspiration/planning-basics/honeymoon-advice`
  - `/wedding-inspiration/planning-basics/budget`
  - `/wedding-inspiration/planning-basics/legal-paperwork`
  - `/wedding-inspiration/planning-basics/trends-tips`
  - `/wedding-inspiration/planning-basics/etiquette`
  - `/wedding-inspiration/planning-basics/marriage-proposals`
  - `/wedding-inspiration/planning-basics/wedding-registry`
- `/wedding-inspiration/ceremony`
  - `/wedding-inspiration/ceremony/officiants`
  - `/wedding-inspiration/ceremony/vows-readings`
  - `/wedding-inspiration/ceremony/traditions`
- `/wedding-inspiration/reception/`
  - `/wedding-inspiration/reception/places-to-celebrate`
  - `/wedding-inspiration/reception/food-beverage`
  - `/wedding-inspiration/reception/cake-desserts`
  - `/wedding-inspiration/reception/favors`
  - `/wedding-inspiration/reception/speeches-traditions`
- `/wedding-inspiration/services`
  - `/wedding-inspiration/services/transportation`
  - `/wedding-inspiration/services/photography-video`
  - `/wedding-inspiration/services/invitations-stationery`
  - `/wedding-inspiration/services/wedding-flowers`
- `/wedding-inspiration/songs-music` — *Wedding Songs & Music*
- `/wedding-inspiration/decor` — *Wedding Decor*
- `/wedding-inspiration/fashion/`
  - `/wedding-inspiration/fashion/health-beauty` — *Hair & Beauty*
  - `/wedding-inspiration/fashion/hair` — *Bridal Hair*
  - `/wedding-inspiration/fashion/beauty-tips`
  - `/wedding-inspiration/fashion/grooming`
  - `/wedding-inspiration/fashion/wellness`
- `/wedding-inspiration/destination-weddings`
- `/wedding-inspiration/married-life`
- `/wedding-inspiration/events-parties`
- `/wedding-inspiration/family-friends`

### Single blog post

- `https://www.sdweddingdirectory.com/{single-post}` — blog post (slug at root — no `/blog/` prefix; WordPress default post permalink)

---

## Real Weddings

### Taxonomy landing

- `https://www.sdweddingdirectory.com/real-weddings` — taxonomy (venue type)

### CPT archives

- `https://www.sdweddingdirectory.com/real-wedding-category/`

### Venue-linked real weddings

- `https://www.sdweddingdirectory.com/real-wedding/{venue}` — shows all real weddings attached to a specific venue

### Single real wedding (couple-linked)

- `https://www.sdweddingdirectory.com/real-wedding/{couple}`

---

## Wedding Registry

- `https://www.sdweddingdirectory.com/wedding-registry`
- `https://www.sdweddingdirectory.com/wedding-registry/retail-registries`

---

## Wedding Websites

- `https://www.sdweddingdirectory.com/wedding-websites`
- `https://www.sdweddingdirectory.com/wedding-websites/templates`
- `https://www.sdweddingdirectory.com/wedding-website/{couple}` — per-couple website (single post)

---

## Cost pages

### Cost landing

- `https://www.sdweddingdirectory.com/cost`

### Cost children (17 vendor categories, San Diego geo-targeted)

- `/cost/wedding-catering-san-diego`
- `/cost/wedding-photographer-san-diego`
- `/cost/wedding-cake-san-diego`
- `/cost/wedding-flowers-san-diego`
- `/cost/wedding-planner-san-diego`
- `/cost/wedding-hair-and-makeup-san-diego`
- `/cost/wedding-dj-san-diego`
- `/cost/wedding-venue-san-diego`
- `/cost/wedding-videographer-san-diego`
- `/cost/wedding-band-san-diego`
- `/cost/wedding-dress-san-diego`
- `/cost/wedding-tuxedo-san-diego`
- `/cost/wedding-officiant-san-diego`
- `/cost/wedding-ceremony-music-san-diego`
- `/cost/wedding-photo-booths-san-diego`
- `/cost/wedding-rentals-san-diego`
- `/cost/wedding-bus-and-limo-san-diego`

---

## Tools

- `https://www.sdweddingdirectory.com/wedding-hashtag-generator`

---

## Company / static

- `https://www.sdweddingdirectory.com/about-us`
- `https://www.sdweddingdirectory.com/contact-us/`
- `https://www.sdweddingdirectory.com/find-venue/` — layout ported from WeddingDir themeforest

### 4-tab policy group

These 4 pages render together as a tabbed navigation group:

- `https://www.sdweddingdirectory.com/ca-privacy`
- `https://www.sdweddingdirectory.com/privacy-policy`
- `https://www.sdweddingdirectory.com/terms-of-use`
- `https://www.sdweddingdirectory.com/faq`

---

## Team

### Archive

- `https://www.sdweddingdirectory.com/team` — CPT archive

### Single

- `https://www.sdweddingdirectory.com/team/{team-member}`

---

## User dashboards

- `https://www.sdweddingdirectory.com/couples-dashboard`
- `https://www.sdweddingdirectory.com/venue-dashboard`
- `https://www.sdweddingdirectory.com/vendor-dashboard`

---

## Error pages

- `/error-404`
- `/listing-not-found`

---

## Utility / global (modal-only, no URL slug)

- Couple login
- Venue login
- Vendor login
- Forgot password

---

## Internal linking rules

Every archive links to related archives per this matrix:

| From | To |
|------|----|
| Blog | Vendors + Venues |
| Venues | Vendors |
| Cost | Vendors |
| Real Weddings | Vendors + Venues |

---

## Content rules

### Archive intro copy

**Every archive** (vendor category, venue location, venue type, blog category, cost landing, etc.) must have a short intro text — **300-word minimum**.

### H1 geo-targeting — all vendor pages use "San Diego" in the H1

**Examples:**

- `<h1>San Diego Wedding DJs</h1>`
- `<h1>San Diego Wedding Photographers</h1>`

### H1 pattern — combo venue pages

- `<h1>San Diego {Type} Weddings in {Location}</h1>`

---

*End of site outline.*
