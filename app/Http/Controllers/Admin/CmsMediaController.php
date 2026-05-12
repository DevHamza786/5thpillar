<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsMedia;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CmsMediaController extends Controller
{
    public function store(Request $request, Page $page): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'max:51200', Rule::mimetypes(
                'application/pdf',
                'application/x-pdf',
                'image/jpeg',
                'image/png',
                'image/webp',
                'image/gif',
            )],
            'label' => ['nullable', 'string', 'max:255'],
        ]);

        $uploaded = $request->file('file');
        $path = $uploaded->store('cms/'.date('Y/m'), 'public');

        $page->media()->create([
            'disk' => 'public',
            'path' => $path,
            'original_name' => $uploaded->getClientOriginalName(),
            'mime' => $uploaded->getClientMimeType(),
            'label' => $request->input('label') ?: $uploaded->getClientOriginalName(),
        ]);

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('status', __('File uploaded.'));
    }

    public function destroy(Page $page, CmsMedia $cmsMedia): RedirectResponse
    {
        if ($cmsMedia->page_id !== $page->id) {
            abort(404);
        }

        if (Storage::disk($cmsMedia->disk)->exists($cmsMedia->path)) {
            Storage::disk($cmsMedia->disk)->delete($cmsMedia->path);
        }

        $cmsMedia->delete();

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('status', __('File removed.'));
    }
}
