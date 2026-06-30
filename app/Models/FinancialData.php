<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialData extends Model
{
    protected $table = 'financial_data';

    protected $fillable = [
        'product',
        'age',
        'term',
        'annual_contribution',
        'growth_rate',
        'takaful_benefit',
        'year_five',
        'year_seven',
        'year_ten',
        'year_fifteen',
        'year_twenty',
        'year_twenty_five',
    ];
}
