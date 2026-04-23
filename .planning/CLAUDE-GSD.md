# CLAUDE-GSD.md — GSD workflow guide for SDWD launch

Sidecar to the root `CLAUDE.md`. The root file owns the hard rules and project rules. This file owns the GSD planning/execution workflow.

> **Precedence:** Root `CLAUDE.md` ALWAYS wins on conflict. If the GSD workflow asks you to do something that would violate a hard rule in `CLAUDE.md` (e.g., add a Bootstrap class, add a 4th file to `inc/`, modify the home page outside the `P1-FIX-02` scoped unlock), STOP and escalate.

---

## What this project is

San Diego Wedding Directory — **launch milestone in progress**. The working theme (`sandiegoweddingdirectory`) + `sdwd-core` + `sdwd-couple` plugin stack is the first public cut after multiple prior attempts. There is no "v1" or "v2" — this is THE theme, THE project, THE working codebase.

- Core value: ship the launch milestone cleanly; pass all 4 hard launch gates (couple AJAX works, `/{location}/{type}/` SEO pages return 200, dashboards E2E for all 3 roles, transactional email delivers).
- Work pattern: founder works in bursts across multi-day gaps between other income work. Cold-resumability via GSD is the primary adoption motivation — keep STATE.md current at every transition.

## Core Principles

- **Legacy code is a recovery source, not a scrap heap.** Prior-attempt folders under `~/Documents/Development/WebDevelopment/` contain fully-built pages and CSS that were shipped in earlier attempts. Port + strip + tokenize beats rebuild-from-scratch. `P3-DISCO-01` (template port-source discovery) and `P2-DISCO-01` (seating-chart plugin hunt) fingerprint every candidate so the founder picks per-target with evidence on the table.
- **weddingwire.com is the canonical UI layout reference.** Every page type copies weddingwire block-for-block until parity, then the founder modifies from there. See `.planning/codebase/DESIGN-REFERENCES.md`.
- **Banned dependencies are non-negotiable** (root `CLAUDE.md`): Bootstrap, Font Awesome, jQuery, Google Fonts, page builders, shortcake, ACF-layout-for-front-end, Elementor. Legacy ports MUST strip these on import.

---

## Planning artifacts (read in this order)

Before any non-trivial work, read:

1. **Root `CLAUDE.md`** — hard rules, banned deps, file boundaries, home-page lock (scoped unlock for `P1-FIX-02`), git safety. Violation is session-ending.
2. **Root `PROJECT.md`** — task tracker. Authoritative for launch-era outline.
3. **`.planning/PROJECT.md`** — GSD project context (milestone scope, launch gates, key decisions, out-of-scope, evolution rules, Core Principles).
4. **`.planning/REQUIREMENTS.md`** — 75 phase-prefixed launch REQ-IDs (P1-*, P2-*, P3-*, P4-*, P5-*) + Post-Launch Backlog (`PL-*`) + out-of-scope + traceability.
5. **`.planning/ROADMAP.md`** — 5-phase breakdown with goals, success criteria, requirement mappings, launch-gate coverage.
6. **`.planning/STATE.md`** — current position, next action, progress metrics. Updated at every phase transition.
7. **`.planning/codebase/DESIGN-REFERENCES.md`** — weddingwire.com canonical UI reference + WeddingDir themeforest port targets + strip-third-party rules.
8. **`.planning/codebase/`** — read-only codebase map (ARCHITECTURE, CONCERNS, CONVENTIONS, INTEGRATIONS, STACK, STRUCTURE, TESTING). Dated 2026-04-23; scheduled for regeneration after Phase 1 text-domain sweep lands. CONCERNS.md is the primary source of Phase 1 + Phase 2 task detail.

---

## GSD config for this project

From `.planning/config.json`:
- **Mode:** YOLO (auto-approve, just execute)
- **Granularity:** Coarse (5 phases, 1-3 plans each)
- **Parallelization:** Parallel within phase, sequential across phases
- **Git tracking:** Yes — planning docs committed
- **AI models:** Balanced (Sonnet for planner, executor, verifier, researcher, synthesizer, roadmapper)
- **Workflow agents:** Verifier = on, Research-before-plan = off, Plan-check = off, Nyquist = off

---

## Phase sequence (read-only — 5 phases, strictly sequential)

| # | Phase | Launch Gates | Requirements |
|---|-------|--------------|--------------|
| 1 | Close in-progress + cleanup | LG-03 (partial via P1-FIX-01) | 11 (P1-*) |
| 2 | Plugin closeout + parity + security | LG-01 | 19 (P2-*) |
| 3 | Missing + ported templates | — | 19 (P3-*) |
| 4 | Combo venue SEO pages | LG-02 | 5 (P4-*) |
| 5 | Launch prep | LG-03, LG-04 | 21 (P5-*) |

