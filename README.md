# jardin-theme

Jardin · thème bloc FSE : modèles, patterns et styles pour [jasonrouet.com](https://jasonrouet.com). Specs longues et roadmap : dépôt local [jardin-docs](../jardin-docs) (non copiés dans ce dépôt).

## Requirements

- WordPress **6.6+** (aligné sur `style.css`) ; thème déclaré **testé jusqu’à 7.0**
- PHP **8.2+** (valider localement sous **8.4** ; la pile Jardin cible cette base)

**Hub pages (journal, projects, now, toasts, etc.)** : assign the matching **custom page template** from the Site Editor (e.g. *Journal hub* on the journal page). Header/footer/patterns resolve URLs from `_wp_page_template`, not hard-coded slugs. If no page uses a template yet, the theme falls back to legacy paths; override with the `jardin_hub_legacy_path` filter. **Core UI language packs** (e.g. `fr_FR` for WordPress itself) are a site install concern, not bundled by the theme.

## Install

1. Clone or copy into `wp-content/themes/jardin-theme` (recommended). Legacy folder name `jardin` is still supported or can symlink to `jardin-theme`.
2. **Appearance → Themes** → activate **jardin-theme**.
3. **Rewrites / permaliens** : flush automatique quand le thème est mis à jour via l’**upgrader** WordPress, et aussi quand les fichiers du thème changent sur le disque (Git Updater sur branche **sans** bump de version) : le thème compare les dates de modification de `functions.php`, `style.css` et `inc/*.php` et appelle `flush_rewrite_rules()` au besoin. Pour désactiver ce dernier comportement (ex. prod très chargée), utiliser le filtre `jardin_auto_flush_rewrites_on_theme_pull` (retourner `false`). Sinon, **Réglages → Permaliens** ou `wp rewrite flush`.

**Customized template parts:** if the header (or another part) ignores Git updates, reset it in the Site Editor (**Patterns / Template parts** → open part → clear customizations) so files from the theme load again.

### Header (logo, icônes, navigation) — réaligner après un changement de thème

Si la page n’affiche plus la **barre d’icônes** (langue, recherche, thème, musique, soutien) ou le **menu burger / zone Tools** sur mobile, le plus souvent la **partie de modèle Header** en base ne contient plus les patterns du fichier du thème.

1. **Apparence → Éditeur de site** → **Modèles** → **Parties de modèle** → **Header** (ou « En-tête »).
2. Menu **⋮** sur la partie → **Effacer les personnalisations** (confirmer). Répéter sur **dev** et **prod** si besoin.
3. Vérifier les **templates** (Accueil, Page, etc.) : aucun ne doit référencer un **autre** en-tête personnalisé à la place du header du thème.
4. **Afficher le code source** de la page d’accueil : chercher `toolbar` ou `wp-block-jardin-theme-header-utilities` et `site-nav-drawer-tools`. S’ils manquent encore, vider caches (plugin, CDN, OPcache) puis recharger.

**Smoke (HTML)** : depuis la racine du thème, `./scripts/smoke-header-remote.sh https://example.com` (voir script).

**Smoke (responsive, manuel)** : bureau — chrome visible à droite du logo ; mobile étroit — chrome masquée dans la première ligne, burger ouvert → navigation en colonne + zone « Tools » avec les mêmes icônes.

**Déploiement** : `./scripts/verify-header-deploy.sh` vérifie que les fichiers requis du bloc utilitaires sont présents dans ce dossier.

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

| Repository | Role |
|------------|------|
| **jardin-theme** (this repo) | FSE theme, templates, patterns |
| [jardin-projects](https://github.com/jaz-on/jardin-projects) | `project` CPT, GitHub changelog sync, project blocks |
| [jardin-events](https://github.com/jaz-on/jardin-events) | `event` CPT, archive, Query Loop helpers, event blocks |
| [jardin-scrobbles](https://github.com/jaz-on/jardin-scrobbles) | Last.fm → `listen` CPT, `/listens/`, player blocks |
| [jardin-toasts](https://github.com/jaz-on/jardin-toasts) | Untappd RSS + import → `beer_checkin` CPT |
| [jardin-bookmarks](https://github.com/jaz-on/jardin-bookmarks) | Feedbin → `favorite` / `blogroll` CPTs, blogroll block |


## Development

- Header deploy smoke: `./scripts/verify-header-deploy.sh` ; remote HTML smoke: `./scripts/smoke-header-remote.sh https://dev.example.com/` (expects toolbar + drawer markup in page source).
- Default branch for day-to-day work: `**dev`**.
- After pushing: refresh **Git Updater**, purge caches, spot-check key URLs (`/`, `/evenements/` when events are in use). Optional build markers in `assets/css/theme-base.css` and `assets/js/filter-tabs.js` for cache bust verification.
- Optional (one-time per clone): install the local `pre-push` hook with `npm run hooks:install`.

## License

GPL-2.0-or-later — see [LICENSE](LICENSE). Sponsorship: [.github/FUNDING.yml](.github/FUNDING.yml).