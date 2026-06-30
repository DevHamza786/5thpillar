<?php

namespace App\Services;

use App\Models\CmsMedia;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CmsMediaStorage
{
    /**
     * @return array{disk: string, path: string, asset_type: string, file_size: int}
     */
    public function storeLibraryFile(UploadedFile $file, string $folder, ?string $assetType = null): array
    {
        $folder = $this->normalizeFolder($folder);
        $assetType = $assetType ?: $this->detectAssetType($file);

        $filename = $this->uniqueFilename($file, $folder);
        $relativePath = $folder.'/'.$filename;
        $absolutePath = public_path('assets/'.$relativePath);

        // Capture before move(); getSize() fails once the temp upload file is gone.
        $fileSize = (int) ($file->getSize() ?: 0);

        File::ensureDirectoryExists(dirname($absolutePath));
        $file->move(dirname($absolutePath), $filename);

        return [
            'disk' => CmsMedia::DISK_ASSETS,
            'path' => 'assets/'.$relativePath,
            'asset_type' => $assetType,
            'file_size' => $fileSize,
        ];
    }

    public function replaceLibraryFile(CmsMedia $media, UploadedFile $file): void
    {
        $this->deletePhysicalFile($media);

        $folder = $media->folder ?: 'images';
        $stored = $this->storeLibraryFile($file, $folder, $media->asset_type);

        $media->fill([
            'disk' => $stored['disk'],
            'path' => $stored['path'],
            'original_name' => $file->getClientOriginalName(),
            'mime' => $file->getClientMimeType(),
            'file_size' => $stored['file_size'],
        ]);
    }

    public function deletePhysicalFile(CmsMedia $media): void
    {
        if ($media->disk === CmsMedia::DISK_ASSETS) {
            $absolute = public_path(ltrim($media->path, '/'));
            if (is_file($absolute)) {
                File::delete($absolute);
            }

            return;
        }

        if (\Illuminate\Support\Facades\Storage::disk($media->disk)->exists($media->path)) {
            \Illuminate\Support\Facades\Storage::disk($media->disk)->delete($media->path);
        }
    }

    public function normalizeFolder(string $folder): string
    {
        $folder = trim(str_replace('\\', '/', $folder), '/');
        $folder = preg_replace('#\.\.+#', '', $folder) ?? $folder;

        $allowed = array_merge(
            array_keys(config('cms.media.image_folders', [])),
            array_keys(config('cms.media.pdf_folders', []))
        );

        if (! in_array($folder, $allowed, true)) {
            return 'images';
        }

        return $folder;
    }

    public function detectAssetType(UploadedFile $file): string
    {
        $mime = $file->getClientMimeType() ?? '';

        if (in_array($mime, config('cms.media.allowed_pdf_mimes', []), true)) {
            return CmsMedia::TYPE_PDF;
        }

        if (in_array($mime, config('cms.media.allowed_image_mimes', []), true)) {
            return CmsMedia::TYPE_IMAGE;
        }

        return CmsMedia::TYPE_FILE;
    }

    private function uniqueFilename(UploadedFile $file, string $folder): string
    {
        $base = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $base = Str::slug($base) ?: 'file';
        $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'bin');
        $candidate = $base.'.'.$ext;
        $absoluteDir = public_path('assets/'.$folder);
        $i = 1;

        while (is_file($absoluteDir.'/'.$candidate)) {
            $candidate = $base.'-'.$i.'.'.$ext;
            $i++;
        }

        return $candidate;
    }
}
