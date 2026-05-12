<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsMedia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class NavMenuMediaController extends Controller
{
    public function index(): View
    {
        $files = CmsMedia::query()
            ->whereNull('page_id')
            ->orderByDesc('id')
            ->paginate(30)
            ->withQueryString();

        return view('admin.navigation.media-index', ['files' => $files]);
    }

    public function store(Request $request): RedirectResponse
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
        $path = $uploaded->store('cms/menu/'.date('Y/m'), 'public');

        CmsMedia::create([
            'page_id' => null,
            'page_section_id' => null,
            'disk' => 'public',
            'path' => $path,
            'original_name' => $uploaded->getClientOriginalName(),
            'mime' => $uploaded->getClientMimeType(),
            'label' => $request->input('label') ?: $uploaded->getClientOriginalName(),
        ]);

        return redirect()
            ->route('admin.navigation.media.index')
            ->with('status', __('File uploaded. You can attach it to a menu item under Navigation → Add / Edit.'));
    }

    public function destroy(CmsMedia $cmsMedia): RedirectResponse
    {
        if ($cmsMedia->page_id !== null) {
            abort(404);
        }

        if ($cmsMedia->navMenuItems()->exists()) {
            return redirect()
                ->route('admin.navigation.media.index')
                ->withErrors([__('This file is still used by a menu item. Remove or change the menu link first.')]);
        }

        if (Storage::disk($cmsMedia->disk)->exists($cmsMedia->path)) {
            Storage::disk($cmsMedia->disk)->delete($cmsMedia->path);
        }

        $cmsMedia->delete();

        return redirect()->route('admin.navigation.media.index')->with('status', __('File deleted.'));
    }
}
