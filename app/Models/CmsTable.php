<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsTable extends Model
{
    protected $fillable = [
        'key',
        'label',
        'description',
        'schema',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'schema' => 'array',
            'settings' => 'array',
        ];
    }

    public function rows(): HasMany
    {
        return $this->hasMany(CmsTableRow::class)->orderBy('sort_order');
    }

    public function enabledRows(): HasMany
    {
        return $this->rows()->where('is_enabled', true);
    }
}
