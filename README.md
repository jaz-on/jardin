# jardin-theme

Jardin · thème bloc FSE : modèles, patterns et styles pour [jasonrouet.com](https://jasonrouet.com). Specs longues et roadmap : dépôt local [jardin-docs](../jardin-docs) (non copiés dans ce dépôt).

## Requirements

- WordPress **6.6+** (aligné sur `style.css`) ; thème déclaré **testé jusqu’à 7.0**
- PHP **8.2+** (valider localement sous **8.4** ; la pile Jardin cible cette base)

**Navigation & footer** : starter links live in **theme patterns** (`patterns/header-nav-row.php` loads serialized markup from `patterns/includes/header-nav-row.markup.html`; `patterns/footer-main.php`, etc.) as core `Navigation` blocks with example paths (`/journal/`, …). **Edit links in the Site Editor** (template parts Header/Footer — clear customizations to reload theme defaults). The **Support** icon URL is the block attribute `supportUrl` on `jardin-theme/header-utilities` (default `/soutenir/`). **Hub page templates** (`page-journal`, `page-projects`, …) still define **layout** (Query Loop, etc.), not automatic menu URLs. **Core UI language packs** are a site install concern, not bundled by the theme.

## Install

1. Clone or copy into `wp-content/themes/jardin-theme` (recommended). Legacy folder name `jardin` is still supported or can symlink to `jardin-theme`.
2. **Appearance → Themes** → activate **jardin-theme**.
3. **Rewrites / permaliens** : flush automatique quand le thème est mis à jour via l’**upgrader** WordPress, et aussi quand les fichiers du thème changent sur le disque (Git Updater sur branche **sans** bump de version) : le thème compare les dates de modification de `functions.php`, `style.css` et `inc/*.php` et appelle `flush_rewrite_rules()` au besoin. Pour désactiver ce dernier comportement (ex. prod très chargée), utiliser le filtre `jardin_auto_flush_rewrites_on_theme_pull` (retourner `false`). Sinon, **Réglages → Permaliens** ou `wp rewrite flush`.

**Customized template parts:** if the header (or another part) ignores Git updates, reset it in the Site Editor (**Patterns / Template parts** → open part → clear customizations) so files from the theme load again.

### Header / Footer (navigation, colonnes) — réaligner après un changement de thème

Si la page n’affiche plus la **barre d’icônes** (langue, recherche, thème, musique, soutien) ou le **menu burger / zone Tools** sur mobile, le plus souvent la **partie de modèle Header** en base ne contient plus les patterns du fichier du thème. Idem pour le **Footer** (4 colonnes + webring) : **Effacer les personnalisations** sur la partie **Footer** pour charger les `Navigation` du thème.

1. **Apparence → Éditeur de site** → **Modèles** → **Parties de modèle** → **Header** (ou « En-tête »).
2. Menu **⋮** sur la partie → **Effacer les personnalisations** (confirmer). Répéter sur **dev** et **prod** si besoin.
3. Vérifier les **templates** (Accueil, Page, etc.) : aucun ne doit référencer un **autre** en-tête personnalisé à la place du header du thème.
4. **Afficher le code source** de la page d’accueil : chercher `toolbar` ou `wp-block-jardin-theme-header-utilities` et `site-nav-drawer-tools`. S’ils manquent encore, vider caches (plugin, CDN, OPcache) puis recharger.

**Debug (logs)** : le thème remplace le Header en base par `parts/header.html` si la structure est incomplète (filtre `get_block_template`, priorité **999**). Pour tracer les décisions dans `debug.log` : `define( 'JARDIN_DEBUG_HEADER_FALLBACK', true );` dans `wp-config.php` (avec `WP_DEBUG_LOG` activé), ou le filtre `jardin_header_template_fallback_debug` à `true`. Les lignes commencent par `[jardin header]` (JSON).

**Smoke (HTML)** : depuis la racine du thème, `./scripts/smoke-header-remote.sh https://example.com` (voir script).

**Smoke (responsive, manuel)** : bureau — chrome visible à droite du logo ; mobile étroit — chrome masquée dans la première ligne, burger ouvert → navigation en colonne + zone « Tools » avec les mêmes icônes.

**Déploiement** : `./scripts/verify-header-deploy.sh` vérifie que les fichiers requis du bloc utilitaires sont présents dans ce dossier.

## What it does

- Default templates for single, archive, category, tag; one custom page template `page-journal.html` (Query Loop + filters).
- Patterns under `patterns/`; shared header/footer layout via template parts and patterns (see Site Editor).
- Self-hosted fonts (`assets/fonts/`, `theme.json` `fontFace`); details in `assets/fonts/README.txt`.
- Translation template: `languages/jardin-theme.pot` (text domain `**jardin-theme`**). Bundled `.mo` files are optional per site.

## Layout — convention `align`

Le thème délègue la cascade de largeurs au moteur FSE (Core layout + `theme.json`). Les templates et patterns expriment **uniquement** un alignement par bloc :

| Align          | Largeur                | Usage                                                                          |
|----------------|------------------------|--------------------------------------------------------------------------------|
| *(défaut)*     | `contentSize` (42rem)  | Lecture : breadcrumb, `post-title`, `post-content`, paragraphes d'intro, asides `page-techie`. |
| `align: wide`  | `wideSize` (56rem)     | Hubs : `wp:query`, grilles de cartes, filtres, sections `feed-header`, `articles-page-shell`. |
| `align: full`  | 100 % viewport         | `<main>` lui-même, `parts/header`, `parts/footer`, covers / featured-image full-bleed. |

Conséquences :

- `<main>` est toujours `align: full` + `layout: constrained` (+ `tagName: main`, `anchor: main`). Core ajoute `.has-global-padding` et applique la **gouttière** `--jardin-site-gutter` (= `styles.spacing.padding` dans `theme.json`).
- Les enfants directs sans `align` héritent du `contentSize` (42rem) — colonne de lecture.
- Les blocs hub (queries, grilles, filtres) doivent porter `"align":"wide"` pour passer en `wideSize` (56rem).
- Pour ajouter une nouvelle page hub : marquer la query / la grille `align: wide`. Pour une page de lecture : ne rien marquer.
- Les images full-bleed dans un single passent en `align: full` ; elles cassent la gouttière via le mécanisme Core `useRootPaddingAwareAlignments`.

CSS thème : **aucune** règle `max-width` sur `main#main`, `.wp-site-blocks > *`, `.site-inner.alignwide` — Core gère seul (cf. [`assets/css/domains/part-02-shell-toolbar-nav.css`](assets/css/domains/part-02-shell-toolbar-nav.css)). Sur mobile (≤ 782 px), `--jardin-site-gutter` est rétréci globalement : la gouttière Core suit automatiquement.

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
| [jardin-updates](https://github.com/jaz-on/jardin-updates) | `now` CPT, hub / permaliens, migrations |
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