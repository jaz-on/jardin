# Changelog

Toutes les modifications notables de ce projet seront documentées dans ce fichier.

## [0.1.0] - 2026-04-18

### Ajouté

- Version initiale du thème Jardin
- Bloc `jardin/table-of-contents` (ancres partagées avec le filtre `the_content`, listes imbriquées valides)
- Configuration `theme.json`, templates FSE et `parts/header` avec lien « Aller au contenu »
- Intégration optionnelle **Jardin Events** : [`inc/jardin-events-integration.php`](inc/jardin-events-integration.php), gabarit [`templates/archive-event.html`](templates/archive-event.html)
- Documentation : [`docs/05-design-tokens-jasonnade.md`](docs/05-design-tokens-jasonnade.md)
- CI : analyse PHPCS (WordPress) via Composer et GitHub Actions (`composer install` à la racine du thème ; le plugin Jardin Events reste une installation WordPress séparée)

### Modifié

- Skip link : rendu via `wp_body_open` et chaîne traduisible ; catalogue `languages/jardin-fr_FR.po` / `.mo` fourni
- Styles complémentaires alignés jasonnade (cadre `body`, h2, skip link, indicateur liens `target="_blank"`)
- Gabarit archive événements : tri par méta `event_date` dans les Query Loop

### Retiré

- Motif PHP « Prochains événements » du thème (remplacé par le motif côté plugin **Jardin Events**)
