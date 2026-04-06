# Component Index — SDWeddingDirectory

Every reusable UI component in the theme. If a visual element appears on 2+ page types, it must be in this index and built as a template part. No duplicating markup across templates.

---

## How components work

Components live in `template-parts/components/` and are called via:

```php
get_template_part( 'template-parts/components/vendor-card', null, $args );
```

The `$args` array is accessible inside the component as `$args`. Components must not depend on globals — all data comes through `$args`.

Component styles live in `assets/css/components.css` unless the component is page-specific (rare).

---

## Section Title

| Field | Value |
|-------|-------|
| **File** | `template-parts/components/section-title.php` |
| **Purpose** | Heading + optional subtext, centered or left-aligned. Most common component on the site. |
| **Class** | `.section-title`, `.section-title__heading`, `.section-title__desc` |
| **Modifier** | `.section-title--center` (centered), default is left-aligned |
| **Styles** | `components.css` |
| **Phase** | R2 |

**Inputs (`$args`):**

| Key | Type | Required | Description |
|-----|------|----------|-------------|
| `heading` | string | yes | Heading text (rendered as `<h2>`) |
| `desc` | string | no | Subtext paragraph below heading |
| `align` | string | no | `'center'` or `'left'` (default: `'left'`) |
| `tag` | string | no | HTML tag override (default: `'h2'`) |

**Used on:** Homepage (×8), planning parent, planning child, about, archives, venues landing

---

## Vendor Card

| Field | Value |
|-------|-------|
| **File** | `template-parts/components/vendor-card.php` |
| **Purpose** | Card displaying a vendor: image, name, category, link. |
| **Class** | `.card`, `.card__image`, `.card__body`, `.card__title` |
| **Styles** | `components.css` |
| **Phase** | R2 |

**Inputs (`$args`):**

| Key | Type | Required | Description |
|-----|------|----------|-------------|
| `post_id` | int | yes | Vendor post ID |
| `image_size` | string | no | WordPress image size (default: `'medium'`) |

Data (name, category, permalink, featured image) is derived from `post_id` inside the component.

**Used on:** Homepage "San Diego Wedding Vendors", `archive-vendor.php`, `taxonomy-vendor-category.php`

---

## Venue Card

| Field | Value |
|-------|-------|
| **File** | `template-parts/components/venue-card.php` |
| **Purpose** | Card displaying a venue: image, name, type, location, link. |
| **Class** | `.card`, `.card__image`, `.card__body`, `.card__title`, `.card__meta` |
| **Styles** | `components.css` |
| **Phase** | R2 |

**Inputs (`$args`):**

| Key | Type | Required | Description |
|-----|------|----------|-------------|
| `post_id` | int | yes | Venue post ID |
| `image_size` | string | no | WordPress image size (default: `'medium'`) |

**Used on:** Homepage "Find Your Location", `archive-venue.php`, `taxonomy-venue-type.php`, `taxonomy-venue-location.php`, venues landing page card rows

---

## Real Wedding Card

| Field | Value |
|-------|-------|
| **File** | `template-parts/components/real-wedding-card.php` |
| **Purpose** | Card displaying a real wedding: image, couple names, date, link. |
| **Class** | `.card`, `.card__image`, `.card__body`, `.card__title`, `.card__date` |
| **Styles** | `components.css` |
| **Phase** | R2 |

**Inputs (`$args`):**

| Key | Type | Required | Description |
|-----|------|----------|-------------|
| `post_id` | int | yes | Real wedding post ID |
| `image_size` | string | no | WordPress image size (default: `'medium'`) |

**Used on:** Homepage "Real Weddings", `archive-real-wedding.php`, all `taxonomy-real-wedding-*.php`, single real wedding "Similar Real Weddings"

---

## Post Card (Blog/Article)

| Field | Value |
|-------|-------|
| **File** | `template-parts/components/post-card.php` |
| **Purpose** | Blog article card: image, category badge, title, link. |
| **Class** | `.post-card`, `.post-card__image`, `.post-card__category`, `.post-card__title` |
| **Styles** | `components.css` |
| **Phase** | R2 |

**Inputs (`$args`):**

| Key | Type | Required | Description |
|-----|------|----------|-------------|
| `post_id` | int | yes | Post ID |
| `image_size` | string | no | WordPress image size (default: `'medium'`) |
| `show_category` | bool | no | Show category badge (default: `true`) |

**Used on:** Homepage "Inspiration", `archive.php`, `page-inspiration.php`, `search.php`, `category.php`

---

## Inline Link Grid

| Field | Value |
|-------|-------|
| **File** | `template-parts/components/inline-link-grid.php` |
| **Purpose** | Multi-column grid of text links. Used for venue type links, vendor category links, and city links. |
| **Class** | `.link-grid`, `.link-grid__row`, `.link-grid__item` |
| **Styles** | `components.css` |
| **Phase** | R2 |

**Inputs (`$args`):**

| Key | Type | Required | Description |
|-----|------|----------|-------------|
| `heading` | string | no | Section heading above the grid |
| `rows` | array | yes | Array of rows. Each row is an array of `['label' => '', 'url' => '']` |
| `columns` | int | no | Number of columns (default: `4`) |

