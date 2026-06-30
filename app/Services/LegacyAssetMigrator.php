<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

/**
 * Migrates legacy WordPress paths (/uploads/, /wp-content/) into public/assets/*.
 */
final class LegacyAssetMigrator
{
    private string $root;

    private string $public;

    /** @var array<string, string> legacy relative path => assets/... destination */
    private array $pathMap = [];

    /** @var array<string, string> longest-first replacement pairs for text/DB */
    private array $textReplacements = [];

    public function __construct(?string $root = null)
    {
        $this->root = $root ?? base_path();
        $this->public = $this->root.'/public';
    }

    /**
     * @return array<string, mixed>
     */
    public function audit(): array
    {
        $this->loadPathMap();

        return [
            'path_map_entries' => count($this->pathMap),
            'database' => $this->auditDatabase(),
            'source_files' => $this->auditSourceFiles(),
            'legacy_dirs' => [
                'uploads_files' => $this->countFiles($this->public.'/uploads'),
                'wp_content_files' => $this->countFiles($this->public.'/wp-content'),
            ],
        ];
    }

    /**
     * @return array{moved: int, skipped: int, missing: list<string>}
     */
    public function migrateFiles(bool $dryRun = false): array
    {
        $this->loadPathMap();

        $moved = 0;
        $skipped = 0;
        $missing = [];

        foreach ($this->pathMap as $from => $to) {
            $source = $this->resolveSourceFile($from);
            $dest = $this->public.'/'.$to;

            if ($source === null) {
                if (is_file($dest)) {
                    $skipped++;

                    continue;
                }
                $missing[] = $from;

                continue;
            }

            if ($dryRun) {
                $moved++;

                continue;
            }

            $destDir = dirname($dest);
            if (! is_dir($destDir)) {
                File::ensureDirectoryExists($destDir);
            }

            if (is_file($dest)) {
                @unlink($source);
                $skipped++;

                continue;
            }

            if (! @rename($source, $dest)) {
                if (! @copy($source, $dest)) {
                    $missing[] = $from;

                    continue;
                }
                @unlink($source);
            }

            $moved++;
        }

        return compact('moved', 'skipped', 'missing');
    }

    /**
     * @return array{files: int, database_rows: int}
     */
    public function updateReferences(bool $dryRun = false): array
    {
        $this->loadPathMap();
        $this->buildTextReplacements();

        $filesUpdated = $this->updateFilesystemReferences($dryRun);
        $dbUpdated = $this->updateDatabaseReferences($dryRun);
        $this->updateNewsEventsJson($dryRun);

        return [
            'files' => $filesUpdated,
            'database_rows' => $dbUpdated,
        ];
    }

    /**
     * @return list<string> missing asset paths (relative to public/)
     */
    public function validate(): array
    {
        $this->loadPathMap();
        $broken = [];

        foreach (array_unique(array_values($this->pathMap)) as $relative) {
            if (! is_file($this->public.'/'.$relative)) {
                $broken[] = $relative;
            }
        }

        return $broken;
    }

    public function cleanupLegacyDirectories(bool $dryRun = false): void
    {
        foreach (['uploads', 'wp-content'] as $dir) {
            $path = $this->public.'/'.$dir;
            if (! is_dir($path)) {
                continue;
            }
            if ($dryRun) {
                continue;
            }
            File::deleteDirectory($path);
        }
    }

    private function loadPathMap(): void
    {
        if ($this->pathMap !== []) {
            return;
        }

        $map = [];
        $mapFile = $this->root.'/config/upload-to-assets-map.php';
        if (! is_file($mapFile)) {
            $mapFile = $this->root.'/database/scripts/legacy-upload-map.php';
        }
        if (is_file($mapFile)) {
            /** @var array<string, string> $loaded */
            $loaded = require $mapFile;
            $map = $loaded;
        }

        $map = $this->normalizePdfDestinations($map);
        $map = array_merge($map, $this->buildNewsImageMap());
        $map = array_merge($map, $this->staticWpContentMap());

        $this->pathMap = $map;
    }

    /**
     * @param  array<string, string>  $map
     * @return array<string, string>
     */
    private function normalizePdfDestinations(array $map): array
    {
        $normalized = [];
        foreach ($map as $from => $to) {
            $normalized[$from] = str_replace('assets/pdfs/', 'assets/pdf/', $to);
        }

        return $normalized;
    }

