<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class CmsMedia extends Model
{
    protected $table = 'cms_media';

    protected $fillable = [
        'page_id',
        'page_section_id',
        'disk',
        'path',
        'original_name',
        'mime',
        'label',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(PageSection::class, 'page_section_id');
    }

    public function navMenuItems(): HasMany
    {
        return $this->hasMany(NavMenuItem::class, 'cms_media_id');
    }

    public function publicUrl(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }
}
