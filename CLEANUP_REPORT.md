# Laravel Codebase Cleanup Report

**Date:** 2026-06-15  
**Project:** 5th Pillar Takaful (Laravel 12)

This report documents verified removals, retained dependencies, and items flagged for manual review. No Composer or NPM packages were removed—all production dependencies are in use.

---

## 1. Files removed (backup list)

### Dead PHP controllers (no routes, superseded by `PageController` + CMS)

| Path | Reason |
|------|--------|
| `app/Http/Controllers/CompanyController.php` | Legacy stub; referenced non-existent `company.*` views |
| `app/Http/Controllers/SitemapController.php` | Empty stub |
| `app/Http/Controllers/DownloadController.php` | Empty stub |
| `app/Http/Controllers/FundController.php` | Empty stub |
| `app/Http/Controllers/GovernanceController.php` | Empty stub |
| `app/Http/Controllers/InvestorController.php` | Empty stub |
| `app/Http/Controllers/MediaController.php` | Empty stub (not Admin `CmsMediaController`) |
| `app/Http/Controllers/ProductController.php` | Empty stub |

### Unused views & assets

| Path | Reason |
|------|--------|
| `resources/views/welcome.blade.php` | Laravel default; not routed (`HomeController` serves `home/index`) |
| `public/assets/css/pages/welcome-fallback.css` | Only referenced by removed `welcome.blade.php` |

### Unused published pagination views

`AppServiceProvider` sets default pagination to `vendor.pagination.admin-wp` only.

| Path | Reason |
|------|--------|
| `resources/views/vendor/pagination/bootstrap-4.blade.php` | Not referenced |
| `resources/views/vendor/pagination/bootstrap-5.blade.php` | Not referenced |
| `resources/views/vendor/pagination/default.blade.php` | Not referenced |
| `resources/views/vendor/pagination/semantic-ui.blade.php` | Not referenced |
| `resources/views/vendor/pagination/simple-bootstrap-4.blade.php` | Not referenced |
| `resources/views/vendor/pagination/simple-bootstrap-5.blade.php` | Not referenced |
| `resources/views/vendor/pagination/simple-default.blade.php` | Not referenced |
| `resources/views/vendor/pagination/simple-tailwind.blade.php` | Not referenced |
| `resources/views/vendor/pagination/tailwind.blade.php` | Not referenced |

**Kept:** `admin-wp.blade.php`, `simple-admin-wp.blade.php`, `pagination/news-events.blade.php`

### One-off dev / migration scripts

| Path | Reason |
|------|--------|
| `translate_homepage.php` | One-off Urdu homepage script; not part of app bootstrap |
| `scratch/scan_xlsx.php` | Local dev utility |
| `scratch/peek_xlsx.php` | Local dev utility |
| `scratch/analyze_db.php` | Local dev utility |

### Duplicate / obsolete private storage artifacts

| Path | Reason |
|------|--------|
| `storage/app/private/original-about.html` | WordPress import source (~281 KB); not loaded at runtime |
| `storage/app/private/original-home.html` | WordPress import source (~319 KB); not loaded at runtime |
| `storage/app/private/news-events-data.json` | Duplicate; canonical file is `resources/data/news-events-data.json` |

**Kept in `storage/app/private/`:** `wp-news-posts.json`, `wp-news-posts-embed.json` (source for `database/scripts/build-news-events-data.php`)

---

## 2. Folders removed / emptied

| Path | Status |
|------|--------|
| `scratch/` | Emptied (all PHP scripts removed); empty directory may remain |

No large public asset directories were deleted (see review list below).

---

## 3. Composer packages — none removed

### Production (`require`)

| Package | Status |
|---------|--------|
| `laravel/framework` | Core |
| `laravel/tinker` | REPL / debugging |
| `maatwebsite/excel` | Hajj/Umrah financial data import (`FinancialDataController`, Artisan import) |

### Development (`require-dev`)

| Package | Status |
|---------|--------|
| `fakerphp/faker` | Tests / seeding |
| `laravel/pail` | Log tailing (`composer dev` script) |
| `laravel/pint` | Code style |
| `laravel/sail` | Docker dev environment |
| `mockery/mockery` | Testing |
| `nunomaduro/collision` | CLI error reporting |
| `phpunit/phpunit` | Tests |

---

## 4. NPM packages — none removed

