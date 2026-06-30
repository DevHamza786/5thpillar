<?php

namespace App\Support;

use Illuminate\Support\Facades\URL;

/**
 * Public files under public/assets/... — use Laravel's asset() helper so URLs
 * respect APP_URL (including subdirectory deployments).
 */
final class PublicPath
{
    public static function uploadHref(string $pathUnderPublic): string
    {
        $path = ltrim(str_replace('\\', '/', $pathUnderPublic), '/');

        if (self::isPdfPath($path)) {
            return self::pdfViewerHref($path);
        }

        return asset($path);
    }

    public static function pdfViewerHref(string $pathUnderPublic): string
    {
        $path = ltrim(str_replace('\\', '/', $pathUnderPublic), '/');

        if (! self::isAllowedPdfPath($path)) {
            return asset($path);
        }

        return route('pdf-viewer.show', ['file' => $path]);
    }

    private static function isPdfPath(string $path): bool
    {
        return str_ends_with(strtolower($path), '.pdf');
    }

    private static function isAllowedPdfPath(string $path): bool
    {
        return $path !== ''
            && ! str_contains($path, '..')
            && str_starts_with($path, 'assets/pdf/')
            && self::isPdfPath($path);
    }

    public static function resolveAllowedAssetPdfPath(string $file): ?string
    {
        $normalized = ltrim(str_replace('\\', '/', $file), '/');

        return self::isAllowedPdfPath($normalized) ? $normalized : null;
    }

    /**
     * Convert direct /assets/pdf/... links to the branded PDF viewer URL.
     */
    public static function ensurePdfViewerUrl(string $url): string
    {
        $trimmed = trim($url);
        if ($trimmed === '' || $trimmed === '#' || str_contains($trimmed, '/pdf-viewer/')) {
            return $trimmed;
        }

        $path = null;
        if (preg_match('#^https?://#i', $trimmed)) {
            $parsed = parse_url($trimmed, PHP_URL_PATH);
            $path = is_string($parsed) ? ltrim($parsed, '/') : null;
        } elseif (str_starts_with($trimmed, '/')) {
            $path = ltrim($trimmed, '/');
        } elseif (str_starts_with($trimmed, 'assets/')) {
            $path = $trimmed;
        }

        if ($path !== null) {
            $path = str_replace('assets/pdf/', 'assets/pdf/', $path);
        }

        if ($path !== null && self::isAllowedPdfPath($path)) {
            return self::pdfViewerHref($path);
        }

        return $trimmed;
    }

    public static function isSitePdfRequestPath(string $path): bool
    {
        $normalized = '/'.ltrim(str_replace('\\', '/', $path), '/');

        return (bool) preg_match('#^/assets/pdf/.+\.pdf$#i', $normalized);
    }

    public static function pdfViewerPathForRequestPath(string $path): string
    {
        $normalized = '/'.ltrim(str_replace('\\', '/', $path), '/');

        return '/pdf-viewer'.$normalized;
    }

    /**
     * Root-relative public paths (/assets, /storage) → asset().
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

        if (str_starts_with($path, '/assets/') || str_starts_with($path, '/storage/')) {
            $relative = str_replace('assets/pdfs/', 'assets/pdf/', ltrim($path, '/'));
            if (self::isAllowedPdfPath($relative)) {
                return self::pdfViewerHref($relative);
            }

            return asset($relative);
        }

        return URL::to($path);
    }

    /**
     * Fix CMS HTML: src/href and CSS url(...) that point to /assets/ or /storage/,
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
     * CMS masthead value: bare /assets/..., full url(...) snippets, or mixed HTML.
     */
    public static function rewriteMastheadBackground(string $raw): string
    {
        $t = trim($raw);
        if ($t === '') {
            return $t;
        }
        if (str_starts_with($t, '/') && (str_starts_with($t, '/assets/') || str_starts_with($t, '/storage/'))) {
            return 'url(\''.self::publicUrlForRootRelativePath($t).'\')';
        }

        return self::rewriteHtmlPublicPaths($t);
    }

    /**
     * Extract an image URL from a masthead background value (bare path or url(...)).
     */
    public static function mastheadImageSrc(string $raw): string
    {
        $t = trim($raw);
        if ($t === '') {
            return '';
        }

        $rewritten = self::rewriteMastheadBackground($t);
        if (preg_match('/url\([\'"]?([^\'")]+)[\'"]?\)/i', $rewritten, $matches)) {
            return $matches[1];
        }

        if (str_starts_with($t, '/') || preg_match('#^https?://#i', $t)) {
            return self::publicUrlForRootRelativePath($t);
        }

        if (str_starts_with($t, 'assets/') || str_starts_with($t, 'storage/')) {
            return self::publicUrlForRootRelativePath('/'.$t);
        }

        return $rewritten;
    }

    /**
     * Normalize stored menu URLs to asset() URLs for /assets/... paths.
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
            if (is_string($path) && (str_starts_with($path, '/assets/') || str_starts_with($path, '/storage/'))) {
                return self::publicUrlForRootRelativePath($path);
            }

            return $trimmed;
        }

        if (str_starts_with($trimmed, 'assets/') || str_starts_with($trimmed, 'storage/')) {
            return self::publicUrlForRootRelativePath('/'.$trimmed);
        }

        return $trimmed;
    }
}
