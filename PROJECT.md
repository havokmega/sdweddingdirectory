# PROJECT.md — San Diego Wedding Directory

Last updated: 2026-04-06

Single source of truth for tasks. Nothing else tracks tasks — not README.md, not architecture.md, not CLAUDE.md. If it's not in this file, it's not on the list.

For v1 historical work, see `legacy-sdweddingdirectory/project-status.md` outside the WordPress install.

Update: 2026-04-22

This wasn't meant to be the task list for everything created by GSD, so much of this is outdated. I will update everything here. First, we need to stop calling the v2 and that v1. All work was lost from a prior build and seemingly unrecoverable. But we have several partial attempts at the site and everything that has ever been done lives somewhere in /WebDevelopment. So whatever you have been calling v1, should just be called legacy code, and that could be anywhere.  What you've been calling v2...that's v1 moving forward. It's not even v1...before it was sdweddingdirectory for the theme name, now I spelled it out sandiegoweddingdirectory as the theme name to start this all over again. So this isn't v2, v1, v anything...this is THE theme. This is the working project. There is no other project. Legacy code exists and should be scoured for reference code in certain places, becuase so much of this work is done. The seating chart plug in was built by us from scratch, that was never part of any legacy code, we made that, and if we can find that it would save a lot of time. 

The CSS used in the vendor dashboard and couples dashboards looked good. I understand we need to refactor some things to get the dashboards working right based on my strict requirements. But the CSS is just the look and the feel. That shipped with the original commercially bought theme. We should be able to grab the css from legacy files without having to rebuild everything.  We were done with wedding inspiration (blog index) and we did the blog category pages. The vendor profile single page, the business profile that everyone sees for that company, was done, we built a custom calendar, I really like the way it looked, it took us forever, and we are very far away from that in the current working project. Nothing has really been done to try and recover that, Ive just been encouraged to redo the work and I dont understand why. 

Below is an updated list. Make notes, we don't even need to know where we've been. You are just picking this up now, so delete the task from everywhere if I say it's done here because otherwise it is just adding noise. 

Also, it should be noted that every element here is 100% copied block for block in the same style used by weddingwire.com/ We could easily look at any page UI, grab the CSS and apply it here. Once I have achieved the same look, I will be modifying from there to make it my own. I just want to use their UI as my starting point so I know I'm got a decent flow through the site.

---

## 1. Front-End UI

Complete the visual design for every public-facing page before moving to backend work. Pages marked "done on v1" exist in the old theme as functional reference but need full v2 rebuilds.

### Global Elements

| Item | Status | Notes |
|------|--------|-------|
| Navigation bar | DONE | Need user-o icon for "Join as Couple" — icomoon SVG export failed, no icon showing |
| Footer | DONE | |
| Error 404 page | 95% | |

### Pages

| Page | Status | Notes |
|------|--------|-------|
| Home (`/`) | Search doesn't work. Searching by category is supposed to drop down a mega menu. We had this working in some prior version. | unlocked, we need to fix that and we are done. |
| Wedding Planning (`/wedding-planning`) | Not an AI task. Content smokehouse got mixed up with child page content. Founder needs to add new copy to the page that makes sense. | Sign-up form, feature blocks, FAQ, breadcrumbs all styled |
| Planning Child Pages (`/wedding-planning/*`) | PENDING | NEEDS IMAGES THAT WILL COME FROM SCREENSHOTS FROM COUPLES DASHBOARD |
| Venues Landing (`/venues`) | DONE | Hero responsive, grey outline fix, city links, lorem removal still needed |
| Venue Search/Results | Was complete in previous version | Search button needs to return results |
| Vendor Search/Results | Was complete in previous version, location should not be in this hero search. The home page has a radio option for venues and vendors. This element needs to exist above the search bar on both /vendors and /venues. On /venues, venues is the default radio selection. On /vendors, vendors is the default selection. Otherwise it's the exact same search and hero etc. Just with a different H1 | Search button needs to return results |
| Venue Location Archive | DONE | |
| Venue Type Archive | DONE | Registered and routing; architectural `has_archive => false` note handled by Phase 2 P2-PARITY-01 |
| Venue Business Profile | Started...very long way to go | was 100% complete in a legacy version somewhere in /WebDevelopment |
| Vendors Landing (`/vendors`) | Done | okay to delete this task |
| Vendor Category Page | Done | okay to delete this task |
| Vendor Profile Page | same situation as venue profile page | perfect code (from a UI standpoint) exists in som legacy version  |
| Inspiration (`/wedding-inspiration`) | not started | complete and perfect on some older version |
| Inspiration Archives | mot started | done on an older version |
| Inspiration Single Posts | Not started | I bought the commercial theme, here is a page that shows the single post layout https://weddingdir.net/what-does-a-wedding-planner-actually-do/ we can literally use that php which we have in a weddingdir folder and just switch out the current themes font |
| Inspiration Category Archive | not started | was previously done |
| Blog Posts (import) | I'm seeing the posts exist in the wp-admin section | just need the wedding-inspiration page to be set up |
| Real Weddings | Not started | Again , great example of exactly what it should look like here https://weddingdir.net/real-wedding/ratna-jacob/ and it came with the WeddingDir theme. No work to do except for eliminating 3rd party tools |
| Wedding Website | Not started | here is the themes single template it comes with https://weddingdir.net/website/hitesh-and-priyanka/ so thats done too. Let's focus on getting that one thing wired correctly with no third party tools THEN we can expand to. Include a total of 6 theme options. But lets get what we already have working first. (see Section 5) |
| Cost Parent Page | Not started | I have this wireframe, we will get to it when we get to |
| Cost Child Pages | Not started | I have this wireframe, we will get to it when we get to, I ve also collected pricing for each category, and in assets/images/pages I have a image titled cost-image-blank.png that we can use for a background for each of the child pages |
| Registry Page | Not started | wireframes, again, this is a 1 to 1 with wedding wire |
| FAQs Page | Not started |  this was done in a legacy version |
| About | Not started |  this was done in a legacy version |
| Contact | Not started |  this was done in a legacy version |
| CA Privacy | Not started |  this was done in a legacy version |
| Privacy Policy | Not started |  this was done in a legacy version |
| Terms of Use | Not started |  this was done in a legacy version |