---

## How to work under GSD (quick reference)

### Resuming cold (the main use case)

Multi-day gap? New session?

1. Read root `CLAUDE.md` + root `PROJECT.md` + `.planning/STATE.md`.
2. `/gsd-resume-work` — identifies current phase/plan and presents the next action.

### Starting a phase

```
/gsd-plan-phase {N}
```

Decomposes Phase N into executable plans. For coarse granularity, expect 1-3 plans per phase.

### Executing a plan

```
/gsd-execute-phase {N}
```

Runs the planned work. Verifier is enabled — expect a verification pass at phase end that confirms success criteria are met.

### Transitioning phases

```
/gsd-transition
```

Updates:
- `ROADMAP.md` progress table (phase marked complete)
- `STATE.md` (current phase, progress bar, metrics)
- `REQUIREMENTS.md` traceability (Pending → Complete for finished REQ-IDs)
- `.planning/PROJECT.md` Validated/Active (completed items move to Validated with phase reference)
- Appends any new Key Decisions discovered during execution

**Keeping these current is non-negotiable — it's the reason GSD is here.**

### Completing the milestone

```
/gsd-complete-milestone
```

After Phase 5 verification passes. Triggers full PROJECT.md review, Out-of-Scope audit, Context refresh.

### Debugging

```
/gsd-debug
```

Scientific-method loop for stubborn bugs. Preferred over ad-hoc fixing when a bug blocks a phase's success criteria.

### Everything else

- `/gsd-progress` — snapshot of where things stand
- `/gsd-health` — sanity check on GSD artifacts (STATE/REQ/ROADMAP consistency)
- `/gsd-help` — command reference
- `/gsd-settings` — change mode/granularity/workflow agents mid-project

---

## Agent routing (what spawns what)

- **gsd-planner** — decomposes a phase into plans with tasks (via `/gsd-plan-phase`)
- **gsd-executor** — executes a plan, commits atomically (via `/gsd-execute-phase`)
- **gsd-verifier** — goal-backward verification of phase success criteria (auto-spawned after execution)
- **gsd-debugger** — scientific-method debug loop (via `/gsd-debug`)

All agents use Sonnet by default (balanced profile).

---

## Project-specific gotchas for downstream agents

### Design parity reference order (when deciding how something should look)

1. **weddingwire.com** — canonical layout reference for every page type. Copy block-for-block until parity, then founder modifies from there.
2. **Existing tokens in `foundation.css :root`** — colors, typography, spacing. Every design decision resolves to existing tokens before inventing new ones.
3. **If a decision requires invention** (no weddingwire equivalent, no wireframe in `Documentation/screenshots/`, no existing token covers it), **STOP and escalate** — do not invent.

### WordPress-specific

