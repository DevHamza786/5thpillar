<?php

namespace App\Imports;

use App\Models\FinancialData;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MarketingSheetImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, SkipsEmptyRows
{
    public function __construct(
        private string $product,
        private int $headingRowNumber = 1,
    ) {}

    public function headingRow(): int
    {
        return $this->headingRowNumber;
    }

    public function model(array $row)
    {
        $age = (int) round($this->getNumeric($row, ['age']));
        $term = (int) round($this->getNumeric($row, ['term']));

        if ($age < 18 || $term < 1) {
            return null;
        }

        $base = [
            'product' => $this->product,
            'age' => $age,
            'term' => $term,
            'annual_contribution' => $this->getNumeric($row, ['annual_contribution']),
            'growth_rate' => $this->getNumeric($row, ['growth_rate']),
            'takaful_benefit' => $this->getNumeric($row, ['takaful_benefit', 'death_benefit']),
        ];

        if ($this->product === 'hajj') {
            return new FinancialData(array_merge($base, [
                'year_five' => null,
                'year_seven' => null,
                'year_ten' => $this->getNumeric($row, ['year_10', 'year_ten']),
                'year_fifteen' => $this->getNumeric($row, ['year_15', 'year_fifteen']),
                'year_twenty' => $this->getNumeric($row, ['year_20', 'year_twenty']),
                'year_twenty_five' => $this->getNumeric($row, ['year_25', 'year_twenty_five']),
            ]));
        }

        return new FinancialData(array_merge($base, [
            'year_five' => $this->getNumeric($row, ['year_5', 'year_five']),
            'year_seven' => $this->getNumeric($row, ['year_7', 'year_seven']),
            'year_ten' => $this->getNumeric($row, ['year_10', 'year_ten']),
            'year_fifteen' => $this->getNumeric($row, ['year_15', 'year_fifteen']),
            'year_twenty' => null,
            'year_twenty_five' => null,
        ]));
    }

    private function getNumeric(array $row, array $candidates): float
    {
        foreach ($candidates as $key) {
            if (! array_key_exists($key, $row)) {
                continue;
            }
            $v = $row[$key];
            if ($v === null || $v === '') {
                continue;
            }
            if (is_numeric($v)) {
                return (float) $v;
            }
        }

        foreach ($row as $k => $v) {
            if ($v === null || $v === '') {
                continue;
            }
            $normK = Str::slug((string) $k, '_');
            foreach ($candidates as $want) {
                if ($normK === Str::slug($want, '_') && is_numeric($v)) {
                    return (float) $v;
                }
            }
        }

        return 0.0;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