**Used on:** Homepage "Search by Category" (venue types sub-section), Homepage "Search by Category" (vendor categories sub-section), Homepage "Browse Wedding Venues by City", Venues landing page (same 3 sections duplicated)

**Critical:** The homepage and venues landing page use the same data for these grids. The component must accept the data as `$args` so both pages can call it with the same arrays.

---

## Pagination

| Field | Value |
|-------|-------|
| **File** | `template-parts/components/pagination.php` |
| **Purpose** | Numbered page navigation for archives. |
| **Class** | `.pagination`, `.pagination__link`, `.pagination__current` |
| **Styles** | `components.css` |
| **Phase** | R2 |

**Inputs (`$args`):**

| Key | Type | Required | Description |
|-----|------|----------|-------------|
| `query` | WP_Query | no | Custom query object (default: global `$wp_query`) |

**Used on:** All archive templates, `search.php`, `page-inspiration.php`, `category.php`

---

## Page Header Banner

| Field | Value |
|-------|-------|
| **File** | `template-parts/components/page-header.php` |
| **Purpose** | Archive or taxonomy page header with title and optional description/breadcrumbs. |
| **Class** | `.page-header`, `.page-header__title`, `.page-header__desc` |
| **Styles** | `components.css` |
| **Phase** | R3 |

**Inputs (`$args`):**

| Key | Type | Required | Description |
|-----|------|----------|-------------|
| `title` | string | yes | Page/archive title |
| `desc` | string | no | Description or subtitle |
| `breadcrumbs` | bool | no | Show breadcrumbs (default: `false`) |

**Used on:** `archive-vendor.php`, `archive-venue.php`, `archive-real-wedding.php`, taxonomy archives, real wedding detail

---

## Breadcrumbs

| Field | Value |
|-------|-------|
| **File** | `template-parts/components/breadcrumbs.php` |
| **Purpose** | Breadcrumb navigation trail (Home > Section > Page). |
| **Class** | `.breadcrumb`, `.breadcrumb__item`, `.breadcrumb__link`, `.breadcrumb__separator` |
| **Styles** | `components.css` |
| **Phase** | R3 |

**Inputs (`$args`):**

| Key | Type | Required | Description |
|-----|------|----------|-------------|
| `items` | array | yes | Array of `['label' => '', 'url' => '']`. Last item has no URL (current page). |

**Used on:** Planning hero, blog single topbar, vendor profile, venue profile, page headers

---

## FAQ Accordion

| Field | Value |
|-------|-------|
| **File** | `template-parts/components/faq-accordion.php` |
| **Purpose** | Styled expand/collapse accordion for FAQ items. |
| **Class** | `.faq-accordion`, `.faq-accordion__item`, `.faq-accordion__question`, `.faq-accordion__answer` |
| **Styles** | `components.css` |
| **Phase** | R4 |

**Inputs (`$args`):**

| Key | Type | Required | Description |
|-----|------|----------|-------------|
| `items` | array | yes | Array of `['question' => '', 'answer' => '']` |
| `allow_multiple` | bool | no | Allow multiple items open at once (default: `false`) |

**Used on:** Planning parent FAQ, planning child FAQ, FAQs page (×4 tabs), vendor profile FAQ, venue profile FAQ

---

## Feature Block (Alternating)

| Field | Value |
|-------|-------|
| **File** | `template-parts/components/feature-block.php` |
| **Purpose** | Full-width split section: text+CTA on one side, image on the other. Alternates left/right. |
| **Class** | `.feature-block`, `.feature-block--reversed`, `.feature-block__content`, `.feature-block__image`, `.feature-block__title`, `.feature-block__desc`, `.feature-block__features`, `.feature-block__cta` |
| **Styles** | `components.css` |
| **Phase** | R5 |

**Inputs (`$args`):**

| Key | Type | Required | Description |
|-----|------|----------|-------------|
| `heading` | string | yes | Section heading |
| `desc` | string | yes | Description text |
| `cta_text` | string | no | CTA button text |
| `cta_url` | string | no | CTA button URL |
| `image_url` | string | yes | Image URL |
| `image_width` | int | no | Image display width |
| `image_height` | int | no | Image display height |
| `reversed` | bool | no | Flip layout: image left, text right (default: `false` = text left, image right) |
| `features` | array | no | Array of `['icon' => '', 'text' => '']` bullet feature items |
| `testimonial` | array | no | `['quote' => '', 'author' => '']` testimonial card |

**Used on:** Planning parent (×3: checklist, vendor organizer, website), planning child (×3), venues landing pop-out sections (×5)

---

## Tool Card Row

| Field | Value |
|-------|-------|
| **File** | `template-parts/components/tool-card-row.php` |
| **Purpose** | Grid of cards with icon, heading, subtext, and optional CTA link. Renamed from `icon-card-row` to avoid icomoon `[class^="icon-"]` selector collision. |
| **Class** | `.tool-card-row`, `.tool-card`, `.tool-card__icon`, `.tool-card__title`, `.tool-card__desc`, `.tool-card__cta` |
| **Styles** | `components.css` |
| **Phase** | R5 |