| Package | Status |
|---------|--------|
| `vite`, `laravel-vite-plugin` | Laravel build toolchain (`composer setup`, `composer dev`) |
| `tailwindcss`, `@tailwindcss/vite` | `resources/css/app.css` (Vite entry) |
| `axios` | `resources/js/bootstrap.js` |
| `concurrently` | `composer dev` multi-process script |

**Note:** The live site uses `public/assets/` (WordPress-migrated theme), not Vite-built bundles. Vite remains part of the standard Laravel scaffold and dev workflow.

---

## 5. Marked for review (NOT deleted)

### High disk usage — verify DB/CMS references before deleting

| Path | Risk |
|------|------|
| `public/uploads/` | Legacy WordPress uploads; many paths mapped via `config/upload-to-assets-map.php` and `PublicPath`; some files may still be linked in CMS HTML |
| `public/wp-content/` | Still referenced directly in blades (e.g. home banner, careers image) and legacy paths |
| `public/uploads/pum/pum-site-scripts.js` | Popup maker legacy JS |

**Recommended next step:** Run a path audit against `pages`, `nav_menu_items`, and `cms_media` tables, then migrate remaining `wp-content/uploads` references to `assets/` before bulk deletion.

### Manual / one-off tooling (keep unless team agrees to drop)

| Path | Purpose |
|------|---------|
| `app/Console/Commands/ImportPagesCommand.php` | WP page import |
| `app/Console/Commands/ImportFinancialDataCommand.php` | CLI financial import |
| `app/Console/Commands/ImportSqliteToMysql.php` | DB migration utility |
| `database/seeders/AboutUsUrduSeeder.php` | Manual Urdu content |
| `database/seeders/HomepageUrduSeeder.php` | Manual Urdu content |
| `database/scripts/build-news-events-data.php` | Regenerate news JSON from WP export |
| `database/factories/UserFactory.php` | No current tests use factories; standard Laravel scaffold |

### Vite / welcome stack

| Path | Notes |
|------|-------|
| `resources/css/app.css`, `resources/js/app.js` | Only used if a Vite-powered view is added; `welcome.blade.php` was removed |

---

## 6. Structural observations & improvements applied

1. **Removed 8 unrouted controllers** — reduces autoload noise and confusion with active `PageController` CMS routing.
2. **Pagination views** — only admin + news-events templates remain; matches `AppServiceProvider` configuration.
3. **Private storage** — removed duplicate/stale JSON and HTML import snapshots; canonical news data lives in `resources/data/`.
4. **Documentation** — updated `database/scripts/build-news-events-data.php` header to reflect correct output path.

### Suggested future structure (not applied in this pass)

- Move one-off scripts under `database/scripts/` or `tools/` with README.
- Consolidate page-specific CSS under `public/assets/css/pages/` (already done).
- Gradually migrate `wp-content/uploads` image URLs in blades to `assets/images/`.
- Consider removing empty `scratch/` directory after confirming no local workflows depend on it.

---

## 7. Verification performed

| Check | Result |
|-------|--------|
| `composer dump-autoload -o` | OK |
| `php artisan route:list` | OK — all routes resolve |
| `php artisan test` | OK — 2 tests passed |
| `php artisan view:cache` | OK (prior session) |

---

## 8. Issues discovered during cleanup

1. **Dual asset systems:** Production UI loads `public/assets/vendor/original-theme/*`; Vite/Tailwind stack is largely scaffold-only.
2. **Legacy path rewriting:** `PublicPath` and `upload-to-assets-map.php` actively rewrite `/uploads/` → `/assets/`; deleting `public/uploads/` without audit would break unmigrated links.
3. **Direct `wp-content` references** in `resources/views/home/index.blade.php` and `careers.blade.php` — these paths must stay until images are moved.
4. **Form submission history:** Contact forms only persist to DB after recent `form_submissions` migration; counts on admin dashboard reflect post-migration data only.

---

## 9. Approximate space recovered

| Category | Approx. size |
|----------|----------------|
| Dead controllers + views + CSS | ~90 KB |
| `original-about.html` + `original-home.html` | ~600 KB |
| Duplicate `news-events-data.json` | ~82 KB |
| Scratch + translate script | ~10 KB |

**Total confirmed removals:** ~780 KB + pagination view stubs.

**Not removed:** `public/uploads/` and `public/wp-content/` (likely GB-scale; requires separate audit).

---

*End of report.*
