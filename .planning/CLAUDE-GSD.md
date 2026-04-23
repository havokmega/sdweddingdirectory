# CLAUDE-GSD.md — GSD workflow guide for SDWD v1-launch

Sidecar to the root `CLAUDE.md`. The root file owns the hard rules and project rules. This file owns the GSD planning/execution workflow.

> **Precedence:** Root `CLAUDE.md` ALWAYS wins on conflict. If GSD workflow asks you to do something that would violate a hard rule in `CLAUDE.md` (e.g., add a Bootstrap class, add a 4th file to `inc/`, modify the home page without approval), STOP and escalate.

---

## What this project is

San Diego Wedding Directory — **v1-launch milestone in progress**. First public cut of the v2 theme (`sandiegoweddingdirectory`) + `sdwd-core` + `sdwd-couple` plugin stack, replacing the v1 directory that's been limping for months.

- Core value: ship v1-launch without regressing the foundation; pass all 4 hard launch gates (couple AJAX works, `/{location}/{type}/` SEO pages return 200, dashboards E2E for all 3 roles, transactional email delivers).
- Work pattern: founder works in bursts across multi-day gaps between other income work. Cold-resumability via GSD is the primary adoption motivation — keep STATE.md current at every transition.

---

## Planning artifacts (read in this order)

Before any non-trivial work, read:

1. **`CLAUDE.md`** (repo root) — hard rules, banned deps, file boundaries, home-page lock, git safety. Violation is session-ending.
2. **`PROJECT.md`** (repo root) — legacy task tracker. Still authoritative for the v1-era outline.
3. **`.planning/PROJECT.md`** — GSD project context (milestone scope, launch gates, key decisions, out-of-scope, evolution rules).
4. **`.planning/REQUIREMENTS.md`** — 70 phase-prefixed v1 REQ-IDs (P1-*, P2-*, P3-*, P4-*, P5-*) + v1.1-deferred + out-of-scope + traceability.
5. **`.planning/ROADMAP.md`** — 5-phase breakdown with goals, success criteria, requirement mappings, launch-gate coverage.
6. **`.planning/STATE.md`** — current position, next action, progress metrics. Updated at every phase transition.
7. **`.planning/codebase/`** — read-only codebase map (ARCHITECTURE, CONCERNS, CONVENTIONS, INTEGRATIONS, STACK, STRUCTURE, TESTING). Dated 2026-04-23. CONCERNS.md is the primary source of Phase 1 + Phase 2 task detail.

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
| 2 | Plugin closeout + parity + security | LG-01 | 18 (P2-*) |
| 3 | Missing + ported templates | — | 13 (P3-*) |
| 4 | Combo venue SEO pages | LG-02 | 5 (P4-*) |
| 5 | Launch prep | LG-03, LG-04 | 23 (P5-*) |

---

## How to work under GSD (quick reference)

### Resuming cold (the main use case)

Multi-day gap? New session?

1. Read `CLAUDE.md` + `PROJECT.md` (root) + `.planning/STATE.md`.
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
- `PROJECT.md` Validated/Active (completed items move to Validated with phase reference)
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

### WordPress-specific

- **WP core is NOT in the repo.** Only `wp-content/` is bind-mounted. Agents that try to edit `wp-admin/*` or `wp-includes/*` are trying to modify code inside a Docker volume, not the repo — changes won't persist across container rebuilds.
- **CPT/taxonomy registration** is plugin-owned (`sdwd-core`). Agents must not add CPT registration to the theme.
- **Meta keys** follow `sdwd_*` prefix convention (not `sdweddingdirectory_*` — that's the legacy/retired plugin).
- **Text-domain** standardizes to `sandiegoweddingdirectory` (Phase 1 normalizes). Don't mint new translations under `sdweddingdirectory-v2` or `sdweddingdirectory`.
- **Nonce pattern** — per-feature scoped (`sdwd_auth_nonce`, `sdwd_quote_nonce`, `sdwd_review_nonce`, etc.). Generated in theme where form renders; verified in plugin handler.

### CSS / front-end

- Tokens live in `foundation.css :root`. No raw hex anywhere in component CSS.
- Single-class selectors, max one level of nesting. BEM-lite.
- Page-specific CSS in `assets/css/pages/` — loaded conditionally via `functions.php`, gated by `is_front_page()`, `is_singular([...])`, etc.
- Icon font: `sdwd-icons` at `assets/library/sdwd-icons/`. Use `icon-{name}` classes. No Font Awesome.

### Port sources (Phase 3 only)

Do NOT pre-commit to a single port-source folder for Phase 3. P3-DISCO-01 is a fingerprinting task that produces a comparison table; founder picks per-page at execution time.

SDWD-v1 candidates: `~/Documents/Development/WebDevelopment/{legacy-sdweddingdirectory,SDWeddingDirectory BBEdit,sdweddingdirectory-contaminated,sdweddingdirectory-final-backup,sdweddingdirectory-v2-snapshot}/`, plus `wp-content.zip`.

WeddingDir themeforest candidates: `~/Documents/Development/WebDevelopment/{themeforest-Os2C2dOt-weddingdir-directory-listing-wordpress-theme,WeddingDIr,ThemeFilesModified}/`.

### Locked / banned

- `front-page.php` + `assets/css/pages/home.css` — LOCKED. No changes without founder approval.
- `inc/` — 3 files total (`sd-mega-navwalker.php`, `vendors.php`, `venues.php`). Do not add a 4th.
- Bootstrap, Font Awesome, jQuery, Google Fonts, page builders — BANNED. Non-negotiable.
- `!important`, raw hex, inline styles (except dynamic PHP bg images), utility classes — BANNED.

---

## When in doubt

- Root `CLAUDE.md` wins on conflict.
- CONCERNS.md is the definitive source of bug reports — if a task sounds like "fix X", cross-reference CONCERNS for exact file + line number.
- If the next action isn't obvious: `/gsd-resume-work`.

---

*Sidecar initialized: 2026-04-22*
