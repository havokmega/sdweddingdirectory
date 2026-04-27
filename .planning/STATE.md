---
gsd_state_version: 1.0
milestone: v1.0
milestone_name: milestone
current_phase: Phase 1 context gathered; plan-phase next
current_plan: None — awaiting `/gsd-plan-phase 1`
status: planning
last_updated: "2026-04-23T08:34:15.530Z"
progress:
  total_phases: 5
  completed_phases: 0
  total_plans: 7
  completed_plans: 0
  percent: 0
---

# Project State: San Diego Wedding Directory — launch

**Last updated:** 2026-04-22
**Session:** Terminology reset + legacy-recovery re-synthesis

---

## Project Reference

**Core value:** Ship the launch milestone cleanly — the working theme + plugin stack must replace the prior public site and pass all four hard launch gates (couple AJAX works, combo SEO pages return 200, all three dashboards are E2E-functional, transactional email delivers).

**Primary files:**

- Task tracker (authoritative): root `PROJECT.md`
- Hard rules: root `CLAUDE.md` — read before any action
- GSD planning: `.planning/PROJECT.md`, `REQUIREMENTS.md`, `ROADMAP.md`, `CLAUDE-GSD.md`
- Codebase map: `.planning/codebase/` (ARCHITECTURE.md, CONCERNS.md, CONVENTIONS.md, INTEGRATIONS.md, STACK.md, STRUCTURE.md, TESTING.md) — will be regenerated after Phase 1 cleanup lands
- Design references: `.planning/codebase/DESIGN-REFERENCES.md` (weddingwire.com canonical UI; WeddingDir themeforest port targets)

---

## Current Position

**Milestone:** launch
**Current phase:** Phase 1 context gathered; plan-phase next
**Current plan:** None — awaiting `/gsd-plan-phase 1`
**Status:** Terminology reset + legacy-recovery re-synthesis complete (2026-04-22); Phase 1 CONTEXT ready for planning

```
Progress: [░░░░░░░░░░░░░░░░░░░░] 0% — 0/5 phases complete
```

**Phase summary:**
| Phase | Name | Status |
|-------|------|--------|
| 1 | Close in-progress + cleanup | Context gathered; ready to plan |
| 2 | Plugin closeout + parity + security | Not started |
| 3 | Missing + ported templates | Not started |
| 4 | Combo venue SEO pages | Not started |
| 5 | Launch prep | Not started |

---

## Next Action

Run `/gsd-plan-phase 1` to decompose Phase 1 into executable plans.

**Phase 1 goal:** All existing in-progress work is finished and the codebase is clean — broken legacy hooks removed, text-domain standardized across theme + plugins, the couple registration form actually creates accounts, and the home-page category-search mega-menu dropdown works.

**Phase 1 requirements (11):** P1-QA-01, P1-BUILD-02, P1-CLEAN-01, P1-CLEAN-02, P1-CLEAN-03, P1-CLEAN-04, P1-CLEAN-05, P1-CLEAN-06, P1-CLEAN-07, P1-FIX-01, P1-FIX-02.

> Note: `P1-BUILD-01` (global footer) was struck during the 2026-04-22 re-synthesis because the founder had already built it in a prior session. It now lives in `.planning/PROJECT.md` §Validated. The Phase 1 CONTEXT retains a verification-only checkpoint so the verifier still eyeballs the footer at phase end.

---

## Launch Gates (hard — site does not ship without all 4 green)

| Gate | Description | Covered By | Status |
|------|-------------|-----------|--------|
| LG-01 | All 5 sdwd-couple AJAX endpoints accept valid nonces | P2-NONCE-01 | Pending |
| LG-02 | /{location}/{type}/ combos return HTTP 200 with real content | P4-TPL-01, P4-ROUTE-01, P4-QA-01 | Pending |
| LG-03 | Dashboards E2E: register → login → save → email for all 3 roles | P1-FIX-01, P5-DASH-01-03 | Pending |
| LG-04 | Transactional email delivers (incl. couple welcome carrying auth material) | P5-EMAIL-01-03 | Pending |

---

## Performance Metrics

- Plans executed: 0
- Plans succeeded: 0
- Plans failed: 0
- Phases complete: 0/5
- Requirements complete: 0/75

---

## Accumulated Context

### Key Decisions (from .planning/PROJECT.md)

