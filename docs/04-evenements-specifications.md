# Spécifications - Système d'Événements (CPT `event`)

## Objectifs

- Centraliser les informations sur les événements (WordCamp, journées de contribution, etc.).
- Afficher automatiquement :
  - les **prochains événements** (home, sidebar, page dédiée),
  - l’**historique** des événements passés (archive).
- Garder un système simple, WordPress-first, sans JavaScript inutile.

---

## 1. Type de Contenu Personnalisé `event`

### 1.1. Enregistrement du CPT

- **Slug** : `event`
- **Libellé admin** : `Événements`
- **Plural name** : `Événements`
- **Singular name** : `Événement`
- **Public** : `true`
- **Show in REST** : `true` (pour compatibilité éditeur de blocs)
- **Has archive** : `true` (page `/events/` ou équivalent)
- **Menu position** : proche des Articles
- **Menu icon** : `dashicons-calendar-alt`
- **Supports** :
  - `title`
  - `editor` (description optionnelle)
  - `excerpt` (accroche courte)
  - `thumbnail` (optionnel)

### 1.2. Taxonomies

- Utiliser les taxonomies core uniquement au départ :
  - `category` (si besoin de regrouper certains événements),
  - `post_tag` (mots-clés libres).
- Pas de nouvelle taxonomie custom dans le MVP.

---

## 2. Métadonnées / Champs Personnalisés

### 2.1. Champs requis

- `event_date`
  - Type : date (stockée en `Y-m-d`).
  - Rôle : date principale de l’événement (si multi-jours, date de début).

- `event_end_date` (optionnel, mais prévu)
  - Type : date `Y-m-d`.
  - Rôle : date de fin pour les événements multi-jours.

- `event_location`
  - Type : texte court.
  - Exemples : `Nice`, `Cracovie, Pologne`.

- `event_link`
  - Type : URL.
  - Rôle : lien “En savoir plus” vers le site officiel ou une page dédiée.

### 2.2. Implémentation

- Métadonnées enregistrées via `register_post_meta()` avec :
  - `show_in_rest: true`
  - `single: true`
  - `type` adapté (`string`).
- Interface d’édition :
  - MVP : champ de métadonnées via une simple **meta box PHP** dans l’éditeur classique,
  - Itération future possible : bloc ou panel React dans l’éditeur.

---

## 3. Logique de Filtrage (Futur vs Passé)

### 3.1. Définition des périodes

- **Aujourd’hui** = date server (format `Y-m-d` via `current_time( 'Y-m-d' )`).
- **Événements futurs** :
  - `event_date >= aujourd’hui`
  - OU, si `event_end_date` est défini : `event_end_date >= aujourd’hui`.
- **Événements passés** :
  - Tous les autres (`event_date < aujourd’hui` ET `event_end_date < aujourd’hui`).

### 3.2. Utilisation dans les requêtes

- Requêtes pour “prochains événements” :
  - `post_type: event`
  - `posts_per_page: 3` (ou paramétrable)
  - `meta_query` sur `event_date` (et `event_end_date` si présent)
  - `orderby: meta_value`, `meta_key: event_date`, `order: ASC`.

- Requêtes pour “historique des événements” :
  - `post_type: event`
  - `posts_per_page: 10` (pagination)
  - `event_date < aujourd’hui` (ou `event_end_date < aujourd’hui`)
  - `orderby: meta_value`, `meta_key: event_date`, `order: DESC`.

### 3.3. Lieu d’implémentation

- Créer une classe dédiée `Jardin_Events` dans `inc/class-jardin-events.php` :
  - Enregistrement du CPT et des métas.
  - Fonctions utilitaires pour construire les `meta_query`.
  - Hooks pour ajuster certaines requêtes du bloc Query.
- Chargement dans `functions.php` (comme les autres classes).

---

## 4. Pattern “Prochains événements”

### 4.1. Objectif

Reproduire le visuel actuel de la home sur jasonrouet.com :

- Titre principal : “J’y serai, on s’y retrouve ?”
- Bloc jaune centré avec :
  - titre “Prochains événements”,
  - liste verticale des événements,
  - pour chaque événement : nom, date, lieu, lien “En savoir plus”.

