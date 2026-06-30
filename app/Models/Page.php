<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'title',
        'title_ur',
        'slug',
        'slug_ur',
        'view_key',
        'content',
        'content_ur',
        'meta_title',
        'meta_title_ur',
        'meta_description',
        'meta_description_ur',
        'meta_keywords',
        'og_image',
        'hero_title',
        'hero_title_ur',
        'masthead_bg',
        'masthead_bg_ur',
        'is_published',
        'sort_order',
        'status',
        'status_ur',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Page $page): void {
            if ($page->isDirty('status')) {
                $page->is_published = $page->status === self::STATUS_PUBLISHED;
            } elseif ($page->isDirty('is_published') && ! $page->isDirty('status')) {
                $page->status = $page->is_published
                    ? self::STATUS_PUBLISHED
                    : self::STATUS_DRAFT;
            }
        });
    }

    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('sort_order');
    }

    public function enabledSections(): HasMany
    {
        return $this->sections()->where('is_enabled', true);
    }

    public function media(): HasMany
    {
        return $this->hasMany(CmsMedia::class)->whereNull('page_section_id')->orderBy('id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopePublishedForLocale(Builder $query, ?string $locale = null): Builder
    {
        $locale ??= app()->getLocale();

        if (in_array($locale, ['ur', 'urdu'], true)) {
            return $query->where('status_ur', self::STATUS_PUBLISHED);
        }

        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function isPublishedUr(): bool
    {
        return $this->status_ur === self::STATUS_PUBLISHED;
    }

    public function isPublishedForLocale(?string $locale = null): bool
    {
        $locale ??= app()->getLocale();

        return in_array($locale, ['ur', 'urdu'], true)
            ? $this->isPublishedUr()
            : $this->isPublished();
    }

    public function isDraftForLocale(?string $locale = null): bool
    {
        return ! $this->isPublishedForLocale($locale);
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function publicUrl(): string
    {
        return app(\App\Services\UrduLocaleService::class)->pageUrl($this);
    }

    public function localizedSlug(?string $locale = null): string
    {
        return app(\App\Services\UrduLocaleService::class)->pageSlug($this, $locale);
    }

    public function ogImageUrl(): ?string
    {
        if (blank($this->og_image)) {
            return null;
        }

        return asset(ltrim($this->og_image, '/'));
    }

    public function trans(string $attribute): mixed
    {
        $locale = app()->getLocale();
        $urAttribute = $attribute.'_ur';

        if (($locale === 'ur' || $locale === 'urdu') && ! empty($this->{$urAttribute})) {
            return $this->{$urAttribute};
        }

        return $this->{$attribute};
    }

    public function templateSlug(): string
    {
        return $this->view_key ?: $this->slug;
    }
}
