<?php

namespace App\Services;

use App\Models\CmsTable;
use App\Models\CmsTableRow;
use App\Models\FundDailySnapshot;
use Carbon\CarbonInterface;

class FundDailySnapshotArchiveSync
{
    public function __construct(
        private readonly CmsTableRegistry $registry
    ) {}

    public function sync(FundDailySnapshot $snapshot): void
    {
        $table = $this->archiveTable();
        $payload = $this->payloadFromSnapshot($snapshot);

        $existing = $this->findRow($table, $payload['year'], $payload['month'], $payload['date']);

        if ($existing !== null) {
            $existing->update([
                'data' => $payload,
                'is_enabled' => true,
            ]);

            return;
        }

        $table->rows()->create([
            'sort_order' => $this->sortOrderForDate($snapshot->price_date),
            'data' => $payload,
            'is_enabled' => true,
        ]);
    }

    public function removeForSnapshot(FundDailySnapshot $snapshot): void
    {
        $this->removeForDate($snapshot->price_date);
    }

    public function removeForDate(CarbonInterface|string $date): void
    {
        $table = CmsTable::query()->where('key', 'fund_prices_archive')->first();
        if ($table === null) {
            return;
        }

        $carbon = $date instanceof CarbonInterface ? $date : \Carbon\Carbon::parse($date);
        $year = (int) $carbon->format('Y');
        $month = $carbon->format('F Y');
        $dateLabel = $carbon->format('l, F j, Y');

        $this->findRow($table, $year, $month, $dateLabel)?->delete();
    }

    /**
     * @return array<string, mixed>
     */
    private function payloadFromSnapshot(FundDailySnapshot $snapshot): array
    {
        $date = $snapshot->price_date;

        return $this->registry->normalizeRow('fund_prices_archive', [
            'year' => (int) $date->format('Y'),
            'month' => $date->format('F Y'),
            'date' => $date->format('l, F j, Y'),
            'agg_bid' => (string) ($snapshot->agg_bid ?? ''),
            'agg_offer' => (string) ($snapshot->agg_offer ?? ''),
            'bal_bid' => (string) ($snapshot->bal_bid ?? ''),
            'bal_offer' => (string) ($snapshot->bal_offer ?? ''),
            'con_bid' => (string) ($snapshot->con_bid ?? ''),
            'con_offer' => (string) ($snapshot->con_offer ?? ''),
            'brk_bid' => (string) ($snapshot->brk_bid ?? ''),
            'brk_offer' => (string) ($snapshot->brk_offer ?? ''),
        ]);
    }

    private function archiveTable(): CmsTable
    {
        return $this->registry->ensureTable('fund_prices_archive');
    }

    private function findRow(CmsTable $table, int $year, string $month, string $dateLabel): ?CmsTableRow
    {
        return $table->rows()
            ->where('data->year', $year)
            ->where('data->month', $month)
            ->where('data->date', $dateLabel)
            ->first();
    }

    private function sortOrderForDate(CarbonInterface $date): int
    {
        return (int) $date->format('Ymd');
    }
}