    /**
     * @return array<string, string>
     */
    private function staticWpContentMap(): array
    {
        return [
            'wp-content/uploads/2025/12/xCDC-Web-Banner-2.jpg.pagespeed.ic.uZmIp9-iq9.webp' => 'assets/images/home/cdc-web-banner.webp',
            'wp-content/uploads/2026/05/Executive-Officer-Senior-Executive-Officer-%E2%80%93-Customer-Services.jpg' => 'assets/images/careers/executive-officer-customer-services.jpg',
            'wp-content/uploads/useanyfont/1303Raleway.woff2' => 'assets/fonts/raleway.woff2',
            'wp-content/uploads/useanyfont/1303Raleway.woff' => 'assets/fonts/raleway.woff',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function buildNewsImageMap(): array
    {
        $path = $this->root.'/resources/data/news-events-data.json';
        if (! is_file($path)) {
            return [];
        }

        $raw = json_decode(file_get_contents($path) ?: '[]', true);
        if (! is_array($raw)) {
            return [];
        }

        $map = [];
        foreach ($raw as $item) {
            $url = (string) ($item['image'] ?? '');
            if ($url === '') {
                continue;
            }
            if (preg_match('#(?:wp-content/uploads/|uploads/)(.+)$#i', $url, $m)) {
                $rel = $m[1];
                $map['wp-content/uploads/'.$rel] = 'assets/images/news/'.$rel;
                $map['uploads/'.$rel] = 'assets/images/news/'.$rel;
            }
        }

        return $map;
    }

    private function buildTextReplacements(): void
    {
        uksort($this->pathMap, static fn (string $a, string $b): int => strlen($b) <=> strlen($a));

        $replacements = [];
        foreach ($this->pathMap as $from => $to) {
            $replacements[$from] = $to;
            $replacements['/'.$from] = '/'.$to;
            $replacements['https://5thpillartakaful.com/'.$from] = '/'.$to;
            $replacements['http://5thpillartakaful.com/'.$from] = '/'.$to;
        }

        $replacements['assets/pdfs/'] = 'assets/pdf/';
        $replacements['/assets/pdfs/'] = '/assets/pdf/';

        $this->textReplacements = $replacements;
    }

    private function resolveSourceFile(string $relative): ?string
    {
        $relative = str_replace('\\', '/', ltrim($relative, '/'));
        $candidates = [$relative];

        if (preg_match('#^uploads/(\d{4})/\1/(.+)$#', $relative, $m)) {
            $candidates[] = "uploads/{$m[1]}/{$m[2]}";
        }

        if (preg_match('#^uploads/(\d{4})/(\d{2})/(.+)$#', $relative, $m)) {
            $candidates[] = "uploads/{$m[1]}/{$m[1]}/{$m[2]}/{$m[3]}";
        }

        if (preg_match('#^wp-content/uploads/(\d{4})/(\d{2})/(.+)$#', $relative, $m)) {
            $candidates[] = "wp-content/uploads/{$m[1]}/{$m[2]}/{$m[3]}";
            $candidates[] = "uploads/{$m[1]}/{$m[2]}/{$m[3]}";
            $candidates[] = "uploads/{$m[1]}/{$m[1]}/{$m[2]}/{$m[3]}";
        }

        if (str_starts_with($relative, 'wp-content/uploads/useanyfont/')) {
            $candidates[] = str_replace('wp-content/uploads/', 'uploads/', $relative);
        }

        foreach (array_unique($candidates) as $candidate) {
            $full = $this->public.'/'.$candidate;
            if (is_file($full)) {
                return $full;
            }
        }

        return null;
    }

    /**
     * @return array<string, int>
     */
    private function auditDatabase(): array
    {
        $out = [];
        $tables = [
            'pages' => ['content', 'content_ur', 'masthead_bg', 'masthead_bg_ur'],
            'nav_menu_items' => ['custom_url'],
            'cms_media' => ['file_path'],
        ];

        foreach ($tables as $table => $cols) {
            if (! Schema::hasTable($table)) {
                continue;
            }
            foreach ($cols as $col) {
                if (! Schema::hasColumn($table, $col)) {
                    continue;
                }
                $count = DB::table($table)
                    ->where(function ($q) use ($col) {
                        $q->where($col, 'like', '%uploads/%')
                            ->orWhere($col, 'like', '%wp-content%');
                    })
                    ->count();
                if ($count > 0) {
                    $out["{$table}.{$col}"] = $count;
                }
            }
        }

        return $out;
    }

    /**
     * @return array{found: int, missing: int}
     */
    private function auditSourceFiles(): array
    {
        $found = 0;
        $missing = 0;
        foreach ($this->pathMap as $from => $to) {
            if ($this->resolveSourceFile($from) !== null || is_file($this->public.'/'.$to)) {
                $found++;
            } else {
                $missing++;
            }
        }

        return compact('found', 'missing');
    }

    private function countFiles(string $dir): int
    {
        if (! is_dir($dir)) {
            return 0;
        }

        $count = 0;
        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS));
        foreach ($it as $file) {
            if ($file->isFile()) {
                $count++;
            }
        }

        return $count;
    }

