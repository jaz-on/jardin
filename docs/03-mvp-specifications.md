# Spécifications MVP - Thème Jardin

## Vue d'ensemble

Ce document définit précisément le périmètre du Minimum Viable Product (MVP) pour le thème Jardin. L'objectif est de livrer une première version fonctionnelle et utilisable rapidement, avec les fonctionnalités essentielles uniquement.

## Philosophie MVP

### Principes

1. **Fonctionnalités Essentielles** : Seulement ce qui est nécessaire pour un digital garden fonctionnel
2. **Qualité Avant Quantité** : Mieux vaut peu de fonctionnalités bien faites que beaucoup de fonctionnalités médiocres
3. **Base Solide** : Architecture qui permet l'évolution future
4. **Expérience Utilisateur** : Focus sur la lisibilité et l'accessibilité

### Objectifs du MVP

- ✅ Thème fonctionnel et utilisable
- ✅ Design minimaliste et élégant
- ✅ Expérience de lecture optimale
- ✅ Accessibilité de base (WCAG AA)
- ✅ Performance correcte
- ✅ Compatibilité avec le contenu existant

---

## Fonctionnalités Incluses dans le MVP

### 1. Fondations ✅

#### 1.1 Système de Design
- [x] Configuration theme.json complète
- [x] Palette de couleurs (mode clair uniquement)
- [x] Système typographique (Inter hébergé localement)
- [x] Système d'espacement (WordPress core 0-8)
- [x] Layout (contentSize: 620px, wideSize: 1000px)

#### 1.2 Structure de Base
- [x] Templates HTML (index, single, page, archive, 404)
- [x] Template parts (header, footer)
- [x] Structure modulaire (classes PHP minimales)
- [x] Enregistrement automatique des blocs

### 2. Fonctionnalités Essentielles ✅

#### 2.1 Table des Matières (ToC)
- [x] Bloc personnalisé `jardin/table-of-contents`
- [x] Génération automatique depuis les titres (H2-H6)
- [x] Navigation smooth scroll (CSS ou JS minimal)
- [x] Positionnement inline (pas de rails complexe)
- [x] Options configurables (niveaux de titres, style de liste)

**Priorité** : Haute  
**Complexité** : Moyenne  
**Temps estimé** : 4-6h

#### 2.2 Accessibilité
- [x] Skip links (si WordPress core ne les fournit pas)
- [x] Navigation clavier
- [x] Styles de focus améliorés via theme.json
- [x] Support prefers-reduced-motion
- [x] Contraste WCAG AA

**Priorité** : Haute  
**Complexité** : Faible-Moyenne  
**Temps estimé** : 3-4h

### 3. Design et Expérience ✅

#### 3.1 Design Minimaliste
- [x] Palette de couleurs inspirée de jasonnade.fr
- [x] Typographie soignée (Inter)
- [x] Espacement généreux
- [x] Layout optimisé pour la lecture

#### 3.2 Responsive Design
- [x] Mobile-first
- [x] Breakpoints WordPress core
- [x] Navigation adaptative
- [x] Images responsive (WordPress core)

**Priorité** : Haute  
**Complexité** : Moyenne  
**Temps estimé** : 4-5h

### 4. Compatibilité ✅

#### 4.1 Blocs WordPress
- [x] Support des blocs WordPress natifs
- [x] Styles via theme.json
- [x] Personnalisation minimale des blocs principaux

#### 4.2 Contenu Existant
- [x] Compatibilité avec le contenu de jasonrouet.com
- [x] Préservation des URLs
- [x] Migration en douceur

**Priorité** : Haute  
**Complexité** : Faible-Moyenne  
**Temps estimé** : 2-3h

---

## Fonctionnalités Exclues du MVP

### 1. Reading Time & Word Count ❌

**Raison** : Simplification du MVP, pas essentiel pour la première version.

**Plan** : Peut être ajouté dans une itération future si nécessaire.

### 2. Sidenotes ❌

**Raison** : Complexité moyenne, pas essentiel pour le MVP.

**Solution MVP** : Utiliser les footnotes natives WordPress.

**Plan** : Version simplifiée dans l'itération 2 si nécessaire.

### 3. Système de Thèmes (Clair/Sombre) ❌

