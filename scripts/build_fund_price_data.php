<?php

/**
 * One-off: parse fund-price-archive-*.md exports into resources/data/fund_price_archives.php
 * Usage: php scripts/build_fund_price_data.php [path-to-uploads-dir]
 */

$base = $argv[1] ?? dirname(__DIR__) . '/../.cursor/projects/d-Project-5thpillar/uploads';
if (! is_dir($base)) {
    $base = dirname(__DIR__) . '/resources/data/fund_md_source';
}

$files = [
    2023 => 'fund-price-archive-2023-1.md',
    2024 => 'fund-price-archive-2024-2.md',
    2025 => 'fund-price-archive-2025-3.md',
    2026 => 'fund-price-archive-2026-4.md',
];

function parse_md(string $path): array
{
    if (! is_readable($path)) {
        fwrite(STDERR, "Missing: $path\n");

        return [];
    }
    $text = file_get_contents($path);
    $months = [];
    $parts = preg_split('/\n## Daily Fund Prices - /', $text);
    array_shift($parts);
    foreach ($parts as $chunk) {
        $lines = explode("\n", $chunk, 2);
        $title = trim($lines[0] ?? '');
        if ($title === '') {
            continue;
        }
        $rest = $lines[1] ?? '';
        if (! preg_match('/\| Day & Date\s+\|(.+)/s', $rest, $m)) {
            continue;
        }
        $table = '| Day & Date |'.$m[1];
        $rows = [];
        foreach (explode("\n", $table) as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '| ---')) {
                continue;
            }
            if (! str_starts_with($line, '|')) {
                continue;
            }
            $cols = array_map('trim', str_getcsv(substr($line, 1, -1), '|', '"', '\\'));
            if (count($cols) < 7) {
                continue;
            }
            $date = $cols[0];
            if ($date === 'Day & Date' || str_contains($date, 'Fund Name')) {
                continue;
            }
            if (! preg_match('/^(Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday),/i', $date)) {
                continue;
            }
            $rows[] = [
                'date' => $date,
                'agg_bid' => $cols[1] ?? '',
                'agg_offer' => $cols[2] ?? '',
                'bal_bid' => $cols[3] ?? '',
                'bal_offer' => $cols[4] ?? '',
                'con_bid' => $cols[5] ?? '',
                'con_offer' => $cols[6] ?? '',
            ];
        }
        $months[$title] = $rows;
    }

    return $months;
}

$out = [];
foreach ($files as $year => $fn) {
    $p = rtrim($base, '/\\') . DIRECTORY_SEPARATOR . $fn;
    $out[$year] = parse_md($p);
}

$export = "<?php\n\nreturn ".var_export($out, true).";\n";
$target = dirname(__DIR__).'/resources/data/fund_price_archives.php';
@mkdir(dirname($target), 0755, true);
file_put_contents($target, $export);
echo "Wrote $target (".strlen($export)." bytes)\n";
foreach ($out as $y => $m) {
    echo "  $y: ".count($m)." months\n";
}
