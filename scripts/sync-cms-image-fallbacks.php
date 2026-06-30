<?php

/**
 * Copy CMS-referenced assets from public/uploads into public/assets/images/cms/...
 * Run after fix-cms-upload-paths.php. Run: php scripts/sync-cms-image-fallbacks.php
 */

$root = dirname(__DIR__);
$public = $root.'/public';

require $root.'/vendor/autoload.php';
$app = require $root.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

$paths = [];
$rows = DB::table('pages')->get(['content', 'content_ur', 'masthead_bg', 'masthead_bg_ur']);
foreach ($rows as $row) {
    foreach (['content', 'content_ur', 'masthead_bg', 'masthead_bg_ur'] as $col) {
        $text = (string) $row->{$col};
        if (preg_match_all('#assets/images/cms/[^\s"\'<>\)]+#', $text, $m)) {
            foreach ($m[0] as $p) {
                $paths[$p] = true;
            }
        }
    }
}

$copied = 0;
$missing = 0;

foreach (array_keys($paths) as $destRelative) {
    $dest = $public.'/'.$destRelative;
    if (is_file($dest)) {
        continue;
    }

    $suffix = substr($destRelative, strlen('assets/images/cms/'));
    $uploadRel = 'uploads/'.$suffix;
    $candidates = [$uploadRel];

    if (preg_match('#^uploads/(\d{4})/(\d{2})/(.+)$#', $uploadRel, $m)) {
        $candidates[] = "uploads/{$m[1]}/{$m[1]}/{$m[2]}/{$m[3]}";
    }

    $source = null;
    foreach ($candidates as $c) {
        if (is_file($public.'/'.$c)) {
            $source = $public.'/'.$c;
            break;
        }
    }

    if ($source === null) {
        $missing++;

        continue;
    }

    File::ensureDirectoryExists(dirname($dest));
    copy($source, $dest);
    $copied++;
}

echo "CMS fallback images: copied {$copied}, still missing {$missing}.\n";
