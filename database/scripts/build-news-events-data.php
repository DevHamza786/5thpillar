<?php

/**
 * One-off: regenerates storage/app/private/news-events-data.json from WP export.
 * Source: storage/app/private/wp-news-posts-embed.json (fetch via WP REST API with _embed).
 */

$src = __DIR__.'/../../storage/app/private/wp-news-posts-embed.json';
$dst = __DIR__.'/../../resources/data/news-events-data.json';

if (! is_file($src)) {
    fwrite(STDERR, "Missing {$src}\n");
    exit(1);
}

$j = json_decode(file_get_contents($src), true);
if (! is_array($j)) {
    fwrite(STDERR, "Invalid JSON\n");
    exit(1);
}

$out = [];
foreach ($j as $p) {
    $img = null;
    if (! empty($p['_embedded']['wp:featuredmedia'][0])) {
        $m = $p['_embedded']['wp:featuredmedia'][0];
        $img = $m['source_url'] ?? null;
        if (! empty($m['media_details']['sizes']['large']['source_url'])) {
            $img = $m['media_details']['sizes']['large']['source_url'];
        } elseif (! empty($m['media_details']['sizes']['medium_large']['source_url'])) {
            $img = $m['media_details']['sizes']['medium_large']['source_url'];
        }
    }
    $out[] = [
        'slug' => $p['slug'],
        'title' => html_entity_decode(strip_tags($p['title']['rendered'] ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
        'date' => substr($p['date'] ?? '', 0, 10),
        'image' => $img,
        'content' => $p['content']['rendered'] ?? '',
    ];
}

file_put_contents($dst, json_encode($out, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

echo count($out).' posts, '.filesize($dst)." bytes written to {$dst}\n";
