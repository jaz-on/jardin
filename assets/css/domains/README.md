# Domain CSS bundles (`jardin-theme`)

Le fichier [`theme-base.css`](../theme-base.css) est la **concaténation** de ces quatre fichiers, dans l’ordre :

| Part | Fichier | Contenu (résumé) |
|------|---------|------------------|
| 1 | `part-01-general-home-projects.css` | Base inline links, marque, home (bio, événements, flux), grille projets |
| 2 | `part-02-shell-toolbar-nav.css` | Shell header / main / footer inner, toolbar, navigation primaire |
| 3 | `part-03-footer-events-journal.css` | Footer colonnes, archives événements, journal filters, misc hubs |
| 4 | `part-04-articles-now-singles.css` | Articles taxonomies, archives notes, Now hub, singles partagés |

## Regénérer `theme-base.css`

```bash
npm run css:bundle --prefix path/to/jardin-theme
```

Ou :

```bash
bash scripts/bundle-theme-css.sh
```

Après modification d’un `part-*.css`, regénérer pour garder `theme-base.css` synchronisé (enqueue charge uniquement `theme-base.css`).