### Dashboards

| Dashboard | Status | Notes |
|-----------|--------|-------|
| Vendor Dashboard | 80% on v1 | Plugin-driven UI; needs v2 theme wrapper + CSS + QA |
| Venue Dashboard | 80% on v1 | Plugin-driven UI; needs v2 theme wrapper + CSS + QA |
| Couple Dashboard | 80% on v1 | Plugin-driven UI; needs v2 theme wrapper + CSS + QA |

- Again, let's remove anything in notes that refers to this working directory as v2. This is the only theme. 

### Modals

| Modal | Status | Notes |
|-------|--------|-------|
| Vendor/Venue Registration | Done | tested, using username and password vendor/vendor and venue/venue until dashboard testing is complete |
| Couple Registration | Done | tested, using username and password couple/coule until dashboard testing is complete |

---

## 2. Build Phases

Phases from the original rebuild plan. Each phase produces a working site. Complete one fully before starting the next.

### Phase 1 — Responsive Polish (Homepage + Planning)

Pages are built but need responsive tuning.

- [X] Super-wide viewports (>2230px): hero bg should scale up so diagonal stays visible
- [X] 75% zoom parity: verify hero height renders identically
- [X] Nav breakpoint transition (~1000px): shift diagonal when nav collapses
- [X] Tablet search bar: stretch wider near full-width below diagonal breakpoint
- [X] Below ~750px: drop search button, show only input fields
- [X] Apply hero responsive fixes to both homepage and planning hero
- [X] Fix horizontal scrollbar under vendor category button row
- [X] Vendor cards: 4 cols -> 3 below 1170px -> 1 below 750px
- [X] Category buttons: no wrapping, hide overflow + "Show All" at narrow widths
- [X] Real Weddings below ~750px: 2 cards, CTA near full-width
- [X] Inspiration circles: horizontal scroll, no vertical stacking
- [X] Location carousel: single row with arrows, no additional rows
- [X] Verify header hide/show on all pages
- [X] Coordinate scroll-triggered CTA bar with header hide on planning child pages

### Phase 2 — Venues Pages

- [X] Venues landing (`page-venues.php`): rebuild as v2 orchestrator - at least I think this is done
- [ ] Venue archive (`archive-venue.php`): grid + filtering + pagination
- [X] Venue taxonomy pages (`taxonomy-venue-type.php`, `taxonomy-venue-location.php`) pretty sure this got done when setting up the sdwd new plug ins. It was the last thing we finished
- [ ] Single venue (`single-venue.php`): profile page wrapper + CSS (CSS to follow wedding wire single profile, was already don win legacy)
- [ ] Conditional CSS: `pages/venues.css` - I don't even know what this is supposed to mean, so its either done or not on my radar anymore.

### Phase 3 — Vendors Pages

- [X] Vendors landing (`page-vendors.php`): category carousel + styled sections
- [X] Vendor taxonomy (`taxonomy-vendor-category.php`): advanced filter sidebar
- [ ] Single vendor (`single-vendor.php`): profile page wrapper + CSS - CSS looks like shit. We already finished this in a legacy
- [ ] Conditional CSS: `pages/vendors.css` - again, no fucking clue what this means

### Phase 4 — Real Weddings (not started, came with WeddingDir but likely contains shortcake, ACF, elementor and other bullshit. Need to extract to straight PHP and CSS

