<?php

namespace App\Support;

use Illuminate\Support\Facades\URL;

/**
 * Public files under public/uploads/... — use Laravel's asset() helper so URLs
 * respect APP_URL (including subdirectory deployments like /5thpillar/public).
 * Root-relative "/uploads/..." breaks when the app is not at the domain root.
 */
final class PublicPath
{
    /**
     * @var array<string, string>
     */
    private const LEGACY_DOUBLE_YEAR_SEGMENTS = [
        '/uploads/2026/2026/04/' => '/uploads/2026/04/',
        '/uploads/2026/2026/03/' => '/uploads/2026/03/',
        '/uploads/2023/2023/09/' => '/uploads/2023/09/',
        '/uploads/2023/2023/12/' => '/uploads/2023/12/',
    ];

    public static function uploadHref(string $pathUnderPublic): string
    {
        $trimmed = ltrim($pathUnderPublic, '/');

        return asset($trimmed);
    }

    /**
     * Collapse legacy WordPress-style /uploads/YEAR/YEAR/mm/ to /uploads/YEAR/mm/.
     */
    public static function canonicalUploadPath(string $path): string
    {
        if ($path === '') {
            return $path;
        }
        if (! str_starts_with($path, '/')) {
            $path = '/'.$path;
        }

        return str_replace(
            array_keys(self::LEGACY_DOUBLE_YEAR_SEGMENTS),
            array_values(self::LEGACY_DOUBLE_YEAR_SEGMENTS),
            $path
        );
    }

    /**
     * Root-relative public paths (/uploads, /assets, /storage) → asset().
     * Other root-relative paths → application URL (subdirectory-safe).
     */
    public static function publicUrlForRootRelativePath(string $path): string
    {
        $path = trim($path);
        if ($path === '' || $path === '#' || str_starts_with($path, '//')) {
            return $path;
        }

        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        if (! str_starts_with($path, '/')) {
            return $path;
        }

        if (str_starts_with($path, '/uploads/')) {
            $canonical = self::canonicalUploadPath($path);

            return asset(ltrim($canonical, '/'));
        }

        if (str_starts_with($path, '/assets/') || str_starts_with($path, '/storage/')) {
            return asset(ltrim($path, '/'));
        }

        return URL::to($path);
    }

    /**
     * Fix CMS HTML: src/href and CSS url(...) that point to /uploads/, /assets/, or /storage/,
     * and fix internal links starting with / (same host, subdirectory deploys).
     */
    public static function rewriteHtmlPublicPaths(string $html): string
    {
        if ($html === '') {
            return $html;
        }

        $html = preg_replace_callback(
            '/\b(href|src)\s*=\s*("|\')([^"\']*)\2/i',
            static function (array $m): string {
                $val = html_entity_decode($m[3], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                if ($val === '' || $val === '#' || str_starts_with($val, 'javascript:')
                    || str_starts_with($val, 'mailto:') || str_starts_with($val, 'tel:')
                    || str_starts_with($val, 'data:')) {
                    return $m[0];
                }
                if (str_starts_with($val, '//') || preg_match('#^https?://#i', $val)) {
                    return $m[0];
                }
                if (! str_starts_with($val, '/')) {
                    return $m[0];
                }

                $new = self::publicUrlForRootRelativePath($val);
                if ($new === $val) {
                    return $m[0];
                }

                return $m[1].'='.$m[2].$new.$m[2];
            },
            $html
        ) ?? $html;

        $html = preg_replace_callback(
            '/\burl\s*\(\s*([^)]+)\s*\)/i',
            static function (array $m): string {
                $inner = trim($m[1]);
                if (preg_match('/^([\'"])(.+)\1$/s', $inner, $q)) {
                    $raw = html_entity_decode($q[2], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $new = self::publicUrlForRootRelativePath($raw);
                    if ($new === $raw) {
                        return $m[0];
                    }

                    return 'url('.$q[1].$new.$q[1].')';
                }

                $raw = html_entity_decode($inner, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $new = self::publicUrlForRootRelativePath($raw);
                if ($new === $raw) {
                    return $m[0];
                }

                return 'url('.$new.')';
            },
            $html
        ) ?? $html;

        return $html;
    }

    /**
     * CMS masthead value: bare /uploads/... or /assets/..., full url(...) snippets, or mixed HTML.
     */
    public static function rewriteMastheadBackground(string $raw): string
    {
        $t = trim($raw);
        if ($t === '') {
            return $t;
        }
        if (str_starts_with($t, '/') && (str_starts_with($t, '/uploads/') || str_starts_with($t, '/assets/') || str_starts_with($t, '/storage/'))) {
            return 'url(\''.self::publicUrlForRootRelativePath($t).'\')';
        }

        return self::rewriteHtmlPublicPaths($t);
    }

    /**
     * Normalize stored menu URLs to asset() URLs for /uploads/... paths
     * (strips wrong host from old seeds; respects current APP_URL).
     */
    public static function normalizeMenuCustomUrl(string $url): string
    {
        $trimmed = trim($url);
        if ($trimmed === '' || $trimmed === '#') {
            return $trimmed;
        }

        if (str_starts_with($trimmed, '/') && ! str_starts_with($trimmed, '//')) {
            return self::publicUrlForRootRelativePath($trimmed);
        }

        if (str_starts_with($trimmed, 'http://') || str_starts_with($trimmed, 'https://')) {
            $path = parse_url($trimmed, PHP_URL_PATH);
            if (is_string($path) && str_starts_with($path, '/uploads/')) {
                $path = self::canonicalUploadPath($path);

                return asset(ltrim($path, '/'));
            }
            if (is_string($path) && (str_starts_with($path, '/assets/') || str_starts_with($path, '/storage/'))) {
                return asset(ltrim($path, '/'));
            }

            return $trimmed;
        }

        if (str_starts_with($trimmed, 'uploads/')) {
            $path = self::canonicalUploadPath('/'.$trimmed);

            return asset(ltrim($path, '/'));
        }

        if (str_starts_with($trimmed, 'assets/') || str_starts_with($trimmed, 'storage/')) {
            return asset($trimmed);
        }

        return $trimmed;
    }
}
