# Optional HTML seeds for **Jardin dev pages**

Files here are read by **Appearance → Jardin dev pages** and `wp jardin-seed` when you use **Sync content** (or when a page is **created** for the first time).

- **Basenames** must match the manifest in `inc/page-seed.php` (e.g. `journal.html`, `styleguide.html`).
- **Body**: raw HTML or a single `core/html` block; the importer wraps plain HTML in a block-friendly shape when needed.
- **Missing file**: new pages get a minimal empty block paragraph; existing pages keep their current body unless you explicitly sync.

For a dev site cloned from production, run **Import** (or `wp jardin-seed import`) **without** sync: templates and hierarchy update, **post_content** of existing pages is left unchanged.
