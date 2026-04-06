# Route → Template Map — SDWeddingDirectory

Every URL pattern, the template WordPress selects, who renders the markup, and what components are used.

**Rendering key:**
- **Theme** = template outputs all markup directly
- **Hybrid** = theme provides wrapper, plugin renders content via action hooks
- **Plugin** = plugin controls nearly all markup, theme provides header/footer + CSS

**URL structure note:** The plugin currently registers some CPT archives under `/wedding-inspiration/` (e.g., `/wedding-inspiration/real-weddings/`). This is incorrect — only blog posts belong under `/wedding-inspiration/`. CPT URL roots will need to be corrected in the plugin's permalink registration. The URLs below reflect the **intended** structure.

---

## Homepage

| URL | Template | Rendering | Components |
|-----|----------|-----------|------------|
| `/` | `front-page.php` | **Theme** | `section-title`, `vendor-card`, `venue-card`, `real-wedding-card`, `post-card`, `inline-link-grid` |

### Sections rendered (in order)

1. Hero Search Banner — inline in `front-page.php`
2. San Diego Wedding Vendors — `section-title` + hard-coded category cards + button grid
3. Plan Your San Diego Wedding — `section-title` + planning tool cards
4. Real Weddings — `section-title` + 4× `real-wedding-card` + CTA
5. Inspiration — `section-title` + circle category links + 4× `post-card` + CTA
6. Find Your Location — `section-title` + city carousel
7. Search by Category — 2× `inline-link-grid` (venue types, vendor categories)
8. Value Columns — 4-column text block (inline)
9. Why SDWeddingDirectory — inline content
10. Browse Wedding Venues by City — `inline-link-grid` (city links)

### Data sources

- Real wedding cards: `WP_Query` for `real-wedding` post type
- Blog article cards: `WP_Query` for `post` type with featured categories
- City links: `get_terms('venue-location')`
- Vendor/venue category links: hard-coded arrays (same data used on venues landing)

---

## Venues

`/venues/` is a WordPress **page** (not a CPT archive). The plugin renders the landing page content. Venue singles use `/venues/{slug}/`. Location-based filtering is the primary taxonomy URL.

| URL | Template | Rendering | Components |
|-----|----------|-----------|------------|
| `/venues/` (landing page) | `page-venues.php` | **Plugin** via `do_action('sdweddingdirectory/find-venue')` | `venue-card`, `inline-link-grid` |
| `/venues/?search=...` (search results) | `page-venues.php` | **Plugin** | `venue-card`, `pagination` |
| `/venues/{city}/` | `taxonomy-venue-location.php` | **Theme** | `page-header`, `venue-card`, `pagination` |
| `/venues/{type-slug}/` | `taxonomy-venue-type.php` | **Theme** | `page-header`, `venue-card`, `pagination` |
| `/venue/{venue-name}/` | `single-venue.php` | **Plugin** via `do_action('sdweddingdirectory/venue/detail-page')` | `breadcrumbs`, `faq-accordion`, `why-use-sdwd` |

**Note:** Venues follows the same singular/plural split as vendors: `/venues/` (plural) for the landing page and taxonomy archives, `/venue/` (singular) for single profiles. Location (`/venues/{city}/`) is the primary taxonomy URL. Venue type (`/venues/{type-slug}/`) is secondary. If both can't share the `/venues/` prefix without conflict, location takes priority. The current live site uses `/venue-types/{slug}/` and `/locations/{city}/` — plugin rewrite rules will need updating.

### Venues landing sections (plugin-rendered)

