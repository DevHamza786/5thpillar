<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Support\NewsEventsRepository;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $newsSliderPosts = NewsEventsRepository::posts()
            ->take(10)
            ->map(function (array $post) {
                $post['date_label'] = Carbon::parse($post['date'])->format('F j, Y');

                return $post;
            })
            ->values();

        $homePage = Page::query()
            ->where('slug', 'home')
            ->publishedForLocale()
            ->with(['sections' => fn ($q) => $q->where('is_enabled', true)->orderBy('sort_order')])
            ->first();

        return view('home.index', [
            'newsSliderPosts' => $newsSliderPosts,
            'homePage' => $homePage,
        ]);
    }
}

