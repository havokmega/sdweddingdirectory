# PROJECT.md — SD Wedding Directory

Last updated: 2026-04-01

Single source of truth for tasks. Nothing else tracks tasks — not README.md, not architecture.md, not CLAUDE.md. If it's not in this file, it's not on the list.

For legacy (v1) historical work, see `legacy-sdweddingdirectory/theme/project-status.md` outside the WordPress install.

---

## 1. Front-End UI

Complete the visual design for every public-facing page before moving to backend work. Pages marked "done on legacy" exist in the old theme as functional reference but need full rebuilds.

### Global Elements

| Item | Status | Notes |
|------|--------|-------|
| Navigation bar | 95% | Need user-o icon for "Join as Couple" — icomoon SVG export failed, no icon showing |
| Footer | Not started | |
| Error 404 page | Not started | Was correct on legacy theme — use v1 screenshots as reference |

### Pages

| Page | Status | Notes |
|------|--------|-------|
| Home (`/`) | Done | LOCKED — do not modify without founder approval |
| Wedding Planning (`/wedding-planning`) | Done | Sign-up form, feature blocks, FAQ, breadcrumbs all styled |
| Planning Child Pages (`/wedding-planning/*`) | 90% | Intro width fixed; need final QA pass |
| Venues Landing (`/venues`) | 80% | Hero responsive, grey outline fix, city links, lorem removal still needed |
| Venue Search/Results | Done on legacy | Needs rebuild; had different search area on legacy |
| Venue Location Archive | Not started | |
| Venue Type Archive | Not started | |
| Venue Business Profile | 90% on legacy | Needs rebuild |
| Venue/Vendor Modal | Done on legacy | Needs rebuild — popout/modal for vendor and venue cards |
| Couple Modal | Done on legacy | Needs rebuild |
| Vendors Landing (`/vendors`) | ~1% | Done on legacy; completely broken currently — needs full rebuild |
| Vendor Category Page | Done on legacy | Needs rebuild; had per-category filters on legacy |
| Vendor Profile Page | 90% on legacy | Needs rebuild |
| Inspiration (`/wedding-inspiration`) | Done on legacy | Needs rebuild |
| Inspiration Archives | Done on legacy | Needs rebuild |
| Inspiration Single Posts | Done on legacy | Needs rebuild |
| Inspiration Category Archive | Done on legacy | Needs rebuild |
| Blog Archives | Done on legacy | Needs rebuild |
| Blog Category Archives | Done on legacy | Needs rebuild |
| Blog Posts (import) | Done on legacy | Content exists in DB |
| Real Weddings | Not started | |
| Wedding Website | Not started | Includes 6 theme options (see Section 5) |
| Cost Parent Page | Not started | |
| Cost Child Pages | Not started | Needs original SD-specific pricing content |
| Registry Page | Not started | |
| FAQs / Privacy / CA Privacy / Terms of Use | Done on legacy | Was a 4-tab subsection layout — needs rebuild with same tabbed UI |
| About | Done on legacy | Needs rebuild |
| Contact | Done on legacy | Needs rebuild |

### Dashboards

| Dashboard | Status | Notes |
|-----------|--------|-------|
| Vendor Dashboard | 80% on legacy | Plugin-driven UI; needs theme wrapper + CSS + QA |
| Venue Dashboard | 80% on legacy | Plugin-driven UI; needs theme wrapper + CSS + QA |
| Couple Dashboard | 80% on legacy | Plugin-driven UI; needs theme wrapper + CSS + QA |

### Modals

| Modal | Status | Notes |
|-------|--------|-------|
| Vendor/Venue Registration | Done on legacy | Needs rebuild |
| Couple Registration | Done on legacy | Needs rebuild |

---

## 2. Build Phases

Phases from the original rebuild plan. Each phase produces a working site. Complete one fully before starting the next.

### Phase 1 — Responsive Polish (Homepage + Planning)

Pages are built but need responsive tuning.

