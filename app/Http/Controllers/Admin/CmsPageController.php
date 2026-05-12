<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CmsPageController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $pages = Page::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('title', 'like', '%'.$q.'%')
                        ->orWhere('slug', 'like', '%'.$q.'%');
                });
            })
            ->orderBy('title')
            ->paginate(30)
            ->withQueryString();

        return view('admin.pages.index', ['pages' => $pages, 'q' => $q]);
    }

    public function create(): View
    {
        return view('admin.pages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedPage($request, null);
        $page = Page::create($data);

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('status', __('Page created.'));
    }

    public function edit(Page $page): View
    {
        $page->load(['sections', 'media']);

        return view('admin.pages.edit', ['page' => $page]);
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $page->update($this->validatedPage($request, $page));

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('status', __('Page saved.'));
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedPage(Request $request, ?Page $page): array
    {
        $slugRule = Rule::unique('pages', 'slug');
        if ($page !== null) {
            $slugRule = $slugRule->ignore($page->id);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slugRule],
            'view_key' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:65535'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'masthead_bg' => ['nullable', 'string', 'max:2048'],
            'content' => ['nullable', 'string'],
            'is_published' => ['sometimes', 'boolean'],
        ]);

        $validated['slug'] = Str::lower($validated['slug']);
        if (! empty($validated['view_key'])) {
            $validated['view_key'] = Str::lower($validated['view_key']);
        } else {
            $validated['view_key'] = null;
        }

        $validated['is_published'] = $request->boolean('is_published');

        return $validated;
    }
}
