<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Support\NewsEventsRepository;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewContract;

class PageController extends Controller
{
    public function show(string $slug): ViewContract
    {
        $newsPost = NewsEventsRepository::find($slug);
        if ($newsPost !== null) {
            return view('pages.news.show', [
                'post' => $newsPost,
            ]);
        }

        $page = Page::query()
            ->where('slug', $slug)
            ->with(['sections', 'media'])
            ->firstOrFail();

        if (! $page->is_published) {
            abort(404);
        }

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
