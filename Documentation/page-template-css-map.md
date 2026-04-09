# Page Template → CSS Map

Scan scope:
- Active theme: `wp-content/themes/sandiegoweddingdirectory/`
- Page templates scanned: `45`
- Template parts scanned: `36`
- Additional shared theme templates scanned: `header.php`, `footer.php`, `searchform.php`, `sidebar.php`
- CSS files scanned: `18`
- Enqueue source scanned: `wp-content/themes/sandiegoweddingdirectory/functions.php`

Theme facts used throughout this document:
- `functions.php` enqueues `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, and `assets/css/layout.css` on every front-end request.
- `footer.php` loads five modal template parts when `! is_user_logged_in()`: `template-parts/modals/couple-login.php`, `template-parts/modals/couple-registration.php`, `template-parts/modals/vendor-login.php`, `template-parts/modals/vendor-registration.php`, and `template-parts/modals/forgot-password.php`.
- `page-inspiration.php` does not call `get_header()`, `wp_head()`, `get_footer()`, or `wp_footer()`.

## Task 1 — Page Inventory

| Page Type | File | Route (if determinable) |
| --- | --- | --- |
| 404 template | `404.php` | 404 route |
| Post type archive: `couple` | `archive-couple.php` | `couple` archive; exact public path not defined in the theme |
| Post type archive: `real-wedding` | `archive-real-wedding.php` | `/real-weddings/` |
| Post type archive: `vendor` | `archive-vendor.php` | `/vendors/` |
| Post type archive: `venue` | `archive-venue.php` | `/venues/` |
| Post type archive: `website` | `archive-website.php` | `website` archive; exact public path not defined in the theme |
| Generic archive template | `archive.php` | Generic archive; exact public path not defined in the theme |
| Category archive template | `category.php` | `/wedding-inspiration/{category-slug}/` |
| Front page | `front-page.php` | `/` |
| Index fallback template | `index.php` | Index fallback; exact public path not defined in the theme |
| Page template: About | `page-about.php` | `/about/` |
| Page template: Contact | `page-contact.php` | `/contact/` |
| Page template: FAQs | `page-faqs.php` | `/faqs/` |
| Page template: Inspiration | `page-inspiration.php` | `/wedding-inspiration/` |
| Page template: Our Team | `page-our-team.php` | Custom page template; exact public path not defined in the theme |
| Page template: Policy | `page-policy.php` | `/policy/` |
| Page template: Style Guide | `page-style-guide.php` | Custom page template; exact public path not defined in the theme |
| Page template: Vendors | `page-vendors.php` | `/vendors/` |
| Page template: Venues | `page-venues.php` | `/venues/` |
| Page template: Wedding Planning | `page-wedding-planning.php` | `/wedding-planning/` |
| Default page template | `page.php` | Generic page permalink; planning child pages use `/wedding-planning/{child-slug}/` |
| Search results template | `search.php` | `/?s=...` |
| Single post type: `changelog` | `single-changelog.php` | Single `changelog` permalink; exact public path not defined in the theme |
| Single post type: `couple` | `single-couple.php` | Single `couple` permalink; exact public path not defined in the theme |
| Single post type: `real-wedding` | `single-real-wedding.php` | Single `real-wedding` permalink; exact public path not defined in the theme |
| Single post type: `team` | `single-team.php` | Single `team` permalink; exact public path not defined in the theme |
| Single post type: `vendor` | `single-vendor.php` | Single `vendor` permalink; exact public path not defined in the theme |
| Single post type: `venue` | `single-venue.php` | Single `venue` permalink; exact public path not defined in the theme |
| Single post type: `website` | `single-website.php` | Single `website` permalink; exact public path not defined in the theme |
| Single post template | `single.php` | Single `post` permalink; breadcrumb parent path: `/wedding-inspiration/` |
| Taxonomy archive: `real-wedding-category` | `taxonomy-real-wedding-category.php` | `real-wedding-category` term archive; exact public path not defined in the theme |
| Taxonomy archive: `real-wedding-color` | `taxonomy-real-wedding-color.php` | `real-wedding-color` term archive; exact public path not defined in the theme |
| Taxonomy archive: `real-wedding-community` | `taxonomy-real-wedding-community.php` | `real-wedding-community` term archive; exact public path not defined in the theme |
| Taxonomy archive: `real-wedding-location` | `taxonomy-real-wedding-location.php` | `real-wedding-location` term archive; exact public path not defined in the theme |
| Taxonomy archive: `real-wedding-season` | `taxonomy-real-wedding-season.php` | `real-wedding-season` term archive; exact public path not defined in the theme |
| Taxonomy archive: `real-wedding-space-preferance` | `taxonomy-real-wedding-space-preferance.php` | `real-wedding-space-preferance` term archive; exact public path not defined in the theme |
| Taxonomy archive: `real-wedding-style` | `taxonomy-real-wedding-style.php` | `real-wedding-style` term archive; exact public path not defined in the theme |
| Taxonomy archive: `real-wedding-tag` | `taxonomy-real-wedding-tag.php` | `real-wedding-tag` term archive; exact public path not defined in the theme |
| Taxonomy archive: `vendor-category` | `taxonomy-vendor-category.php` | `/vendors/{term-slug}/` |
| Taxonomy archive: `venue-location` | `taxonomy-venue-location.php` | `/venues/{term-slug}/` |
| Taxonomy archive: `venue-type` | `taxonomy-venue-type.php` | `venue-type` term archive; exact public path not defined in the theme |
| Custom page template: Couple Dashboard | `user-template/couple-dashboard.php` | `/couple-dashboard/` |
| Custom page template: Front Page (User Template) | `user-template/front-page.php` | `/` via located `front-page.php` when WordPress routes the homepage through this template |
| Custom page template: Vendor Dashboard | `user-template/vendor-dashboard.php` | `/vendor-dashboard/` |
| Custom page template: Venue Dashboard | `user-template/venue-dashboard.php` | `/venue-dashboard/` |

## Task 2 — Template Usage Per Page

| Page Template | Section | Template Part File | Path |
| --- | --- | --- | --- |
| `archive-couple.php` | Line 10 | `page-header.php` | `template-parts/components/page-header.php` |
| `archive-couple.php` | Line 41 | `pagination.php` | `template-parts/components/pagination.php` |
| `archive-real-wedding.php` | Line 11 | `page-header.php` | `template-parts/components/page-header.php` |
| `archive-real-wedding.php` | Line 44 | `real-wedding-card.php` | `template-parts/components/real-wedding-card.php` |
| `archive-real-wedding.php` | Line 54 | `pagination.php` | `template-parts/components/pagination.php` |
| `archive-vendor.php` | Line 8 | `listing.php` | `template-parts/vendors/listing.php` |
| `archive-venue.php` | Line 8 | `listing.php` | `template-parts/venues/listing.php` |
| `archive-website.php` | Line 10 | `page-header.php` | `template-parts/components/page-header.php` |
| `archive-website.php` | Line 41 | `pagination.php` | `template-parts/components/pagination.php` |
| `archive.php` | Line 11 | `page-header.php` | `template-parts/components/page-header.php` |
| `archive.php` | Line 23 | `post-card.php` | `template-parts/components/post-card.php` |
| `archive.php` | Line 30 | `pagination.php` | `template-parts/components/pagination.php` |
| `category.php` | Check if this category or its parent is "wedding-planning-how-to" | `page-header.php` | `template-parts/components/page-header.php` |
| `category.php` | Line 42 | `post-card.php` | `template-parts/components/post-card.php` |
| `category.php` | Line 48 | `pagination.php` | `template-parts/components/pagination.php` |
| `category.php` | Line 103 | `post-card.php` | `template-parts/components/post-card.php` |
| `category.php` | Line 109 | `pagination.php` | `template-parts/components/pagination.php` |
| `front-page.php` | SECTION 2: San Diego Wedding Vendors | `section-title.php` | `template-parts/components/section-title.php` |
| `front-page.php` | SECTION 3: Plan Your San Diego Wedding | `section-title.php` | `template-parts/components/section-title.php` |
| `front-page.php` | SECTION 4: Real Weddings | `section-title.php` | `template-parts/components/section-title.php` |
| `front-page.php` | SECTION 5: Inspiration | `section-title.php` | `template-parts/components/section-title.php` |
| `front-page.php` | Line 571 | `section-title.php` | `template-parts/components/section-title.php` |
| `front-page.php` | SECTION 7: Search by Category | `section-title.php` | `template-parts/components/section-title.php` |
| `front-page.php` | SECTION 7: Search by Category | `inline-link-grid.php` | `template-parts/components/inline-link-grid.php` |
| `front-page.php` | SECTION 7: Search by Category | `inline-link-grid.php` | `template-parts/components/inline-link-grid.php` |
| `page-about.php` | Line 13 | `section-title.php` | `template-parts/components/section-title.php` |
| `page-about.php` | Line 37 | `section-title.php` | `template-parts/components/section-title.php` |
| `page-contact.php` | Line 10 | `page-header.php` | `template-parts/components/page-header.php` |
| `page-contact.php` | Line 27 | `contact-details.php` | `template-parts/components/contact-details.php` |
| `page-faqs.php` | Line 12 | `policy-subnav.php` | `template-parts/components/policy-subnav.php` |
| `page-faqs.php` | Line 39 | `faq-accordion.php` | `template-parts/components/faq-accordion.php` |
| `page-faqs.php` | Line 60 | `faq-accordion.php` | `template-parts/components/faq-accordion.php` |
| `page-faqs.php` | Line 81 | `faq-accordion.php` | `template-parts/components/faq-accordion.php` |
| `page-faqs.php` | Line 102 | `faq-accordion.php` | `template-parts/components/faq-accordion.php` |
| `page-faqs.php` | Line 123 | `contact-details.php` | `template-parts/components/contact-details.php` |
| `page-our-team.php` | Line 13 | `section-title.php` | `template-parts/components/section-title.php` |
| `page-policy.php` | Line 14 | `policy-subnav.php` | `template-parts/components/policy-subnav.php` |
| `page-vendors.php` | SECTION 1: Compact Search Hero | `breadcrumbs.php` | `template-parts/components/breadcrumbs.php` |
| `page-vendors.php` | SECTION 2: Browse by Category (carousel) | `section-title.php` | `template-parts/components/section-title.php` |
| `page-vendors.php` | SECTION 3: Vendor Popout | `venue-popout.php` | `template-parts/components/venue-popout.php` |
| `page-vendors.php` | Line 239 | `vendor-card.php` | `template-parts/components/vendor-card.php` |
| `page-vendors.php` | SECTION 5: SEO text columns | `section-title.php` | `template-parts/components/section-title.php` |
| `page-vendors.php` | SECTION 7: All Vendor Categories (link grid) | `inline-link-grid.php` | `template-parts/components/inline-link-grid.php` |
| `page-vendors.php` | SECTION 8: San Diego Wedding Venues (cross-link grid) | `inline-link-grid.php` | `template-parts/components/inline-link-grid.php` |
| `page-venues.php` | SECTION 1: Compact Search Hero | `breadcrumbs.php` | `template-parts/components/breadcrumbs.php` |
| `page-venues.php` | SECTION 2: Venues by Area (circular carousel) | `section-title.php` | `template-parts/components/section-title.php` |
| `page-venues.php` | San Diego popout | `venue-popout.php` | `template-parts/components/venue-popout.php` |
| `page-venues.php` | Line 334 | `venue-card.php` | `template-parts/components/venue-card.php` |
| `page-venues.php` | SECTION 13: SEO text columns | `section-title.php` | `template-parts/components/section-title.php` |
| `page-venues.php` | SECTION 14: Venue type buttons | `section-title.php` | `template-parts/components/section-title.php` |
| `page-venues.php` | SECTION 16: San Diego Wedding Vendors (vendor link grid) | `inline-link-grid.php` | `template-parts/components/inline-link-grid.php` |
| `page-wedding-planning.php` | Line 13 | `planning-hero.php` | `template-parts/planning/planning-hero.php` |
| `page-wedding-planning.php` | Line 14 | `planning-intro.php` | `template-parts/planning/planning-intro.php` |
| `page-wedding-planning.php` | Line 15 | `planning-checklist.php` | `template-parts/planning/planning-checklist.php` |
| `page-wedding-planning.php` | Line 16 | `planning-vendor-organizer.php` | `template-parts/planning/planning-vendor-organizer.php` |
| `page-wedding-planning.php` | Line 17 | `planning-wedding-website.php` | `template-parts/planning/planning-wedding-website.php` |
| `page-wedding-planning.php` | Line 18 | `planning-secondary-intro.php` | `template-parts/planning/planning-secondary-intro.php` |
| `page-wedding-planning.php` | Line 19 | `planning-tool-cards.php` | `template-parts/planning/planning-tool-cards.php` |
| `page-wedding-planning.php` | Line 20 | `planning-detailed-copy.php` | `template-parts/planning/planning-detailed-copy.php` |
| `page-wedding-planning.php` | Line 21 | `planning-faq.php` | `template-parts/planning/planning-faq.php` |
| `page.php` | Line 12 | `planning-child-page.php` | `template-parts/planning/planning-child-page.php` |
| `search.php` | Line 8 | `page-header.php` | `template-parts/components/page-header.php` |
| `search.php` | Line 23 | `post-card.php` | `template-parts/components/post-card.php` |
| `search.php` | Line 30 | `pagination.php` | `template-parts/components/pagination.php` |
| `single-vendor.php` | Line 82 | `breadcrumbs.php` | `template-parts/components/breadcrumbs.php` |
| `single-venue.php` | Line 95 | `breadcrumbs.php` | `template-parts/components/breadcrumbs.php` |
| `taxonomy-real-wedding-category.php` | Line 12 | `page-header.php` | `template-parts/components/page-header.php` |
| `taxonomy-real-wedding-category.php` | Line 47 | `real-wedding-card.php` | `template-parts/components/real-wedding-card.php` |
| `taxonomy-real-wedding-category.php` | Line 57 | `pagination.php` | `template-parts/components/pagination.php` |
| `taxonomy-real-wedding-color.php` | Line 12 | `page-header.php` | `template-parts/components/page-header.php` |
| `taxonomy-real-wedding-color.php` | Line 47 | `real-wedding-card.php` | `template-parts/components/real-wedding-card.php` |
| `taxonomy-real-wedding-color.php` | Line 57 | `pagination.php` | `template-parts/components/pagination.php` |
| `taxonomy-real-wedding-community.php` | Line 12 | `page-header.php` | `template-parts/components/page-header.php` |
| `taxonomy-real-wedding-community.php` | Line 47 | `real-wedding-card.php` | `template-parts/components/real-wedding-card.php` |
| `taxonomy-real-wedding-community.php` | Line 57 | `pagination.php` | `template-parts/components/pagination.php` |
| `taxonomy-real-wedding-location.php` | Line 15 | `page-header.php` | `template-parts/components/page-header.php` |
| `taxonomy-real-wedding-location.php` | Line 92 | `real-wedding-card.php` | `template-parts/components/real-wedding-card.php` |
| `taxonomy-real-wedding-location.php` | Line 102 | `pagination.php` | `template-parts/components/pagination.php` |
| `taxonomy-real-wedding-season.php` | Line 12 | `page-header.php` | `template-parts/components/page-header.php` |
| `taxonomy-real-wedding-season.php` | Line 47 | `real-wedding-card.php` | `template-parts/components/real-wedding-card.php` |
| `taxonomy-real-wedding-season.php` | Line 57 | `pagination.php` | `template-parts/components/pagination.php` |
| `taxonomy-real-wedding-space-preferance.php` | Line 12 | `page-header.php` | `template-parts/components/page-header.php` |
| `taxonomy-real-wedding-space-preferance.php` | Line 47 | `real-wedding-card.php` | `template-parts/components/real-wedding-card.php` |
| `taxonomy-real-wedding-space-preferance.php` | Line 57 | `pagination.php` | `template-parts/components/pagination.php` |
| `taxonomy-real-wedding-style.php` | Line 12 | `page-header.php` | `template-parts/components/page-header.php` |
| `taxonomy-real-wedding-style.php` | Line 47 | `real-wedding-card.php` | `template-parts/components/real-wedding-card.php` |
| `taxonomy-real-wedding-style.php` | Line 57 | `pagination.php` | `template-parts/components/pagination.php` |
| `taxonomy-real-wedding-tag.php` | Line 12 | `page-header.php` | `template-parts/components/page-header.php` |
| `taxonomy-real-wedding-tag.php` | Line 47 | `real-wedding-card.php` | `template-parts/components/real-wedding-card.php` |
| `taxonomy-real-wedding-tag.php` | Line 57 | `pagination.php` | `template-parts/components/pagination.php` |
| `taxonomy-vendor-category.php` | Line 15 | `listing.php` | `template-parts/vendors/listing.php` |
| `taxonomy-venue-location.php` | Line 15 | `listing.php` | `template-parts/venues/listing.php` |
| `taxonomy-venue-type.php` | Line 15 | `listing.php` | `template-parts/venues/listing.php` |
| `user-template/front-page.php` | Line 9 | `front-page.php` | `front-page.php` |

Page templates with no direct `get_template_part()`, `include`, `require`, or `locate_template()` call: `404.php`, `index.php`, `page-inspiration.php`, `page-style-guide.php`, `single-changelog.php`, `single-couple.php`, `single-real-wedding.php`, `single-team.php`, `single-website.php`, `single.php`, `user-template/couple-dashboard.php`, `user-template/vendor-dashboard.php`, `user-template/venue-dashboard.php`.

| Page Template | Other Shared Template Files |
| --- | --- |
| `404.php` | `header.php`, `footer.php` |
| `archive-couple.php` | `header.php`, `footer.php` |
| `archive-real-wedding.php` | `header.php`, `footer.php` |
| `archive-vendor.php` | `header.php`, `footer.php` |
| `archive-venue.php` | `header.php`, `footer.php` |
| `archive-website.php` | `header.php`, `footer.php` |
| `archive.php` | `header.php`, `footer.php` |
| `category.php` | `header.php`, `footer.php` |
| `front-page.php` | `header.php`, `footer.php` |
| `index.php` | `header.php`, `footer.php` |
| `page-about.php` | `header.php`, `footer.php` |
| `page-contact.php` | `header.php`, `footer.php` |
| `page-faqs.php` | `header.php`, `footer.php` |
| `page-our-team.php` | `header.php`, `footer.php` |
| `page-policy.php` | `header.php`, `footer.php` |
| `page-style-guide.php` | `header.php`, `footer.php` |
| `page-vendors.php` | `header.php`, `footer.php` |
| `page-venues.php` | `header.php`, `footer.php` |
| `page-wedding-planning.php` | `header.php`, `footer.php` |
| `page.php` | `header.php`, `footer.php` |
| `search.php` | `header.php`, `searchform.php`, `footer.php` |
| `single-changelog.php` | `header.php`, `footer.php` |
| `single-couple.php` | `header.php`, `footer.php` |
| `single-real-wedding.php` | `header.php`, `footer.php` |
| `single-team.php` | `header.php`, `footer.php` |
| `single-vendor.php` | `header.php`, `footer.php` |
| `single-venue.php` | `header.php`, `footer.php` |
| `single-website.php` | `header.php`, `footer.php` |
| `single.php` | `header.php`, `searchform.php`, `footer.php` |
| `taxonomy-real-wedding-category.php` | `header.php`, `footer.php` |
| `taxonomy-real-wedding-color.php` | `header.php`, `footer.php` |
| `taxonomy-real-wedding-community.php` | `header.php`, `footer.php` |
| `taxonomy-real-wedding-location.php` | `header.php`, `footer.php` |
| `taxonomy-real-wedding-season.php` | `header.php`, `footer.php` |
| `taxonomy-real-wedding-space-preferance.php` | `header.php`, `footer.php` |
| `taxonomy-real-wedding-style.php` | `header.php`, `footer.php` |
| `taxonomy-real-wedding-tag.php` | `header.php`, `footer.php` |
| `taxonomy-vendor-category.php` | `header.php`, `footer.php` |
| `taxonomy-venue-location.php` | `header.php`, `footer.php` |
| `taxonomy-venue-type.php` | `header.php`, `footer.php` |
| `user-template/couple-dashboard.php` | `header.php`, `footer.php` |
| `user-template/vendor-dashboard.php` | `header.php`, `footer.php` |
| `user-template/venue-dashboard.php` | `header.php`, `footer.php` |

## Task 3 — Component Usage

| Template Part | Component | Path |
| --- | --- | --- |
| `template-parts/blog/blog-grid-5.php` | `post-card.php` | `template-parts/components/post-card.php` |
| `template-parts/blog/blog-list-with-sidebar.php` | `pagination.php` | `template-parts/components/pagination.php` |
| `template-parts/components/faq-section.php` | `section-title.php` | `template-parts/components/section-title.php` |
| `template-parts/components/page-header.php` | `breadcrumbs.php` | `template-parts/components/breadcrumbs.php` |
| `template-parts/planning/planning-checklist.php` | `feature-block.php` | `template-parts/components/feature-block.php` |
| `template-parts/planning/planning-child-page.php` | `planning-hero.php` | `template-parts/planning/planning-hero.php` |
| `template-parts/planning/planning-child-page.php` | `section-title.php` | `template-parts/components/section-title.php` |
| `template-parts/planning/planning-child-page.php` | `feature-block.php` | `template-parts/components/feature-block.php` |
| `template-parts/planning/planning-child-page.php` | `section-title.php` | `template-parts/components/section-title.php` |
| `template-parts/planning/planning-child-page.php` | `tool-card-row.php` | `template-parts/components/tool-card-row.php` |
| `template-parts/planning/planning-child-page.php` | `faq-section.php` | `template-parts/components/faq-section.php` |
| `template-parts/planning/planning-faq.php` | `faq-section.php` | `template-parts/components/faq-section.php` |
| `template-parts/planning/planning-secondary-intro.php` | `section-title.php` | `template-parts/components/section-title.php` |
| `template-parts/planning/planning-tool-cards.php` | `tool-card-row.php` | `template-parts/components/tool-card-row.php` |
| `template-parts/planning/planning-vendor-organizer.php` | `feature-block.php` | `template-parts/components/feature-block.php` |
| `template-parts/planning/planning-wedding-website.php` | `feature-block.php` | `template-parts/components/feature-block.php` |
| `template-parts/vendors/listing.php` | `page-header.php` | `template-parts/components/page-header.php` |
| `template-parts/vendors/listing.php` | `section-title.php` | `template-parts/components/section-title.php` |
| `template-parts/vendors/listing.php` | `inline-link-grid.php` | `template-parts/components/inline-link-grid.php` |
| `template-parts/vendors/listing.php` | `vendor-card.php` | `template-parts/components/vendor-card.php` |
| `template-parts/vendors/listing.php` | `pagination.php` | `template-parts/components/pagination.php` |
| `template-parts/venues/listing.php` | `page-header.php` | `template-parts/components/page-header.php` |
| `template-parts/venues/listing.php` | `section-title.php` | `template-parts/components/section-title.php` |
| `template-parts/venues/listing.php` | `section-title.php` | `template-parts/components/section-title.php` |
| `template-parts/venues/listing.php` | `venue-card.php` | `template-parts/components/venue-card.php` |
| `template-parts/venues/listing.php` | `pagination.php` | `template-parts/components/pagination.php` |

Template parts with no nested `get_template_part()`, `include`, `require`, or `locate_template()` call: `template-parts/components/breadcrumbs.php`, `template-parts/components/contact-details.php`, `template-parts/components/faq-accordion.php`, `template-parts/components/feature-block.php`, `template-parts/components/inline-link-grid.php`, `template-parts/components/pagination.php`, `template-parts/components/policy-subnav.php`, `template-parts/components/post-card.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/section-title.php`, `template-parts/components/tool-card-row.php`, `template-parts/components/vendor-card.php`, `template-parts/components/venue-card.php`, `template-parts/components/venue-popout.php`, `template-parts/modals/couple-login.php`, `template-parts/modals/couple-registration.php`, `template-parts/modals/forgot-password.php`, `template-parts/modals/vendor-login.php`, `template-parts/modals/vendor-registration.php`, `template-parts/planning/planning-detailed-copy.php`, `template-parts/planning/planning-hero.php`, `template-parts/planning/planning-intro.php`, `template-parts/why-use-sdwd.php`.

| Additional Shared Theme Template | Nested Component | Path |
| --- | --- | --- |
| `header.php` | None | None |
| `footer.php` | `couple-login.php` | `template-parts/modals/couple-login.php` |
| `footer.php` | `couple-registration.php` | `template-parts/modals/couple-registration.php` |
| `footer.php` | `vendor-login.php` | `template-parts/modals/vendor-login.php` |
| `footer.php` | `vendor-registration.php` | `template-parts/modals/vendor-registration.php` |
| `footer.php` | `forgot-password.php` | `template-parts/modals/forgot-password.php` |
| `searchform.php` | None | None |
| `sidebar.php` | None | None |

## Task 4 — CSS Mapping

No `@import` rules were found in the scanned CSS files.

| Page Template | CSS File | How Loaded (enqueue / import / inline) |
| --- | --- | --- |
| `404.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `404.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `404.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `404.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `404.php` | `assets/css/pages/static.css` | enqueue in `functions.php` when `is_404()` |
| `404.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `archive-couple.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `archive-couple.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `archive-couple.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `archive-couple.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `archive-couple.php` | `assets/css/pages/archive.css` | enqueue in `functions.php` when `is_archive() || is_tax() || is_search()` |
| `archive-couple.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `archive-real-wedding.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `archive-real-wedding.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `archive-real-wedding.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `archive-real-wedding.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `archive-real-wedding.php` | `assets/css/pages/archive.css` | enqueue in `functions.php` when `is_archive() || is_tax() || is_search()` |
| `archive-real-wedding.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `archive-vendor.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `archive-vendor.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `archive-vendor.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `archive-vendor.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `archive-vendor.php` | `assets/css/pages/archive.css` | enqueue in `functions.php` when `is_archive() || is_tax() || is_search()` |
| `archive-vendor.php` | `assets/css/pages/vendors.css` | enqueue in `functions.php` when `is_post_type_archive( 'vendor' )` |
| `archive-vendor.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `archive-venue.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `archive-venue.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `archive-venue.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `archive-venue.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `archive-venue.php` | `assets/css/pages/archive.css` | enqueue in `functions.php` when `is_archive() || is_tax() || is_search()` |
| `archive-venue.php` | `assets/css/pages/venues.css` | enqueue in `functions.php` when `is_post_type_archive( 'venue' )` |
| `archive-venue.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `archive-website.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `archive-website.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `archive-website.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `archive-website.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `archive-website.php` | `assets/css/pages/archive.css` | enqueue in `functions.php` when `is_archive() || is_tax() || is_search()` |
| `archive-website.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `archive.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `archive.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `archive.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `archive.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `archive.php` | `assets/css/pages/archive.css` | enqueue in `functions.php` when `is_archive() || is_tax() || is_search()` |
| `archive.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `category.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `category.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `category.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `category.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `category.php` | `assets/css/pages/archive.css` | enqueue in `functions.php` when `is_archive() || is_tax() || is_search()` |
| `category.php` | `assets/css/pages/planning.css` | enqueue in `functions.php` when `is_category() && sdwdv2_is_planning_category()` |
| `category.php` | `assets/css/pages/blog.css` | enqueue in `functions.php` when `is_category()` |
| `category.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `front-page.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `front-page.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `front-page.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `front-page.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `front-page.php` | `assets/css/pages/home.css` | enqueue in `functions.php` when `is_front_page()` |
| `front-page.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `index.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `index.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `index.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `index.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `index.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `page-about.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `page-about.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `page-about.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `page-about.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `page-about.php` | `assets/css/pages/static.css` | enqueue in `functions.php` when `is_page_template( [ 'page-about.php', 'page-contact.php', 'page-faqs.php', 'page-policy.php', 'page-our-team.php' ] ) || is_404() || is_search() || is_singular( 'team' ) || is_singular( 'changelog' )` |
| `page-about.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `page-contact.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `page-contact.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `page-contact.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `page-contact.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `page-contact.php` | `assets/css/pages/static.css` | enqueue in `functions.php` when `is_page_template( [ 'page-about.php', 'page-contact.php', 'page-faqs.php', 'page-policy.php', 'page-our-team.php' ] ) || is_404() || is_search() || is_singular( 'team' ) || is_singular( 'changelog' )` |
| `page-contact.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `page-faqs.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `page-faqs.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `page-faqs.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `page-faqs.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `page-faqs.php` | `assets/css/pages/static.css` | enqueue in `functions.php` when `is_page_template( [ 'page-about.php', 'page-contact.php', 'page-faqs.php', 'page-policy.php', 'page-our-team.php' ] ) || is_404() || is_search() || is_singular( 'team' ) || is_singular( 'changelog' )` |
| `page-faqs.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `page-inspiration.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `page-inspiration.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `page-inspiration.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `page-inspiration.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `page-inspiration.php` | `assets/css/pages/blog.css` | enqueue in `functions.php` when `is_page_template( 'page-inspiration.php' )` |
| `page-inspiration.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `page-our-team.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `page-our-team.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `page-our-team.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `page-our-team.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `page-our-team.php` | `assets/css/pages/static.css` | enqueue in `functions.php` when `is_page_template( [ 'page-about.php', 'page-contact.php', 'page-faqs.php', 'page-policy.php', 'page-our-team.php' ] ) || is_404() || is_search() || is_singular( 'team' ) || is_singular( 'changelog' )` |
| `page-our-team.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `page-policy.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `page-policy.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `page-policy.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `page-policy.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `page-policy.php` | `assets/css/pages/static.css` | enqueue in `functions.php` when `is_page_template( [ 'page-about.php', 'page-contact.php', 'page-faqs.php', 'page-policy.php', 'page-our-team.php' ] ) || is_404() || is_search() || is_singular( 'team' ) || is_singular( 'changelog' )` |
| `page-policy.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `page-style-guide.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `page-style-guide.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `page-style-guide.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `page-style-guide.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `page-style-guide.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `page-vendors.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `page-vendors.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `page-vendors.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `page-vendors.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `page-vendors.php` | `assets/css/pages/vendors.css` | enqueue in `functions.php` when `is_page( 'vendors' ) || is_page_template( 'page-vendors.php' ) || is_post_type_archive( 'vendor' ) || is_tax( 'vendor-category' )` |
| `page-vendors.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `page-venues.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `page-venues.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `page-venues.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `page-venues.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `page-venues.php` | `assets/css/pages/home.css` | enqueue in `functions.php` when `is_page( 'venues' ) || is_page_template( 'page-venues.php' )` |
| `page-venues.php` | `assets/css/pages/venues.css` | enqueue in `functions.php` when `is_page( 'venues' ) || is_page_template( 'page-venues.php' ) || is_post_type_archive( 'venue' ) || is_tax( 'venue-type' ) || is_tax( 'venue-location' )` |
| `page-venues.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `page-wedding-planning.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `page-wedding-planning.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `page-wedding-planning.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `page-wedding-planning.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `page-wedding-planning.php` | `assets/css/pages/planning.css` | enqueue in `functions.php` when `is_page_template( 'page-wedding-planning.php' ) || ( is_category() && sdwdv2_is_planning_category() ) || ( is_page() && absint( wp_get_post_parent_id( get_the_ID() ) ) === 4180 )` |
| `page-wedding-planning.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `page.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `page.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `page.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `page.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `page.php` | `assets/css/pages/planning.css` | enqueue in `functions.php` when `is_page() && absint( wp_get_post_parent_id( get_the_ID() ) ) === 4180` |
| `page.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `search.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `search.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `search.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `search.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `search.php` | `assets/css/pages/archive.css` | enqueue in `functions.php` when `is_archive() || is_tax() || is_search()` |
| `search.php` | `assets/css/pages/static.css` | enqueue in `functions.php` when `is_search()` |
| `search.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `single-changelog.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `single-changelog.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `single-changelog.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `single-changelog.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `single-changelog.php` | `assets/css/pages/static.css` | enqueue in `functions.php` when `is_singular( 'changelog' )` |
| `single-changelog.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `single-couple.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `single-couple.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `single-couple.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `single-couple.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `single-couple.php` | `assets/css/pages/profile.css` | enqueue in `functions.php` when `is_singular( [ 'vendor', 'venue', 'real-wedding', 'couple' ] )` |
| `single-couple.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `single-real-wedding.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `single-real-wedding.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `single-real-wedding.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `single-real-wedding.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `single-real-wedding.php` | `assets/css/pages/profile.css` | enqueue in `functions.php` when `is_singular( [ 'vendor', 'venue', 'real-wedding', 'couple' ] )` |
| `single-real-wedding.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `single-team.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `single-team.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `single-team.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `single-team.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `single-team.php` | `assets/css/pages/static.css` | enqueue in `functions.php` when `is_singular( 'team' )` |
| `single-team.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `single-vendor.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `single-vendor.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `single-vendor.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `single-vendor.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `single-vendor.php` | `assets/css/pages/profile.css` | enqueue in `functions.php` when `is_singular( [ 'vendor', 'venue', 'real-wedding', 'couple' ] )` |
| `single-vendor.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `single-venue.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `single-venue.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `single-venue.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `single-venue.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `single-venue.php` | `assets/css/pages/profile.css` | enqueue in `functions.php` when `is_singular( [ 'vendor', 'venue', 'real-wedding', 'couple' ] )` |
| `single-venue.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `single-website.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `single-website.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `single-website.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `single-website.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `single-website.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `single.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `single.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `single.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `single.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `single.php` | `assets/css/pages/blog.css` | enqueue in `functions.php` when `is_singular( 'post' )` |
| `single.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `taxonomy-real-wedding-category.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `taxonomy-real-wedding-category.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `taxonomy-real-wedding-category.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `taxonomy-real-wedding-category.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `taxonomy-real-wedding-category.php` | `assets/css/pages/archive.css` | enqueue in `functions.php` when `is_archive() || is_tax() || is_search()` |
| `taxonomy-real-wedding-category.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `taxonomy-real-wedding-color.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `taxonomy-real-wedding-color.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `taxonomy-real-wedding-color.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `taxonomy-real-wedding-color.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `taxonomy-real-wedding-color.php` | `assets/css/pages/archive.css` | enqueue in `functions.php` when `is_archive() || is_tax() || is_search()` |
| `taxonomy-real-wedding-color.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `taxonomy-real-wedding-community.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `taxonomy-real-wedding-community.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `taxonomy-real-wedding-community.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `taxonomy-real-wedding-community.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `taxonomy-real-wedding-community.php` | `assets/css/pages/archive.css` | enqueue in `functions.php` when `is_archive() || is_tax() || is_search()` |
| `taxonomy-real-wedding-community.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `taxonomy-real-wedding-location.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `taxonomy-real-wedding-location.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `taxonomy-real-wedding-location.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `taxonomy-real-wedding-location.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `taxonomy-real-wedding-location.php` | `assets/css/pages/archive.css` | enqueue in `functions.php` when `is_archive() || is_tax() || is_search()` |
| `taxonomy-real-wedding-location.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `taxonomy-real-wedding-season.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `taxonomy-real-wedding-season.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `taxonomy-real-wedding-season.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `taxonomy-real-wedding-season.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `taxonomy-real-wedding-season.php` | `assets/css/pages/archive.css` | enqueue in `functions.php` when `is_archive() || is_tax() || is_search()` |
| `taxonomy-real-wedding-season.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `taxonomy-real-wedding-space-preferance.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `taxonomy-real-wedding-space-preferance.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `taxonomy-real-wedding-space-preferance.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `taxonomy-real-wedding-space-preferance.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `taxonomy-real-wedding-space-preferance.php` | `assets/css/pages/archive.css` | enqueue in `functions.php` when `is_archive() || is_tax() || is_search()` |
| `taxonomy-real-wedding-space-preferance.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `taxonomy-real-wedding-style.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `taxonomy-real-wedding-style.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `taxonomy-real-wedding-style.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `taxonomy-real-wedding-style.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `taxonomy-real-wedding-style.php` | `assets/css/pages/archive.css` | enqueue in `functions.php` when `is_archive() || is_tax() || is_search()` |
| `taxonomy-real-wedding-style.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `taxonomy-real-wedding-tag.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `taxonomy-real-wedding-tag.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `taxonomy-real-wedding-tag.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `taxonomy-real-wedding-tag.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `taxonomy-real-wedding-tag.php` | `assets/css/pages/archive.css` | enqueue in `functions.php` when `is_archive() || is_tax() || is_search()` |
| `taxonomy-real-wedding-tag.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `taxonomy-vendor-category.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `taxonomy-vendor-category.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `taxonomy-vendor-category.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `taxonomy-vendor-category.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `taxonomy-vendor-category.php` | `assets/css/pages/archive.css` | enqueue in `functions.php` when `is_archive() || is_tax() || is_search()` |
| `taxonomy-vendor-category.php` | `assets/css/pages/vendors.css` | enqueue in `functions.php` when `is_tax( 'vendor-category' )` |
| `taxonomy-vendor-category.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `taxonomy-venue-location.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `taxonomy-venue-location.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `taxonomy-venue-location.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `taxonomy-venue-location.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `taxonomy-venue-location.php` | `assets/css/pages/archive.css` | enqueue in `functions.php` when `is_archive() || is_tax() || is_search()` |
| `taxonomy-venue-location.php` | `assets/css/pages/venues.css` | enqueue in `functions.php` when `is_tax( 'venue-location' )` |
| `taxonomy-venue-location.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `taxonomy-venue-type.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `taxonomy-venue-type.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `taxonomy-venue-type.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `taxonomy-venue-type.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `taxonomy-venue-type.php` | `assets/css/pages/archive.css` | enqueue in `functions.php` when `is_archive() || is_tax() || is_search()` |
| `taxonomy-venue-type.php` | `assets/css/pages/venues.css` | enqueue in `functions.php` when `is_tax( 'venue-type' )` |
| `taxonomy-venue-type.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `user-template/couple-dashboard.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `user-template/couple-dashboard.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `user-template/couple-dashboard.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `user-template/couple-dashboard.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `user-template/couple-dashboard.php` | `assets/css/pages/dashboard.css` | enqueue in `functions.php` when `sdwdv2_is_dashboard_page()` returns true for this template slug |
| `user-template/couple-dashboard.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `user-template/front-page.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `user-template/front-page.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `user-template/front-page.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `user-template/front-page.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `user-template/front-page.php` | `assets/css/pages/home.css` | enqueue in `functions.php` when the request is the front page; this template locates `front-page.php` |
| `user-template/front-page.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `user-template/vendor-dashboard.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `user-template/vendor-dashboard.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `user-template/vendor-dashboard.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `user-template/vendor-dashboard.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `user-template/vendor-dashboard.php` | `assets/css/pages/dashboard.css` | enqueue in `functions.php` when `sdwdv2_is_dashboard_page()` returns true for this template slug |
| `user-template/vendor-dashboard.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |
| `user-template/venue-dashboard.php` | `assets/library/sdwd-icons/style.css` | enqueue in `functions.php` on every front-end request |
| `user-template/venue-dashboard.php` | `assets/css/foundation.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwd-icons` |
| `user-template/venue-dashboard.php` | `assets/css/components.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-foundation` |
| `user-template/venue-dashboard.php` | `assets/css/layout.css` | enqueue in `functions.php` on every front-end request; dependency: `sdwdv2-components` |
| `user-template/venue-dashboard.php` | `assets/css/pages/dashboard.css` | enqueue in `functions.php` when `sdwdv2_is_dashboard_page()` returns true for this template slug |
| `user-template/venue-dashboard.php` | `assets/css/pages/modals.css` | enqueue in `functions.php` when `! is_user_logged_in()` |

Page templates with no `get_header()` / `wp_head()` call in the scanned file body: `page-inspiration.php`.

| CSS File | Status |
| --- | --- |
| `assets/css/pages/inspiration.css` | No enqueue, `@import`, or inline load reference found in the scanned theme files |
| `assets/css/pages/venues-landing.css` | No enqueue, `@import`, or inline load reference found in the scanned theme files |
| `assets/library/sdwd-icons/demo-files/demo.css` | No enqueue, `@import`, or inline load reference found in the scanned theme files |
| `style.css` | No enqueue, `@import`, or inline load reference found in the scanned theme files |

## Task 5 — Duplication Detection

| Pattern | Files Found In |
| --- | --- |
| Exact route target `/vendors/` appears in two root templates | `page-vendors.php`, `archive-vendor.php` |
| Exact route target `/venues/` appears in two root templates | `page-venues.php`, `archive-venue.php` |
| Shared listing template `template-parts/vendors/listing.php` | `archive-vendor.php`, `taxonomy-vendor-category.php` |
| Shared listing template `template-parts/venues/listing.php` | `archive-venue.php`, `taxonomy-venue-type.php`, `taxonomy-venue-location.php` |
| Shared archive loop pattern `page-header` + `real-wedding-card` + `pagination` | `archive-real-wedding.php`, `taxonomy-real-wedding-category.php`, `taxonomy-real-wedding-color.php`, `taxonomy-real-wedding-community.php`, `taxonomy-real-wedding-location.php`, `taxonomy-real-wedding-season.php`, `taxonomy-real-wedding-space-preferance.php`, `taxonomy-real-wedding-style.php`, `taxonomy-real-wedding-tag.php` |
| Shared archive/search loop pattern `page-header` + `post-card` + `pagination` | `archive.php`, `search.php` |
| `category.php` repeats the `post-card` + `pagination` loop in both branches of the file | `category.php` |
| Shared single-item breadcrumb component | `single-vendor.php`, `single-venue.php` |
| Shared landing-page pattern `breadcrumbs` + `section-title` + popout + card grid + `inline-link-grid` | `page-vendors.php`, `page-venues.php` |
| Shared planning `feature-block` component | `template-parts/planning/planning-checklist.php`, `template-parts/planning/planning-vendor-organizer.php`, `template-parts/planning/planning-wedding-website.php`, `template-parts/planning/planning-child-page.php` |
| Shared `page-header.php` component | `archive-couple.php`, `archive-real-wedding.php`, `archive-website.php`, `archive.php`, `category.php`, `page-contact.php`, `search.php`, `taxonomy-real-wedding-category.php`, `taxonomy-real-wedding-color.php`, `taxonomy-real-wedding-community.php`, `taxonomy-real-wedding-location.php`, `taxonomy-real-wedding-season.php`, `taxonomy-real-wedding-space-preferance.php`, `taxonomy-real-wedding-style.php`, `taxonomy-real-wedding-tag.php`, `template-parts/vendors/listing.php`, `template-parts/venues/listing.php` |

Template parts with no incoming reference found: `template-parts/blog/blog-grid-5.php`, `template-parts/blog/blog-list-with-sidebar.php`, `template-parts/why-use-sdwd.php`.

## Task 6 — Final Page Map

| Page | Root Template | Template Parts Used | Components Used | Other Shared Template Files | CSS |
| --- | --- | --- | --- | --- | --- |
| 404 | `404.php` | None | None | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/static.css`, `assets/css/pages/modals.css` |
| Archive Couple | `archive-couple.php` | `template-parts/components/page-header.php`, `template-parts/components/pagination.php` | `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/pagination.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/archive.css`, `assets/css/pages/modals.css` |
| Archive Real Wedding | `archive-real-wedding.php` | `template-parts/components/page-header.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php` | `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/archive.css`, `assets/css/pages/modals.css` |
| Archive Vendor | `archive-vendor.php` | `template-parts/vendors/listing.php` | `template-parts/vendors/listing.php`, `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/section-title.php`, `template-parts/components/inline-link-grid.php`, `template-parts/components/vendor-card.php`, `template-parts/components/pagination.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/archive.css`, `assets/css/pages/vendors.css`, `assets/css/pages/modals.css` |
| Archive Venue | `archive-venue.php` | `template-parts/venues/listing.php` | `template-parts/venues/listing.php`, `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/section-title.php`, `template-parts/components/venue-card.php`, `template-parts/components/pagination.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/archive.css`, `assets/css/pages/venues.css`, `assets/css/pages/modals.css` |
| Archive Website | `archive-website.php` | `template-parts/components/page-header.php`, `template-parts/components/pagination.php` | `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/pagination.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/archive.css`, `assets/css/pages/modals.css` |
| Archive | `archive.php` | `template-parts/components/page-header.php`, `template-parts/components/post-card.php`, `template-parts/components/pagination.php` | `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/post-card.php`, `template-parts/components/pagination.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/archive.css`, `assets/css/pages/modals.css` |
| Category Archive | `category.php` | `template-parts/components/page-header.php`, `template-parts/components/post-card.php`, `template-parts/components/pagination.php` | `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/post-card.php`, `template-parts/components/pagination.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/archive.css`, `assets/css/pages/planning.css`, `assets/css/pages/blog.css`, `assets/css/pages/modals.css` |
| Home | `front-page.php` | `template-parts/components/section-title.php`, `template-parts/components/inline-link-grid.php` | `template-parts/components/section-title.php`, `template-parts/components/inline-link-grid.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/home.css`, `assets/css/pages/modals.css` |
| Index | `index.php` | None | None | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` |
| About | `page-about.php` | `template-parts/components/section-title.php` | `template-parts/components/section-title.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/static.css`, `assets/css/pages/modals.css` |
| Contact | `page-contact.php` | `template-parts/components/page-header.php`, `template-parts/components/contact-details.php` | `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/contact-details.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/static.css`, `assets/css/pages/modals.css` |
| FAQs | `page-faqs.php` | `template-parts/components/policy-subnav.php`, `template-parts/components/faq-accordion.php`, `template-parts/components/contact-details.php` | `template-parts/components/policy-subnav.php`, `template-parts/components/faq-accordion.php`, `template-parts/components/contact-details.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/static.css`, `assets/css/pages/modals.css` |
| Inspiration | `page-inspiration.php` | None | None | None | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/blog.css`, `assets/css/pages/modals.css` |
| Our Team | `page-our-team.php` | `template-parts/components/section-title.php` | `template-parts/components/section-title.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/static.css`, `assets/css/pages/modals.css` |
| Policy | `page-policy.php` | `template-parts/components/policy-subnav.php` | `template-parts/components/policy-subnav.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/static.css`, `assets/css/pages/modals.css` |
| Style Guide | `page-style-guide.php` | None | None | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` |
| Vendors | `page-vendors.php` | `template-parts/components/breadcrumbs.php`, `template-parts/components/section-title.php`, `template-parts/components/venue-popout.php`, `template-parts/components/vendor-card.php`, `template-parts/components/inline-link-grid.php` | `template-parts/components/breadcrumbs.php`, `template-parts/components/section-title.php`, `template-parts/components/venue-popout.php`, `template-parts/components/vendor-card.php`, `template-parts/components/inline-link-grid.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/vendors.css`, `assets/css/pages/modals.css` |
| Venues | `page-venues.php` | `template-parts/components/breadcrumbs.php`, `template-parts/components/section-title.php`, `template-parts/components/venue-popout.php`, `template-parts/components/venue-card.php`, `template-parts/components/inline-link-grid.php` | `template-parts/components/breadcrumbs.php`, `template-parts/components/section-title.php`, `template-parts/components/venue-popout.php`, `template-parts/components/venue-card.php`, `template-parts/components/inline-link-grid.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/home.css`, `assets/css/pages/venues.css`, `assets/css/pages/modals.css` |
| Wedding Planning | `page-wedding-planning.php` | `template-parts/planning/planning-hero.php`, `template-parts/planning/planning-intro.php`, `template-parts/planning/planning-checklist.php`, `template-parts/planning/planning-vendor-organizer.php`, `template-parts/planning/planning-wedding-website.php`, `template-parts/planning/planning-secondary-intro.php`, `template-parts/planning/planning-tool-cards.php`, `template-parts/planning/planning-detailed-copy.php`, `template-parts/planning/planning-faq.php` | `template-parts/planning/planning-hero.php`, `template-parts/planning/planning-intro.php`, `template-parts/planning/planning-checklist.php`, `template-parts/components/feature-block.php`, `template-parts/planning/planning-vendor-organizer.php`, `template-parts/planning/planning-wedding-website.php`, `template-parts/planning/planning-secondary-intro.php`, `template-parts/components/section-title.php`, `template-parts/planning/planning-tool-cards.php`, `template-parts/components/tool-card-row.php`, `template-parts/planning/planning-detailed-copy.php`, `template-parts/planning/planning-faq.php`, `template-parts/components/faq-section.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/planning.css`, `assets/css/pages/modals.css` |
| Default Page | `page.php` | `template-parts/planning/planning-child-page.php` | `template-parts/planning/planning-child-page.php`, `template-parts/planning/planning-hero.php`, `template-parts/components/section-title.php`, `template-parts/components/feature-block.php`, `template-parts/components/tool-card-row.php`, `template-parts/components/faq-section.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/planning.css`, `assets/css/pages/modals.css` |
| Search Results | `search.php` | `template-parts/components/page-header.php`, `template-parts/components/post-card.php`, `template-parts/components/pagination.php` | `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/post-card.php`, `template-parts/components/pagination.php` | `header.php`, `footer.php`, `searchform.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/archive.css`, `assets/css/pages/static.css`, `assets/css/pages/modals.css` |
| Single Changelog | `single-changelog.php` | None | None | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/static.css`, `assets/css/pages/modals.css` |
| Single Couple | `single-couple.php` | None | None | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/profile.css`, `assets/css/pages/modals.css` |
| Single Real Wedding | `single-real-wedding.php` | None | None | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/profile.css`, `assets/css/pages/modals.css` |
| Single Team | `single-team.php` | None | None | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/static.css`, `assets/css/pages/modals.css` |
| Single Vendor | `single-vendor.php` | `template-parts/components/breadcrumbs.php` | `template-parts/components/breadcrumbs.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/profile.css`, `assets/css/pages/modals.css` |
| Single Venue | `single-venue.php` | `template-parts/components/breadcrumbs.php` | `template-parts/components/breadcrumbs.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/profile.css`, `assets/css/pages/modals.css` |
| Single Website | `single-website.php` | None | None | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` |
| Single Post | `single.php` | None | None | `header.php`, `footer.php`, `searchform.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/blog.css`, `assets/css/pages/modals.css` |
| Taxonomy Real Wedding Category | `taxonomy-real-wedding-category.php` | `template-parts/components/page-header.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php` | `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/archive.css`, `assets/css/pages/modals.css` |
| Taxonomy Real Wedding Color | `taxonomy-real-wedding-color.php` | `template-parts/components/page-header.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php` | `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/archive.css`, `assets/css/pages/modals.css` |
| Taxonomy Real Wedding Community | `taxonomy-real-wedding-community.php` | `template-parts/components/page-header.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php` | `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/archive.css`, `assets/css/pages/modals.css` |
| Taxonomy Real Wedding Location | `taxonomy-real-wedding-location.php` | `template-parts/components/page-header.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php` | `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/archive.css`, `assets/css/pages/modals.css` |
| Taxonomy Real Wedding Season | `taxonomy-real-wedding-season.php` | `template-parts/components/page-header.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php` | `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/archive.css`, `assets/css/pages/modals.css` |
| Taxonomy Real Wedding Space Preferance | `taxonomy-real-wedding-space-preferance.php` | `template-parts/components/page-header.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php` | `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/archive.css`, `assets/css/pages/modals.css` |
| Taxonomy Real Wedding Style | `taxonomy-real-wedding-style.php` | `template-parts/components/page-header.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php` | `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/archive.css`, `assets/css/pages/modals.css` |
| Taxonomy Real Wedding Tag | `taxonomy-real-wedding-tag.php` | `template-parts/components/page-header.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php` | `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/archive.css`, `assets/css/pages/modals.css` |
| Taxonomy Vendor Category | `taxonomy-vendor-category.php` | `template-parts/vendors/listing.php` | `template-parts/vendors/listing.php`, `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/section-title.php`, `template-parts/components/inline-link-grid.php`, `template-parts/components/vendor-card.php`, `template-parts/components/pagination.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/archive.css`, `assets/css/pages/vendors.css`, `assets/css/pages/modals.css` |
| Taxonomy Venue Location | `taxonomy-venue-location.php` | `template-parts/venues/listing.php` | `template-parts/venues/listing.php`, `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/section-title.php`, `template-parts/components/venue-card.php`, `template-parts/components/pagination.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/archive.css`, `assets/css/pages/venues.css`, `assets/css/pages/modals.css` |
| Taxonomy Venue Type | `taxonomy-venue-type.php` | `template-parts/venues/listing.php` | `template-parts/venues/listing.php`, `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/section-title.php`, `template-parts/components/venue-card.php`, `template-parts/components/pagination.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/archive.css`, `assets/css/pages/venues.css`, `assets/css/pages/modals.css` |
| Couple Dashboard | `user-template/couple-dashboard.php` | None | None | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/dashboard.css`, `assets/css/pages/modals.css` |
| Front Page (User Template) | `user-template/front-page.php` | `front-page.php` | `front-page.php`, `template-parts/components/section-title.php`, `template-parts/components/inline-link-grid.php` | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/home.css`, `assets/css/pages/modals.css` |
| Vendor Dashboard | `user-template/vendor-dashboard.php` | None | None | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/dashboard.css`, `assets/css/pages/modals.css` |
| Venue Dashboard | `user-template/venue-dashboard.php` | None | None | `header.php`, `footer.php` | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/dashboard.css`, `assets/css/pages/modals.css` |

