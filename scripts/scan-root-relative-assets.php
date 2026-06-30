<?php

/**
 * One-off scanner: CSS/JS under public/ with url("/uploads/...") or url("/assets/...")
 * (breaks subdirectory deploys). Run: php scripts/scan-root-relative-assets.php
 */
$root = dirname(__DIR__).'/public';
$it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
);

$patterns = [
    '#url\s*\(\s*["\']?/uploads/#i',
    '#url\s*\(\s*["\']?/assets/#i',
];

$hits = [];
foreach ($it as $f) {
    if (! preg_match('/\.(css|js)$/i', $f->getFilename())) {
        continue;
    }
    $c = @file_get_contents($f->getPathname());
    if ($c === false) {
        continue;
    }
    foreach ($patterns as $p) {
        if (preg_match($p, $c)) {
            $hits[] = $f->getPathname();
            break;
        }
    }
}

if ($hits === []) {
    echo "OK: no root-relative url(/uploads/ or url(/assets/ in public *.css / *.js\n";
    exit(0);
}

echo "Found:\n";
foreach (array_unique($hits) as $path) {
    echo $path."\n";
}
exit(1);
