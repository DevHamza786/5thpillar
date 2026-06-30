<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsTableRow extends Model
{
    protected $fillable = [
        'cms_table_id',
        'sort_order',
        'data',
        'is_enabled',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'data' => 'array',
            'is_enabled' => 'boolean',
        ];
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(CmsTable::class, 'cms_table_id');
    }
}
