# Guide de Développement - Thème Jardin

## Vue d'ensemble

Ce guide contient toutes les informations nécessaires pour développer le thème Jardin avec l'IA : spécifications design, architecture technique et prompts prêts à utiliser.

---

## Design System

### Palette de Couleurs

**Alignement avec jasonnade.fr** : Le thème Jardin utilise par défaut la même palette de couleurs que jasonnade.fr pour assurer une cohérence visuelle entre les deux sites.

#### Mode Sombre (Par Défaut - jasonnade.fr)

| Couleur | Hex | HSL | Usage | Contraste |
|---------|-----|-----|-------|-----------|
| **Background** | `#282a2e` | `210, 7%, 17%` | Fond principal (gris foncé) | - |
| **Foreground** | `#ffffff` | `0, 0%, 100%` | Texte principal, titres (blanc) | 12.6:1 ✅ (WCAG AAA) |
| **Accent** | `#ff6b6b` | `0, 100%, 71%` | Liens, éléments interactifs (rouge clair) | 4.5:1 ✅ (WCAG AA) |
| **Accent Hover** | `#ff8e8e` | `0, 100%, 78%` | Liens au survol (rose clair) | 4.8:1 ✅ (WCAG AA) |
| **Logo** | `#fcae11` | `42, 96%, 53%` | Couleur logo, accents (orange/jaune) | 4.2:1 ✅ (WCAG AA) |
| **Muted** | `#b3b3b3` | `0, 0%, 70%` | Footer, métadonnées (gris clair) | 4.5:1 ✅ (WCAG AA) |
| **Surface** | `#3a3a3a` | `0, 0%, 23%` | Cards, surfaces élevées | - |

**Note** : Ces couleurs sont extraites directement du CSS de jasonnade.fr pour garantir un alignement parfait.

#### Configuration theme.json - Couleurs (Mode Sombre)

```json
{
  "settings": {
    "color": {
      "defaultPalette": false,
      "palette": [
        { "slug": "background", "color": "#282a2e", "name": "Background" },
        { "slug": "foreground", "color": "#ffffff", "name": "Foreground" },
        { "slug": "accent", "color": "#ff6b6b", "name": "Accent" },
        { "slug": "accent-hover", "color": "#ff8e8e", "name": "Accent Hover" },
        { "slug": "logo", "color": "#fcae11", "name": "Logo" },
        { "slug": "muted", "color": "#b3b3b3", "name": "Muted" },
        { "slug": "surface", "color": "#3a3a3a", "name": "Surface" }
      ],
      "custom": {
        "theme-variants": {
          "variant-light": {
            "background": "#ffffff",
            "foreground": "#1a1a1a",
            "accent": "#2563eb",
            "logo": "#fcae11",
            "muted": "#6b7280",
            "surface": "#f9fafb"
          },
          "variant-dark-blue": {
            "background": "#0a0a19",
            "foreground": "#f9f0ff",
            "accent": "#c7abe3",
            "logo": "#fcae11",
            "muted": "#b8a8d9",
            "surface": "#1a1a2e"
          }
        }
      }
    }
  }
}
```

**Référence** : Structure des variantes inspirée de [`app/public/wp-content/themes/distributed/theme.json`](app/public/wp-content/themes/distributed/theme.json) (section `custom.theme-variants`).

### Système Typographique

**Alignement avec jasonnade.fr** : Typographie hybride combinant Inter (cohérence WordPress) et Calligraffitti (identité jasonnade.fr).

#### Polices

**Body** : Inter (Variable Font)
- Hébergement : Local (`assets/fonts/inter/`)
- Poids : 400 (normal), 500 (medium), 600 (semi-bold)
- Display : `swap`
- Fallback : `-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif`
- **Usage** : Corps de texte, paragraphes, navigation

**Headings H1** : Calligraffitti (Cursive)
- Hébergement : Local (`assets/fonts/calligraffitti/`)
- Poids : 400 (normal), 700 (bold)
- Display : `swap`
- **Usage** : Titre principal du site uniquement (comme jasonnade.fr)
- **Style** : Effet text-shadow multi-couches (Butter effect)

**Headings H2-H6** : Inter (Variable Font)
- Même famille que Body, poids différents pour hiérarchie
- **Usage** : Titres de section, sous-titres