- **WP core is NOT in the repo.** Only `wp-content/` is bind-mounted. Agents that try to edit `wp-admin/*` or `wp-includes/*` are trying to modify code inside a Docker volume, not the repo — changes won't persist across container rebuilds.
- **CPT/taxonomy registration** is plugin-owned (`sdwd-core`). Agents must not add CPT registration to the theme.
- **Meta keys** follow `sdwd_*` prefix convention (not `sdweddingdirectory_*` — that's the retired legacy plugin).
- **Text-domain** standardizes to `sandiegoweddingdirectory` (Phase 1 `P1-CLEAN-04` normalizes across theme AND plugin files). Don't mint new translations under `sdweddingdirectory-v2` or `sdweddingdirectory`. Until the sweep lands, these legacy literal strings survive in code (and will appear in backticks in planning docs) — the sweep eliminates them.
- **Nonce pattern** — per-feature scoped (`sdwd_auth_nonce`, `sdwd_quote_nonce`, `sdwd_review_nonce`, etc.). Generated in theme where form renders; verified in plugin handler.

### CSS / front-end

- Tokens live in `foundation.css :root`. No raw hex anywhere in component CSS.
- Single-class selectors, max one level of nesting. BEM-lite.
- Page-specific CSS in `assets/css/pages/` — loaded conditionally via `functions.php`, gated by `is_front_page()`, `is_singular([...])`, etc.
- Icon font: `sdwd-icons` at `assets/library/sdwd-icons/`. Use `icon-{name}` classes. No Font Awesome.

### Port sources — Legacy is a RECOVERY SOURCE

**Principle:** Many pages, CSS files, and plugins have been fully built in prior attempts. Port + strip + tokenize beats rebuild-from-scratch. `P3-DISCO-01` and `P2-DISCO-01` fingerprint every candidate before the founder picks a source per target.

Do NOT pre-commit to a single port-source folder for Phase 3. `P3-DISCO-01` is a fingerprinting task that produces a comparison table; founder picks per-page at execution time.

**SDWD-era candidates (`~/Documents/Development/WebDevelopment/`):**
- `legacy-sdweddingdirectory/`
- `SDWeddingDirectory BBEdit/`
- `sdweddingdirectory-contaminated/`
- `sdweddingdirectory-final-backup/`
- `sdweddingdirectory-v2-snapshot/` (folder name is a literal path — the "v2" in the folder name is historical and does not imply the working project is "v2"; it is not)
- `wp-content.zip`

**WeddingDir themeforest candidates:**
- `themeforest-Os2C2dOt-weddingdir-directory-listing-wordpress-theme/` (contains `Untouched Original Theme Source WeddingDir/` and `weddingdir-child/`)
- `WeddingDIr/`
- `ThemeFilesModified/` (contains `weddingdir/` + `weddingdir-child/`)

**Seating-chart hunt targets (for `P2-DISCO-01`, additive to the above):**
- `sdwd-ui/`
- `ww_html_clone/`
- `backupwp/`
- `RECOVERY/`
- Any folder with `seating` / `chart` / `layout` / `table-plan` in filenames

**Strip-on-import rules (every port):**
- No Bootstrap classes
- No Font Awesome markup
- No jQuery
- No Google Fonts
- No shortcake / ACF layout / Elementor
- Tokenize all colors/spacing/typography via `foundation.css :root`

### Dev environment accounts (PRESERVE until Phase 5 LG-03 clears)

Founder runs a **local-only Docker dev environment** with weak throwaway credentials for rapid cross-role QA while dashboards are still in active iteration:

| Username | Password | Role |
|----------|----------|------|
| `couple` | `couple` | couple |
| `vendor` | `vendor` | vendor |
| `venue`  | `venue`  | venue  |

**These accounts MUST continue to log in** until Phase 5's dashboard E2E QA (LG-03) signs off. Site is not going online soon; dashboard iteration will continue for weeks.

Phase 2 security work (`P2-SEC-01` ≥8-char minimum, `P2-SEC-02` current-password verification, `P2-SEC-04` rate limiting) would lock out these accounts as written. Implementation MUST gate enforcement on an `SDWD_DEV_MODE` constant so dev accounts survive locally while production behavior is unchanged.

**Pattern** (WordPress convention — `WP_DEBUG`, `WP_ENV`, `WP_LOCAL_DEV` are analogous):

```php
// sdwd-core/includes/auth.php and any other security enforcement site
if ( ! defined( 'SDWD_DEV_MODE' ) || ! SDWD_DEV_MODE ) {
    // enforce strength / current-password / rate-limit
}
```

- **Local dev** `wp-config.php` declares `define( 'SDWD_DEV_MODE', true );` — bypasses enforcement.
- **Staging + production** `wp-config.php` does NOT define it — enforcement is active.

**Launch gate interaction (Phase 5 — LG-03 clearance):**
- `P5-DASH-03` verification MUST confirm `SDWD_DEV_MODE` is NOT defined in the production `wp-config.php` before LG-03 can be marked green.
- `P5-QA-05` codebase-audit MUST grep for `SDWD_DEV_MODE` to catch any code paths that forgot to gate on it.

### Locked / banned

- `front-page.php` + `assets/css/pages/home.css` — LOCKED **except for the scope of `P1-FIX-02`** (category-search mega-menu dropdown fix). Re-locks on completion. Mirror of root `CLAUDE.md` §Locked Pages.
- `inc/` — 3 files total (`sd-mega-navwalker.php`, `vendors.php`, `venues.php`). Do not add a 4th.
- Bootstrap, Font Awesome, jQuery, Google Fonts, page builders, shortcake, ACF layout, Elementor — BANNED. Non-negotiable.
- `!important`, raw hex, inline styles (except dynamic PHP bg images), utility classes — BANNED.

---

## When in doubt

- Root `CLAUDE.md` wins on conflict.
- CONCERNS.md is the definitive source of bug reports — if a task sounds like "fix X", cross-reference CONCERNS for exact file + line number.
- If the next action isn't obvious: `/gsd-resume-work`.

---

*Sidecar initialized: 2026-04-22*
*Sidecar re-synthesized: 2026-04-22 (terminology reset + legacy-recovery principle)*
