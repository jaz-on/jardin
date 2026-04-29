# jardin-theme (WordPress block theme)

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

1. Clone or copy this repository under `wp-content/themes/` as **`jardin-theme`** (recommended). **Legacy:** installs that still use the directory name `jardin` can keep it or symlink `jardin` → `jardin-theme`.
2. In WordPress admin: **Appearance → Themes** → activate **jardin-theme**.
3. After theme updates that touch rewrite rules, visit **Settings → Permalinks** once (or use `wp rewrite flush`).

### Header (or any template part) not updating on dev

WordPress stores **customized** template parts in the database. If the site editor was used to edit **Header**, the filesystem `parts/header.html` from Git can be ignored until you reset the customized part.

1. **Appearance → Editor → Patterns** (or **Template parts**) → open **Header**.
2. Menu **⋮** (three dots) → **Restore** / **Clear customizations** / **Reset** (label varies by WP version) so the theme file is used again.
3. **Save**; hard-refresh or purge CDN cache if applicable.

Without this, only **CSS** and **patterns** that are not overridden in the DB will change — which often looks like “the header never updates.”

The shipped header stacks **`jardin/header-main`** (brand row + primary nav), nested patterns **`jardin/site-brand`** and **`jardin/site-toolbar`** — same idea as **`jardin/footer-main`** for the footer. **Legacy (WordPress identifiers):** block and pattern namespaces stay `jardin/…` because they are serialized in post and template content; renaming them would require a DB migration. The canonical **repository and text domain** name is `jardin-theme`.

**Primary navigation** comes from the WordPress menu assigned to the block. It will not show mockup-style path labels (`/journal`, …) until the menu uses those labels. Remove duplicate utility links (e.g. « Coffee ») if they already exist in the toolbar pattern.

## Internationalization (theme strings)

The repository keeps `**languages/jardin-theme.pot`** as the translation template (source strings are English in PHP/HTML). **Bundled `fr_FR` `.po` / `.mo` files are not versioned** here; site French UI and content use **Polylang** (and related plugins). `load_theme_textdomain()` still loads from `languages/` if you add a `.mo` locally or in deployment. **Migration:** if you had local `.mo` files compiled for the old text domain `jardin`, regenerate them for **`jardin-theme`** (same strings, new domain).

## Development

Work on branch `dev`. Text domain: **`jardin-theme`**.

### Post-push verification (Git Updater + cache)

Use this checklist after every push on `dev` to confirm `dev.jasonrouet.com` serves the expected revision:

1. In WP admin, run Git Updater refresh/update for `jardin-theme` and related plugins (`jardin-events` when events UI changed).
2. Purge caches in order: WordPress cache plugin -> CDN/Cloudflare -> browser hard refresh.
3. Open assets directly and confirm build markers:
   - `.../wp-content/themes/jardin-theme/assets/css/theme-base.css` -> `Build marker: 2026-04-29-events-css-v2`
   - `.../wp-content/themes/jardin-theme/assets/js/filter-tabs.js` -> `build marker: 2026-04-29-feed-v3` (comment header)
4. Smoke test:
   - `/` -> IRL block layout and metadata style
   - `/evenements/` -> filter chips, counts, role filtering behavior

### E2E (Playwright) — phase 5

- Doc des parcours : [`jardin-docs/tests-strategy.md`](../jardin-docs/tests-strategy.md) (dossier local, hors dépôt Git pour la doc jardin en général : si tu n’as pas le clone, ouvre le fichier depuis l’arbo partagée).
- Installation : `npm ci` (racine du thème), `npx playwright install chromium` (ou `npx playwright install` pour les trois navigateurs).
- Variables : copie `e2e/.env.example` en **`.env`** à la racine du thème et définis `E2E_BASE_URL` (ex. `https://dev.jasonrouet.com`) ; `E2E_SKIP_EN=1` si `/en/` n’est pas encore en place.
- Lancer : `npm run e2e` (depuis `jardin-theme/`) ; le rapport est dans `playwright-report/` (non versionné).