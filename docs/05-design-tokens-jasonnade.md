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
| Cadre page | `border: 12px` couleur lien (6px ≤782px, 0 ≤480px) | `assets/css/theme-styles.css` → `body` |

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

## Variantes notées dans `theme.json`

Les objets sous `settings.color.custom.theme-variants` (**variant-light**, **variant-dark-blue**) sont des **notes de palette** pour référence ou travaux futurs (y compris `accent-hover` pour chaque variante). Ce ne sont **pas** des style variations FSE : la palette **active** du site est uniquement celle définie dans `settings.color.palette` (mode sombre jasonnade par défaut). Aucune bascule automatique clair / sombre n’est prévue tant qu’on n’expose pas ces couleurs autrement (par ex. style variations ou CSS).

## Contraste (vérification)

À revérifier avec un outil de contraste (WCAG) lors des changements de palette, notamment : **accent** sur **background** (liens dans le corps), **foreground** sur **background**, **texte des boutons** (`background` sur **accent** / **accent-hover**), et **logo** si utilisé comme texte petit sur fond clair. Les notes du guide de développement restent indicatives ; un échec sur une paire peut justifier un token dédié ou un ajustement de teinte.
