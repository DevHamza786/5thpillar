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

        $page = Page::where('slug', $slug)->firstOrFail();

        $view = 'pages.static.'.$slug;

        if (! View::exists($view)) {
            abort(404);
        }

        return view($view, [
            'page' => $page,
        ]);
    }
}
