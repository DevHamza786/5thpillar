<?php

/**
 * Download news post images from live wp-content/uploads into public/assets/images/news/
 * and normalize resources/data/news-events-data.json to local asset paths.
 */

$root = dirname(__DIR__);
$jsonPath = $root.'/resources/data/news-events-data.json';
$uploadBase = 'https://5thpillartakaful.com/wp-content/uploads/';

$posts = json_decode(file_get_contents($jsonPath), true);
if (! is_array($posts)) {
    fwrite(STDERR, "Invalid JSON\n");
    exit(1);
}

$ok = 0;
$fail = 0;
$skipped = 0;

foreach ($posts as &$post) {
    if (empty($post['image']) || ! is_string($post['image'])) {
        continue;
    }

    $image = $post['image'];
    $rel = null;

    if (preg_match('#(?:assets/images/news/|wp-content/uploads/|uploads/)(.+)$#i', $image, $m)) {
        $rel = $m[1];
    } elseif (preg_match('#5thpillartakaful\.com/(?:assets/images/news/|wp-content/uploads/)(.+)$#i', $image, $m)) {
        $rel = $m[1];
    }

    if ($rel === null) {
        fwrite(STDERR, "SKIP unknown image URL: {$image}\n");
        $skipped++;

        continue;
    }

    $dest = $root.'/public/assets/images/news/'.$rel;
    $destDir = dirname($dest);
    if (! is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }

    if (! is_file($dest)) {
        $src = $uploadBase.$rel;
        $ctx = stream_context_create([
            'http' => [
                'timeout' => 30,
                'header' => 'User-Agent: 5thPillarAssetSync/1.0',
            ],
        ]);
        $data = @file_get_contents($src, false, $ctx);
        if ($data === false || $data === '') {
            fwrite(STDERR, "FAIL download: {$src}\n");
            $fail++;

            continue;
        }
        file_put_contents($dest, $data);
        echo "OK {$rel}\n";
        $ok++;
    } else {
        $skipped++;
    }

    $post['image'] = 'assets/images/news/'.$rel;
}
unset($post);

file_put_contents($jsonPath, json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n");

echo "\nDownloaded: {$ok}, failed: {$fail}, skipped/existing: {$skipped}\n";