| Decision | Rationale |
|----------|-----------|
| Legacy code is a recovery source, not a scrap heap | Prior-attempt folders under `~/Documents/Development/WebDevelopment/` contain fully-built pages and CSS shipped during earlier attempts. Port + strip + tokenize beats rebuild-from-scratch. |
| weddingwire.com is the canonical UI layout reference | Every page type copies weddingwire block-for-block until parity; founder modifies from there. |
| Terminology reset: no version framing | There is ONE theme, ONE project. Code-identifier literals like `'sdweddingdirectory-v2'` survive in backticks until Phase 1 `P1-CLEAN-04` rewrites them. |
| Home page scoped-unlock for `P1-FIX-02` only | Category-search mega-menu dropdown fix. Re-locks on completion. |
| Run full launch milestone through GSD | `STATE.md` + `/gsd-resume-work` solves cold-start problem across multi-day gaps |
| Fold all HIGH security items from CONCERNS.md into Phase 2 | Password-change surface + admin metabox wp_unslash are exploitable in prod; not safe to defer |
| Vendor/venue parity lands in Phase 2 | Central architecture goal of the rebuild; delaying deepens the code fork |
| `P3-DISCO-01` port-source discovery is a dedicated first task of Phase 3 | Recovery mindset makes fingerprinting load-bearing — 6+ SDWD-era snapshots + 3+ WeddingDir variants exist |
| `P2-DISCO-01` seating-chart hunt is the first task of Phase 2 | Custom-built previously; highest risk of permanent loss. If not found → `PL-FEAT-01` opens in Post-Launch Backlog. |
| Text-domain standardizes on `sandiegoweddingdirectory` | Three variants in use today; translation loading silently no-ops on mismatches. Phase 1 normalizes, sweep extends to plugin files. |
| Launch ships 1 wedding-website template | Founder explicit: "get what we already have working first." Expand-to-6 deferred to `PL-THEME-01..05`. |
| URL cleanup / permalink reorg deferred to Post-Launch Backlog | Phase 4 combo-SEO pages cover new URL structure; wholesale reorg risks launch window. |
| No automated test suite in launch | Foundation first; tests post-launch once code stops moving |
| Dev accounts preserved via `SDWD_DEV_MODE` constant | Dashboards still in active iteration. `P2-SEC-01/02/04` enforcement gated. `P5-DASH-03` + `P5-QA-05` verify constant absent from production before LG-03 clears. |

### Hard Constraints (root CLAUDE.md)

- Theme directory: ONLY `wp-content/themes/sandiegoweddingdirectory/` — do not create/rename/move
- Banned: Bootstrap, Font Awesome, jQuery, Google Fonts, external CSS/JS frameworks, page builders, shortcake, ACF layout, Elementor
- Legacy ports MUST strip banned deps on import
- `inc/` capped at 3 files — do not add more
- Home page (`front-page.php` + `home.css`) is LOCKED except for `P1-FIX-02` scoped unlock (re-locks on completion)
- CSS: tokens only (no raw hex, no `!important`, no inline styles except dynamic bg images)
- All output escaped: `esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`
- Assets via `get_template_directory_uri()` only — no hardcoded URLs
- CPT/taxonomy registration: plugin (`sdwd-core`) only — theme never registers

### Brownfield Notes

- Legacy plugins physically removed from `wp-content/plugins/` — their dead hooks cause Phase 1 cleanup items
- Working theme code is partially complete: home page locked (scoped unlock active for `P1-FIX-02`), planning pages at ~90%, venue/vendor shells exist, global nav/footer shipped
- `real-wedding` CPT + 8 taxonomies are referenced in theme but NOT registered in `sdwd-core` — Phase 3 `P3-PORT-07` resolves
- Five `sdwd-couple` AJAX endpoints have no nonce issuers — all couple dashboard features are currently broken (Phase 2 `P2-NONCE-01` fix)
- Vendor claim button on `single-vendor.php` is wired but has no JS handler — currently a dead click (Phase 2 `P2-CLAIM-01` fix)
- Custom vendor calendar previously built in a legacy snapshot — `P3-PORT-14` recovers it
- Custom seating-chart plugin previously built from scratch — `P2-DISCO-01` hunts for it before any rebuild is considered

### Todos / Blockers

- **Regenerate `.planning/codebase/` after Phase 1 text-domain sweep lands.** In-place prose purge is complete; Phase 1 `P1-CLEAN-04` will rewrite the underlying code, so a fresh `/gsd-map-codebase` run after Phase 1 will produce accurate maps.
- None blocking start of Phase 1
- Phase 3: founder must confirm per-page port-source selection after `P3-DISCO-01` fingerprint runs
- Phase 5: `P5-THEME-01/02` (additional wedding-website themes) moved to Post-Launch Backlog (`PL-THEME-01..05`)

---

## Session Continuity

### Cold-start resume

1. Read root `CLAUDE.md` (hard rules) and root `PROJECT.md` (task tracker) — mandatory before any action
2. Read this file (`.planning/STATE.md`) and `.planning/ROADMAP.md` for milestone context
3. Run `/gsd-resume-work` — it will identify current phase/plan and present next action

### Phase transition protocol

After each phase completes, run `/gsd-transition` to:

1. Mark the phase complete in `.planning/ROADMAP.md` progress table
2. Update this STATE.md (current phase, progress bar, metrics)
3. Update `.planning/REQUIREMENTS.md` traceability (Pending → Complete for finished REQ-IDs)
4. Update `.planning/PROJECT.md` (move Active items to Validated)
5. Log any new decisions or constraints discovered during execution

### Session log

- 2026-04-22 — Initial roadmap + requirements created (70 launch REQs)
- 2026-04-22 — Phase 1 context gathered (`01-CONTEXT.md` committed)
- 2026-04-22 — Terminology reset + legacy-recovery re-synthesis: v-language purged; milestone renamed to launch; `P1-FIX-02` home mega-menu added; `P2-DISCO-01` seating-chart hunt added; `P3-PORT-09..14` static pages + profile ports added; `P5-DASH-02` reclassified as port-from-commercial-theme; Wedding Website expand-to-6 deferred to `PL-THEME-01..05`; `DESIGN-REFERENCES.md` created; root `CLAUDE.md` home-lock + root `PROJECT.md` Phase 7/8/9 rewrites applied. Launch requirement count 70 → 75.

---

*State initialized: 2026-04-22*
*State re-synthesized: 2026-04-22 (terminology reset)*

**Planned Phase:** 1 (Close in-progress + cleanup) — 7 plans — 2026-04-23T08:34:15.517Z
