<?php

/**
 * Sync daily fund prices + fund manager reports from live 5thpillartakaful.com
 * into Laravel local data (JSON + optional DB snapshot + PDF downloads).
 *
 * Usage:
 *   php scripts/sync-fund-pages-from-live.php
 *   php scripts/sync-fund-pages-from-live.php --dry-run
 *   php scripts/sync-fund-pages-from-live.php --skip-pdfs
 *   php scripts/sync-fund-pages-from-live.php --with-archives
 *
 * Archive year tables (fund-price-archive-2023 … 2026):
 *   php scripts/sync-fund-price-archives-from-live.php
 *   php scripts/sync-fund-price-archives-from-live.php 2026
 */

declare(strict_types=1);

$root = dirname(__DIR__);
$dryRun = in_array('--dry-run', $argv, true);
$skipPdfs = in_array('--skip-pdfs', $argv, true);
$withArchives = in_array('--with-archives', $argv, true);

require $root.'/vendor/autoload.php';
$app = require $root.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

const LIVE_BASE = 'https://5thpillartakaful.com';

function fetchLiveHtml(string $path): string
{
    $url = rtrim(LIVE_BASE, '/').'/'.ltrim($path, '/');
    $ctx = stream_context_create([
        'http' => [
            'timeout' => 45,
            'header' => "User-Agent: 5thPillarFundSync/1.0\r\nAccept: text/html\r\n",
        ],
        'ssl' => [
            'verify_peer' => true,
            'verify_peer_name' => true,
        ],
    ]);
    $html = @file_get_contents($url, false, $ctx);
    if ($html === false || $html === '') {
        throw new RuntimeException("Failed to fetch {$url}");
    }

    return $html;
}

function normalizeLiveAssetPath(string $url): string
{
    $url = html_entity_decode(trim($url), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    if (preg_match('#^https?://[^/]+/(.+)$#i', $url, $m)) {
        $url = $m[1];
    }
    $url = ltrim($url, '/');

    if (preg_match('#^(?:wp-content/uploads/|uploads/)(.+)$#i', $url, $m)) {
        return 'assets/pdf/'.$m[1];
    }

    if (str_starts_with($url, 'assets/')) {
        return $url;
    }

    return 'assets/pdf/'.basename($url);
}

/** @return list<array{label: string, path: string, live_url: string}> */
function parseFundManagerReports(string $html): array
{
    $dom = new DOMDocument;
    libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="UTF-8">'.$html);
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);
    $rows = [];

    foreach ($xpath->query('//table//tr') as $tr) {
        $cells = $xpath->query('./td', $tr);
        if ($cells->length < 2) {
            continue;
        }
        $label = trim(preg_replace('/\s+/u', ' ', $cells->item(0)?->textContent ?? ''));
        if ($label === '' || strcasecmp($label, "Fund Manager's Report") === 0) {
            continue;
        }
        $link = $xpath->query('.//a[@href]', $cells->item(1))->item(0);
        if (! $link instanceof DOMElement) {
            continue;
        }
        $href = $link->getAttribute('href');
        if ($href === '' || $href === '#') {
            continue;
        }
        $rows[] = [
            'label' => $label,
            'path' => normalizeLiveAssetPath($href),
            'live_url' => str_starts_with($href, 'http') ? $href : rtrim(LIVE_BASE, '/').'/'.ltrim($href, '/'),
        ];
    }

    return $rows;
}

