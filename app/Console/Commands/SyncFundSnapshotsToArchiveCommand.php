<?php

namespace App\Console\Commands;

use App\Models\FundDailySnapshot;
use App\Services\FundDailySnapshotArchiveSync;
use Illuminate\Console\Command;

class SyncFundSnapshotsToArchiveCommand extends Command
{
    protected $signature = 'cms:sync-fund-snapshots-to-archive';

    protected $description = 'Push all daily fund snapshot rows into the fund price archive table';

    public function handle(FundDailySnapshotArchiveSync $sync): int
    {
        $count = 0;

        FundDailySnapshot::query()
            ->orderBy('price_date')
            ->each(function (FundDailySnapshot $snapshot) use ($sync, &$count): void {
                $sync->sync($snapshot);
                $count++;
            });

        $this->info("Synced {$count} daily fund snapshot(s) to fund_prices_archive.");

        return self::SUCCESS;
    }
}
