# Jardin (WordPress block theme)

FSE theme for [jasonrouet.com](https://jasonrouet.com). **Product specs and roadmap** live in the sibling folder `[../jardin-docs](../jardin-docs)` (local-only documentation tree; do not duplicate long `.md` files into this repo).

## Doc entry points

- [roadmap.md](../jardin-docs/roadmap.md) — phases and progress
- [theme/theme-json-spec.md](../jardin-docs/theme/theme-json-spec.md)
- [theme/blocks_inventory.md](../jardin-docs/theme/blocks_inventory.md)
- [integration/permalinks-rewrites.md](../jardin-docs/integration/permalinks-rewrites.md)
- [phase-2-site-checklist.md](../jardin-docs/theme/phase-2-site-checklist.md) — post-deploy QA on dev

## Fonts

Fonts are **self-hosted** under `assets/fonts/` as `.woff2` files, with `fontFace` entries in `theme.json` (see [theme-json-spec.md](../jardin-docs/theme/theme-json-spec.md)). Origins and licenses are summarized in `assets/fonts/README.txt`.

Only **one** custom page template is shipped (`page-journal.html`) because it has a unique Query Loop and filters. Other pages use the default `templates/page.html` plus **block patterns** under `patterns/` (placeholders, meta rows, etc.). **Singles, archives, categories, and tags** use the default `single.html`, `archive.html`, and `category.html` files so WordPress does not need per–post-type duplicates unless a layout truly diverges later.

## Install

1. Clone or copy this repository under `wp-content/themes/`. The production folder name is expected to be `jardin` (you may symlink `jardin` → `jardin-theme`).
2. In WordPress admin: **Appearance → Themes** → activate **Jardin**.
3. After theme updates that touch rewrite rules, visit **Settings → Permalinks** once (or use `wp rewrite flush`).

## Internationalization (theme strings)

The repository keeps `**languages/jardin.pot`** as the translation template (source strings are English in PHP/HTML). **Bundled `fr_FR` `.po` / `.mo` files are not versioned** here; site French UI and content use **Polylang** (and related plugins). `load_theme_textdomain()` still loads from `languages/` if you add a `.mo` locally or in deployment.

## Development

Work on branch `dev`. Text domain: `jardin`.

### E2E (Playwright) — phase 5

- Doc des parcours : [`jardin-docs/tests-strategy.md`](../jardin-docs/tests-strategy.md) (dossier local, hors dépôt Git pour la doc jardin en général : si tu n’as pas le clone, ouvre le fichier depuis l’arbo partagée).
- Installation : `npm ci` (racine du thème), `npx playwright install chromium` (ou `npx playwright install` pour les trois navigateurs).
- Variables : copie `e2e/.env.example` en **`.env`** à la racine du thème et définis `E2E_BASE_URL` (ex. `https://dev.jasonrouet.com`) ; `E2E_SKIP_EN=1` si `/en/` n’est pas encore en place.
- Lancer : `npm run e2e` (depuis `jardin-theme/`) ; le rapport est dans `playwright-report/` (non versionné).