- [ ] Real weddings archive (`archive-real-wedding.php`)
- [ ] Real wedding taxonomy templates (8 taxonomies, same pattern)
- [ ] Single real wedding (`single-real-wedding.php`): gallery + story + vendor credits

### Phase 5 — Inspiration / Blog (was done, now, not done, starting from scratch. Have wireframes, and perfectly functional CSS somewhere in the webdevelopment folder

- [ ] Inspiration landing (`page-inspiration.php`)
- [ ] Blog single (`single.php`): article layout + JS content splitter
- [ ] Category archive (`category.php`): planning subcategory sidebar handling
- [ ] Blog archive (`archive.php`)

### Phase 6 — Static Pages + Cost Pages - not started, too far down the road to thing about right now. Wireframes exist. Based on 11 copy of weddingwire.com

All these were done and used a 4 tabbed sub nav bar for these in a legacy version that also mimics wedding wire
- [ ] About (`page-about.php`)
- [ ] Our Team (`page-our-team.php`)
- [ ] Contact (`page-contact.php`)
- [ ] FAQs (`page-faqs.php`): tabbed accordion
- [ ] Policy pages (`page-policy.php`): privacy, terms, CA privacy
- [ ] 404 (`404.php`)
- [ ] Search results (`search.php`)

Not started
- [ ] Cost parent page (`/cost/`)
- [ ] Cost child pages (17 vendor categories)

### Phase 7 — Dashboard Integration

- [ ] Vendor dashboard page — the page vendors see when logged in; clean template that calls the existing dashboard plugin code so vendors can edit their profile, see quote requests, and manage their listing
- [ ] Couple dashboard page — the page couples see when logged in; clean template that calls the existing couple-side plugin code (checklist, budget, wishlist, reviews, quote history)
- [ ] Dashboard CSS — CSS to style the dashboard interface visually (ported from the commercial theme the dashboards originally shipped with + stripped of third-party framework markup)
- [ ] Test every dashboard tab for vendor, venue, and couple roles (login, edit, save, confirm the saved values stick)

### Phase 8 — URL Cleanup (Permalinks)

Plain-language: make vendor URLs live at `/vendor/name` instead of `/wedding-inspiration/name`, and set up redirects so old URLs still work and SEO isn't lost. Same principle for venues and category pages.

- [ ] Move vendor/venue archive URLs out from under `/wedding-inspiration/` so vendors live at `/vendors/...` and venues live at `/venues/...`
- [ ] Set vendor-category URLs to live under `/vendors/` (e.g. `/vendors/photographers/`)
- [ ] Set venue-location URLs to live under `/venues/` (e.g. `/venues/carlsbad/`)
- [ ] Set up 301 redirects from the old `/wedding-inspiration/...` URLs to the new ones so search-engine rankings transfer and no visitor gets a 404
- [ ] Verify the theme doesn't hard-code any of the old URL patterns (if the theme contains a link like `<a href="/wedding-inspiration/vendor/name">`, the new URL won't load)

### Phase 9 — Final QA & Launch Prep

- [ ] Cross-browser testing (Chrome, Safari, Firefox, mobile)
- [ ] Lighthouse audit: performance, accessibility, SEO
- [ ] Verify conditional CSS/JS enqueuing
- [ ] Verify `loading="lazy"` and theme path helpers on all images
- [ ] Verify no hardcoded URLs, inline styles, or raw hex colors
- [ ] Update all Documentation files to reflect final state

---

## 3. Plugin Refactoring

The v1 plugins (`sdweddingdirectory` core + `sdweddingdirectory-couple`) were built for the v1 theme. ~35% of their code is HTML rendering that belongs in the theme, not the plugin. They don't need a ground-up rewrite, but they need surgery.

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

### What to rebuild in v2 theme

- Dashboard UI as theme templates using `get_template_part()`
- Vendor/venue/real wedding profile page rendering
- All front-end forms and modals
- Replace legacy JS libraries with lightweight alternatives or native browser APIs

### Vendor/venue architecture fix

v1 problem: vendor and venue are completely separate codebases despite being structurally similar. Vendor was the original, then "listing" code was modified to become venues, creating inconsistent admin behavior and forked code.

v2 goal: unified base architecture — one shared system, differentiated by taxonomy, not by forked code.

### Tasks

- [ ] Audit v1 plugins to catalog what backend functionality to carry forward
- [ ] Design unified vendor/venue post type architecture
- [ ] Determine scope for v2 custom plugins (dashboards, Google Maps, quote requests, reviews)
- [ ] Strip HTML rendering from plugins, move to theme templates
- [ ] Remove Bootstrap/FA/jQuery/legacy library dependencies
- [ ] Build clean v2 plugins using v1 as functional reference

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
