# Phase 1: Close in-progress + cleanup — Discussion Log

> **Audit trail only.** Do not use as input to planning, research, or execution agents.
> Decisions are captured in `01-CONTEXT.md` — this log preserves the alternatives considered.

**Date:** 2026-04-22
**Phase:** 01-close-in-progress-cleanup
**Areas discussed:** Global footer, 404 page, Registration form UX, Cleanup sweep scope

---

## Flow note

A single consolidated gray-areas multi-select was drafted and shown to the user; the user interrupted the AskUserQuestion and responded with a complete pre-formed answer set covering all four areas. No iterative question loop took place — decisions were captured in one written pass. The log below reflects the options that *would* have been presented and the founder's direct answers.

---

## Global footer

**Area:** `P1-BUILD-01`
**Proposed question:** What goes in the footer? (Columns, newsletter opt-in, social icons, site credit row, design reference.)

| Option | Description | Selected |
|--------|-------------|----------|
| Build from scratch per design reference | Ask founder for a Documentation/screenshots reference and build content into the 5 widget areas | |
| Widget-area driven (admin-populated) | Keep 5 widget areas registered; founder wires content via wp-admin Widgets screen | |
| Already built — verify only | Footer landed in a prior session; no planned work in Phase 1 | ✓ |

**Founder's answer:** *Already built.* "Founder built the footer in a prior session. `footer.php` exists (113 lines) and all 5 footer widget areas are registered in `functions.php:63-76`. Mark P1-BUILD-01 as already-landed work and verify visually during phase verification — no new build needed. If the verifier finds gaps, surface them for a touch-up, but don't plan a from-scratch build."

**Captured in CONTEXT.md:** D-01, D-02.

---

## 404 page

**Area:** `P1-BUILD-02`
**Proposed question:** Layout, copy, asset use, button structure, tone.

| Option | Description | Selected |
|--------|-------------|----------|
| Playful / copy-forward | Dominant hero copy, minimal links, whimsical tone | |
| Asset-forward + navigation grid | Real SVG as visual anchor + navigation buttons to primary pages | ✓ |
| Minimal system-style | Plain text + home link, no hero asset | |

**Founder's answer:** *Asset-forward.* Replace placeholder inline SVG with real asset (`assets/images/404-error-page/404_error.svg`). 5 buttons in a right-aligned row, all `.btn--outline` (fix the `Home` button currently being `.btn--primary`). Order Home → Planning Tools → Venues → Vendors → Real Weddings. Keep existing `<h1>Page Not Found</h1>` and description. CSS lives in `assets/css/pages/static.css` (already conditionally enqueued). Token-based `gap` on `.error-404__nav`; no hardcoded spacing.

**Captured in CONTEXT.md:** D-03, D-04, D-05, D-06, D-07.

---

## Registration form UX

**Area:** `P1-FIX-01`
**Proposed question:** Keep silent auto-password generation or surface a real password field? Post-submit behavior? Lockout recovery?

| Option | Description | Selected |
|--------|-------------|----------|
| Keep auto-password (silent) | Preserve current 2-step UX, auto-generated password, AJAX submit, auto-login + redirect to `/couple-dashboard/` | ✓ |
| Add password field | Couple sets their own password during Step 2 | |
| Hybrid (email magic link) | No password at all; registration sends a magic-link email; couple completes via link | |

**Founder's answer:** *Keep silent auto-password* + critical cross-phase constraint: welcome email (LG-04, Phase 5) MUST include either (a) the auto-generated password in plaintext OR (b) a one-click password-set/reset link. Without that, a couple who logs out before setting their own password is locked out with no recovery path. P1-FIX-01 is not *truly complete* until LG-04 ships.

**Captured in CONTEXT.md:** D-08, D-09, D-10, D-11. Cross-phase hook recorded for `P5-EMAIL-02`.

---

## Cleanup sweep scope

**Area:** `P1-CLEAN-04` + `P1-CLEAN-07`
**Proposed question:** Text-domain sweep breadth + which LOW CONCERNS items to bundle into P1-CLEAN-07.

| Option | Description | Selected |
|--------|-------------|----------|
| Narrow sweep (functions.php only) | Fix only the 7 `'sdweddingdirectory-v2'` occurrences in `functions.php`; leave template `'sdweddingdirectory'` strings alone | |
| Full sweep (theme only) | Rename all theme occurrences of both legacy variants to `'sandiegoweddingdirectory'`; leave plugin code untouched | |
| Full sweep (theme + plugins) | Include plugin code in the sweep; phpcs binary check before/after | ✓ |

**Founder's answer:** *Full sweep including plugins.* Normalize ALL text-domain strings to `sandiegoweddingdirectory`: 7 in `functions.php`, ~517 across templates/template-parts/user-template, AND the plugin code (`sdwd-core`, `sdwd-couple`). Verification: phpcs text-domain error count 1,039+ → 0 (binary check). Plugin-file modification is explicitly authorized here (CLAUDE.md §File boundaries ordinarily forbids it).

**P1-CLEAN-07 bundle:**
- **Include:** root-level macOS `Icon\r` artifact deletion; `style.css` `Version:` header bump if stale.
- **Exclude:** `Documentation/TASK_LOG.md` (personal workspace, not theme code); `wp-content/debug.log` truncation (WP writes freely — skip).

**Captured in CONTEXT.md:** D-12 through D-17.

---

## Claude's Discretion

- Commit granularity within Phase 1 plans (prefer atomic per-requirement commits).
- Exact phpcs rule set used for the text-domain sniff, as long as the binary 1,039+ → 0 gate holds.
- Specific token name(s) used for the 404 button-row `gap` (any `foundation.css :root` token is acceptable).
- Whether the text-domain sweep uses `sed` / editor scripts / WP-CLI — mechanical execution choice.

## Deferred Ideas

- `debug.log` truncation deferred — not durable.
- `TASK_LOG.md` population deferred — not theme code.
- Plugin-text-domain architectural question (`sandiegoweddingdirectory` vs. `sdwd-core` / `sdwd-couple` in plugin files) deferred to phpcs resolution at execution; reopenable at plan-phase if it deserves a first-class decision.
- Footer touch-ups — only if phase verification finds gaps.
