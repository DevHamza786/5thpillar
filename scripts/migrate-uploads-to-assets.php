<?php

/**
 * Move referenced public/uploads files into public/assets/images and public/assets/pdfs.
 * Run: php scripts/migrate-uploads-to-assets.php
 */

$root = dirname(__DIR__);
$public = $root.'/public';

/** @var array<string, string> old relative path (uploads/...) => new relative path (assets/...) */
$pathMap = require $root.'/database/scripts/legacy-upload-map.php';

function resolveSourceFile(string $publicDir, string $relative): ?string
{
    $relative = str_replace('\\', '/', ltrim($relative, '/'));
    $candidates = [$relative];

    // uploads/YEAR/YEAR/mm/file → uploads/YEAR/mm/file
    if (preg_match('#^uploads/(\d{4})/\1/(.+)$#', $relative, $m)) {
        $candidates[] = "uploads/{$m[1]}/{$m[2]}";
    }

    // uploads/YEAR/mm/file → uploads/YEAR/YEAR/mm/file (WordPress legacy)
    if (preg_match('#^uploads/(\d{4})/(\d{2})/(.+)$#', $relative, $m) && $m[1] !== $m[2]) {
        $candidates[] = "uploads/{$m[1]}/{$m[1]}/{$m[2]}/{$m[3]}";
    }

    foreach (array_unique($candidates) as $candidate) {
        $full = $publicDir.'/'.$candidate;
        if (is_file($full)) {
            return $full;
        }
    }

    return null;
}

$moved = 0;
$skipped = 0;
$missing = [];

foreach ($pathMap as $from => $to) {
    $source = resolveSourceFile($public, $from);
    $dest = $public.'/'.$to;

    if ($source === null) {
        if (is_file($dest)) {
            $skipped++;

            continue;
        }
        $missing[] = $from;

        continue;
    }

    $destDir = dirname($dest);
    if (! is_dir($destDir) && ! mkdir($destDir, 0755, true) && ! is_dir($destDir)) {
        fwrite(STDERR, "Failed to create directory: {$destDir}\n");
        exit(1);
    }

    if (is_file($dest)) {
        @unlink($source);
        $skipped++;

        continue;
    }

    if (! rename($source, $dest)) {
        if (! copy($source, $dest)) {
            fwrite(STDERR, "Failed to move: {$from} -> {$to}\n");
            exit(1);
        }
        @unlink($source);
    }

    $moved++;
}

echo "Moved: {$moved}, already at destination: {$skipped}, missing source: ".count($missing)."\n";
if ($missing !== []) {
    echo "Missing sources (first 20):\n";
    foreach (array_slice($missing, 0, 20) as $path) {
        echo "  - {$path}\n";
    }
}
