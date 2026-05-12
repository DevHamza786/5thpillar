<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'view_key',
        'content',
        'meta_title',
        'meta_description',
        'hero_title',
        'masthead_bg',
        'masthead_bg_ur',
        'title_ur',
        'hero_title_ur',
        'content_ur',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('sort_order');
    }

    public function media(): HasMany
    {
        return $this->hasMany(CmsMedia::class)->whereNull('page_section_id')->orderBy('id');
    }

    public function trans(string $attribute): mixed
    {
        $locale = app()->getLocale();
        $urAttribute = $attribute . '_ur';
        
        if (($locale === 'ur' || $locale === 'urdu') && !empty($this->{$urAttribute})) {
            return $this->{$urAttribute};
        }

        return $this->{$attribute};
    }


    public function templateSlug(): string
    {
        return $this->view_key ?: $this->slug;
    }
}

