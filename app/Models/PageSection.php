<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PageSection extends Model
{
    protected $fillable = [
        'page_id',
        'sort_order',
        'section_type',
        'heading',
        'body_html',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function trans(string $attribute): mixed
    {
        if (app()->getLocale() === 'ur' && ! empty($this->{$attribute.'_ur'})) {
            return $this->{$attribute.'_ur'};
        }

        return $this->{$attribute};
    }

    public function media(): HasMany
    {
        return $this->hasMany(CmsMedia::class, 'page_section_id');
    }
}

