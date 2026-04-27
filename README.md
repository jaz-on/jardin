# jardin-theme

WordPress **block theme** (FSE) for [jasonrouet.com](https://jasonrouet.com) — **repository reset** on branch `dev` to start implementation from a clean tree (previous experiments removed from Git history on this branch going forward via this commit).

## Where the specs live

Product and theme specifications are in the local sibling folder **`jardin-docs`** (not versioned inside this repo). From this clone: **`../jardin-docs/`** — start with `roadmap.md` and `theme/theme-json-spec.md`.

## Current contents

This branch intentionally holds only **`.gitignore`**, **`LICENSE`**, and this **`README.md`** until the Phase 2 bootstrap is re-applied on top of this empty base.

## If the editor still shows old folders

Run `git pull origin dev` in this folder, then **reload the Cursor / VS Code window** (or re-open the workspace) so the file explorer refreshes. A full clean match to remote is: `git fetch origin && git reset --hard origin/dev && git clean -fd` (avoid `git clean -x` unless you intend to delete ignored local files such as under `.cursor/rules/`).