**Raison** : Focus d'abord sur un thème unique bien conçu.

**Plan** : Implémentation dans l'itération 2 ou 3.

### 4. Layout Rails Avancé ❌

**Raison** : Complexité du système à 3 colonnes, pas essentiel pour le MVP.

**Solution MVP** : ToC inline suffit pour commencer.

**Plan** : Layout rails dans l'itération 2 si nécessaire.

---

## Structure de Fichiers Finale

```
jardin/
├── docs/
│   ├── 01-vision-principes.md
│   ├── 02-guide-developpement.md
│   └── 03-mvp-specifications.md
├── inc/
│   ├── class-jardin-blocks.php
│   ├── class-jardin-toc.php
│   └── blocks/
│       └── table-of-contents/
│           ├── block.json
│           ├── render.php
│           └── view.js (optionnel)
├── templates/
│   ├── index.html
│   ├── single.html
│   ├── page.html
│   ├── archive.html
│   └── 404.html
├── parts/
│   ├── header.html
│   └── footer.html
├── assets/
│   └── fonts/
│       ├── inter/
│       └── fira-code/
├── style.css
├── functions.php
└── theme.json
```

---

## Critères d'Acceptation du MVP

### Fonctionnels

- [ ] Tous les templates s'affichent correctement
- [ ] La navigation fonctionne sur tous les appareils
- [ ] Le bloc ToC fonctionne et génère automatiquement la table des matières
- [ ] Le contenu existant s'affiche correctement
- [ ] Les liens internes/externes fonctionnent
- [ ] Les images s'affichent correctement et sont responsive

### Design

- [ ] Design cohérent avec jasonnade.fr
- [ ] Typographie lisible et hiérarchie claire
- [ ] Espacement généreux et équilibré
- [ ] Responsive sur mobile, tablette, desktop
- [ ] Couleurs accessibles (contraste WCAG AA)

### Performance

- [ ] Temps de chargement < 3s
- [ ] First Contentful Paint < 1.5s
- [ ] Pas de Cumulative Layout Shift notable
- [ ] Images optimisées (WordPress core)
- [ ] Polices chargées efficacement (preload)

### Accessibilité

- [ ] Navigation clavier fonctionnelle
- [ ] Skip links présents (si nécessaire)
- [ ] Contraste WCAG AA respecté
- [ ] Focus visible sur tous les éléments interactifs
- [ ] Support prefers-reduced-motion

### Compatibilité

- [ ] Compatible WordPress 6.5+
- [ ] Compatible avec les blocs WordPress natifs
- [ ] Compatible avec le contenu existant
- [ ] Pas de conflits avec les plugins essentiels

---

## Planning de Développement

### Phase 1 : Fondations (Semaine 1)

**Objectifs** :
- Configuration theme.json complète
- Système de couleurs et typographie
- Structure de base des fichiers

**Livrables** :
- theme.json configuré
- Palette de couleurs définie
- Typographie configurée (Inter + Fira Code)
- Structure de dossiers créée

### Phase 2 : Templates et Structure (Semaine 1-2)

**Objectifs** :
- Création des templates HTML
- Template parts (header, footer)
- Structure PHP de base

**Livrables** :
- Tous les templates créés
- Header et footer fonctionnels
- Classes PHP de base (Jardin_Blocks, Jardin_TOC)

### Phase 3 : Bloc Table des Matières (Semaine 2)

**Objectifs** :
- Bloc personnalisé ToC
- Génération automatique des IDs
- Navigation smooth scroll

**Livrables** :
- Bloc ToC fonctionnel
- Génération automatique des IDs
- Navigation fluide

### Phase 4 : Design et Responsive (Semaine 2-3)

**Objectifs** :
- Finalisation du design
- Responsive design
- Optimisations

**Livrables** :
- Design finalisé
- Responsive fonctionnel
- Optimisations appliquées

### Phase 5 : Tests et Finalisation (Semaine 3)

**Objectifs** :
- Tests complets
- Corrections finales
- Validation des critères d'acceptation

**Livrables** :
- Tests validés
- Thème prêt pour production

---

## Checklist d'Implémentation

### Fondations
- [ ] Créer la structure de dossiers
- [ ] Configurer theme.json (couleurs, typo, layout)
- [ ] Créer functions.php avec chargement des classes
- [ ] Créer les classes PHP minimales (Jardin_Blocks, Jardin_TOC)

