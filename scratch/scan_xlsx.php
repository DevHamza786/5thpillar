<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

$base = dirname(__DIR__);

// Umrah: find "Age" cell
$wb = \PhpOffice\PhpSpreadsheet\IOFactory::load($base.'/CV_MKT_BAFL_SAADAT_UMRAH_20260507.xlsx');
$sheet = $wb->getSheetByName('Format');
for ($r = 1; $r <= 20; $r++) {
    for ($c = 1; $c <= 30; $c++) {
        $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
        $v = $sheet->getCell($col.$r)->getValue();
        if ($v === 'Age') {
            echo "Umrah Age header at {$col}{$r}\n";
        }
    }
}

// Hajj: distinct growth rates
$wb2 = \PhpOffice\PhpSpreadsheet\IOFactory::load($base.'/CV_MKT_DSF_HAAZIR_HAJJ_20260508.xlsx');
$sh2 = $wb2->getSheetByName('Hajj');
$rates = [];
for ($r = 2; $r <= $sh2->getHighestRow(); $r++) {
    $v = $sh2->getCell('C'.$r)->getCalculatedValue();
    if ($v !== null && $v !== '') {
        $rates[(string) $v] = true;
    }
}
echo 'Hajj distinct growth_rate values: '.implode(', ', array_keys($rates))."\n";

$row = 4;
$out = [];
for ($c = 2; $c <= 25; $c++) {
    $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
    $v = $sheet->getCell($col.$row)->getValue();
    $out[] = (string) ($v ?? '');
}
echo 'Umrah R4 headers (B..): '.implode(' | ', $out)."\n";

$row = 5;
$out = [];
for ($c = 1; $c <= 20; $c++) {
    $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
    $v = $sheet->getCell($col.$row)->getCalculatedValue();
    if (is_float($v)) {
        $v = round($v, 4);
    }
    $out[] = (string) ($v ?? '');
}
echo 'Umrah R5 sample: '.implode(' | ', $out)."\n";

$ages = [];
$terms = [];
for ($r = 5; $r <= $sheet->getHighestRow(); $r++) {
    $a = $sheet->getCell('B'.$r)->getValue();
    $t = $sheet->getCell('C'.$r)->getValue();
    if (is_numeric($a)) {
        $ages[(int) $a] = true;
    }
    if (is_numeric($t)) {
        $terms[(int) $t] = true;
    }
}
ksort($ages);
ksort($terms);
echo 'Umrah ages: '.implode(', ', array_keys($ages))."\n";
echo 'Umrah terms: '.implode(', ', array_keys($terms))."\n";
