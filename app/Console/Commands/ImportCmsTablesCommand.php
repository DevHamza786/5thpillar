<?php

namespace App\Console\Commands;

use App\Services\CmsTableRegistry;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ImportCmsTablesCommand extends Command
{
    protected $signature = 'cms:import-tables
                            {table? : Table key to import (default: all)}
                            {--force : Replace existing rows}';

    protected $description = 'Import legacy PHP data files into CMS data tables';

    public function __construct(
        private readonly CmsTableRegistry $registry
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $table = $this->argument('table');

        if ($table === null || $table === 'fund_prices_archive') {
            $this->importFundPriceArchives();
        }

        if ($table !== null && $table !== 'fund_prices_archive') {
            $this->error("Unknown table key: {$table}");

            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    private function importFundPriceArchives(): void
    {
        $path = resource_path('data/fund_price_archives.php');
        if (! File::exists($path)) {
            $this->warn('fund_price_archives.php not found.');

            return;
        }

        $archives = require $path;
        if (! is_array($archives)) {
            $this->error('Invalid archive file format.');

            return;
        }

        $cmsTable = $this->registry->ensureTable('fund_prices_archive');

        if ($cmsTable->rows()->exists() && ! $this->option('force')) {
            $this->warn('fund_prices_archive already has rows. Use --force to replace.');

            return;
        }

        if ($this->option('force')) {
            $cmsTable->rows()->delete();
        }

        $sort = 0;
        $count = 0;

        foreach ($archives as $year => $months) {
            if (! is_array($months)) {
                continue;
            }

            foreach ($months as $monthLabel => $rows) {
                if (! is_array($rows)) {
                    continue;
                }

                foreach ($rows as $row) {
                    if (! is_array($row)) {
                        continue;
                    }

                    $cmsTable->rows()->create([
                        'sort_order' => $sort++,
                        'is_enabled' => true,
                        'data' => $this->registry->normalizeRow('fund_prices_archive', [
                            'year' => (int) $year,
                            'month' => (string) $monthLabel,
                            'date' => (string) ($row['date'] ?? ''),
                            'agg_bid' => (string) ($row['agg_bid'] ?? ''),
                            'agg_offer' => (string) ($row['agg_offer'] ?? ''),
                            'bal_bid' => (string) ($row['bal_bid'] ?? ''),
                            'bal_offer' => (string) ($row['bal_offer'] ?? ''),
                            'con_bid' => (string) ($row['con_bid'] ?? ''),
                            'con_offer' => (string) ($row['con_offer'] ?? ''),
                        ]),
                    ]);
                    $count++;
                }
            }
        }

        $this->info("Imported {$count} fund price archive rows.");
    }
}
