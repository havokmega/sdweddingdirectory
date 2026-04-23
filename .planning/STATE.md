# Project State: San Diego Wedding Directory — v1-launch

**Last updated:** 2026-04-22
**Session:** Initialization — roadmap created

---

## Project Reference

**Core value:** Ship `v1-launch` without regressing the v2 foundation — the first public cut of the v2 stack must replace the v1 site cleanly and pass all four hard launch gates (couple AJAX works, combo SEO pages return 200, all three dashboards are E2E-functional, transactional email delivers).

**Primary files:**
- Task tracker (authoritative): `PROJECT.md` (root)
- Hard rules: `CLAUDE.md` (root) — read before any action
- Roadmap: `.planning/ROADMAP.md`
- Requirements: `.planning/REQUIREMENTS.md`
- Codebase map: `.planning/codebase/` (ARCHITECTURE.md, CONCERNS.md, CONVENTIONS.md, INTEGRATIONS.md, STACK.md, STRUCTURE.md, TESTING.md)

---

## Current Position

**Milestone:** v1-launch
**Current phase:** Not started (Phase 1 is next)
**Current plan:** None — awaiting `/gsd-plan-phase 1`
**Status:** Roadmap created; ready to begin planning

```
Progress: [░░░░░░░░░░░░░░░░░░░░] 0% — 0/5 phases complete
```

**Phase summary:**
| Phase | Name | Status |
|-------|------|--------|
| 1 | Close in-progress + cleanup | Not started |
| 2 | Plugin closeout + parity + security | Not started |
| 3 | Missing + ported templates | Not started |
| 4 | Combo venue SEO pages | Not started |
| 5 | Launch prep | Not started |

---

## Next Action

Run `/gsd-plan-phase 1` to decompose Phase 1 into executable plans.

Phase 1 goal: All existing in-progress work is finished and the codebase is clean — broken legacy hooks removed, text-domain standardized, and the couple registration form actually creates accounts.

Phase 1 requirements (11): P1-QA-01, P1-BUILD-01, P1-BUILD-02, P1-CLEAN-01, P1-CLEAN-02, P1-CLEAN-03, P1-CLEAN-04, P1-CLEAN-05, P1-CLEAN-06, P1-CLEAN-07, P1-FIX-01

---

## Launch Gates (hard — site does not ship without all 4 green)

| Gate | Description | Covered By | Status |
|------|-------------|-----------|--------|
| LG-01 | All 5 sdwd-couple AJAX endpoints accept valid nonces | P2-NONCE-01 | Pending |
| LG-02 | /{location}/{type}/ combos return HTTP 200 with real content | P4-TPL-01, P4-ROUTE-01, P4-QA-01 | Pending |
| LG-03 | Dashboards E2E: register → login → save → email for all 3 roles | P1-FIX-01, P5-DASH-01-03 | Pending |
| LG-04 | Transactional email delivers to real inbox | P5-EMAIL-01-03 | Pending |

---

## Performance Metrics

- Plans executed: 0
- Plans succeeded: 0
- Plans failed: 0
- Phases complete: 0/5
- Requirements complete: 0/70

---

## Accumulated Context

### Key Decisions (from PROJECT.md)

| Decision | Rationale |
|----------|-----------|
| Run full v1-launch milestone through GSD | STATE.md + `/gsd-resume-work` solves cold-start problem across multi-day gaps |
| All HIGH security items from CONCERNS.md → Phase 2 | Password-change surface + wp_unslash are exploitable in prod; not safe to defer |
| Vendor/venue parity lands in Phase 2 | Central architecture goal of the rebuild; delaying deepens the code fork |
| Phase 3 port-source discovery is a dedicated first task | 6+ SDWD-v1 snapshots + 3+ themeforest variants exist; can't pre-select without fingerprint |
| Text-domain standardizes on `sandiegoweddingdirectory` | Three variants in use today; Phase 1 normalizes |
| Phase 8 "Permalink Cleanup" deferred to v1.1 | Combo SEO pages cover new URL structure; wholesale permalink reorg risks launch window |
| No automated test suite in v1-launch | Foundation first; tests post-launch once code stops moving |

### Hard Constraints (CLAUDE.md)

- Theme directory: ONLY `wp-content/themes/sandiegoweddingdirectory/` — do not create/rename/move
- Banned: Bootstrap, Font Awesome, jQuery, Google Fonts, external CSS/JS frameworks, page builders
- `inc/` capped at 3 files — do not add more
- Home page (`front-page.php` + `home.css`) is LOCKED — no changes without explicit founder approval
- CSS: tokens only (no raw hex, no `!important`, no inline styles except dynamic bg images)
- All output escaped: `esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`
- Assets via `get_template_directory_uri()` only — no hardcoded URLs
- CPT/taxonomy registration: plugin (`sdwd-core`) only — theme never registers

### Brownfield Notes

- v1 legacy plugins physically removed from `wp-content/plugins/` — their dead hooks cause Phase 1 cleanup items
- Existing v2 code is partially complete: home page locked and shipped, planning pages at ~90%, venue/vendor shells exist
- `real-wedding` CPT + 8 taxonomies are referenced in theme but NOT registered in `sdwd-core` — Phase 3 must resolve
- Five `sdwd-couple` AJAX endpoints have no nonce issuers — all couple dashboard features are currently broken (Phase 2 fix)
- Vendor claim button on `single-vendor.php` is wired but has no JS handler — currently a dead click (Phase 2 fix)

### Todos / Blockers

- None blocking start of Phase 1
- Phase 3: founder must confirm per-page port-source selection after P3-DISCO-01 fingerprint runs
- Phase 5: founder must finalize the 5 additional wedding-website theme concepts (P5-THEME-01)

---

## Session Continuity

### Cold-start resume

1. Read `CLAUDE.md` (hard rules) and `PROJECT.md` (task tracker) — mandatory before any action
2. Read this file (`STATE.md`) and `.planning/ROADMAP.md` for milestone context
3. Run `/gsd-resume-work` — it will identify current phase/plan and present next action

### Phase transition protocol

After each phase completes, run `/gsd-transition` to:
1. Mark the phase complete in ROADMAP.md progress table
2. Update this STATE.md (current phase, progress bar, metrics)
3. Update REQUIREMENTS.md traceability (Pending → Complete for finished REQ-IDs)
4. Update PROJECT.md (move Active items to Validated)
5. Log any new decisions or constraints discovered during execution

---

*State initialized: 2026-04-22*
