<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\UrduLocaleService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __construct(
        private readonly UrduLocaleService $urdu
    ) {}

    public function index(Request $request): View
    {
        $query = trim((string) $request->query('s', ''));

        /** @var Collection<int, Page> $results */
        $results = collect();

        if ($query !== '') {
            $like = '%'.$this->escapeLike($query).'%';
            $isUrdu = $this->urdu->isUrduLocale();

            $results = Page::query()
                ->publishedForLocale()
                ->where(function (Builder $inner) use ($like, $isUrdu): void {
                    $inner->where('title', 'like', $like)
                        ->orWhere('content', 'like', $like)
                        ->orWhere('meta_title', 'like', $like)
                        ->orWhere('meta_description', 'like', $like)
                        ->orWhere('meta_keywords', 'like', $like);

                    if ($isUrdu) {
                        $inner->orWhere('title_ur', 'like', $like)
                            ->orWhere('content_ur', 'like', $like)
                            ->orWhere('meta_title_ur', 'like', $like)
                            ->orWhere('meta_description_ur', 'like', $like);
                    }
                })
                ->orderBy('title')
                ->limit(50)
                ->get();
        }

        return view('pages.static.search-results', [
            'query' => $query,
            'results' => $results,
        ]);
    }

    /**
     * Escape LIKE wildcards so user input is matched literally.
     */
    private function escapeLike(string $value): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
    }
}