- [ ] Super-wide viewports (>2230px): hero bg should scale up so diagonal stays visible
- [ ] 75% zoom parity: verify hero height renders identically
- [ ] Nav breakpoint transition (~1000px): shift diagonal when nav collapses
- [ ] Tablet search bar: stretch wider near full-width below diagonal breakpoint
- [ ] Below ~750px: drop search button, show only input fields
- [ ] Apply hero responsive fixes to both homepage and planning hero
- [ ] Fix horizontal scrollbar under vendor category button row
- [ ] Vendor cards: 4 cols -> 3 below 1170px -> 1 below 750px
- [ ] Category buttons: no wrapping, hide overflow + "Show All" at narrow widths
- [ ] Real Weddings below ~750px: 2 cards, CTA near full-width
- [ ] Inspiration circles: horizontal scroll, no vertical stacking
- [ ] Location carousel: single row with arrows, no additional rows
- [ ] Verify header hide/show on all pages
- [ ] Coordinate scroll-triggered CTA bar with header hide on planning child pages

### Phase 2 — Venues Pages

- [ ] Venues landing (`page-venues.php`): rebuild as orchestrator
- [ ] Venue archive (`archive-venue.php`): grid + filtering + pagination
- [ ] Venue taxonomy pages (`taxonomy-venue-type.php`, `taxonomy-venue-location.php`)
- [ ] Single venue (`single-venue.php`): profile page wrapper + CSS
- [ ] Conditional CSS: `pages/venues.css`

### Phase 3 — Vendors Pages

- [ ] Vendors landing (`page-vendors.php`): category carousel + styled sections
- [ ] Vendor taxonomy (`taxonomy-vendor-category.php`): advanced filter sidebar with per-category filters
- [ ] Single vendor (`single-vendor.php`): profile page wrapper + CSS
- [ ] Conditional CSS: `pages/vendors.css`

### Phase 4 — Real Weddings

- [ ] Real weddings archive (`archive-real-wedding.php`)
- [ ] Real wedding taxonomy templates (8 taxonomies, same pattern)
- [ ] Single real wedding (`single-real-wedding.php`): gallery + story + vendor credits

### Phase 5 — Inspiration / Blog

- [ ] Inspiration landing (`page-inspiration.php`)
- [ ] Blog single (`single.php`): article layout + JS content splitter
- [ ] Category archive (`category.php`): planning subcategory sidebar handling
- [ ] Blog archive (`archive.php`)

### Phase 6 — Static Pages + Cost Pages

- [ ] About (`page-about.php`)
- [ ] Our Team (`page-our-team.php`)
- [ ] Contact (`page-contact.php`)
- [ ] FAQs / Privacy / CA Privacy / Terms of Use: 4-tab subsection layout (replicate legacy tabbed UI)
- [ ] 404 (`404.php`)
- [ ] Search results (`search.php`)
- [ ] Cost parent page (`/cost/`)
- [ ] Cost child pages (17 vendor categories)

### Phase 7 — Dashboard Integration

- [ ] `user-template/vendor-dashboard.php` — thin wrapper
- [ ] `user-template/couple-dashboard.php` — thin wrapper
- [ ] `dashboard.css` — CSS overrides for plugin dashboard markup
- [ ] Test every dashboard tab for vendor, venue, and couple roles

### Phase 8 — Permalink Cleanup (Plugin)

- [ ] Move CPT archives out from under `/wedding-inspiration/`
- [ ] Register vendor-category base as `/vendors/`
- [ ] Register venue-location base as `/venues/`
- [ ] Set up 301 redirects from old permalink paths
- [ ] Verify no hardcoded URL patterns in theme

### Phase 9 — Final QA & Launch Prep

- [x] ~~Delete old theme directory, rename to `sdweddingdirectory`~~ Done 2026-04-01
- [ ] Cross-browser testing (Chrome, Safari, Firefox, mobile)
- [ ] Lighthouse audit: performance, accessibility, SEO
- [ ] Verify conditional CSS/JS enqueuing
- [ ] Verify `loading="lazy"` and theme path helpers on all images
- [ ] Verify no hardcoded URLs, inline styles, or raw hex colors
- [ ] Update all Documentation files to reflect final state

---

## 3. Plugin Refactoring

The legacy plugins (`sdweddingdirectory` core + `sdweddingdirectory-couple`) were built for the legacy theme. ~35% of their code is HTML rendering that belongs in the theme, not the plugin. They don't need a ground-up rewrite, but they need surgery.

### What to keep (data/logic layer — works fine)

