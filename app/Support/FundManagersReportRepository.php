<?php

namespace App\Support;

use Illuminate\Support\Facades\File;

final class FundManagersReportRepository
{
    private static ?array $reports = null;

    /**
     * @return list<array{label: string, path: string}>
     */
    public static function reports(): array
    {
        if (self::$reports !== null) {
            return self::$reports;
        }

        $path = resource_path('data/fund_managers_reports.php');
        if (! File::exists($path)) {
            self::$reports = [];

            return self::$reports;
        }

        $raw = require $path;
        self::$reports = is_array($raw) ? array_values($raw) : [];

        return self::$reports;
    }
}
