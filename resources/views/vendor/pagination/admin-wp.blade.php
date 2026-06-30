@if ($paginator->hasPages())
    <nav class="laravel-admin-pagination tablenav" role="navigation" aria-label="{{ __('Pagination navigation') }}">
        <div class="tablenav-pages">
            <span class="displaying-num">
                {{ sprintf(__('Showing %1$s–%2$s of %3$s'), $paginator->firstItem(), $paginator->lastItem(), $paginator->total()) }}
            </span>
            @if ($paginator->onFirstPage())
                <span class="button disabled" aria-disabled="true">‹</span>
            @else
                <a class="button" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('Previous page') }}">‹</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="tablenav-paging-text">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="button is-current" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="button" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a class="button" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('Next page') }}">›</a>
            @else
                <span class="button disabled" aria-disabled="true">›</span>
            @endif
        </div>
    </nav>
@endif
