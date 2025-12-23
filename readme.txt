=== Jardin ===
Contributors: jasonrouet
Requires at least: 6.5
Tested up to: 6.9
Requires PHP: 8.1
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: jardin
Tags: blog, block-theme, full-site-editing, one-column

Un thème WordPress minimaliste pour cultiver ses idées sur le web.

== Description ==

Jardin est un thème WordPress block theme (Full Site Editing) pensé dans l'esprit KISS et minimaliste, pour servir de base à la création d'un site personnel de type "digital garden".

Il met l'accent sur :

* une typographie lisible (Inter + Atkinson Hyperlegible + Calligraffitti + Fira Code),
* un design sombre minimaliste inspiré de jasonnade.fr,
* une table des matières intégrée pour les longs contenus,
* une configuration theme.json-first pour profiter au maximum de l'éditeur de site.

== Installation ==

1. Téléchargez le thème.
2. Téléversez-le dans le dossier `/wp-content/themes/`.
3. Activez-le via `Apparence > Thèmes`.
4. Ouvrez l'éditeur de site (`Apparence > Editeur`) pour personnaliser les templates.

== Fonctionnalités ==

* Full Site Editing (templates HTML + template parts).
* Bloc personnalisé `jardin/table-of-contents` pour générer une ToC à partir des titres.
* Palette de couleurs personnalisée alignée sur jasonnade.fr.
* Polices locales (Inter, Atkinson Hyperlegible, Calligraffitti, Fira Code) avec licences incluses.
* Styles de focus et support `prefers-reduced-motion`.

== Frequently Asked Questions ==

= Où modifier les templates (auteur, catégorie, etc.) ? =

Tous les templates (y compris `author`, `category`, `tag`, `date`, `home`)
sont éditables via l'éditeur de site (`Apparence > Editeur > Templates`).

= Comment utiliser le bloc Table des matières ? =

Ajoutez le bloc **Table of Contents (Jardin)** en début d'article. Le bloc
détecte automatiquement les titres H2–H6 et crée une navigation interne.

== Changelog ==

Voir le fichier [CHANGELOG.md](CHANGELOG.md) pour l'historique complet des modifications.

