# Jardin (WordPress block theme)

FSE theme for [jasonrouet.com](https://jasonrouet.com). **Product specs and roadmap** live in the sibling folder [`../jardin-docs`](../jardin-docs) (local-only documentation tree; do not duplicate long `.md` files into this repo).

## Doc entry points

- [roadmap.md](../jardin-docs/roadmap.md) — phases and progress
- [theme/theme-json-spec.md](../jardin-docs/theme/theme-json-spec.md)
- [theme/blocks_inventory.md](../jardin-docs/theme/blocks_inventory.md)
- [integration/permalinks-rewrites.md](../jardin-docs/integration/permalinks-rewrites.md)
- [phase-2-site-checklist.md](../jardin-docs/theme/phase-2-site-checklist.md) — post-deploy QA on dev

## Fonts

Fonts are **self-hosted** under `assets/fonts/` as `.woff2` files, with `fontFace` entries in `theme.json` (see [theme-json-spec.md](../jardin-docs/theme/theme-json-spec.md)). Origins and licenses are summarized in `assets/fonts/README.txt`.

## Install

1. Clone or copy this repository under `wp-content/themes/`. The production folder name is expected to be `jardin` (you may symlink `jardin` → `jardin-theme`).
2. In WordPress admin: **Appearance → Themes** → activate **Jardin**.
3. After theme updates that touch rewrite rules, visit **Settings → Permalinks** once (or use `wp rewrite flush`).

## Dev pages (manifest import)

On staging or a **dev clone** of production: **Appearance → Jardin dev pages** creates missing manifest pages, assigns FSE page templates, and by default **does not overwrite** existing page bodies (so cloned content stays). Optional HTML lives in `content/seeds/` (see that folder’s README). Import can also set **Reading** (front page = slug `home`, posts page = slug `blog` when Reading is already static) and build an FSE **`wp_navigation`** post (`jardin-dev-nav`) linked to the header **Navigation** block (`jardin-primary-nav`) via the `jardin_seed_navigation_post_id` option. CLI: `wp jardin-seed import --set-home --set-blog --create-nav` (see `wp jardin-seed` help).

During **Import**, Polylang’s bulk meta copy on save is temporarily disabled (`pll_copy_post_metas` forced empty) so `wp_update_post` / `wp_insert_post` do not enqueue `update_metadata` chains that break with strict third-party listeners (e.g. MediaPapa Pro). Template and seed-marker meta are also written without `update_post_meta`. Linked translations do not receive those metas from this run alone—run import per language admin context or assign templates manually where needed.

## Internationalization (theme strings)

The repository keeps **`languages/jardin.pot`** as the translation template (source strings are English in PHP/HTML). **Bundled `fr_FR` `.po` / `.mo` files are not versioned** here; site French UI and content use **Polylang** (and related plugins). `load_theme_textdomain()` still loads from `languages/` if you add a `.mo` locally or in deployment.

## Development

Work on branch `dev`. Text domain: `jardin`.