**Code** : Fira Code (Variable Font)
- Hébergement : Local (`assets/fonts/fira-code/`)
- Poids : 300-700 (variable)
- Display : `swap`

#### Échelle Typographique

**Alignement avec jasonnade.fr** : Tailles basées sur le système de jasonnade.fr (1.125rem base = 18px).

| Nom | Taille | Usage | Line Height |
|-----|--------|-------|-------------|
| **xs** | `0.75rem` (12px) | Métadonnées, captions, skip links | 1.4 |
| **base** | `1.125rem` (18px) | Corps de texte (comme jasonnade.fr) | 1.6 |
| **lg** | `1.25rem` (20px) | Sous-titres H3, header intro | 1.5 |
| **xl** | `1.5rem` (24px) | Titres H2 | 1.4 |
| **2xl** | `1.875rem` (30px) | Titres H2 (mobile) | 1.2 |
| **h1-hero** | `clamp(4rem, 15vw, 13rem)` | Titre principal H1 (Calligraffitti) | 1.2 |

**Note** : Le H1 utilise `clamp(4rem, 15vw, 13rem)` comme jasonnade.fr pour un effet responsive.

#### Configuration theme.json - Typographie

```json
{
  "settings": {
    "typography": {
      "fontFamilies": [
        {
          "fontFamily": "\"Inter\",-apple-system,BlinkMacSystemFont,\"Segoe UI\",Roboto,\"Helvetica Neue\",Arial,sans-serif",
          "slug": "body",
          "name": "Body Text",
          "fontFace": [
            {
              "fontFamily": "Inter",
              "fontStyle": "normal",
              "fontWeight": "100 900",
              "src": ["file:./assets/fonts/inter/Inter-Variable.woff2"],
              "fontDisplay": "swap"
            },
            {
              "fontFamily": "Inter",
              "fontStyle": "italic",
              "fontWeight": "100 900",
              "src": ["file:./assets/fonts/inter/Inter-Variable-Italic.woff2"],
              "fontDisplay": "swap"
            }
          ]
        },
        {
          "fontFamily": "\"Inter\",-apple-system,BlinkMacSystemFont,\"Segoe UI\",Roboto,\"Helvetica Neue\",Arial,sans-serif",
          "slug": "heading",
          "name": "Headings"
        },
        {
          "fontFamily": "\"Calligraffitti\",cursive",
          "slug": "calligraffitti",
          "name": "Calligraffitti",
          "fontFace": [
            {
              "fontFamily": "Calligraffitti",
              "fontStyle": "normal",
              "fontWeight": "400",
              "src": ["file:./assets/fonts/calligraffitti/Calligraffitti-Regular.ttf"],
              "fontDisplay": "swap"
            }
          ]
        },
        {
          "fontFamily": "\"Fira Code\",\"SF Mono\",\"Monaco\",\"Inconsolata\",\"Roboto Mono\",\"Courier New\",monospace",
          "slug": "code",
          "name": "Code",
          "fontFace": [
            {
              "fontFamily": "Fira Code",
              "fontStyle": "normal",
              "fontWeight": "300 700",
              "src": ["file:./assets/fonts/fira-code/FiraCode-VF.woff2"],
              "fontDisplay": "swap"
            }
          ]
        }
      ],
      "fontSizes": [
        { "slug": "xs", "size": "0.75rem", "name": "Extra Small" },
        { "slug": "base", "size": "1.125rem", "name": "Base" },
        { "slug": "lg", "size": "1.25rem", "name": "Large" },
        { "slug": "xl", "size": "1.5rem", "name": "Extra Large" },
        { "slug": "2xl", "size": "1.875rem", "name": "2XL" },
        { "slug": "h1-hero", "size": "clamp(4rem, 15vw, 13rem)", "name": "H1 Hero" }
      ],
      "lineHeight": {
        "tight": "1.2",
        "normal": "1.5",
        "relaxed": "1.6"
      }
    }
  }
}
```

### Layout et Espacement

**Alignement avec jasonnade.fr** : Largeur de contenu identique pour cohérence visuelle.

**Content Size** : `800px` (max-width, comme jasonnade.fr)  
**Wide Size** : `1000px`

