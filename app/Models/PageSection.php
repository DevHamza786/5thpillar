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
        'heading_ur',
        'body_html',
        'body_html_ur',
        'content',
        'settings',
        'is_enabled',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'content' => 'array',
            'settings' => 'array',
            'is_enabled' => 'boolean',
        ];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function trans(string $attribute): mixed
    {
        $locale = app()->getLocale();
        $urAttribute = $attribute.'_ur';

        if (in_array($locale, ['ur', 'urdu'], true) && ! empty($this->{$urAttribute})) {
            return $this->{$urAttribute};
        }

        return $this->{$attribute};
    }

    public function media(): HasMany
    {
        return $this->hasMany(CmsMedia::class, 'page_section_id');
    }
}