    private function updateFilesystemReferences(bool $dryRun): int
    {
        $updated = 0;
        $roots = [
            $this->root.'/app',
            $this->root.'/config',
            $this->root.'/resources',
            $this->root.'/public/assets',
            $this->root.'/routes',
            $this->root.'/database/migrations',
        ];

        foreach ($roots as $root) {
            if (! is_dir($root)) {
                continue;
            }
            $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS));
            foreach ($it as $file) {
                if (! $file->isFile()) {
                    continue;
                }
                $path = $file->getPathname();
                $name = $file->getFilename();
                if (str_contains($path, 'upload-to-assets-map.php') || str_contains($path, 'LegacyAssetMigrator.php')) {
                    continue;
                }
                if (! preg_match('/\.(php|css|js|json|blade\.php)$/i', $name)) {
                    continue;
                }
                $contents = file_get_contents($path);
                if ($contents === false) {
                    continue;
                }
                if (! str_contains($contents, 'uploads/') && ! str_contains($contents, 'wp-content') && ! str_contains($contents, 'assets/pdfs/')) {
                    continue;
                }
                $new = $this->applyReplacements($contents);
                if ($new !== $contents) {
                    $updated++;
                    if (! $dryRun) {
                        file_put_contents($path, $new);
                    }
                }
            }
        }

        // server.php and .htaccess at public root
        foreach ([$this->root.'/server.php', $this->public.'/.htaccess'] as $path) {
            if (! is_file($path)) {
                continue;
            }
            $contents = file_get_contents($path) ?: '';
            $new = str_replace('assets/pdfs/', 'assets/pdf/', $contents);
            if ($new !== $contents) {
                $updated++;
                if (! $dryRun) {
                    file_put_contents($path, $new);
                }
            }
        }

        return $updated;
    }

    private function updateDatabaseReferences(bool $dryRun): int
    {
        $rowsUpdated = 0;
        $tables = [
            'pages' => ['content', 'content_ur', 'masthead_bg', 'masthead_bg_ur'],
            'nav_menu_items' => ['custom_url'],
            'cms_media' => ['file_path'],
        ];

        foreach ($tables as $table => $cols) {
            if (! Schema::hasTable($table)) {
                continue;
            }
            foreach ($cols as $col) {
                if (! Schema::hasColumn($table, $col)) {
                    continue;
                }
                $rows = DB::table($table)
                    ->where(function ($q) use ($col) {
                        $q->where($col, 'like', '%uploads/%')
                            ->orWhere($col, 'like', '%wp-content%')
                            ->orWhere($col, 'like', '%assets/pdfs/%');
                    })
                    ->get(['id', $col]);

                foreach ($rows as $row) {
                    $original = (string) $row->{$col};
                    $new = $this->applyReplacements($original);
                    if ($new !== $original) {
                        $rowsUpdated++;
                        if (! $dryRun) {
                            DB::table($table)->where('id', $row->id)->update([$col => $new]);
                        }
                    }
                }
            }
        }

        return $rowsUpdated;
    }

    private function updateNewsEventsJson(bool $dryRun): void
    {
        $path = $this->root.'/resources/data/news-events-data.json';
        if (! is_file($path)) {
            return;
        }

        $raw = json_decode(file_get_contents($path) ?: '[]', true);
        if (! is_array($raw)) {
            return;
        }

        $changed = false;
        foreach ($raw as &$item) {
            if (! is_array($item) || empty($item['image'])) {
                continue;
            }
            $image = (string) $item['image'];
            if (preg_match('#(?:wp-content/uploads/|uploads/|assets/images/news/)(.+)$#i', $image, $m)) {
                $item['image'] = 'assets/images/news/'.$m[1];
                $changed = true;
            }
        }
        unset($item);

        if ($changed && ! $dryRun) {
            file_put_contents($path, json_encode($raw, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n");
        }
    }

    private function applyReplacements(string $content): string
    {
        foreach ($this->textReplacements as $from => $to) {
            $content = str_replace($from, $to, $content);
        }

        return $content;
    }
}
