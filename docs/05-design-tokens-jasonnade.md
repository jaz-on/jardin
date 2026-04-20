# Tokens design — alignement Jardin / jasonnade

Référence site statique : [jasonnade](https://github.com/jaz-on/jasonnade) (`src/assets/css/main.css`). Le thème Jardin reprend la même palette et la même largeur de lecture ; les différences volontaires sont notées ci‑dessous.

## Couleurs (mode sombre par défaut)

| Rôle jasonnade (`:root`) | Hex | Preset Jardin (`theme.json`) |
|--------------------------|-----|-------------------------------|
| `--color-bg` | `#282a2e` | `background` |
| `--color-text` | `#ffffff` | `foreground` |
| `--color-link` | `#ff6b6b` | `accent` |
| `--color-link-hover` | `#ff8e8e` | `accent-hover` |
| `--color-logo` | `#fcae11` | `logo` |
| `--color-footer` / gris secondaire | `#b3b3b3` | `muted` |
| Surface | — | `surface` (`#3a3a3a`) |

## Mise en page

| Élément | jasonnade | Jardin |
|---------|-----------|--------|
| Largeur contenu | `800px` max | `theme.json` → `contentSize: 800px` |
| Cadre page | `border: 12px` couleur lien | `assets/css/theme-styles.css` → `body` |

## Typographie

| Usage | jasonnade | Jardin |
|-------|-----------|--------|
| Corps | Stack système (`-apple-system`, Segoe, Roboto…) | **Inter** (local) + fallbacks proches |
| Titre hero | Calligraffitti | `core/site-title` + variante butter |
| Code | — | Fira Code |
| Accessibilité optionnelle | — | Atkinson Hyperlegible (preset) |

**Décision** : conserver Inter pour le blog (lisibilité long format) tout en gardant couleurs, cadre et effets (butter, h2) alignés sur jasonnade.

## Composants signature

- **Effet butter** sur le titre du site : `theme.json` + `.is-style-butter-effect` dans `theme-styles.css`.
- **Titres de section (h2)** : ombre deux couches (logo + accent) sur les `h2` du contenu principal, hors titre de la ToC.
- **Skip link** : `jardin_skip_link()` dans `functions.php` (hook `wp_body_open`), chaîne traduisible `Skip to content` (domaine `jardin`) + `.jardin-skip-link` dans `theme-styles.css` ; cible `#main` (ancre des groupes `main` des gabarits).

## Variantes clair / sombre dans `theme.json`

Les objets sous `settings.color.custom.theme-variants` sont des **notes de palette** pour travaux futurs, pas des style variations FSE actives. Le thème reste **sombre par défaut** jusqu’à implémentation explicite (style variations ou autre).
