<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$mapFile = base_path('database/scripts/legacy-upload-map.php');
$rawMap = is_file($mapFile) ? require $mapFile : [];

$normalizedMap = [];
foreach ($rawMap as $from => $to) {
    $normalizedMap[$from] = str_replace('assets/pdfs/', 'assets/pdf/', $to);
}

uksort($normalizedMap, static fn (string $a, string $b): int => strlen($b) <=> strlen($a));

function mapUploadPath(string $uploadPath, array $normalizedMap): string
{
    $uploadPath = preg_replace('#\?id=\d+#', '', $uploadPath) ?? $uploadPath;
    $uploadPath = rtrim($uploadPath, ')');

    if (isset($normalizedMap[$uploadPath])) {
        return $normalizedMap[$uploadPath];
    }

    foreach ($normalizedMap as $from => $to) {
        if (str_starts_with($uploadPath, $from)) {
            return $to.substr($uploadPath, strlen($from));
        }
    }

    if (preg_match('#^uploads/(.+)$#', $uploadPath, $m)) {
        return 'assets/images/cms/'.$m[1];
    }

    return $uploadPath;
}

$tables = [
    'pages' => ['content', 'content_ur', 'masthead_bg', 'masthead_bg_ur'],
    'nav_menu_items' => ['custom_url'],
    'cms_media' => ['file_path'],
];

$updated = 0;
foreach ($tables as $table => $cols) {
    if (! Schema::hasTable($table)) {
        continue;
    }
    foreach ($cols as $col) {
        if (! Schema::hasColumn($table, $col)) {
            continue;
        }
        $rows = DB::table($table)->where($col, 'like', '%uploads/%')->get(['id', $col]);
        foreach ($rows as $row) {
            $original = (string) $row->{$col};
            $new = preg_replace_callback(
                '#uploads/[^\s"\'<>\)]+#',
                static function (array $m) use ($normalizedMap): string {
                    return mapUploadPath($m[0], $normalizedMap);
                },
                $original
            ) ?? $original;

            $new = str_replace(['wp-content/uploads/', '/wp-content/uploads/'], ['assets/images/', '/assets/images/'], $new);
            $new = str_replace('assets/pdfs/', 'assets/pdf/', $new);

            if ($new !== $original) {
                DB::table($table)->where('id', $row->id)->update([$col => $new]);
                $updated++;
            }
        }
    }
}

echo "Updated {$updated} rows.\n";
