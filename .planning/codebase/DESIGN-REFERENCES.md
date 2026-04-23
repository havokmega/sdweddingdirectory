# Design References

**Purpose:** Canonical UI references that downstream agents (planner, executor, UI auditor, verifier) consult when deciding how a page should look.

**Precedence:** This doc sits next to the codebase map, not above it. Root `CLAUDE.md` still wins on conflict (banned deps, locked pages, token-only CSS). This doc tells you *which layout to mirror*; `CLAUDE.md` tells you *what you may not use* to build it.

---

## 1. Canonical UI Reference — `weddingwire.com`

Every page type copies **weddingwire.com** block-for-block until parity. The founder modifies from there.

**Why weddingwire:** Established, well-tested wedding-directory UX. Users coming from weddingwire have muscle memory. Founder has settled on this as the reference after multiple prior attempts so the team stops reinventing layouts.

**How to use:**
1. Identify the target page type (archive, single, landing, dashboard, etc.).
2. Find the equivalent page on weddingwire.com (e.g., `weddingwire.com/biz/<name>` for single vendor, `weddingwire.com/wedding-venues/<city>` for venue archive).
3. Copy the block structure, the information hierarchy, the interaction model, and the sectional layout.
4. Translate every color / spacing / typography value to existing tokens in `foundation.css :root` — **no raw hex, no `!important`, no inline styles**.
5. Once structural parity is reached, the founder reviews and modifies.

**Precedence when references disagree:**
- `foundation.css :root` token > weddingwire visual value (tokens own colors/spacing/type)
- Wireframe in `Documentation/screenshots/` > weddingwire layout (if one exists for that target)
- `CLAUDE.md` banned-deps rule > everything (no Bootstrap class survives, even if weddingwire uses one)

---

## 2. Live Port-Target Examples — `weddingdir.net`

The **WeddingDir themeforest theme** (purchased source at `~/Documents/Development/WebDevelopment/themeforest-Os2C2dOt-weddingdir-directory-listing-wordpress-theme/`) has live working templates for three page types that the launch milestone ports. The live demo URLs below show the visual + functional target:

| Page type | Live reference URL | GSD requirement | Port source folder |
|-----------|--------------------|-----------------|--------------------|
| Single Real Wedding | `https://weddingdir.net/real-wedding/ratna-jacob/` | `P3-PORT-07` | `themeforest-Os2C2dOt-...`, `WeddingDIr/`, `ThemeFilesModified/weddingdir/` |
| Single Wedding Website | `https://weddingdir.net/website/hitesh-and-priyanka/` | `P3-PORT-06` | same candidates as above |
| Single Blog Post | `https://weddingdir.net/what-does-a-wedding-planner-actually-do/` | `P3-PORT-04` | same candidates as above |

**How to use:** Fetch the live demo page, study the block structure and visual design, then port the markup + approved CSS from the themeforest source folder. The live demo shows the *target*; the source folder provides the *starting point*.

**Strip on import (mandatory) — every port:**
- ❌ No Bootstrap classes (`row`, `col-md-*`, `container-fluid`, `d-flex`, any utility)
- ❌ No Font Awesome (`fa`, `fa-*`)
- ❌ No jQuery (use vanilla JS or delete the behavior)
- ❌ No Google Fonts (fonts are local WOFF2 in `assets/fonts/`)
- ❌ No shortcake (`[shortcode]` UI layout)
- ❌ No ACF layout-for-front-end (field-rendering ACF usage is banned at the layout tier; backend meta storage is fine)
- ❌ No Elementor (`elementor-*`, `e-*`, `ekit-*`)
- ❌ No page builders of any kind
- ❌ No inline styles (except dynamic background images from PHP data)
- ❌ No raw hex colors → tokenize via `foundation.css :root`
- ❌ No `!important`

**Keep on import (approved):**
- ✅ Markup structure and semantic HTML
- ✅ CSS values the founder approves (then tokenize)
- ✅ Image assets (subject to `P5-PERF-01` optimization later)
- ✅ Icon intent (replace FA with `icon-{name}` from `sdwd-icons`)

---

## 3. Port-Source Discovery Flow

Before any port begins, discovery fingerprints every candidate folder:

- **`P2-DISCO-01`** — Seating-chart plugin hunt (before any plugin-port decision in Phase 2)
- **`P3-DISCO-01`** — Template port-source discovery (before any template-port decision in Phase 3)

Both discovery tasks produce a comparison table (candidate × target, with presence / last-modified / line-count / first-30-lines). Founder picks per-target with evidence on the table.

**Rule:** Do not pre-commit to a port source. The fingerprint picks the source.

---

## 4. What this doc is NOT

- Not the final style guide. `page-style-guide.php` (at URL `/style-guide/`) is the living style guide. It will be rewritten in `P5-STYLE-01` to use only approved patterns.
- Not a substitute for `foundation.css :root`. Tokens are authoritative for colors/spacing/typography.
- Not the CSS architecture reference. See `.planning/codebase/ARCHITECTURE.md` and `Documentation/architecture.md` for that.
- Not a CLAUDE.md override. Banned deps stay banned even if weddingwire or weddingdir.net uses them.

---

## Consumed By

- `gsd-planner` — consult when planning any page type to identify the layout reference
- `gsd-executor` — consult during port tasks (`P3-PORT-*`, `P5-DASH-02`) to know what to keep vs. strip
- `gsd-ui-researcher` / `gsd-ui-auditor` — consult when evaluating whether a page "looks right"
- `gsd-verifier` — consult when verifying UI-touched phase success criteria

---

*Created: 2026-04-22 after terminology reset*
