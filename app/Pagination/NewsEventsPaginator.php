<?php

namespace App\Pagination;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 * WordPress-style URLs: /news-and-events and /news-and-events/page/{n}
 */
class NewsEventsPaginator extends LengthAwarePaginator
{
    public function url($page): string
    {
        $page = max(1, (int) $page);

        if ($page === 1) {
            return route('news-events.index');
        }

        return route('news-events.paginated', ['page' => $page]);
    }
}
