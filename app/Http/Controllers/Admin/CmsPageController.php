<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Support\NewsEventsRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View as ViewContract;

class CmsPageController extends Controller
{
    public function index(Request $request): ViewContract
    {
        $q = trim((string) $request->query('q', ''));
        $status = $request->query('status', '');
        $statusUr = $request->query('status_ur', '');

        $pages = Page::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('title', 'like', '%'.$q.'%')
                        ->orWhere('title_ur', 'like', '%'.$q.'%')
                        ->orWhere('slug', 'like', '%'.$q.'%')
                        ->orWhere('slug_ur', 'like', '%'.$q.'%');
                });
            })
            ->when(in_array($status, [Page::STATUS_DRAFT, Page::STATUS_PUBLISHED], true), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when(in_array($statusUr, [Page::STATUS_DRAFT, Page::STATUS_PUBLISHED], true), function ($query) use ($statusUr) {
                $query->where('status_ur', $statusUr);
            })
            ->orderBy('sort_order')
            ->orderBy('title')
            ->paginate(30)
            ->withQueryString();

        return view('admin.pages.index', [
            'pages' => $pages,
            'q' => $q,
            'status' => $status,
            'statusUr' => $statusUr,
        ]);
    }

    public function create(): ViewContract
    {
        return view('admin.pages.create', [
            'statuses' => config('cms.page_statuses', []),
            'imageFolders' => config('cms.media.image_folders', []),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedPage($request, null);
        $page = Page::create($data);

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('status', __('Page created.'));
    }

    public function edit(Page $page): ViewContract
    {
        $page->load(['sections', 'media']);

        return view('admin.pages.edit', [
            'page' => $page,
            'statuses' => config('cms.page_statuses', []),
            'imageFolders' => config('cms.media.image_folders', []),
            'sectionTypes' => config('cms.section_types', []),
            'sectionRoles' => config('cms.section_roles', []),
            'libraryImages' => \App\Models\CmsMedia::query()->library()->images()->orderByDesc('updated_at')->limit(50)->get(),
        ]);
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $page->update($this->validatedPage($request, $page));

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('status', __('Page saved.'));
    }

    public function preview(Request $request, Page $page): ViewContract
    {
        if ($request->query('locale') === 'ur') {
            app()->setLocale('ur');
        }

        $page->load(['sections' => fn ($q) => $q->orderBy('sort_order'), 'media']);

        if ($page->slug === 'home') {
            $newsSliderPosts = NewsEventsRepository::posts()
                ->take(10)
                ->map(function (array $post) {
                    $post['date_label'] = Carbon::parse($post['date'])->format('F j, Y');

                    return $post;
                })
                ->values();

            return view('home.index', [
                'page' => $page,
                'homePage' => $page,
                'newsSliderPosts' => $newsSliderPosts,
                'cmsPreview' => true,
            ]);
        }

        $viewKey = $page->templateSlug();
        $view = 'pages.static.'.$viewKey;

        if (! View::exists($view)) {
            $view = 'pages.static.cms-generic';
        }

        return view($view, [
            'page' => $page,
            'cmsPreview' => true,
        ]);
    }

    public function purgeLegacy(Request $request): RedirectResponse
    {
        $slugs = config('cms.legacy_demo_slugs', []);

        $pages = Page::query()
            ->whereIn('slug', $slugs)
            ->orWhereIn('view_key', $slugs)
            ->get();

        $count = 0;

        foreach ($pages as $legacyPage) {
            $legacyPage->update([
                'status' => Page::STATUS_DRAFT,
                'status_ur' => Page::STATUS_DRAFT,
                'is_published' => false,
            ]);
            $count++;
        }

        return redirect()
            ->route('admin.dashboard')
            ->with('status', __(':count legacy placeholder page(s) set to draft.', ['count' => $count]));
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedPage(Request $request, ?Page $page): array
    {
        $slugRule = Rule::unique('pages', 'slug');
        $slugUrRule = Rule::unique('pages', 'slug_ur');
        if ($page !== null) {
            $slugRule = $slugRule->ignore($page->id);
            $slugUrRule = $slugUrRule->ignore($page->id);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'title_ur' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slugRule],
            'slug_ur' => ['nullable', 'string', 'max:255', 'regex:/^[\p{L}\p{N}\-_]+(?:[\p{L}\p{N}\-_]+)*$/u', $slugUrRule],
            'view_key' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_title_ur' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:65535'],
            'meta_description_ur' => ['nullable', 'string', 'max:65535'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'og_image' => ['nullable', 'string', 'max:512', 'regex:#^(assets/|https?://)#'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_title_ur' => ['nullable', 'string', 'max:255'],
            'masthead_bg' => ['nullable', 'string', 'max:2048'],
            'masthead_bg_ur' => ['nullable', 'string', 'max:2048'],
            'content' => ['nullable', 'string'],
            'content_ur' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:99999'],
            'status' => ['required', Rule::in(array_keys(config('cms.page_statuses', [])))],
            'status_ur' => ['required', Rule::in(array_keys(config('cms.page_statuses', [])))],
        ]);

        $validated['slug'] = Str::lower($validated['slug']);
        if (array_key_exists('slug_ur', $validated)) {
            $validated['slug_ur'] = trim((string) ($validated['slug_ur'] ?? '')) ?: null;
        }
        if (! empty($validated['view_key'])) {
            $validated['view_key'] = Str::lower($validated['view_key']);
        } else {
            $validated['view_key'] = null;
        }

        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['is_published'] = $validated['status'] === Page::STATUS_PUBLISHED;

        if (! empty($validated['og_image']) && preg_match('#^https?://#i', $validated['og_image'])) {
            $parsed = parse_url($validated['og_image'], PHP_URL_PATH);
            if (is_string($parsed) && str_contains($parsed, '/assets/')) {
                $relative = ltrim(Str::after($parsed, '/assets/'), '/');
                $validated['og_image'] = 'assets/'.$relative;
            }
        }

        return $validated;
    }
}
