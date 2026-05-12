<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PageSectionController extends Controller
{
    public function store(Request $request, Page $page): RedirectResponse
    {
        $data = $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'body_html' => ['nullable', 'string'],
            'section_type' => ['nullable', 'string', 'max:64'],
        ]);

        $maxOrder = (int) $page->sections()->max('sort_order');

        $page->sections()->create([
            'heading' => $data['heading'] ?? null,
            'body_html' => $data['body_html'] ?? null,
            'section_type' => $data['section_type'] ?? 'content',
            'sort_order' => $maxOrder + 1,
        ]);

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('status', __('Section added.'));
    }

    public function update(Request $request, Page $page, PageSection $pageSection): RedirectResponse
    {
        if ($pageSection->page_id !== $page->id) {
            abort(404);
        }

        $data = $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'body_html' => ['nullable', 'string'],
            'section_type' => ['nullable', 'string', 'max:64'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:99999'],
        ]);

        $pageSection->update([
            'heading' => $data['heading'] ?? null,
            'body_html' => $data['body_html'] ?? null,
            'section_type' => $data['section_type'] ?? 'content',
            'sort_order' => isset($data['sort_order']) ? (int) $data['sort_order'] : $pageSection->sort_order,
        ]);

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('status', __('Section updated.'));
    }

    public function destroy(Page $page, PageSection $pageSection): RedirectResponse
    {
        if ($pageSection->page_id !== $page->id) {
            abort(404);
        }

        $pageSection->delete();

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('status', __('Section removed.'));
    }
}
