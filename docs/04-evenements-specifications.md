# Spécifications — événements (CPT `event`)

## Objectifs

- Centraliser les informations sur les événements (WordCamp, journées de contribution, etc.).
- Afficher les **prochains** événements et l’**historique** des événements passés.
- Séparation claire : **plugin** = données et enregistrements WordPress ; **thème** = gabarits FSE et styles.

---

## Répartition plugin / thème

| Zone | Responsable | Contenu |
|------|-------------|---------|
| CPT `event`, métas (`event_date`, etc.), REST, requêtes PHP | Plugin **Jardin Events** (`jaz-on/jardin-event`) | `inc/class-events-core.php`, etc. |
| Motif de bloc « Prochains événements » par défaut | Plugin | `patterns/events-upcoming.php` → slug `jardin-event/upcoming-events` |
| Gabarit d’archive minimal (repli) | Plugin | `templates/archive-event.html` |
| Styles de base (listes, titres) | Plugin | `assets/css/events-base.css` |
| Gabarit d’archive personnalisé (deux sections, pagination) | Thème **Jardin** | `templates/archive-event.html` (remplace le repli du plugin quand le thème est actif) |
| Styles complémentaires (ex. ligne « passés ») | Thème | `assets/css/theme-styles.css` |
| Notice admin si le plugin est absent | Thème | `inc/jardin-events-integration.php` |

Fonctions publiques côté plugin : `jardin_events_is_active()`, `jardin_events_get_upcoming()`, `jardin_events_get_past()`, `jardin_events_format_date()`, etc.

---

## 1. Type de contenu et métadonnées

Détail fonctionnel inchangé : CPT `event`, supports titre / éditeur / extrait / miniature, archive publique (slug `events` côté plugin), métas `event_date`, `event_end_date`, `event_location`, `event_link` avec `register_post_meta()` et `show_in_rest: true`.

---

## 2. Filtrage futur / passé (logique métier)

- Référence : `Jardin_Events_Core` dans le plugin (`meta_query`, `event_date`, etc.).
- Les boucles Query Loop dans les fichiers HTML du thème utilisent les réglages du bloc ; pour coller exactement à la logique métier (dates d’événement vs date de publication), il faudra des extensions côté plugin (filtres de requête ou blocs dédiés) — hors périmètre de ce document de structure.

---

## 3. Motif « Prochains événements »

- Source : plugin uniquement (éviter tout doublon dans le thème).
- Insertion : éditeur de site → motifs → **Prochains événements (Jardin Events)**.

---

## 4. Archive `/events/`

- Avec le thème Jardin actif : mise en page à partir de `templates/archive-event.html` du thème (sections prochains / passés).
- Sans ce gabarit : repli sur le template du plugin.

---

## 5. Workflow éditeur

1. Créer un événement sous **Événements** (une fois le plugin actif).
2. Pour la page d’accueil : insérer le motif fourni par le plugin.
3. Archive publique : URL d’archive du CPT (ex. `/events/` selon réglages permaliens).

---

## 6. Références internes

- `docs/01-vision-principes.md`
- `docs/02-guide-developpement.md`
- `docs/03-mvp-specifications.md`