1. Hero search (shorter than homepage)
2. Venues by area circular carousel
3–12. Alternating venue pop-out sections + 4-column venue card rows (×5 cities)
13. San Diego Wedding Venues text section
14. Button row for venue types
15. San Diego Wedding Venues city links — `inline-link-grid` (same data as homepage #10)
16. San Diego Wedding Vendors category links — `inline-link-grid` (same data as homepage #7b)

### Venue detail sections (plugin-rendered)

1. Breadcrumbs
2. Photo collage
3. Sticky profile nav (About, Pricing, Availability, FAQ, Reviews, Map)
4. About/description
5. Gallery
6. Video
7. Room facilities
8. Preferred venues
9. Menu
10. Services
11. Team
12. Pricing
13. Availability calendar
14. FAQ accordion
15. Reviews
16. Map
17. "Are You Interested?" CTA
18. Why Use SDWD (`get_template_part('template-parts/why-use-sdwd')`)
19. Footer links (venue types + city links with nearby/all toggle)

---

## Vendors

`/vendors/` is a WordPress **page** (not a CPT archive). Vendor category archives use the plural (`/vendors/{category}/`). Single vendor profiles use the singular (`/vendor/{slug}/`).

| URL | Template | Rendering | Components |
|-----|----------|-----------|------------|
| `/vendors/` (landing page) | `page-vendors.php` | **Theme** | `vendor-card`, `inline-link-grid` |
| `/vendors/{category}/` | `taxonomy-vendor-category.php` | **Theme** | `page-header`, `vendor-card`, `pagination` + filter sidebar |
| `/vendor/{vendor-name}/` | `single-vendor.php` | **Plugin** via `do_action('sdweddingdirectory/vendor/detail-page')` | `breadcrumbs`, `faq-accordion`, `why-use-sdwd` |

### Vendors landing sections

1. Hero search (shorter than homepage)
2. Vendor categories square image carousel
3–12. Alternating styled sections + vendor card rows (same layout pattern as venues)
13–16. Additional sections (section title + 3-column text row + link grids)

### Vendor category filter sidebar

Rendered by theme in `taxonomy-vendor-category.php`:
- Pricing filter
- Services multi-select
- Style filter
- Specialties filter

### Vendor detail sections (plugin-rendered)

1. Breadcrumbs
2. Photo collage
3. Sticky profile nav (About, Pricing, Availability, FAQ, Reviews)
4. About/description (profile image, owner name, quick facts, social links)
5. Gallery
6. Services
7. Pricing tiers
8. Availability calendar
9. FAQ accordion
10. Endorsements / professional network
11. Reviews + write-a-review form
12. Video
13. Contact sidebar (sticky right column)
14. "Are You Interested?" CTA
15. Why Use SDWD (`get_template_part('template-parts/why-use-sdwd')`)
16. Footer links (vendor category links — no city section)

---

## Real Weddings (deferred — keep current structure)

Details TBD. Current plugin registration places these under `/wedding-inspiration/real-weddings/`. Final URL structure will be decided when this phase is active.

| URL | Template | Rendering | Components |
|-----|----------|-----------|------------|
| Real wedding archive | `archive-real-wedding.php` | **Theme** | `page-header`, `real-wedding-card`, `pagination` |
| Single real wedding | `single-real-wedding.php` | **Plugin** via `do_action('sdweddingdirectory/real-wedding/detail-page')` | `real-wedding-card` |

### Real wedding taxonomy archives

8 taxonomy templates, all using: `page-header`, `real-wedding-card`, `pagination`. The `real-wedding-location` taxonomy has special parent/child logic (parent term shows child location cards, child term shows real wedding cards).

### Real wedding detail sections (plugin-rendered)

1. Page header (couple names, date, location, share/favorite, request pricing)
2. Our Story content card
3. Photo gallery (8 + expandable)
4. Couple info (2-column)
5. Vendor team grid
6. Outside vendor team grid
7. Similar real weddings (4× `real-wedding-card`)

---

## Blog / Inspiration

Blog posts live under `/wedding-inspiration/`. This prefix is for blog content ONLY — no CPTs.

| URL | Template | Rendering | Components |
|-----|----------|-----------|------------|
| `/wedding-inspiration/` | `page-inspiration.php` | **Theme** | `post-card`, `pagination` |
| `/wedding-inspiration/{category}/` | `category.php` | **Theme** | `post-card`, `pagination` |
| `/wedding-inspiration/{post-slug}/` | `single.php` | **Theme** | `breadcrumbs` |

### Blog single sections

1. Topbar: breadcrumbs + search form
2. Intro row: featured image (left) + category/title/date/excerpt (right)
3. Post content (JS splits between intro and body based on image height)

### Category archive special case

`category.php` detects planning subcategories and renders a 2-column layout:
- Left: post card grid (2 per row)
- Right: sticky sidebar with category browser widget + planning tool links

---

## Wedding Planning

| URL | Template | Rendering | Components |
|-----|----------|-----------|------------|
| `/wedding-planning/` | `page-wedding-planning.php` | **Theme** | `section-title`, `feature-block`, `tool-card-row`, `faq-section`, `breadcrumbs` |

### Planning hub sections (in order)

1. `planning-hero.php` — hero + 2-step registration form + random bg images
2. `planning-intro.php` — heading + subtext (uses `section-title`)
3. `planning-checklist.php` — uses `feature-block` (text left, image right)
4. `planning-vendor-organizer.php` — uses `feature-block` (reversed)
5. `planning-wedding-website.php` — uses `feature-block`
6. `planning-secondary-intro.php` — bridge text (uses `section-title`)
7. `planning-tool-cards.php` — uses `tool-card-row` (Budget, Seating Chart, Guest List)
8. `planning-detailed-copy.php` — long-form content
9. `planning-faq.php` — uses `faq-section` component

### Planning child pages

| URL | Template | Rendering |
|-----|----------|-----------|
| `/wedding-planning/{child-slug}/` | `page.php` → `planning-child-page.php` | **Theme** — full-width orchestrator |

`page.php` detects planning children via `wp_get_post_parent_id() === 4180` and routes to `planning-child-page.php`. This orchestrator contains per-slug data arrays for all 6 child pages (wedding-checklist, wedding-seating-chart, vendor-manager, wedding-guest-list, wedding-budget, wedding-website). Sections: scroll-triggered CTA bar → planning hero → section title → 3 SVG icon cards → 3 feature blocks (alternating) → section title → 5 cross-link tool cards → detailed copy → FAQ.

---

## Cost Pages (deferred)

| URL | Template | Rendering | Components |
|-----|----------|-----------|------------|
| `/cost/` | `page-cost.php` (planned) | **Theme** | Hero search, icon cards, expandable category grid, 3-card rows |
| `/cost/{category-slug}/` | `page-cost-child.php` (planned) | **Theme** | Breadcrumbs, sidebar, vendor cards, content |

17 child pages exist (wedding-dj, wedding-photographer, wedding-venue, etc.). These are WordPress pages, not CPTs. Layout details documented in README.md.

---

## Static Pages

| URL | Template | Rendering | Components |
|-----|----------|-----------|------------|
| `/about/` | `page-about.php` | **Theme** | `section-title` |
| `/our-team/` | `page-our-team.php` | **Theme** | `section-title` |
| `/contact/` | `page-contact.php` | **Theme** | `contact-details` |
| `/faqs/` | `page-faqs.php` | **Theme** | `policy-subnav`, `faq-accordion`, `contact-details` |
| `/privacy-policy/` | `page-policy.php` | **Theme** | `policy-subnav` |
| `/ca-privacy/` | `page-policy.php` | **Theme** | `policy-subnav` |
| `/terms-of-use/` | `page-policy.php` | **Theme** | `policy-subnav` |
| `/style-guide/` | `page-style-guide.php` | **Theme** | All component examples |
| Any page without named template | `page.php` | **Theme** | — |

---

## Utility Pages

| URL | Template | Rendering | Components |
|-----|----------|-----------|------------|
| Any invalid URL | `404.php` | **Theme** | — |
| `/?s={query}` | `search.php` | **Theme** | `post-card`, `pagination` |

---

## Dashboards

| URL | Template | Rendering | Components |
|-----|----------|-----------|------------|
| `/vendor-dashboard/` | `user-template/vendor-dashboard.php` | **Plugin** via `do_action('sdweddingdirectory/dashboard')` | — |
| `/couple-dashboard/` | `user-template/couple-dashboard.php` | **Plugin** via `do_action('sdweddingdirectory/dashboard')` | — |
| `/couple-dashboard/?dashboard=<tool>` | same | **Plugin** | — |

Theme provides: `get_header()` + `get_footer()` + `dashboard.css` overrides.

Plugin renders: dashboard header, role-specific sidebar nav, content panels per tab.

### Vendor/Venue dashboard tabs (plugin-rendered)

- My Profile
- My Venue / My Vendor listing
- Reviews
- Pricing Plans
- Invoice
- Logout

### Couple dashboard tabs (plugin-rendered)

- Budget Calculator (`?dashboard=budget-calculator`)
- Guest List (`?dashboard=guest-management`)
- Seating Chart (`?dashboard=seating-chart`)
- Checklist (`?dashboard=checklist`)
- Wedding Website Builder (`?dashboard=wedding-website`)
- Vendor Manager (`?dashboard=vendor-manager`)

---

## Wedding Website

| URL | Template | Rendering | Status |
|-----|----------|-----------|--------|
| Website archive | `archive-website.php` | **Theme** | Works (uses `sdweddingdirectory/couple/post` filter) |
| Single website | `single-website.php` | **Plugin** (hook: `sdweddingdirectory/website/detail-page`) | **No plugin handler exists** — placeholder |
| Website builder | `user-template/wedding-website.php` | — | **Placeholder — not implemented** |

---

## Couple

| URL | Template | Rendering | Components |
|-----|----------|-----------|------------|
| Couple archive | `archive-couple.php` | **Theme** | Card grid, `pagination` |
| Single couple | `single-couple.php` | **Plugin** via `do_action('sdweddingdirectory/couple/detail-page')` | — |

---

## Single Team / Changelog

| URL | Template | Rendering | Components |
|-----|----------|-----------|------------|
| `/team/{member-name}/` | `single-team.php` | **Theme** | — |
| `/changelog/{entry}/` | `single-changelog.php` | **Hybrid** | — |

---

## Known permalink issues requiring plugin fixes

1. **CPTs under `/wedding-inspiration/`** — The plugin registers CPT archives (real-wedding, couple, website, team, testimonial, venue-request, venue-review) with the blog prefix. Only blog posts should use this prefix. CPTs need their own URL roots.
2. **Taxonomy base URLs** — `vendor-category`, `venue-type`, and `venue-location` taxonomies currently resolve under `/wedding-inspiration/`. They need to resolve under their respective CPT URL roots (`/vendors/{category}/`, `/venues/{city}/`, `/venues/{type}/`).
3. **Hardcoded URL mismatches** — The old theme hardcodes `/vendors/<term>/`, `/venue-types/<term>/`, and `/inspiration/` in template markup, but the actual WordPress permalinks resolve differently. The theme must use WordPress functions (`get_term_link()`, `get_permalink()`, etc.) instead of hardcoded URL patterns.
