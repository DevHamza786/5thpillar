# Asset Migration Report

**Date:** 2026-06-15  
**Project:** 5th Pillar Takaful (Laravel)

## Summary

Legacy WordPress asset paths (`/uploads/`, `/wp-content/`) were migrated into Laravel’s `public/assets/` structure. References in Blade templates, config, JSON data, and the CMS database were updated. Legacy path rewriting via `upload-to-assets-map.php` was removed from runtime code.

---

## Target structure (implemented)

| Type | Path |
|------|------|
| Images | `public/assets/images/` (subfolders: `home/`, `news/`, `careers/`, `cms/`, `favicons/`, …) |
| PDFs | `public/assets/pdf/` (was `assets/pdfs/` — renamed) |
| CSS | `public/assets/css/` + `public/assets/vendor/` (theme) |
| JavaScript | `public/assets/js/` + vendor bundles |
| Fonts | `public/assets/fonts/` |
| Videos | `public/assets/videos/` (ready; no active video assets migrated) |

---

## Phase 1 — Audit (before migration)

| Source | Count |
|--------|-------|
| Path map entries | 238 |
| Files in `public/uploads/` | 7,813 |
| Files in `public/wp-content/` | 2 |
| DB `pages.content` with `uploads/` | 34 |
| `news-events-data.json` wp-content URLs | 54 |

---

## Phase 2 — File migration

Command: `php artisan assets:migrate-legacy`

| Result | Count |
|--------|-------|
| Files moved into `public/assets/` | 57 |
| Already at destination | 95 |
| Missing source on disk | 86 |
| News images migrated to `assets/images/news/` | 54 paths |
| Home popup banner | `assets/images/home/cdc-web-banner.webp` |
| Fonts (Raleway) | `assets/fonts/raleway.woff2`, `raleway.woff` |

Follow-up: `php scripts/sync-cms-image-fallbacks.php` copied 4 CMS background images into `assets/images/cms/`.

**Note:** Many PDFs listed in the legacy map are not present in this local environment (production-only files). Deploy production `uploads/` or PDF archive before deleting `public/uploads/`.

---

## Phase 3 — Reference updates

| Area | Action |
|------|--------|
| Blade / PHP / config | `assets/pdfs/` → `assets/pdf/`; `wp-content/uploads/` → `assets/images/…` |
| `resources/data/news-events-data.json` | Images → `assets/images/news/{year}/{month}/{file}` |
| Database (`pages`, `nav_menu_items`, `cms_media`) | 26 rows + 22 CMS rows via `fix-cms-upload-paths.php` |
| `PublicPath` | Removed `upload-to-assets-map` rewriting; PDF viewer uses `assets/pdf/` |
| `server.php`, `.htaccess` | PDF redirect rule updated to `/assets/pdf/` |
| `config/brochures.php` | Paths under `assets/pdf/` |

---

## Phase 4 — CSS / JS

Active site assets remain in:

- `public/assets/css/` (admin + page-specific)
- `public/assets/js/` (planners, brochure modal, admin)
- `public/assets/vendor/original-theme/` (WordPress theme bundle — still required for layout)

WordPress plugin caches under `public/uploads/` (elementor, wpforms, revslider, etc.) were **not** migrated — they are not loaded by the Laravel app.

---

## Phase 5 — Validation

| Check | Result |
|-------|--------|
| `php artisan test` | Pass |
| Legacy refs in `resources/` blades | None |
| `news-events-data.json` wp-content URLs | 0 |
| Known gaps (local env) | Some PDFs + ~74 CMS images missing source files on disk |

Broken-link check: run on production after syncing files:

```bash
php artisan assets:migrate-legacy --skip-files --skip-refs
```

---

## Phase 6 — Cleanup

| Item | Status |
|------|--------|
| `public/wp-content/` | **Deleted** |
| `config/upload-to-assets-map.php` | **Removed** (archive: `database/scripts/legacy-upload-map.php`) |
| `PublicPath` legacy upload map | **Removed** |
| `public/uploads/` | **Retained** — still holds ~7k files; many CMS fallbacks and unmigrated PDFs reference sources here. Delete only after production file sync and link audit. |

To remove `public/uploads/` safely on production:

1. Run `php artisan assets:migrate-legacy` on a server with full `uploads/` tree.
2. Run `php scripts/fix-cms-upload-paths.php` and `php scripts/sync-cms-image-fallbacks.php`.
3. Verify PDFs and images in admin + key public pages.
4. `php artisan assets:migrate-legacy --cleanup` (or manually remove `public/uploads/`).

---

## Tooling added

| Command / script | Purpose |
|----------------|---------|
| `php artisan assets:migrate-legacy` | Audit, migrate files, update refs, validate |
| `php scripts/audit-legacy-assets.php` | JSON audit report |
| `php scripts/fix-cms-upload-paths.php` | CMS HTML `uploads/…?id=` → `assets/images/cms/…` |
| `php scripts/sync-cms-image-fallbacks.php` | Copy CMS images from `uploads/` to `assets/images/cms/` |
| `database/scripts/legacy-upload-map.php` | Archived path map (reference only) |

---

## Before / after path examples

| Before | After |
|--------|-------|
| `wp-content/uploads/2025/12/xCDC-Web-Banner-2.jpg.pagespeed.ic…webp` | `assets/images/home/cdc-web-banner.webp` |
| `https://5thpillartakaful.com/wp-content/uploads/2025/09/cxo-1.jpg` | `assets/images/news/2025/09/cxo-1.jpg` |
| `/uploads/2026/04/Notice-of-AGM-2026.pdf` | `/assets/pdf/investors/Notice-of-AGM-2026.pdf` |
| `assets/pdfs/forms/8-Complaint-Resolution-Forum.pdf` | `assets/pdf/forms/8-Complaint-Resolution-Forum.pdf` |
| `uploads/2017/07/bg.jpg?id=145` (CMS HTML) | `assets/images/cms/2017/07/bg.jpg` |

---

## Risks & follow-up

1. **Production PDFs** — Sync all files from production `uploads/` before deleting that folder.
2. **Unpublished WP demo pages** — Slugs like `home-2`, `typography`, `shortcodes` still exist in DB with CMS image paths; low risk if unpublished.
3. **Brochure PDFs** — Ensure brochure PDF files exist under `public/assets/pdf/` (or env overrides).
4. **Careers image** — Pulled from live site into `assets/images/careers/executive-officer-customer-services.jpg` when missing locally.

---

*End of report.*
