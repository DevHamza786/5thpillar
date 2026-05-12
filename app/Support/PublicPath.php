<?php

namespace App\Support;

/**
 * Public files under /uploads/... should use root-relative hrefs so they never
 * bake APP_URL from another machine (e.g. localhost after seeding on dev).
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

    public static function uploadHref(string $pathUnderWebRoot): string
    {
        return '/'.ltrim($pathUnderWebRoot, '/');
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
     * Turn stored menu URLs into a root-relative /uploads/... path when possible.
     */
    public static function normalizeMenuCustomUrl(string $url): string
    {
        $trimmed = trim($url);
        if ($trimmed === '' || $trimmed === '#') {
            return $trimmed;
        }

        if (str_starts_with($trimmed, '/') && ! str_starts_with($trimmed, '//')) {
            return self::canonicalUploadPath($trimmed);
        }

        if (str_starts_with($trimmed, 'http://') || str_starts_with($trimmed, 'https://')) {
            $path = parse_url($trimmed, PHP_URL_PATH);
            if (is_string($path) && str_starts_with($path, '/uploads/')) {
                return self::canonicalUploadPath($path);
            }

            return $trimmed;
        }

        return $trimmed;
    }
}
