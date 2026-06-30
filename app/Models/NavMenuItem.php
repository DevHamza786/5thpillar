<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NavMenuItem extends Model
{
    public const LINK_HOME = 'home';

    public const LINK_NONE = 'none';

    public const LINK_PAGE_SLUG = 'page_slug';

    public const LINK_NAMED_ROUTE = 'named_route';

    public const LINK_CUSTOM_URL = 'custom_url';

    public const LINK_MEDIA = 'media';

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'link_type' => self::LINK_NONE,
        'sort_order' => 0,
        'open_new_tab' => false,
    ];

    protected $fillable = [
        'parent_id',
        'sort_order',
        'label',
        'link_type',
        'page_slug',
        'route_name',
        'custom_url',
        'cms_media_id',
        'open_new_tab',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'open_new_tab' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function cmsMedia(): BelongsTo
    {
        return $this->belongsTo(CmsMedia::class, 'cms_media_id');
    }
}
