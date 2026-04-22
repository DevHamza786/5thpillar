<?php

namespace App\Http\Controllers;

use App\Pagination\NewsEventsPaginator;
use App\Support\NewsEventsRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class NewsEventsController extends Controller
{
    /** Archive page size: 8 posts (4×2 checkerboard tiles) per /news-and-events page. */
    private const PER_PAGE = 8;

    public function index(): View
    {
        return $this->listing(1);
    }

    public function paginated(int $page): View|RedirectResponse
    {
        if ($page < 2) {
            return redirect()->route('news-events.index', [], 301);
        }

        return $this->listing($page);
    }

    private function listing(int $currentPage): View
    {
        $all = NewsEventsRepository::posts();
        $total = $all->count();
        $lastPage = max((int) ceil($total / self::PER_PAGE), 1);

        if ($currentPage > $lastPage) {
            abort(404);
        }

        $items = $all->slice(($currentPage - 1) * self::PER_PAGE, self::PER_PAGE)->values();

        $paginator = new NewsEventsPaginator(
            $items,
            $total,
            self::PER_PAGE,
            $currentPage,
            ['path' => '/news-and-events']
        );

        return view('pages.news.index', [
            'posts' => $items,
            'paginator' => $paginator,
        ]);
    }
}
