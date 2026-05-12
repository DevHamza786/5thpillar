<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MarketingWorkbookImport implements WithMultipleSheets
{
    public function __construct(
        private string $product,
        private int $headingRow,
        private string $sheetTitle,
    ) {}

    public function sheets(): array
    {
        return [
            $this->sheetTitle => new MarketingSheetImport($this->product, $this->headingRow),
        ];
    }
}
