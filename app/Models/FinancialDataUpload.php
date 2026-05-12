<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialDataUpload extends Model
{
    protected $table = 'financial_data_uploads';

    protected $fillable = [
        'filename',
        'total_rows',
        'uploaded_by',
    ];
}
