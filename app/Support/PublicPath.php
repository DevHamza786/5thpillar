<?php

namespace App\Support;

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
            $path = self::canonicalUploadPath($trimmed);

            return asset(ltrim($path, '/'));
        }

        if (str_starts_with($trimmed, 'http://') || str_starts_with($trimmed, 'https://')) {
            $path = parse_url($trimmed, PHP_URL_PATH);
            if (is_string($path) && str_starts_with($path, '/uploads/')) {
                $path = self::canonicalUploadPath($path);

                return asset(ltrim($path, '/'));
            }

            return $trimmed;
        }

        if (str_starts_with($trimmed, 'uploads/')) {
            $path = self::canonicalUploadPath('/'.$trimmed);

            return asset(ltrim($path, '/'));
        }

        return $trimmed;
    }
}
