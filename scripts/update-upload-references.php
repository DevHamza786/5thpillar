<?php

/**
 * Replace legacy uploads/ paths with assets/ paths in application source.
 * Run: php scripts/update-upload-references.php
 */

$root = dirname(__DIR__);
/** @var array<string, string> $pathMap */
$pathMap = require $root.'/database/scripts/legacy-upload-map.php';

// Prefer longest keys first to avoid partial replacements.
uksort($pathMap, static fn (string $a, string $b): int => strlen($b) <=> strlen($a));

$replacements = [];
foreach ($pathMap as $from => $to) {
    $replacements[$from] = $to;
    $replacements['/'.$from] = '/'.$to;
}

$scanRoots = [
    $root.DIRECTORY_SEPARATOR.'app',
    $root.DIRECTORY_SEPARATOR.'config',
    $root.DIRECTORY_SEPARATOR.'resources',
    $root.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'assets',
    $root.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'migrations',
];

$updatedFiles = 0;

foreach ($scanRoots as $scanRoot) {
    if (! is_dir($scanRoot)) {
        continue;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($scanRoot, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if (! $file->isFile()) {
            continue;
        }

        $path = $file->getPathname();
        if (str_contains($path, 'upload-to-assets-map.php')) {
            continue;
        }

        $name = $file->getFilename();
        if (! preg_match('/\.(php|css|js|json)$/i', $name) && ! str_ends_with($name, '.blade.php')) {
            continue;
        }

        $contents = file_get_contents($path);
        if ($contents === false || ! str_contains($contents, 'uploads/')) {
            continue;
        }

        $original = $contents;
        foreach ($replacements as $from => $to) {
            $contents = str_replace($from, $to, $contents);
        }

        if ($contents !== $original) {
            file_put_contents($path, $contents);
            $updatedFiles++;
            $relative = str_replace('\\', '/', substr($path, strlen($root) + 1));
            echo "Updated: {$relative}\n";
        }
    }
}

echo "Done. Updated {$updatedFiles} files.\n";
