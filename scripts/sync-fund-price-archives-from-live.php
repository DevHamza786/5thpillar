<?php

/**
 * Sync fund price archive pages from live 5thpillartakaful.com into
 * resources/data/fund_price_archives.php (used by fund-price-archive-{year} blades).
 *
 * Usage:
 *   php scripts/sync-fund-price-archives-from-live.php           # 2023–2026
 *   php scripts/sync-fund-price-archives-from-live.php 2026      # one year
 *   php scripts/sync-fund-price-archives-from-live.php 2025 2026
 *   php scripts/sync-fund-price-archives-from-live.php --dry-run 2026
 */

declare(strict_types=1);

$root = dirname(__DIR__);
$dryRun = in_array('--dry-run', $argv, true);
$years = array_values(array_filter(
    array_map('intval', array_filter($argv, static fn (string $arg): bool => $arg !== '--dry-run' && $arg !== $argv[0])),
    static fn (int $y): bool => $y >= 2000 && $y <= 2100
));
if ($years === []) {
    $years = [2023, 2024, 2025, 2026];
}

const LIVE_BASE = 'https://5thpillartakaful.com';

function fetchLiveHtml(string $path): string
{
    $url = rtrim(LIVE_BASE, '/').'/'.ltrim($path, '/');
    $ctx = stream_context_create([
        'http' => [
            'timeout' => 90,
            'header' => "User-Agent: 5thPillarFundArchiveSync/1.0\r\nAccept: text/html\r\n",
        ],
    ]);
    $html = @file_get_contents($url, false, $ctx);
    if ($html === false || $html === '') {
        throw new RuntimeException("Failed to fetch {$url}");
    }

    return $html;
}

function cellText(?DOMNode $node): string
{
    if ($node === null) {
        return '';
    }

    return trim(preg_replace('/\s+/u', ' ', $node->textContent ?? ''));
}

/**
 * @return array<string, list<array{date: string, agg_bid: string, agg_offer: string, bal_bid: string, bal_offer: string, con_bid: string, con_offer: string}>>
 */
function parseFundPriceArchiveHtml(string $html, int $year): array
{
    $dom = new DOMDocument;
    libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="UTF-8">'.$html);
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);
    $months = [];

    foreach ($xpath->query('//div[contains(concat(" ", normalize-space(@class), " "), " vc_toggle ")]') as $toggle) {
        $heading = $xpath->query('.//div[contains(@class,"vc_toggle_title")]//h2', $toggle)->item(0);
        $title = cellText($heading);
        if ($title === '' || ! preg_match('/Daily Fund Prices\s*-\s*(.+)$/iu', $title, $m)) {
            continue;
        }

        $monthLabel = trim($m[1]);
        if (! preg_match('/\b'.$year.'\b/', $monthLabel)) {
            continue;
        }

        $rows = [];
        foreach ($xpath->query('.//tr[contains(@class,"wpdt-cell-row")]', $toggle) as $tr) {
            $cells = [];
            foreach ($xpath->query('./td', $tr) as $td) {
                $cells[] = cellText($td);
            }
            if (count($cells) < 7) {
                continue;
            }

            $date = $cells[0];
            if (! preg_match('/^(Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday),/i', $date)) {
                continue;
            }

            $rows[] = [
                'date' => $date,
                'agg_bid' => $cells[1],
                'agg_offer' => $cells[2],
                'bal_bid' => $cells[3],
                'bal_offer' => $cells[4],
                'con_bid' => $cells[5],
                'con_offer' => $cells[6],
            ];
        }

        if ($rows !== []) {
            $months[$monthLabel] = $rows;
        }
    }

    return $months;
}

function writePhpDataFile(string $path, array $data): void
{
    file_put_contents($path, "<?php\n\nreturn ".var_export($data, true).";\n");
}

$archivePath = $root.'/resources/data/fund_price_archives.php';
$archives = is_file($archivePath) ? require $archivePath : [];
if (! is_array($archives)) {
    $archives = [];
}

foreach ($years as $year) {
    echo "Fetching fund-price-archive-{$year}...\n";
    $html = fetchLiveHtml("/fund-price-archive-{$year}/");
    $months = parseFundPriceArchiveHtml($html, $year);
    $dayCount = array_sum(array_map('count', $months));
    echo "  Parsed ".count($months)." months, {$dayCount} day rows\n";

    if ($months === []) {
        echo "  WARN: no data parsed for {$year}\n";
        continue;
    }

    foreach ($months as $label => $rows) {
        echo "    - {$label}: ".count($rows)." days\n";
    }

    if (! $dryRun) {
        $archives[$year] = $months;
    }
}

if (! $dryRun) {
    writePhpDataFile($archivePath, $archives);
    echo "Wrote {$archivePath}\n";
} else {
    echo "[dry-run] would write {$archivePath}\n";
}

echo "Done.\n";
