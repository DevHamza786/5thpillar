<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

function peekSheet(string $path, string $sheetName = null): void
{
    echo "\n=== {$path} ===\n";
    if (! is_file($path)) {
        echo "MISSING\n";

        return;
    }
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
    $sheet = $sheetName
        ? $spreadsheet->getSheetByName($sheetName)
        : $spreadsheet->getActiveSheet();
    if (! $sheet) {
        echo "Sheet not found\n";

        return;
    }
    echo 'Sheet: '.$sheet->getTitle()."\n";
    $hr = $sheet->getHighestRow();
    $hc = $sheet->getHighestColumn();
    $w = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($hc);
    echo "Dimensions: rows={$hr} cols={$w} (to {$hc})\n";
    for ($r = 1; $r <= min(3, $hr); $r++) {
        $cells = [];
        for ($ci = 1; $ci <= $w; $ci++) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($ci);
            $v = $sheet->getCell($col.$r)->getCalculatedValue();
            if (is_float($v)) {
                $v = round($v, 4);
            }
            $cells[] = $v === null ? '' : (string) $v;
        }
        echo 'R'.$r.":\t".implode("\t", $cells)."\n";
    }
}

$base = dirname(__DIR__);
peekSheet($base.'/CV_MKT_DSF_HAAZIR_HAJJ_20260508.xlsx', 'Hajj');
peekSheet($base.'/CV_MKT_BAFL_SAADAT_UMRAH_20260507.xlsx', 'Format');
