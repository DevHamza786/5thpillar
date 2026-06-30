<?php

namespace App\Console\Commands;

use App\Imports\MarketingSheetImport;
use App\Imports\MarketingWorkbookImport;
use App\Models\FinancialData;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ImportFinancialDataCommand extends Command
{
    protected $signature = 'financial-data:import
                            {file? : Path to CSV/XLSX (absolute, or relative to project root)}
                            {--product=hajj : hajj or umrah (sets defaults for sheet and heading row)}
                            {--sheet= : Excel sheet name (default: Hajj for hajj, Format for umrah)}
                            {--heading-row= : 1-based heading row (default: 1 for hajj, 4 for umrah)}
                            {--append : Do not delete existing rows for this product before import}';

    protected $description = 'Import Hajj or Umrah planner financial rows into financial_data from CSV/XLSX.';

    public function handle(): int
    {
        $product = $this->normalizeProduct((string) $this->option('product'));
        $defaultSheet = $product === 'umrah' ? 'Format' : 'Hajj';
        $defaultHeading = $product === 'umrah' ? 4 : 1;

        $sheet = $this->option('sheet') ?: $defaultSheet;
        $headingRow = $this->option('heading-row') !== null && $this->option('heading-row') !== ''
            ? (int) $this->option('heading-row')
            : $defaultHeading;

        $arg = $this->argument('file');
        $candidates = array_filter([
            $arg ? (is_file($arg) ? $arg : base_path($arg)) : null,
            $product === 'umrah'
                ? base_path('CV_MKT_BAFL_SAADAT_UMRAH_20260507.xlsx')
                : base_path('CV_MKT_DSF_HAAZIR_HAJJ_20260508.xlsx'),
            base_path('wp_financial_data-20260429-131832.csv'),
            base_path('wp_financial_data.csv'),
        ]);

        $path = null;
        foreach ($candidates as $candidate) {
            if ($candidate !== '' && is_file($candidate)) {
                $path = $candidate;
                break;
            }
        }

        if ($path === null) {
            $this->error('No import file found. Pass a path, or place the marketing XLSX in the project root.');

            return self::FAILURE;
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if ($ext === 'csv') {
            $product = 'hajj';
            $sheet = 'Sheet1';
            $headingRow = 1;
            $this->warn('CSV is always imported as Hajj planner data (heading row 1).');
        }

        $this->info("Using file: {$path}");
        $this->info("Product: {$product}, sheet: {$sheet}, heading row: {$headingRow}");

        try {
            DB::beginTransaction();

            if (! $this->option('append')) {
                FinancialData::query()->where('product', $product)->delete();
                $this->line("Deleted existing financial_data rows for product \"{$product}\".");
            }

            if ($ext === 'csv') {
                Excel::import(new MarketingSheetImport('hajj', 1), $path);
            } else {
                Excel::import(new MarketingWorkbookImport($product, $headingRow, $sheet), $path);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Import failed: '.$e->getMessage());

            return self::FAILURE;
        }

        $count = FinancialData::query()->where('product', $product)->count();
        $this->info("Done. Rows for \"{$product}\" in financial_data: {$count}.");

        $ageSample = 20;
        $termSample = $product === 'umrah' ? 10 : 15;
        $sample = FinancialData::query()
            ->where('product', $product)
            ->where('age', $ageSample)
            ->where('term', $termSample)
            ->orderBy('growth_rate')
            ->get(['growth_rate']);

        if ($sample->count() >= 2) {
            $this->info("Sanity check: age {$ageSample} / term {$termSample} has both growth rates (9% and 13%).");
        } elseif ($sample->isNotEmpty()) {
            $this->warn("Sanity check: age {$ageSample} / term {$termSample} is missing one growth_rate row — planners need both 0.09 and 0.13.");
        } else {
            $this->warn("Sanity check: no rows for age {$ageSample} / term {$termSample}.");
        }

        return self::SUCCESS;
    }

    private function normalizeProduct(string $p): string
    {
        $p = strtolower(trim($p));

        return in_array($p, ['hajj', 'umrah'], true) ? $p : 'hajj';
    }
}