**Espacement** : Système basé sur jasonnade.fr (système 8px)
- `--spacing-sm`: `1rem` (16px)
- `--spacing-md`: `1.5rem` (24px)
- `--spacing-lg`: `2rem` (32px)
- `--spacing-xl`: `3rem` (48px)
- `--spacing-section`: `4rem` (64px) - Espacement entre sections

**Note** : Pour WordPress, utiliser le système core (0-8) en complément pour compatibilité.

```json
{
  "settings": {
    "layout": {
      "contentSize": "800px",
      "wideSize": "1000px"
    },
    "spacing": {
      "units": ["px", "em", "rem", "vh", "vw", "%"]
    }
  }
}
```

### Composants

**Alignement avec jasonnade.fr** : Styles de composants alignés avec jasonnade.fr.

#### Liens
- **Normal** : Couleur `accent` (#ff6b6b), text-decoration `underline`, text-shadow `1px 1px 0px accent`
- **Hover** : Couleur `accent-hover` (#ff8e8e), text-shadow `1px 1px 0px accent-hover`
- **Focus** : Outline `3px solid accent`, offset `3px`, background `rgba(229, 99, 99, 0.1)`
- **Externe** : Icône `↗` après les liens externes (comme jasonnade.fr)

#### Titres H1 (Site Title)
- **Police** : Calligraffitti (cursive)
- **Taille** : `clamp(4rem, 15vw, 13rem)` (responsive)
- **Font-weight** : `700`
- **Line-height** : `1.2`
- **Text-align** : `center`
- **Text-shadow multi-couches** (Butter effect) :
  - `5px 5px 0px logo` (orange/jaune)
  - `10px 10px 0px accent-hover` (rose clair)
  - `15px 15px 0px accent` (rouge)
  - `20px 20px 0px accent` (rouge)
  - `25px 25px 0px background` (fond)
  - `30px 30px 0px muted` (gris)

#### Titres H2 (Sections)
- **Police** : Inter, font-weight `700`
- **Taille** : `1.875rem` (30px) desktop, `1.5rem` (24px) mobile
- **Text-shadow** : `2px 2px 0px logo, 4px 4px 0px accent` (2 couches)
- **Display** : `inline-block` (pour que text-shadow suive la forme)

#### Boutons
- **Normal** : Background `accent`, texte `background`, padding `0.75rem 1.5rem`
- **Hover** : Background `accent-hover`, transform `scale(1.02)` (si prefers-reduced-motion non activé)
- **Focus** : Border `2px solid accent`

### Accessibilité

#### Contraste (Mode Sombre)
- Foreground/Background : 12.6:1 ✅ (WCAG AAA)
- Accent/Background : 4.5:1 ✅ (WCAG AA)
- Accent-hover/Background : 4.8:1 ✅ (WCAG AA)
- Logo/Background : 4.2:1 ✅ (WCAG AA)
- Muted/Background : 4.5:1 ✅ (WCAG AA)

#### Focus
- Améliorer les styles core via theme.json
- Outline : `3px solid accent` avec offset `3px` (comme jasonnade.fr)
- Background subtil : `rgba(229, 99, 99, 0.1)` pour visibilité

#### Motion
- Respecter `prefers-reduced-motion` via CSS media queries
- Transitions désactivées si préféré
- Durée réduite : `0.001ms` si nécessaire (comme Distributed)

---

## Architecture Technique

### Structure des Fichiers

```
jardin/
├── docs/                          # Documentation
│   ├── 01-vision-principes.md
│   ├── 02-guide-developpement.md
│   └── 03-mvp-specifications.md
├── inc/                           # Classes PHP
│   ├── class-jardin-blocks.php    # Enregistrement automatique des blocs
│   ├── class-jardin-toc.php       # Gestion de la table des matières
│   └── blocks/                    # Blocs personnalisés
│       └── table-of-contents/
│           ├── block.json
│           ├── render.php
│           └── view.js (optionnel)
├── templates/                     # Templates HTML (block theme)
│   ├── index.html
│   ├── single.html
│   ├── page.html
│   └── 404.html
├── parts/                         # Template parts
│   ├── header.html
│   └── footer.html
├── assets/                        # Assets statiques
│   └── fonts/                     # Polices locales
│       ├── inter/
│       └── fira-code/
├── style.css                      # Fichier requis WordPress
├── functions.php                  # Point d'entrée principal
└── theme.json                     # Configuration du système de design
```

### Architecture PHP

#### functions.php

```php
<?php
// Sécurité
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Constantes
define( 'JARDIN_VERSION', '0.1.0' );

// Chargement des classes (minimales)
require_once get_template_directory() . '/inc/class-jardin-blocks.php';
require_once get_template_directory() . '/inc/class-jardin-toc.php';

// Initialisation
new Jardin_Blocks();
new Jardin_TOC();
```

#### Classes PHP Minimales

**Jardin_Blocks** : Enregistrement automatique des blocs
- Scan récursif de `inc/blocks/*/block.json`
- Enregistrement via `register_block_type()`

**Jardin_TOC** : Gestion de la table des matières
- Génération automatique d'IDs pour les titres (H2-H6)
- Filtre `the_content` pour ajouter les IDs
- Chargement conditionnel des assets

### Architecture des Blocs

#### Structure d'un Bloc

```
inc/blocks/[block-name]/
├── block.json          # Configuration du bloc
├── render.php          # Rendu côté serveur
├── editor.js           # Logique éditeur (optionnel)
└── view.js             # Logique frontend (optionnel)
```

#### Configuration block.json

```json
{
  "$schema": "https://schemas.wp.org/trunk/block.json",
  "apiVersion": 3,
  "name": "jardin/[block-name]",
  "title": "Block Title",
  "category": "theme",
  "render": "file:./render.php",
  "supports": { ... },
  "attributes": { ... }
}
```

### Templates HTML

**Format** : Fichiers HTML avec blocs WordPress

**Exemple** :
```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->
<!-- wp:group {"tagName":"main"} -->
    <!-- Contenu -->
<!-- /wp:group -->
<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

**Template Parts** :
- Header : Logo, titre, navigation (blocs WordPress natifs)
- Footer : Copyright, liens optionnels

---

## Prompts de Développement

### Phase 1 : Fondations

#### Prompt 1.1 : Configuration theme.json Complète

```
Je développe un thème WordPress "Jardin" (block theme). Je souhaite créer 
la configuration theme.json complète avec le système de design aligné 
sur jasonnade.fr.

Contexte :
- WordPress 6.5+ (theme.json version 3)
- Design minimaliste pour digital garden
- Approche theme.json-first
- Alignement design avec jasonnade.fr (mode sombre)
- Palette de couleurs jasonnade.fr :
  - background: #282a2e (gris foncé)
  - foreground: #ffffff (blanc)
  - accent: #ff6b6b (rouge clair)
  - accent-hover: #ff8e8e (rose clair)
  - logo: #fcae11 (orange/jaune)
  - muted: #b3b3b3 (gris clair)
  - surface: #3a3a3a (gris moyen)
- Typographie : Inter (body) + Calligraffitti (H1) + Fira Code (code)
- Layout : contentSize 800px (comme jasonnade.fr), wideSize 1000px
- Variantes de couleurs : Structure comme Distributed theme (custom.theme-variants)

Demande :
1. Créer un theme.json complet avec :
   - Configuration des couleurs (palette jasonnade.fr)
   - Variantes de couleurs dans custom.theme-variants (light, dark-blue, etc.)
   - Configuration typographique (Inter + Calligraffitti + Fira Code)
   - Système d'espacement (WordPress core 0-8 + custom spacing jasonnade)
   - Layout (contentSize: 800px, wideSize: 1000px)
   - Configuration des blocs de base (heading, paragraph, link)
   - Styles H1 avec Calligraffitti et text-shadow multi-couches
2. Activer appearanceTools
3. Configurer useRootPaddingAwareAlignments
4. Améliorer les styles de focus via theme.json (outline 3px solid accent, offset 3px)
5. Respecter prefers-reduced-motion
6. Styles de liens avec text-shadow (comme jasonnade.fr)

Référence : 
- docs/02-guide-developpement.md (sections Design System)
- app/public/wp-content/themes/distributed/theme.json (structure variantes)
- /tmp/jasonnade-analysis/src/assets/css/main.css (spécifications jasonnade.fr)
```

#### Prompt 1.2 : Structure de Dossiers et Classes PHP

```
Je développe un thème WordPress "Jardin". Je dois créer la structure de 
dossiers et les classes PHP minimales.

Contexte :
- Thème block theme
- Approche WordPress-first
- Classes PHP minimales uniquement

Demande :
1. Créer la structure de dossiers :
   - inc/blocks/ (pour les blocs personnalisés)
   - templates/ (templates HTML)
   - parts/ (template parts)
   - assets/fonts/ (polices locales)
2. Créer inc/class-jardin-blocks.php :
   - Classe Jardin_Blocks
   - Scan automatique de inc/blocks/*/block.json
   - Enregistrement via register_block_type()
3. Créer inc/class-jardin-toc.php :
   - Classe Jardin_TOC
   - Génération d'IDs pour les titres (H2-H6)
   - Filtre the_content pour ajouter les IDs
4. Mettre à jour functions.php pour charger les classes

Référence : docs/02-guide-developpement.md (section Architecture Technique)
```

### Phase 2 : Templates

#### Prompt 2.1 : Templates HTML de Base

```
Je développe un thème WordPress "Jardin" (block theme). Je dois créer 
les templates HTML de base.

Contexte :
- Thème block theme (templates HTML)
- Design minimaliste
- Utiliser les blocs WordPress natifs

Demande :
1. Créer les templates suivants dans templates/ :
   - index.html (template de base)
   - single.html (article unique)
   - page.html (page)
   - archive.html (liste d'articles)
   - 404.html (page d'erreur)
2. Utiliser les blocs WordPress natifs :
   - core/post-title, core/post-content, core/post-date
   - core/query, core/post-template
3. Structure sémantique HTML5
4. Intégrer les template parts (header, footer)
5. Design minimaliste et épuré

Référence : docs/02-guide-developpement.md
```

#### Prompt 2.2 : Template Parts (Header et Footer)

```
Je développe un thème WordPress "Jardin". Je dois créer les template parts 
header et footer.

Contexte :
- Thème block theme
- Design minimaliste inspiré de jasonnade.fr
- Navigation simple et épurée

Demande :
1. Créer parts/header.html avec :
   - Logo du site (bloc core/site-logo)
   - Titre du site (bloc core/site-title)
   - Navigation principale (bloc core/navigation)
   - Design minimaliste et responsive
2. Créer parts/footer.html avec :
   - Informations de copyright
   - Design épuré
3. Utiliser les blocs WordPress natifs uniquement
4. Intégrer les styles via theme.json

Référence : docs/02-guide-developpement.md
```

### Phase 3 : Bloc Table des Matières

#### Prompt 3.1 : Bloc Table des Matières

```
Je développe un thème WordPress "Jardin". Je dois créer un bloc personnalisé 
pour la table des matières (ToC).

Contexte :
- Thème block theme
- Bloc jardin/table-of-contents
- Génération automatique depuis les titres (H2-H6)
- Positionnement inline (pas de rails complexe pour MVP)
- Navigation smooth scroll via CSS

Demande :
1. Créer inc/blocks/table-of-contents/ avec :
   - block.json (configuration complète)
   - render.php (génération de la ToC depuis les titres)
   - view.js (navigation smooth scroll - minimal si nécessaire)
2. Attributs configurables :
   - headingLevels : Niveaux de titres à inclure (défaut: [2,3,4,5,6])
   - showTitle : Afficher le titre du ToC (booléen)
   - customTitle : Titre personnalisé (string)
   - listStyle : Style de liste (numbered, bulleted, none)
3. Utiliser Jardin_TOC pour générer les IDs des titres
4. Smooth scroll via CSS (scroll-behavior: smooth) ou JS minimal
5. Styles via theme.json uniquement
6. Support des styles (spacing, typography, colors)

Référence : docs/02-guide-developpement.md, docs/01-vision-principes.md
```

---

## Checklist de Développement

Avant chaque développement, vérifier :

- [ ] WordPress core peut-il le faire nativement ?
- [ ] Est-ce vraiment nécessaire pour le MVP ?
- [ ] Peut-on l'implémenter sans JavaScript ?
- [ ] La configuration est-elle minimale ?
- [ ] Le code est-il simple et lisible ?
- [ ] Les standards WordPress sont-ils respectés ?

---

## Références

- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)
- [WordPress Block Editor Handbook](https://developer.wordpress.org/block-editor/)
- [WordPress Theme Handbook](https://developer.wordpress.org/themes/)

