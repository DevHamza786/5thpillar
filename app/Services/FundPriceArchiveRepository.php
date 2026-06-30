<?php

namespace App\Services;

use App\Models\CmsTable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

final class FundPriceArchiveRepository
{
    private static ?array $legacyCache = null;

    /**
     * @return array<string, list<array<string, mixed>>>
     */
    public static function monthsForYear(int $year): array
    {
        $fromDb = self::monthsFromDatabase($year);
        if ($fromDb !== []) {
            return $fromDb;
        }

        return self::legacyMonthsForYear($year);
    }

    /**
     * @return array<string, list<array<string, mixed>>>
     */
    private static function monthsFromDatabase(int $year): array
    {
        $table = CmsTable::query()->where('key', 'fund_prices_archive')->first();
        if ($table === null) {
            return [];
        }

        $rows = $table->enabledRows()
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($row) => $row->data)
            ->filter(fn ($data) => (int) ($data['year'] ?? 0) === $year)
            ->values();

        if ($rows->isEmpty()) {
            return [];
        }

        return $rows
            ->groupBy(fn ($data) => (string) ($data['month'] ?? 'Unknown'))
            ->map(fn (Collection $group) => $group->map(function (array $data) {
                return [
                    'date' => $data['date'] ?? '',
                    'agg_bid' => $data['agg_bid'] ?? '',
                    'agg_offer' => $data['agg_offer'] ?? '',
                    'bal_bid' => $data['bal_bid'] ?? '',
                    'bal_offer' => $data['bal_offer'] ?? '',
                    'con_bid' => $data['con_bid'] ?? '',
                    'con_offer' => $data['con_offer'] ?? '',
                    'brk_bid' => $data['brk_bid'] ?? '',
                    'brk_offer' => $data['brk_offer'] ?? '',
                ];
            })->sortBy(fn (array $row) => self::archiveRowSortKey($row['date']))->values()->all())
            ->all();
    }

    /**
     * @return array<string, list<array<string, mixed>>>
     */
    private static function legacyMonthsForYear(int $year): array
    {
        $months = is_array(self::legacyArchives()[$year] ?? null) ? self::legacyArchives()[$year] : [];

        foreach ($months as $label => $rows) {
            if (! is_array($rows)) {
                continue;
            }

            usort($rows, fn (array $a, array $b) => self::archiveRowSortKey($a['date'] ?? '') <=> self::archiveRowSortKey($b['date'] ?? ''));
            $months[$label] = $rows;
        }

        return $months;
    }

    /**
     * @return array<int|string, mixed>
     */
    private static function legacyArchives(): array
    {
        if (self::$legacyCache !== null) {
            return self::$legacyCache;
        }

        $path = resource_path('data/fund_price_archives.php');
        if (! File::exists($path)) {
            self::$legacyCache = [];

            return self::$legacyCache;
        }

        $loaded = require $path;
        self::$legacyCache = is_array($loaded) ? $loaded : [];

        return self::$legacyCache;
    }

    /**
     * @return list<int>
     */
    public static function availableYears(): array
    {
        $years = collect(array_keys(self::legacyArchives()))
            ->map(fn ($year) => (int) $year)
            ->filter(fn ($year) => $year > 0);

        $table = CmsTable::query()->where('key', 'fund_prices_archive')->first();
        if ($table !== null) {
            $dbYears = $table->enabledRows()
                ->get()
                ->map(fn ($row) => (int) ($row->data['year'] ?? 0))
                ->filter(fn ($year) => $year > 0);

            $years = $years->merge($dbYears);
        }

        return $years->unique()->sort()->values()->all();
    }

    /**
     * Latest row from legacy archives (fallback when no CMS snapshot).
     *
     * @return array<string, mixed>|null
     */
    public static function latestLegacyRow(): ?array
    {
        $all = self::legacyArchives();
        if ($all === []) {
            return null;
        }

        krsort($all, SORT_NUMERIC);

        foreach ($all as $months) {
            if (! is_array($months)) {
                continue;
            }

            $monthKeys = array_keys($months);
            for ($mi = count($monthKeys) - 1; $mi >= 0; $mi--) {
                $rows = $months[$monthKeys[$mi]] ?? null;
                if (is_array($rows) && count($rows) > 0) {
                    return end($rows) ?: null;
                }
            }
        }

        return null;
    }

    private static function archiveRowSortKey(string $dateLabel): int
    {
        try {
            return (int) \Carbon\Carbon::createFromFormat('l, F j, Y', $dateLabel)->format('Ymd');
        } catch (\Throwable) {
            $fallback = strtotime($dateLabel);

            return $fallback !== false ? (int) date('Ymd', $fallback) : 0;
        }
    }
}
