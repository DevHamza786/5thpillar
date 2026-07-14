<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsMedia;
use App\Services\CmsMediaStorage;
use App\Services\CmsPdfUsageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MediaLibraryController extends Controller
{
    public function __construct(
        private readonly CmsMediaStorage $storage
    ) {}

    public function index(Request $request, CmsPdfUsageService $pdfUsage): View
    {
        $type = $request->query('type', 'all');
        $folder = $request->query('folder', '');

        $query = CmsMedia::query()->library()->orderByDesc('updated_at');

        if ($type === 'image') {
            $query->images();
        } elseif ($type === 'pdf') {
            $query->pdfs();
        }

        if ($folder !== '') {
            $query->where('folder', $folder);
        }

        $media = $query->paginate(36)->withQueryString();
        $pdfUsageByPath = $pdfUsage->usageByPath(
            $media->getCollection()->filter(fn (CmsMedia $item) => $item->isPdf())
        );

        return view('admin.media.index', [
            'media' => $media,
            'type' => $type,
            'folder' => $folder,
            'imageFolders' => config('cms.media.image_folders', []),
            'pdfFolders' => config('cms.media.pdf_folders', []),
            'pdfUsageByPath' => $pdfUsageByPath,
        ]);
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $imageFolders = array_keys(config('cms.media.image_folders', []));
        $pdfFolders = array_keys(config('cms.media.pdf_folders', []));
        $allFolders = array_merge($imageFolders, $pdfFolders);

        $request->validate([
            'file' => ['required', 'file', 'max:'.config('cms.media.max_upload_kb', 51200)],
            'folder' => ['required', 'string', Rule::in($allFolders)],
            'label' => ['nullable', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
        ]);

        $file = $request->file('file');
        $folder = $this->storage->normalizeFolder($request->input('folder'));
        $stored = $this->storage->storeLibraryFile($file, $folder);

        $media = CmsMedia::create([
            'disk' => $stored['disk'],
            'path' => $stored['path'],
            'original_name' => $file->getClientOriginalName(),
            'mime' => $file->getClientMimeType(),
            'label' => $request->input('label') ?: $file->getClientOriginalName(),
            'folder' => $folder,
            'asset_type' => $stored['asset_type'],
            'file_size' => $stored['file_size'],
            'alt_text' => $request->input('alt_text'),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => __('File uploaded to media library.'),
                'data' => $this->pickerItem($media),
            ], 201);
        }

        return redirect()
            ->route('admin.media.index', ['type' => $stored['asset_type'] === CmsMedia::TYPE_PDF ? 'pdf' : 'image', 'folder' => $folder])
            ->with('status', __('File uploaded to media library.'));
    }

    public function update(Request $request, CmsMedia $cmsMedia): RedirectResponse
    {
        if ($cmsMedia->page_id !== null || $cmsMedia->page_section_id !== null) {
            abort(404);
        }

        $imageFolders = array_keys(config('cms.media.image_folders', []));
        $pdfFolders = array_keys(config('cms.media.pdf_folders', []));
        $allFolders = array_merge($imageFolders, $pdfFolders);

        $request->validate([
            'label' => ['nullable', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'folder' => ['nullable', 'string', Rule::in($allFolders)],
            'file' => ['nullable', 'file', 'max:'.config('cms.media.max_upload_kb', 51200)],
        ]);

        if ($request->hasFile('file')) {
            $this->storage->replaceLibraryFile($cmsMedia, $request->file('file'));
        }

        if ($request->filled('folder') && $request->input('folder') !== $cmsMedia->folder) {
            $cmsMedia->folder = $this->storage->normalizeFolder($request->input('folder'));
        }

        $cmsMedia->label = $request->input('label', $cmsMedia->label);
        $cmsMedia->alt_text = $request->input('alt_text', $cmsMedia->alt_text);
        $cmsMedia->save();

        return redirect()
            ->route('admin.media.index', ['folder' => $cmsMedia->folder])
            ->with('status', __('Media updated.'));
    }

    public function destroy(CmsMedia $cmsMedia): RedirectResponse
    {
        if ($cmsMedia->page_id !== null || $cmsMedia->page_section_id !== null) {
            abort(404);
        }

        if ($cmsMedia->navMenuItems()->exists()) {
            return redirect()
                ->route('admin.media.index')
                ->withErrors(['media' => __('This file is linked from the navigation menu and cannot be deleted.')]);
        }

        $this->storage->deletePhysicalFile($cmsMedia);
        $cmsMedia->delete();

        return redirect()
            ->route('admin.media.index')
            ->with('status', __('File deleted.'));
    }

    public function picker(Request $request): JsonResponse
    {
        $type = $request->query('type', 'image');
        $folder = $request->query('folder');

        $query = CmsMedia::query()->library()->orderByDesc('updated_at');

        if ($type === 'pdf') {
            $query->pdfs();
        } else {
            $query->images();
        }

        if ($folder) {
            $query->where('folder', $folder);
        }

        $items = $query->limit(100)->get()->map(fn (CmsMedia $m) => $this->pickerItem($m));

        return response()->json(['data' => $items]);
    }

    /**
     * @return array<string, mixed>
     */
    private function pickerItem(CmsMedia $media): array
    {
        return [
            'id' => $media->id,
            'label' => $media->label,
            'path' => $media->path,
            'url' => $media->copyUrl(),
            'public_url' => $media->publicUrl(),
            'mime' => $media->mime,
            'folder' => $media->folder,
            'asset_type' => $media->asset_type,
            'alt_text' => $media->alt_text,
        ];
    }
}
