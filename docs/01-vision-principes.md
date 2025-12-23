# Vision et Principes de Développement - Thème Jardin

## Vue d'ensemble

**Jardin** est un thème WordPress minimaliste conçu pour cultiver ses idées sur le web. Il s'agit d'un thème de type "digital garden" destiné à un blog personnel, optimisé pour la publication de contenu mixte (articles techniques, réflexions product management, contenu WordPress, notes personnelles).

## Objectifs Principaux

### 1. Digital Garden Personnel
- **Objectif** : Créer un espace de publication qui favorise la réflexion longue et la documentation d'apprentissages
- **Public cible** : Blog personnel pour jasonrouet.com
- **Philosophie** : Minimalisme, lisibilité, accessibilité

### 2. Esthétique Minimaliste
- **Inspiration** : [jasonnade.fr](https://jasonnade.fr/) - site professionnel minimaliste et épuré
- **Direction** : Design clean, typographie soignée, espace blanc généreux
- **Approche** : Moins c'est plus, focus sur le contenu

### 3. Expérience de Lecture Optimale
- **Typographie** : Système typographique accessible et lisible
- **Espacement** : Marges généreuses pour une lecture confortable
- **Navigation** : Table des matières (ToC) pour faciliter la navigation dans les articles longs

## Contexte Spécifique

### Site Cible
- **URL** : [jasonrouet.com](https://jasonrouet.com)
- **Contenu existant** : Oui, migration nécessaire
- **Approche** : MVP rapide puis itérations progressives

### Types de Contenu
Le thème doit supporter efficacement :
- Articles techniques et développement
- Réflexions product management
- Contenu communauté WordPress
- Notes personnelles et apprentissages
- Documentation de projets

### Contraintes Techniques
- **WordPress** : Dernière version (block themes)
- **PHP** : Minimum 7.4
- **Approche** : theme.json-first (priorité au système de design WordPress)
- **Compatibilité** : Préservation des URLs et SEO existants

## Principes de Développement WordPress-First

### 1. WordPress Core/Natif en Priorité

**Principe** : Toujours vérifier et utiliser les fonctionnalités WordPress natives avant de créer du code custom.

**Application** :
- ✅ Vérifier si WordPress core fournit une fonctionnalité avant d'en créer une
- ✅ Utiliser les blocs WordPress natifs (Navigation, Post Meta, etc.)
- ✅ S'appuyer sur les APIs WordPress (wp_enqueue_*, wp_register_*, etc.)
- ✅ Utiliser les hooks et filtres WordPress standards
- ❌ Éviter de réinventer ce que WordPress fait déjà

**Exemples** :
- Navigation : Utiliser le bloc `core/navigation` natif
- Métadonnées : Utiliser les blocs `core/post-date`, `core/post-author` quand possible
- Images : S'appuyer sur WordPress core (srcset, lazy loading natif)
- Espacement : Utiliser le système WordPress core (0-8)

### 2. Approche Minimaliste

**Principe** : Éviter la complexité, privilégier la simplicité.

**Application** :
- ✅ Code simple et lisible
- ✅ Fonctionnalités essentielles uniquement
- ✅ Configuration minimale (theme.json, classes PHP)
- ❌ Éviter le sur-engineering
- ❌ Éviter les abstractions inutiles

**Exemples** :
- Classes PHP minimales (uniquement ce qui est nécessaire)
- JavaScript évité si possible
- Configuration theme.json minimale
- Templates essentiels uniquement

### 3. JavaScript Minimal ou Évité

**Principe** : Éviter JavaScript si possible, sinon minimal avec APIs WordPress.

**Application** :
- ✅ Préférer CSS pour les interactions (scroll-behavior: smooth, etc.)
- ✅ Si JavaScript nécessaire : utiliser les APIs WordPress (wp-i18n, etc.)
- ✅ Chargement conditionnel (uniquement si nécessaire)
- ❌ Éviter les frameworks JavaScript lourds
- ❌ Éviter le JavaScript pour ce que CSS peut faire

**Exemples** :
- Smooth scroll : CSS `scroll-behavior: smooth` plutôt que JS
- ToC : CSS pour le style, JS minimal uniquement si nécessaire
- Pas de sidenotes JavaScript complexe (utiliser footnotes natives)

### 4. Configuration Minimale

**Principe** : Configuration minimale, laisser WordPress core gérer le reste.

**Application** :
- ✅ theme.json minimal (couleurs, typo, spacing essentiels)
- ✅ Personnalisation minimale des blocs (uniquement si nécessaire)
- ✅ Classes PHP minimales (uniquement les fonctionnalités custom)
- ❌ Ne pas surcharger theme.json
- ❌ Ne pas personnaliser tous les blocs

**Exemples** :
- theme.json : Couleurs, typographie, spacing de base
- Blocs : Personnaliser uniquement heading, paragraph, link
- Breakpoints : Utiliser ceux de WordPress core si disponibles

### 5. Performance et Optimisation

**Principe** : Performance dès le départ, optimisations progressives.

**Application** :
- ✅ Assets minimaux (polices locales uniquement)
- ✅ Chargement conditionnel
- ✅ Versioning avec filemtime()
- ✅ S'appuyer sur WordPress pour les optimisations (images, etc.)
- ❌ Ne pas sur-optimiser prématurément

**Exemples** :
- Polices : Hébergement local avec preload
- Images : S'appuyer sur WordPress core (srcset, lazy loading)
- CSS/JS : Minimaux, chargés conditionnellement

### 6. Accessibilité Native

**Principe** : S'appuyer sur WordPress core pour l'accessibilité, améliorer si nécessaire.

**Application** :
- ✅ Vérifier ce que WordPress core fait nativement
- ✅ Améliorer les styles core via theme.json
- ✅ Ajouter skip links si WordPress ne les fournit pas
- ✅ Respecter prefers-reduced-motion via CSS
- ❌ Ne pas réinventer les fonctionnalités d'accessibilité

**Exemples** :
- Focus : Améliorer les styles core via theme.json
- Skip links : Vérifier WordPress core, ajouter si nécessaire
- Motion : CSS media queries pour prefers-reduced-motion

### 7. Code Propre et Documenté

**Principe** : Code lisible, commenté, suivant les standards WordPress.

**Application** :
- ✅ Commentaires pour expliquer le "pourquoi"
- ✅ Code auto-documenté (noms de variables/fonctions clairs)
- ✅ Standards WordPress (coding standards)
- ✅ Documentation minimale mais à jour
- ❌ Commentaires redondants (code évident)

**Exemples** :
- Fonctions : Docblocks WordPress standard
- Classes : Commentaires pour les décisions importantes
- Code : Noms explicites plutôt que commentaires

## Checklist de Développement

Avant d'ajouter une fonctionnalité, se poser :

- [ ] WordPress core peut-il le faire nativement ?
- [ ] Est-ce vraiment nécessaire pour le MVP ?
- [ ] Peut-on l'implémenter sans JavaScript ?
- [ ] La configuration est-elle minimale ?
- [ ] Le code est-il simple et lisible ?
- [ ] Les standards WordPress sont-ils respectés ?

## Exemples Concrets

### ✅ Bon : Utiliser WordPress Core

```php
// Utiliser le bloc Navigation natif WordPress
// Pas besoin de créer un système custom
```

### ❌ Éviter : Réinventer WordPress

```php
// Ne pas créer un système de navigation custom
// si le bloc core/navigation suffit
```

### ✅ Bon : JavaScript Minimal

```css
/* Smooth scroll via CSS */
html {
  scroll-behavior: smooth;
}
```

### ❌ Éviter : JavaScript Inutile

```javascript
// Ne pas créer un smooth scroll en JS
// si CSS peut le faire
```

### ✅ Bon : Configuration Minimale

```json
{
  "settings": {
    "color": {
      "palette": [
        // Couleurs essentielles uniquement
      ]
    }
  }
}
```

### ❌ Éviter : Surcharge

```json
{
  "settings": {
    "blocks": {
      // Ne pas personnaliser tous les blocs
      // Uniquement si nécessaire
    }
  }
}
```

## Fonctionnalités Clés MVP

### Inclus dans le MVP
1. **Table des matières (ToC)** : Affichage automatique basé sur les titres, positionnement inline
2. **Système de couleurs** : Palette complète inspirée de jasonnade.fr
3. **Typographie** : Inter hébergé localement, système typographique complet

### Reporté post-MVP
- **Reading Time & Word Count** : Simplification du MVP
- **Sidenotes** : Utiliser footnotes natives WordPress pour MVP
- **Thème sombre/clair** : Système de basculement reporté

## Résultat Attendu

Un thème WordPress moderne, minimaliste et élégant qui :
- Offre une expérience de lecture exceptionnelle
- Met en valeur le contenu sans distraction
- S'intègre naturellement dans l'écosystème WordPress moderne
- Facilite la publication et la découverte de contenu
- Respecte les standards d'accessibilité et de performance

## Références

- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)
- [WordPress Block Editor Handbook](https://developer.wordpress.org/block-editor/)
- [WordPress Theme Handbook](https://developer.wordpress.org/themes/)

