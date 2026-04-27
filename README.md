# Jardin (WordPress block theme)

FSE theme for [jasonrouet.com](https://jasonrouet.com). **Product specs and roadmap** live in the sibling folder **`../jardin-docs`** (local-only documentation tree; do not duplicate long `.md` files into this repo).

## Doc entry points

- [roadmap.md](../jardin-docs/roadmap.md) — phases and progress
- [theme/theme-json-spec.md](../jardin-docs/theme/theme-json-spec.md)
- [theme/blocks_inventory.md](../jardin-docs/theme/blocks_inventory.md)
- [integration/permalinks-rewrites.md](../jardin-docs/integration/permalinks-rewrites.md)

## Fonts

`theme.json` uses **system font stacks** (no bundled `.woff2` yet). To match [theme-json-spec.md](../jardin-docs/theme/theme-json-spec.md) fully, add font files under `assets/fonts/` and add matching `fontFace` entries in `theme.json`.

## Install

1. Clone or copy this repository under `wp-content/themes/`. The production folder name is expected to be `jardin` (you may symlink `jardin` → `jardin-theme`).
2. In WordPress admin: **Appearance → Themes** → activate **Jardin**.
3. After theme updates that touch rewrite rules, visit **Settings → Permalinks** once (or use `wp rewrite flush`).

## Development

Work on branch `dev`. Text domain: **`jardin`**.
