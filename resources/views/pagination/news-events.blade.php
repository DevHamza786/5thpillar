@if ($paginator->hasPages())
    <nav class="laravel-news-pagination" aria-label="{{ __('Posts pagination') }}">
        <ul class="laravel-news-pagination__list">
            @if ($paginator->onFirstPage())
                <li class="laravel-news-pagination__item laravel-news-pagination__item--disabled">
                    <span class="laravel-news-pagination__btn" aria-disabled="true">&lt;</span>
                </li>
            @else
                <li class="laravel-news-pagination__item">
                    <a class="laravel-news-pagination__btn" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('Previous') }}">&lt;</a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="laravel-news-pagination__item laravel-news-pagination__item--ellipsis">
                        <span class="laravel-news-pagination__btn">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="laravel-news-pagination__item laravel-news-pagination__item--active">
                                <span class="laravel-news-pagination__btn" aria-current="page">{{ $page }}</span>
                            </li>
                        @else
                            <li class="laravel-news-pagination__item">
                                <a class="laravel-news-pagination__btn" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="laravel-news-pagination__item">
                    <a class="laravel-news-pagination__btn" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('Next') }}">&gt;</a>
                </li>
            @else
                <li class="laravel-news-pagination__item laravel-news-pagination__item--disabled">
                    <span class="laravel-news-pagination__btn" aria-disabled="true">&gt;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
