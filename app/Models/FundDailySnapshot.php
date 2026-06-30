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
            $sync = app(FundDailySnapshotArchiveSync::class);

            if ($snapshot->wasChanged('price_date') && $snapshot->getOriginal('price_date')) {
                $sync->removeForDate($snapshot->getOriginal('price_date'));
            }

            $sync->sync($snapshot);
        });

        static::deleted(function (FundDailySnapshot $snapshot): void {
            app(FundDailySnapshotArchiveSync::class)->removeForSnapshot($snapshot);
        });
    }
}
