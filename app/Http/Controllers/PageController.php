<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\UrduLocaleService;
use App\Support\NewsEventsRepository;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewContract;

class PageController extends Controller
{
    public function __construct(
        private readonly UrduLocaleService $urdu
    ) {}

    public function show(string $slug): ViewContract
    {
        $newsPost = NewsEventsRepository::find($slug);
        if ($newsPost !== null) {
            return view('pages.news.show', [
                'post' => $newsPost,
            ]);
        }

        $page = Page::query()
            ->when($this->urdu->isUrduLocale(), function ($query) use ($slug) {
                $query->where(function ($inner) use ($slug) {
                    $inner->where('slug_ur', $slug)->orWhere('slug', $slug);
                });
            }, function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->publishedForLocale()
            ->with([
                'sections' => fn ($q) => $q->where('is_enabled', true)->orderBy('sort_order'),
                'media',
            ])
            ->firstOrFail();

        $viewKey = $page->templateSlug();
        $view = 'pages.static.'.$viewKey;

        if (! View::exists($view)) {
            $view = 'pages.static.cms-generic';
        }

        return view($view, [
            'page' => $page,
        ]);
    }
}
