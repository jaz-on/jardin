# Jardin

Un thème WordPress minimaliste pour cultiver ses idées sur le web.

## Jardin et Jardin Events : deux dépôts, deux rôles

Les deux projets partagent la même vision (« jardin » numérique) mais ne vivent pas au même endroit dans WordPress :

| | **Jardin** (ce dépôt) | **Jardin Events** |
|---|------------------------|-------------------|
| **Rôle** | Thème block (FSE, `theme.json`, styles, motifs de base) | Plugin : événements (CPT, métadonnées, blocs, motifs liés aux événements) |
| **Dépôt** | [jaz-on/jardin](https://github.com/jaz-on/jardin) (`dev`) | [jaz-on/jardin-event](https://github.com/jaz-on/jardin-event) (`dev`) |
| **Installation** | `wp-content/themes/` (dossier du thème) | `wp-content/plugins/jardin-event/` |
| **Activation** | **Apparence** → Thèmes | **Extensions** |

Pour travailler sur le thème et le plugin dans le même environnement, clonez aussi [jardin-event](https://github.com/jaz-on/jardin-event) dans `wp-content/plugins/` (ou un workspace multi-dossiers). Les outils PHP du thème (**PHPCS**) s’installent avec `composer install` à la racine du thème uniquement.

## Installation (thème)

1. Placez le dossier du thème dans `wp-content/themes/`
2. Activez-le via **Apparence** → **Thèmes**

Les chaînes du thème sont dans le domaine `jardin` ; un catalogue français d’exemple est fourni (`languages/jardin-fr_FR.po` / `.mo`). Pour régénérer le binaire après édition du `.po` : `msgfmt -o languages/jardin-fr_FR.mo languages/jardin-fr_FR.po`.

## Licence

Comme **WordPress** et les thèmes / extensions distribués selon les mêmes principes que le projet officiel, ce thème est publié sous la **GNU General Public License v2 ou toute version ultérieure** (GPL‑2.0‑or‑later).

- Texte complet : fichier [`LICENSE`](LICENSE) à la racine du dépôt  
- Même mention dans les métadonnées du thème (`style.css`, `readme.txt`) et URI de licence : [https://www.gnu.org/licenses/gpl-2.0.html](https://www.gnu.org/licenses/gpl-2.0.html)

L’extension **Jardin Events** doit suivre la même licence (GPL‑2.0‑or‑later) pour rester alignée sur l’écosystème WordPress et sur ce thème.
