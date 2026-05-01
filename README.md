# jardin-theme

WordPress **block theme** (FSE) for [jasonrouet.com](https://jasonrouet.com). Long-form specs and roadmap live in the local tree `[../jardin-docs](../jardin-docs)` (do not copy those `.md` files into this repo).

## Requirements

- WordPress **6.4+**
- PHP **7.4+** (match your host; plugins in the stack may require **8.2+**)

## Install

1. Clone or copy into `wp-content/themes/jardin-theme` (recommended). Legacy folder name `jardin` is still supported or can symlink to `jardin-theme`.
2. **Appearance → Themes** → activate **jardin-theme**.
3. **Rewrites / permaliens** : flush automatique quand le thème est mis à jour via l’**upgrader** WordPress, et aussi quand les fichiers du thème changent sur le disque (Git Updater sur branche **sans** bump de version) : le thème compare les dates de modification de `functions.php`, `style.css` et `inc/*.php` et appelle `flush_rewrite_rules()` au besoin. Pour désactiver ce dernier comportement (ex. prod très chargée), utiliser le filtre `jardin_auto_flush_rewrites_on_theme_pull` (retourner `false`). Sinon, **Réglages → Permaliens** ou `wp rewrite flush`.

**Customized template parts:** if the header (or another part) ignores Git updates, reset it in the Site Editor (**Patterns / Template parts** → open part → clear customizations) so files from the theme load again.

**Block namespace migration:** on upgrade, `inc/content-migration.php` can rewrite stored markup from `jardin/…` to `jardin-theme/…` in post content (see `JARDIN_THEME_CONTENT_MIGRATION_VERSION`). Plugin blocks such as `jardin/lastfm-`* are not migrated.

## What it does

- Default templates for single, archive, category, tag; one custom page template `page-journal.html` (Query Loop + filters).
- Patterns under `patterns/`; shared header/footer layout via template parts and patterns (see Site Editor).
- Self-hosted fonts (`assets/fonts/`, `theme.json` `fontFace`); details in `assets/fonts/README.txt`.
- Translation template: `languages/jardin-theme.pot` (text domain `**jardin-theme`**). Bundled `.mo` files are optional per site.

## Doc entry points

- [roadmap.md](../jardin-docs/roadmap.md)
- [theme/theme-json-spec.md](../jardin-docs/theme/theme-json-spec.md)
- [theme/blocks_inventory.md](../jardin-docs/theme/blocks_inventory.md)
- [integration/permalinks-rewrites.md](../jardin-docs/integration/permalinks-rewrites.md)
- [phase-2-site-checklist.md](../jardin-docs/theme/phase-2-site-checklist.md)

## Jardin stack


| Repository                                                     | Role                                                   |
| -------------------------------------------------------------- | ------------------------------------------------------ |
| **jardin-theme** (this repo)                                   | FSE theme, templates, patterns                         |
| [jardin-events](https://github.com/jaz-on/jardin-events)       | `event` CPT, archive, Query Loop helpers, event blocks |
| [jardin-scrobbles](https://github.com/jaz-on/jardin-scrobbles) | Last.fm → `listen` CPT, `/listens/`, player blocks     |
| [jardin-toasts](https://github.com/jaz-on/jardin-toasts)       | Untappd RSS + import → `beer_checkin` CPT              |
| [jardin-bookmarks](https://github.com/jaz-on/jardin-bookmarks) | Feedbin → `favorite` / `blogroll` CPTs, blogroll block |


## Development

- Default branch for day-to-day work: `**dev`**.
- After pushing: refresh **Git Updater**, purge caches, spot-check key URLs (`/`, `/evenements/` when events are in use). Optional build markers in `assets/css/theme-base.css` and `assets/js/filter-tabs.js` for cache bust verification.
- E2E: `npm ci`, `npx playwright install chromium`, copy `e2e/.env.example` → `.env` with `E2E_BASE_URL`, then `npm run e2e` ([tests strategy](../jardin-docs/tests-strategy.md)).
- Optional (one-time per clone): install the local `pre-push` hook with `npm run hooks:install`.

## License

GPL-2.0-or-later