/** @return ?array{date: string, price_date: string, agg_bid: string, agg_offer: string, bal_bid: string, bal_offer: string, con_bid: string, con_offer: string} */
function parseDailyFundPrices(string $html): ?array
{
    $dom = new DOMDocument;
    libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="UTF-8">'.$html);
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);
    $dateLabel = null;

    foreach ($xpath->query('//table//th|//table//td') as $cell) {
        $text = trim(preg_replace('/\s+/u', ' ', $cell->textContent ?? ''));
        if ($text !== '' && preg_match('/^[A-Z][a-z]+ \d{1,2}, \d{4}$/', $text)) {
            $dateLabel = $text;
            break;
        }
    }

    if ($dateLabel === null) {
        return null;
    }

    $ts = strtotime($dateLabel);
    if ($ts === false) {
        return null;
    }

    $fundRows = [];
    foreach ($xpath->query('//table//tr') as $tr) {
        $cells = $xpath->query('./th|./td', $tr);
        if ($cells->length < 5) {
            continue;
        }
        $name = trim($cells->item(0)?->textContent ?? '');
        if (! str_starts_with($name, '5th Pillar')) {
            continue;
        }
        $values = [];
        for ($i = 1; $i < $cells->length; $i++) {
            $values[] = trim($cells->item($i)?->textContent ?? '');
        }
        $fundRows[] = ['name' => $name, 'values' => $values];
    }

    if (count($fundRows) < 3) {
        return null;
    }

    $pick = static function (array $row): array {
        $v = $row['values'];
        $bid = $v[count($v) - 2] ?? '';
        $offer = $v[count($v) - 1] ?? '';

        return [$bid, $offer];
    };

    [$aggBid, $aggOffer] = $pick($fundRows[0]);
    [$balBid, $balOffer] = $pick($fundRows[1]);
    [$conBid, $conOffer] = $pick($fundRows[2]);

    return [
        'date' => $dateLabel,
        'price_date' => date('Y-m-d', $ts),
        'agg_bid' => $aggBid,
        'agg_offer' => $aggOffer,
        'bal_bid' => $balBid,
        'bal_offer' => $balOffer,
        'con_bid' => $conBid,
        'con_offer' => $conOffer,
    ];
}

function downloadPdf(string $liveUrl, string $destRelative, bool $dryRun): bool
{
    $dest = public_path($destRelative);
    $dir = dirname($dest);
    if (! is_dir($dir) && ! $dryRun) {
        mkdir($dir, 0755, true);
    }
    if (is_file($dest)) {
        return true;
    }

    if ($dryRun) {
        echo "  [dry-run] would download PDF -> {$destRelative}\n";

        return false;
    }

    $ctx = stream_context_create([
        'http' => ['timeout' => 60, 'header' => "User-Agent: 5thPillarFundSync/1.0\r\n"],
    ]);
    $data = @file_get_contents($liveUrl, false, $ctx);
    if ($data === false || $data === '') {
        echo "  FAIL PDF: {$liveUrl}\n";

        return false;
    }
    file_put_contents($dest, $data);
    echo "  OK PDF: {$destRelative}\n";

    return true;
}

function writePhpDataFile(string $path, array $data): void
{
    $export = "<?php\n\nreturn ".var_export($data, true).";\n";
    file_put_contents($path, $export);
}

echo "Fetching live fund manager reports...\n";
$fmrHtml = fetchLiveHtml('/fund-managers-report/');
$fmrRows = parseFundManagerReports($fmrHtml);
echo '  Parsed '.count($fmrRows)." report rows\n";

echo "Fetching live daily fund prices...\n";
$dfpHtml = fetchLiveHtml('/daily-fund-prices/');
$dfpRow = parseDailyFundPrices($dfpHtml);
if ($dfpRow === null) {
    echo "  WARN: could not parse daily fund prices table\n";
} else {
    echo "  Latest date: {$dfpRow['date']}\n";
}

$fmrDataPath = $root.'/resources/data/fund_managers_reports.php';
$fmrExport = array_map(static fn (array $row): array => [
    'label' => $row['label'],
    'path' => $row['path'],
], $fmrRows);

if (! $dryRun) {
    writePhpDataFile($fmrDataPath, $fmrExport);
    echo "Wrote {$fmrDataPath}\n";
} else {
    echo "[dry-run] would write {$fmrDataPath}\n";
}

