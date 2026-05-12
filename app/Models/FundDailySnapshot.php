<?php

namespace App\Models;

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
}