**Inputs (`$args`):**

| Key | Type | Required | Description |
|-----|------|----------|-------------|
| `heading` | string | no | Section heading above cards |
| `desc` | string | no | Section subtext |
| `cards` | array | yes | Array of `['icon_url' => '', 'title' => '', 'desc' => '', 'cta_label' => '', 'cta_url' => '']` |
| `columns` | int | no | Number of columns (default: `3`) |

**Used on:** Planning parent "3-Card Tool Row", planning child cross-link section (5 cards)

---

## FAQ Section

| Field | Value |
|-------|-------|
| **File** | `template-parts/components/faq-section.php` |
| **Purpose** | Reusable FAQ accordion with heading, description, and expand/collapse items. Wraps `section-title` + `faq-accordion` pattern. |
| **Class** | `.planning-faq`, `.planning-faq__question`, `.planning-faq__answer`, `.planning-faq__toggle` |
| **Styles** | `pages/planning.css` |
| **Phase** | R5 |

**Inputs (`$args`):**

| Key | Type | Required | Description |
|-----|------|----------|-------------|
| `heading` | string | yes | Section heading |
| `desc` | string | no | Subtext below heading |
| `align` | string | no | `'center'` or `'left'` (default: `'center'`) |
| `id_prefix` | string | no | Prefix for answer element IDs (default: `'faq'`) |
| `open` | int | no | 0-based index of default-open item (default: `1`) |
| `items` | array | yes | Array of `['question' => '', 'answer' => '']` |

**Used on:** Planning parent page, planning child pages

---

## Policy Sub-Nav

| Field | Value |
|-------|-------|
| **File** | `template-parts/components/policy-subnav.php` |
| **Purpose** | Horizontal navigation links for FAQs, Privacy Policy, Terms of Use. |
| **Class** | `.policy-subnav`, `.policy-subnav__link`, `.policy-subnav__link--active` |
| **Styles** | `components.css` |
| **Phase** | R5 |

**Inputs (`$args`):**

| Key | Type | Required | Description |
|-----|------|----------|-------------|
| `active` | string | yes | Slug of currently active page (`'faqs'`, `'privacy-policy'`, `'terms-of-use'`) |

**Used on:** `page-faqs.php`, `page-policy.php`

---

## Contact Details Grid

| Field | Value |
|-------|-------|
| **File** | `template-parts/components/contact-details.php` |
| **Purpose** | 3-column grid with phone, address, and inquiry — each with icon. |
| **Class** | `.contact-details`, `.contact-details__item`, `.contact-details__icon`, `.contact-details__label`, `.contact-details__value` |
| **Styles** | `components.css` |
| **Phase** | R5 |

**Inputs (`$args`):** None. Data is hard-coded in the component (phone number, address, email are site-level constants).

**Used on:** `page-contact.php`, `page-faqs.php`

---

## Why Use SDWD

| Field | Value |
|-------|-------|
| **File** | `template-parts/why-use-sdwd.php` |
| **Purpose** | Value proposition section. Auto-detects vendor vs venue context via `get_post_type()` and swaps copy. |
| **Class** | `.why-sdwd`, `.why-sdwd__title`, `.why-sdwd__list`, `.why-sdwd__item` |
| **Styles** | `components.css` |
| **Phase** | R4 |

**Inputs (`$args`):** None. Self-detecting via `get_post_type()`.

**Used on:** `single-vendor.php` (via plugin `get_template_part()` call), `single-venue.php` (via plugin `get_template_part()` call)

**Note:** This component is NOT in `template-parts/components/` — it sits at `template-parts/why-use-sdwd.php` because the plugin calls it with that exact path.

---

## Buttons (CSS-only component)

Not a template part — buttons are styled via CSS classes applied directly in markup.

| Class | Purpose |
|-------|---------|
| `.btn` | Base button style |
| `.btn--primary` | Teal accent background (`--sdwd-accent`), white text |
| `.btn--outline` | Transparent background, accent border, accent text. Fills on hover. |
| `.btn--cta` | Red-orange background (`--sdwd-cta`), white text |
| `.btn--small` | Smaller padding and font size |
| `.btn--dark` | Dark background (`--sdwd-dark`), white text |

**Styles:** `foundation.css` (base), `components.css` (variants)

**Specs:**
- Border radius: `4px`
- Padding: `13px 30px`
- Font: `15px`, weight `800`
- Small: `8px 18px`, `13px`
- Hover: `translateY(-1px)`, 0.2s ease

---

## Grid (CSS-only component)

Not a template part — grids are CSS classes.

| Class | Purpose |
|-------|---------|
| `.grid` | CSS Grid parent |
| `.grid--2col` | 2-column grid |
| `.grid--3col` | 3-column grid |
| `.grid--4col` | 4-column grid |
| `.container` | Max-width centered wrapper (1320px) |

**Styles:** `layout.css`

All grids collapse responsively (4→3→2→1 columns at breakpoints).