### Templates
- [ ] Créer templates/index.html
- [ ] Créer templates/single.html
- [ ] Créer templates/page.html
- [ ] Créer templates/archive.html
- [ ] Créer templates/404.html
- [ ] Créer parts/header.html
- [ ] Créer parts/footer.html

### Bloc ToC
- [ ] Créer inc/blocks/table-of-contents/block.json
- [ ] Créer inc/blocks/table-of-contents/render.php
- [ ] Implémenter la génération automatique de la ToC
- [ ] Ajouter la navigation smooth scroll
- [ ] Configurer les styles via theme.json

### Accessibilité
- [ ] Vérifier les skip links WordPress core
- [ ] Améliorer les styles de focus via theme.json
- [ ] Ajouter le support prefers-reduced-motion
- [ ] Tester la navigation clavier
- [ ] Vérifier les contrastes WCAG AA

### Polices
- [ ] Télécharger Inter (Variable Font)
- [ ] Télécharger Fira Code (Variable Font)
- [ ] Placer dans assets/fonts/
- [ ] Configurer dans theme.json
- [ ] Ajouter preload dans header si nécessaire

### Tests
- [ ] Tester tous les templates
- [ ] Tester le bloc ToC
- [ ] Tester la navigation
- [ ] Tester le responsive
- [ ] Tester l'accessibilité
- [ ] Tester la performance

---

## Risques et Mitigation

### Risque 1 : Complexité du ToC

**Risque** : Le système de ToC peut être plus complexe que prévu

**Mitigation** :
- Commencer par une version simple
- Itérer progressivement
- Utiliser CSS pour le smooth scroll si possible

### Risque 2 : Compatibilité du Contenu

**Risque** : Le contenu existant peut nécessiter des adaptations

**Mitigation** :
- Tests précoces sur environnement de staging
- Documentation des adaptations nécessaires
- Plan de rollback préparé

### Risque 3 : Performance

**Risque** : Les performances peuvent être dégradées

**Mitigation** :
- Optimisations dès le début
- Tests de performance réguliers
- Utilisation des bonnes pratiques WordPress

### Risque 4 : Délais

**Risque** : Les délais peuvent être dépassés

**Mitigation** :
- Priorisation stricte (MVP uniquement)
- Fonctionnalités exclues clairement identifiées
- Itérations courtes et fréquentes

---

## Métriques de Succès

### Métriques Techniques

- **Performance** : Score Lighthouse > 90
- **Accessibilité** : Score WAVE > 95
- **SEO** : Score Lighthouse > 90
- **Compatibilité** : 100% des blocs natifs fonctionnels

### Métriques Utilisateur

- **Lisibilité** : Feedback positif sur la lisibilité
- **Navigation** : Navigation intuitive
- **Design** : Design apprécié et cohérent
- **Expérience** : Expérience de lecture agréable

---

## Prochaines Itérations

### Itération 2 : Enrichissements

**Fonctionnalités** :
- Reading Time & Word Count (si nécessaire)
- Sidenotes simplifiées (si nécessaire)
- Layout rails (ToC en marge) (si nécessaire)
- Optimisations avancées

**Timeline** : 2-3 semaines après MVP

### Itération 3 : Thèmes et Variantes

**Fonctionnalités** :
- Système de thèmes (clair/sombre)
- Variantes de couleurs
- Personnalisations avancées

**Timeline** : 1-2 mois après MVP

---

## Conclusion

Le MVP du thème Jardin se concentre sur les fonctionnalités essentielles pour créer un digital garden fonctionnel et élégant. L'approche minimaliste permet de livrer rapidement une première version tout en gardant la flexibilité pour les améliorations futures.

**Focus MVP** :
- ✅ Fondations solides (theme.json, structure)
- ✅ Fonctionnalité essentielle (ToC)
- ✅ Design minimaliste et élégant
- ✅ Accessibilité et performance
- ✅ Compatibilité avec le contenu existant

**Exclu du MVP** :
- ❌ Reading Time & Word Count
- ❌ Sidenotes (utiliser footnotes WordPress)
- ❌ Thèmes clair/sombre
- ❌ Layout rails avancé