- All 7 post type registrations (vendor, venue, couple, real-wedding, claim-venue, team, testimonials)
- All taxonomy definitions and hierarchy
- 31 AJAX handlers (data operations)
- 319 meta field operations (`get_post_meta`/`update_post_meta`)
- Role/capability system
- 9 couple tool modules (budget, guest list, seating chart, todo, website, wishlist, RSVP, reviews, quote requests)
- Form field configuration system
- Admin settings

### What to strip out (rendering that belongs in theme)

- All HTML output from plugin files (echo/printf of markup in 40+ files)
- Bootstrap grid classes (`col-md-*`, `btn`, `alert`, etc.)
- Font Awesome icon classes (`fa fa-*`)
- jQuery calls (57+ references across dashboard files)
- Plugin-owned CSS files (20+ files)
- Bundled JS libraries: fullcalendar v4, bootstrap-datepicker, summernote, select2, toastr, Magnific Popup, Isotope, ApexCharts

### What to rebuild in theme

- Dashboard UI as theme templates using `get_template_part()`
- Vendor/venue/real wedding profile page rendering
- All front-end forms and modals
- Replace legacy JS libraries with lightweight alternatives or native browser APIs

### Vendor/venue architecture fix

Legacy problem: vendor and venue are completely separate codebases despite being structurally similar. Vendor was the original, then "listing" code was modified to become venues, creating inconsistent admin behavior and forked code.

Goal: unified base architecture — one shared system, differentiated by taxonomy, not by forked code.

### Tasks

- [ ] Audit legacy plugins to catalog what backend functionality to carry forward
- [ ] Design unified vendor/venue post type architecture
- [ ] Determine scope for custom plugins (dashboards, Google Maps, quote requests, reviews)
- [ ] Strip HTML rendering from plugins, move to theme templates
- [ ] Remove Bootstrap/FA/jQuery/legacy library dependencies
- [ ] Build clean plugins using legacy as functional reference

---

## 4. Wedding Website Themes

Currently 1 template ("Website Template One") in the `weddingdir-couple-website` plugin module. Expand to 6.

- [ ] Finalize theme concepts with founder (modern minimal, romantic classic, rustic, bold/colorful, elegant dark, garden/floral)
- [ ] Build 5 additional templates sharing the same data model (couple names, photos, RSVP, events, gallery, story)
- [ ] Update template selector UI with previews in couple dashboard
- [ ] Add theme thumbnail previews

---

## 5. Email System

- [ ] Activate and configure `wp-mail-smtp` plugin
- [ ] Set up email templates for transactional emails (quote requests, registration confirmations, claim notifications)

---

## 6. Third-Party Plugin Setup

- [ ] SEO by Rank Math
- [ ] ShortPixel Image Optimizer
- [ ] UpdraftPlus (backups)
- [ ] W3 Total Cache
- [ ] Wordfence (staging/live only)
- [ ] WP Mail SMTP

---

## 7. SEO & Launch

- [ ] Crawl and fix 404s, redirect chains, missing H1s, duplicate titles, thin pages
- [ ] Finalize canonicals, robots, sitemap, schema, metadata
- [ ] Add unique local content, FAQs, and internal linking on money pages
- [ ] Optimize images and trim render-blocking assets
- [ ] Search Console, Bing, analytics, mail-deliverability checks
- [ ] Pre-launch and post-launch checklists

---

## 8. Media Upload Routing

WordPress defaults to `uploads/YYYY/MM/` for all media. We want uploads routed by content type instead.

Directory structure created:
- `wp-content/uploads/vendors/` — vendor profile images, gallery photos
- `wp-content/uploads/venues/` — venue profile images, gallery photos
- `wp-content/uploads/couples/` — couple profile photos, wedding photos
- `wp-content/uploads/blog/` — blog post images

### Tasks

- [ ] Hook into `wp_handle_upload_prefilter` or `upload_dir` filter to route uploads by post type
- [ ] Create vendor/venue subdirectories by business name (e.g., `vendors/elegant-affairs/photo.jpg`)
- [ ] Handle edge cases: what happens on post type change, orphaned files, admin-uploaded media
- [ ] Test upload routing for each user role (vendor, venue, couple, admin)

---

## 9. Founder Questions to Resolve

- [ ] How real weddings work (content flow, who submits, what gets displayed)
- [ ] Purpose of these wp-admin sections: Request Quote, Website, Review vs Testimonial, Team
- [ ] Whether couple/vendor/venue accounts also show as Users in wp-admin
