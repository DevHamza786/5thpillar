# Live server `.htaccess` fix — ERR_TOO_MANY_REDIRECTS

## Diagnosis (confirmed on live site)

| URL | Result |
|-----|--------|
| `https://5thpillartakaful.com/` | **301 loop** (often **CDN cached** bad response) |
| `https://5thpillartakaful.com/?v=1` | **200 OK** (bypasses CDN cache — origin works) |
| `https://5thpillartakaful.com/index.php` | **200 OK** |

So Laravel is fine — only the **homepage `/`** fails when document root is the **project root** and this rule runs:

```apache
RewriteRule ^(.*)$ public/$1 [L]
```

For `/`, that becomes `public/` (a real folder). Apache serves the directory instead of `index.php`, which triggers a **301 back to `/`** → infinite loop.

**Fix:** upload root `index.php` + root `.htaccess` from the repo, then **purge CDN cache** (see below).

---

## CDN cache (StackCP / StackCDN)

Even when **Global Edge Caching** is “Not Activated”, responses can still show `x-cdn-cache-status: HIT`. The homepage `301` may be **cached for 24 hours** (`cache-control: max-age=86400`).

After uploading fixed files:

1. StackCP → **CDN** → enable **Edge Caching** temporarily if needed → **Cache Purge** / **Purge all**
2. Or open **Traffic Distribution** / support and request purge for `https://5thpillartakaful.com/`
3. Test in **incognito** or with `https://5thpillartakaful.com/?v=2` (query string bypasses stale cache)

---

## Cause (general)

The broken rules on production were:

```apache
RewriteCond %{REQUEST_URI} !^/public/
RewriteRule ^(.*)$ /public/$1 [L,QSA]
```

combined with trailing-slash redirects and (often) hosting that hides `/public/` in URLs.

Typical loop:

1. Browser requests `/`
2. Apache rewrites to `/public/`
3. Host or another rule redirects `/public/` back to `/`
4. Repeat → **ERR_TOO_MANY_REDIRECTS**

Those `/public/` rules must **not** live inside `public/.htaccess` when the document root is already `public/`.

---

## Recommended setup (cPanel / shared hosting)

### Option A — Best (document root = `public/`)

1. In cPanel → **Domains** → **Document Root**, set:
   ```
   /home/username/5thpillar/public
   ```
   (adjust path to your project)

2. Use **only** the file from this repo:
   ```
   public/.htaccess
   ```

3. **Delete** any `/public/` rewrite rules from the server. There should be **no** parent `.htaccess` that rewrites to `/public/` unless Option B is required.

4. Production `.env`:
   ```env
   APP_URL=https://5thpillartakaful.com
   URDU_REDIRECT_TO_LIVE=false
   ```
   `URDU_REDIRECT_TO_LIVE=true` with `URDU_LIVE_BASE_URL=https://5thpillartakaful.com` causes `/urdu/*` to redirect to itself on the same domain.

---

### Option B — Document root = project root

If you cannot point the domain to `public/`:

1. Place the **root** `.htaccess` from this repo (project root, one level above `public/`):
   ```apache
   <IfModule mod_rewrite.c>
       RewriteEngine On
       RewriteRule ^(.*)$ public/$1 [L]
   </IfModule>
   ```

2. Keep `public/.htaccess` exactly as in the repo (no extra `/public/` rules).

3. Set:
   ```env
   APP_URL=https://5thpillartakaful.com
   ```
   Do **not** include `/public` in `APP_URL`.

---

## What to remove from live server

Remove this block from `public/.htaccess` if it exists there:

```apache
RewriteCond %{REQUEST_URI} !^/public/
RewriteRule ^(.*)$ /public/$1 [L,QSA]
```

Also remove duplicate front-controller rules at the project root if document root is already `public/`.

---

## After uploading

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

Hard-refresh the browser or test in a private window.

---

## Quick checklist

| Check | Correct value |
|-------|----------------|
| Document root | `.../public` (preferred) |
| `public/.htaccess` | Repo version only |
| Root `.htaccess` | Minimal `public/$1` rule OR none if doc root is `public/` |
| `APP_URL` | `https://5thpillartakaful.com` (no trailing `/public`) |
| `URDU_REDIRECT_TO_LIVE` | `false` on production |
