<?php

namespace App\Models;

use App\Services\FundDailySnapshotArchiveSync;
use Illuminate\Database\Eloquent\Model;

class FundDailySnapshot extends Model
{
    protected $fillable = [
        'price_date',
        'agg_bid',
        'agg_offer',
        'bal_bid',
        'bal_offer',
        'con_bid',
        'con_offer',
        'brk_bid',
        'brk_offer',
    ];

    protected function casts(): array
    {
        return [
            'price_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (FundDailySnapshot $snapshot): void {
            // The archive is a permanent historical log. Only ever update the
            // row that matches this snapshot's current date, or create a new one.
            // Changing a snapshot's date must NOT remove the previous date's
            // archive row — that entry stays as history.
            app(FundDailySnapshotArchiveSync::class)->sync($snapshot);
        });
    }
}
