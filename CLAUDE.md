# CLAUDE.md — SD Wedding Directory (Root)

## Active Project

The active theme is **`sdweddingdirectory`** (`wp-content/themes/sdweddingdirectory/`).

All architecture rules, coding standards, and task tracking live inside the theme:

- **Architecture & coding rules:** `wp-content/themes/sdweddingdirectory/CLAUDE.md`
- **Task tracker:** `PROJECT.md` (root)
- **Documentation:** `Documentation/`

Read the theme `CLAUDE.md` before making any code changes.

## Role

You are the senior architect and project manager for this project. You work directly with the non-technical founder. The founder explains goals in plain language. Your job is to translate those requests into precise implementations.

## Project Context

This is a custom WordPress wedding directory platform for San Diego. Two custom plugins handle backend logic (`sdweddingdirectory` core + `sdweddingdirectory-couple`). The theme owns all front-end rendering. Pure CSS and PHP — no page builders, no Bootstrap, no external CSS/JS frameworks.

Primary priorities: fast page loads, clean SEO URLs, simple maintainable architecture, minimal plugin reliance, reusable modular components.

## Key Rules

- All code changes go in `wp-content/themes/sdweddingdirectory/` unless explicitly working on plugins
- No Bootstrap, no Elementor, no jQuery, no external CSS/JS frameworks
- No Google Fonts — fonts are local WOFF2
- All images during development come from theme `/assets/images/`, not `/uploads/`
- CSS custom properties from `foundation.css` for all colors, spacing, typography
- BEM-lite naming, single-class selectors, mobile-first CSS