if (! $skipPdfs) {
    echo "Syncing PDF files...\n";
    $pdfOk = 0;
    $pdfFail = 0;
    foreach ($fmrRows as $row) {
        if (downloadPdf($row['live_url'], $row['path'], $dryRun)) {
            $pdfOk++;
        } else {
            if (! is_file(public_path($row['path'])) && ! $dryRun) {
                $pdfFail++;
            }
        }
    }
    echo "  PDFs present/downloaded: {$pdfOk}, failed: {$pdfFail}\n";
}

if ($dfpRow !== null) {
    $snapshotPayload = [
        'price_date' => $dfpRow['price_date'],
        'agg_bid' => $dfpRow['agg_bid'],
        'agg_offer' => $dfpRow['agg_offer'],
        'bal_bid' => $dfpRow['bal_bid'],
        'bal_offer' => $dfpRow['bal_offer'],
        'con_bid' => $dfpRow['con_bid'],
        'con_offer' => $dfpRow['con_offer'],
    ];

    if (! $dryRun) {
        App\Models\FundDailySnapshot::query()->updateOrCreate(
            ['price_date' => $dfpRow['price_date']],
            $snapshotPayload
        );
        echo "Upserted FundDailySnapshot for {$dfpRow['price_date']}\n";

        // Keep archive fallback in sync for the latest month
        $archivePath = $root.'/resources/data/fund_price_archives.php';
        if (is_file($archivePath)) {
            $archives = require $archivePath;
            if (is_array($archives)) {
                $year = (int) date('Y', strtotime($dfpRow['price_date']));
                $monthTitle = date('F Y', strtotime($dfpRow['price_date']));
                $dayTitle = date('l, F j, Y', strtotime($dfpRow['price_date']));
                if (! isset($archives[$year]) || ! is_array($archives[$year])) {
                    $archives[$year] = [];
                }
                if (! isset($archives[$year][$monthTitle]) || ! is_array($archives[$year][$monthTitle])) {
                    $archives[$year][$monthTitle] = [];
                }
                $rowPayload = [
                    'date' => $dayTitle,
                    'agg_bid' => $dfpRow['agg_bid'],
                    'agg_offer' => $dfpRow['agg_offer'],
                    'bal_bid' => $dfpRow['bal_bid'],
                    'bal_offer' => $dfpRow['bal_offer'],
                    'con_bid' => $dfpRow['con_bid'],
                    'con_offer' => $dfpRow['con_offer'],
                ];
                $updated = false;
                foreach ($archives[$year][$monthTitle] as $idx => $existing) {
                    if (is_array($existing) && ($existing['date'] ?? '') === $dayTitle) {
                        $archives[$year][$monthTitle][$idx] = $rowPayload;
                        $updated = true;
                        break;
                    }
                }
                if (! $updated) {
                    $archives[$year][$monthTitle][] = $rowPayload;
                }
                writePhpDataFile($archivePath, $archives);
                echo "Updated archive row in fund_price_archives.php ({$dayTitle})\n";
            }
        }
    } else {
        echo "[dry-run] would upsert FundDailySnapshot: ".json_encode($snapshotPayload)."\n";
    }
}

echo "\nLive vs local summary:\n";
echo "  FMR months (live): ".implode(', ', array_slice(array_column($fmrExport, 'label'), 0, 5))."...\n";
if ($dfpRow !== null) {
    echo "  DFP latest: {$dfpRow['date']} | Agg offer {$dfpRow['agg_offer']} | Con offer {$dfpRow['con_offer']}\n";
}

if ($withArchives) {
    echo "\nRunning archive sync (all years)...\n";
    $archiveCmd = escapeshellarg(PHP_BINARY).' '.escapeshellarg($root.'/scripts/sync-fund-price-archives-from-live.php');
    if ($dryRun) {
        $archiveCmd .= ' --dry-run';
    }
    passthru($archiveCmd, $archiveExit);
    if ($archiveExit !== 0) {
        exit($archiveExit);
    }
}

echo "Done.\n";
