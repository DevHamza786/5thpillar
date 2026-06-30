<?php

namespace App\Models;

use App\Support\PublicPath;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class CmsMedia extends Model
{
    public const TYPE_IMAGE = 'image';

    public const TYPE_PDF = 'pdf';

    public const TYPE_FILE = 'file';

    public const DISK_ASSETS = 'assets';

    public const DISK_PUBLIC = 'public';

    protected $table = 'cms_media';

    protected $fillable = [
        'page_id',
        'page_section_id',
        'disk',
        'path',
        'original_name',
        'mime',
        'label',
        'folder',
        'asset_type',
        'file_size',
        'alt_text',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }

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

    public function scopeLibrary(Builder $query): Builder
    {
        return $query->whereNull('page_id')->whereNull('page_section_id');
    }

    public function scopeImages(Builder $query): Builder
    {
        return $query->where(function (Builder $inner) {
            $inner->where('asset_type', self::TYPE_IMAGE)
                ->orWhere('mime', 'like', 'image/%');
        });
    }

    public function scopePdfs(Builder $query): Builder
    {
        return $query->where(function (Builder $inner) {
            $inner->where('asset_type', self::TYPE_PDF)
                ->orWhereIn('mime', ['application/pdf', 'application/x-pdf']);
        });
    }

    public function publicUrl(): string
    {
        if ($this->disk === self::DISK_ASSETS) {
            return PublicPath::ensurePdfViewerUrl(asset($this->path));
        }

        return PublicPath::ensurePdfViewerUrl(Storage::disk($this->disk)->url($this->path));
    }

    public function copyUrl(): string
    {
        if ($this->disk === self::DISK_ASSETS) {
            return asset($this->path);
        }

        return Storage::disk($this->disk)->url($this->path);
    }

    public function isImage(): bool
    {
        if ($this->asset_type === self::TYPE_IMAGE) {
            return true;
        }

        return is_string($this->mime) && str_starts_with($this->mime, 'image/');
    }

    public function isPdf(): bool
    {
        if ($this->asset_type === self::TYPE_PDF) {
            return true;
        }

        return in_array($this->mime, ['application/pdf', 'application/x-pdf'], true);
    }
}