## Task 7 — Simplification Map

Rule used for `single PHP file`: `YES` = the root template file contains no `get_template_part()`, `locate_template()`, `include`, or `require` call. `NO` = the root template file contains at least one of those calls.

Rule used for `single CSS file`: `YES` = no shared CSS dependency exists outside `assets/css/foundation.css` and `assets/css/layout.css`. `NO` = at least one shared CSS dependency exists outside those two files. The current theme globally enqueues `assets/library/sdwd-icons/style.css` and `assets/css/components.css` on every front-end request, so every page is `NO` under current usage.

| Page | Single PHP File | Single CSS File | Dependencies That Must Remain Shared |
| --- | --- | --- | --- |
| 404 | YES | NO | `header.php`, `footer.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Archive Couple | NO | NO | `header.php`, `footer.php`, `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/pagination.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Archive Real Wedding | NO | NO | `header.php`, `footer.php`, `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Archive Vendor | NO | NO | `header.php`, `footer.php`, `template-parts/vendors/listing.php`, `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/section-title.php`, `template-parts/components/inline-link-grid.php`, `template-parts/components/vendor-card.php`, `template-parts/components/pagination.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Archive Venue | NO | NO | `header.php`, `footer.php`, `template-parts/venues/listing.php`, `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/section-title.php`, `template-parts/components/venue-card.php`, `template-parts/components/pagination.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Archive Website | NO | NO | `header.php`, `footer.php`, `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/pagination.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Archive | NO | NO | `header.php`, `footer.php`, `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/post-card.php`, `template-parts/components/pagination.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Category Archive | NO | NO | `header.php`, `footer.php`, `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/post-card.php`, `template-parts/components/pagination.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Home | NO | NO | `header.php`, `footer.php`, `template-parts/components/section-title.php`, `template-parts/components/inline-link-grid.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Index | YES | NO | `header.php`, `footer.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| About | NO | NO | `header.php`, `footer.php`, `template-parts/components/section-title.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Contact | NO | NO | `header.php`, `footer.php`, `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/contact-details.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| FAQs | NO | NO | `header.php`, `footer.php`, `template-parts/components/policy-subnav.php`, `template-parts/components/faq-accordion.php`, `template-parts/components/contact-details.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Inspiration | YES | NO | `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Our Team | NO | NO | `header.php`, `footer.php`, `template-parts/components/section-title.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Policy | NO | NO | `header.php`, `footer.php`, `template-parts/components/policy-subnav.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Style Guide | YES | NO | `header.php`, `footer.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Vendors | NO | NO | `header.php`, `footer.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/section-title.php`, `template-parts/components/venue-popout.php`, `template-parts/components/vendor-card.php`, `template-parts/components/inline-link-grid.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Venues | NO | NO | `header.php`, `footer.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/section-title.php`, `template-parts/components/venue-popout.php`, `template-parts/components/venue-card.php`, `template-parts/components/inline-link-grid.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Wedding Planning | NO | NO | `header.php`, `footer.php`, `template-parts/planning/planning-hero.php`, `template-parts/planning/planning-intro.php`, `template-parts/planning/planning-checklist.php`, `template-parts/components/feature-block.php`, `template-parts/planning/planning-vendor-organizer.php`, `template-parts/planning/planning-wedding-website.php`, `template-parts/planning/planning-secondary-intro.php`, `template-parts/components/section-title.php`, `template-parts/planning/planning-tool-cards.php`, `template-parts/components/tool-card-row.php`, `template-parts/planning/planning-detailed-copy.php`, `template-parts/planning/planning-faq.php`, `template-parts/components/faq-section.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Default Page | NO | NO | `header.php`, `footer.php`, `template-parts/planning/planning-child-page.php`, `template-parts/planning/planning-hero.php`, `template-parts/components/section-title.php`, `template-parts/components/feature-block.php`, `template-parts/components/tool-card-row.php`, `template-parts/components/faq-section.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Search Results | NO | NO | `header.php`, `footer.php`, `searchform.php`, `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/post-card.php`, `template-parts/components/pagination.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Single Changelog | YES | NO | `header.php`, `footer.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Single Couple | YES | NO | `header.php`, `footer.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Single Real Wedding | YES | NO | `header.php`, `footer.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Single Team | YES | NO | `header.php`, `footer.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Single Vendor | NO | NO | `header.php`, `footer.php`, `template-parts/components/breadcrumbs.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Single Venue | NO | NO | `header.php`, `footer.php`, `template-parts/components/breadcrumbs.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Single Website | YES | NO | `header.php`, `footer.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Single Post | YES | NO | `header.php`, `footer.php`, `searchform.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Taxonomy Real Wedding Category | NO | NO | `header.php`, `footer.php`, `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Taxonomy Real Wedding Color | NO | NO | `header.php`, `footer.php`, `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Taxonomy Real Wedding Community | NO | NO | `header.php`, `footer.php`, `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Taxonomy Real Wedding Location | NO | NO | `header.php`, `footer.php`, `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Taxonomy Real Wedding Season | NO | NO | `header.php`, `footer.php`, `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Taxonomy Real Wedding Space Preferance | NO | NO | `header.php`, `footer.php`, `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Taxonomy Real Wedding Style | NO | NO | `header.php`, `footer.php`, `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Taxonomy Real Wedding Tag | NO | NO | `header.php`, `footer.php`, `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/real-wedding-card.php`, `template-parts/components/pagination.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Taxonomy Vendor Category | NO | NO | `header.php`, `footer.php`, `template-parts/vendors/listing.php`, `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/section-title.php`, `template-parts/components/inline-link-grid.php`, `template-parts/components/vendor-card.php`, `template-parts/components/pagination.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Taxonomy Venue Location | NO | NO | `header.php`, `footer.php`, `template-parts/venues/listing.php`, `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/section-title.php`, `template-parts/components/venue-card.php`, `template-parts/components/pagination.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Taxonomy Venue Type | NO | NO | `header.php`, `footer.php`, `template-parts/venues/listing.php`, `template-parts/components/page-header.php`, `template-parts/components/breadcrumbs.php`, `template-parts/components/section-title.php`, `template-parts/components/venue-card.php`, `template-parts/components/pagination.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Couple Dashboard | YES | NO | `header.php`, `footer.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Front Page (User Template) | NO | NO | `header.php`, `footer.php`, `front-page.php`, `template-parts/components/section-title.php`, `template-parts/components/inline-link-grid.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Vendor Dashboard | YES | NO | `header.php`, `footer.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |
| Venue Dashboard | YES | NO | `header.php`, `footer.php`, `assets/library/sdwd-icons/style.css`, `assets/css/foundation.css`, `assets/css/components.css`, `assets/css/layout.css`, `assets/css/pages/modals.css` when `! is_user_logged_in()` |

## Compliance Audit

| Requirement | PASS / FAIL |
| --- | --- |
| Read `AGENTS.md` completely before proceeding | PASS |
| Read `PROJECT.md` before proceeding | PASS |
| Scan only the active theme directory and `functions.php` for enqueue logic | PASS |
| List every current page template, including `front-page.php`, `page-*`, `archive-*`, `single-*`, `taxonomy-*`, and custom templates | PASS |
| List direct `get_template_part()`, `include`, `require`, and `locate_template()` usage for each page template | PASS |
| List nested template-part and component usage for each template part | PASS |
| Map CSS per page, including global CSS, page CSS, and conditional enqueue rules | PASS |
| Detect duplicated template-part/component/markup patterns from current theme usage | PASS |
| Produce a final page map for each page | PASS |
| Produce a simplification map for each page based only on current usage | PASS |
| Create `/Documentation/page-template-css-map.md` | PASS |
| Do not modify theme code or restructure files | PASS |
| Do not use heuristic language | PASS |
| Do not skip files in the requested scan scope | PASS |

Deviations: None.