### 4.2. Structure du pattern

Fichier : `patterns/events-upcoming.php` (ou équivalent) avec `block.json` si on suit le modèle des patterns modernes.

- Structure côté blocs :
  - `core/group` main (fond jaune, padding)
  - `core/heading` H2 “Prochains événements”
  - `core/separator` (ligne fine)
  - **Bloc Query Loop** configuré pour :
    - `postType: event`
    - `postsPerPage: 3`
    - `orderby: meta_value` sur `event_date`
    - `order: ASC`
  - À l’intérieur de la boucle :
    - `core/paragraph` : nom de l’événement (titre du CPT).
    - `core/paragraph` : date formatée (`event_date` +/- `event_end_date`).
    - `core/paragraph` : lieu (`event_location`, italique).
    - `core/paragraph` avec lien stylé “En savoir plus…” (`event_link`).
    - `core/separator` entre chaque bloc.

### 4.3. Filtrage “futur uniquement”

- Deux options possibles :
  1. **Filtrage via `pre_get_posts`** :
     - Cibler la requête du Query Loop par un `queryId` spécifique (ex: `upcoming-events-home`).
     - Ajouter la `meta_query` pour `event_date >= today`.
  2. **Filtrage via rendu dynamique** :
     - Créer un bloc dynamique `jardin/upcoming-events` (option post-MVP).

- MVP : **Option 1** (simple `pre_get_posts` ciblé).

---

## 5. Page d’Archive des Événements

### 5.1. Archive principale (`/events/`)

- Utiliser l’archive native du CPT :
  - Template : `templates/archive-event.html`.

- Contenu :
  - Titre principal : “Événements”.
  - Deux sections :
    1. **Prochains événements** (même Query Loop que le pattern, sans limite stricte ou avec `posts_per_page` paramétrable).
    2. **Événements passés** :
       - Query Loop séparée pour les événements passés.
       - Ordre décroissant sur `event_date`.

### 5.2. Intégration FSE

- Tous les blocs restent **éditables dans l’éditeur de site** :
  - Le HTML de base est dans `archive-event.html`.
  - L’utilisateur peut ajuster la mise en page sans toucher au PHP.

---

## 6. Workflow Éditeur

1. **Créer un nouvel événement** :
   - Aller dans `Événements > Ajouter`.
   - Renseigner :
     - Titre (ex : `WordCamp Nice`),
     - Date de début (et date de fin si besoin),
     - Lieu (Nice, Cracovie, etc.),
     - Lien “En savoir plus” (site officiel, page interne).
   - Optionnel : contenu détaillé dans l’éditeur + image mise en avant.

2. **Afficher les prochains événements sur la home** :
   - Ouvrir l’éditeur de site (`Apparence > Editeur`).
   - Sur le template de la home, insérer le **pattern “Prochains événements”**.
   - Sauvegarder.

3. **Consulter l’historique** :
   - Accéder à la page d’archive `/events/` (ou slug défini).
   - La section “Événements passés” liste automatiquement tout l’historique.

---

## 7. MVP vs Itérations Futures

### 7.1. Inclus dans le MVP

- CPT `event` + métas (date, lieu, lien).
- Pattern “Prochains événements” (home ou n’importe où).
- Logiciel de filtrage futur/passé basique (via `pre_get_posts`).
- Archive principale `archive-event.html` avec deux listes (futur / passé).

### 7.2. Idées post-MVP

- Bloc dynamique `jardin/upcoming-events` avec options dans l’éditeur (nombre d’items, affichage lieu, etc.).
- Support multi-jours plus avancé (intervalles de dates, état “en cours”).
- Ajout d’un champ “Type d’événement” (WordCamp, meetup, journée de contribution).
- Intégration avec des icônes (par ex. dashicons ou SVG minimalistes).

---

## 8. Références internes

- `docs/01-vision-principes.md` : philosophie minimaliste, WordPress-first.
- `docs/02-guide-developpement.md` : architecture des classes PHP, approche theme.json-first.
- `docs/03-mvp-specifications.md` : cadre général du MVP Jardin (ce système d’événements est une extension ciblée pour le site personnel).


