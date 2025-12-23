# Polices du Thème Jardin

Ce thème utilise trois polices principales, toutes incluses avec leurs licences respectives.

## Polices Incluses

### 1. Inter (Variable Font)
**Dossier :** `inter/`
**Fichiers :**
- `InterVariable.woff2` - Police variable standard
- `InterVariable-Italic.woff2` - Police variable italique
- `OFL-Inter.txt` - Licence Open Font License

**Usage :** Police principale pour le texte et l'interface utilisateur.

### 2. Calligraffitti
**Dossier :** `calligraffitti/`
**Fichiers :**
- `Calligraffitti-Regular.woff2` - Police décorative (format web optimisé)
- `Calligraffitti-Regular.ttf` - Police décorative (fallback)
- `Apache-Calligraffitti.txt` - Licence Apache 2.0

**Usage :** Police décorative pour les titres et éléments spéciaux (effet "Butter").

### 3. Fira Code (Variable Font)
**Dossier :** `fira-code/`
**Fichiers :**
- `FiraCode-VF.woff2` - Police monospace variable
- `OFL-FiraCode.txt` - Licence Open Font License

**Usage :** Police monospace pour le code et les éléments techniques.

## Structure Actuelle

```
assets/fonts/
├── README.md (ce fichier)
├── inter/
│   ├── InterVariable.woff2
│   ├── InterVariable-Italic.woff2
│   └── OFL-Inter.txt
├── calligraffitti/
│   ├── Calligraffitti-Regular.woff2
│   ├── Calligraffitti-Regular.ttf
│   └── Apache-Calligraffitti.txt
└── fira-code/
    ├── FiraCode-VF.woff2
    └── OFL-FiraCode.txt
```

## Fonctionnalités

Le thème charge automatiquement ces polices avec :
- ✅ Preload pour des performances optimales
- ✅ Fallbacks système intégrés
- ✅ Support des polices variables pour Inter et Fira Code
- ✅ Formats web optimisés (WOFF2) avec fallbacks TTF
- ✅ Effet "Butter" appliqué avec Calligraffitti

## Licences

Les polices sont distribuées sous licences libres :
- **Inter** : Licence incluse dans `inter/OFL-Inter.txt`
- **Calligraffitti** : Licence incluse dans `calligraffitti/Apache-Calligraffitti.txt`
- **Fira Code** : Licence incluse dans `fira-code/OFL-FiraCode.txt`

## Fallbacks

En cas de problème de chargement, le thème utilisera :
- **Inter** → Polices système (Apple System, Segoe UI, Roboto, etc.)
- **Calligraffitti** → Police cursive générique
- **Fira Code** → Polices monospace système (SF Mono, Consolas, etc.)
