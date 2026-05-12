@if ($paginator->hasPages())
    <nav class="laravel-admin-pagination tablenav" role="navigation" aria-label="{{ __('Pagination navigation') }}">
        <div class="tablenav-pages" style="display:flex;flex-wrap:wrap;align-items:center;gap:6px;margin:8px 0;">
            <span class="displaying-num" style="color:#646970;margin-right:8px;">
                {{ sprintf(__('Showing %1$s–%2$s of %3$s'), $paginator->firstItem(), $paginator->lastItem(), $paginator->total()) }}
            </span>
            @if ($paginator->onFirstPage())
                <span class="button disabled" aria-disabled="true" style="opacity:0.5;cursor:default;">‹</span>
            @else
                <a class="button" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('Previous page') }}">‹</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="tablenav-paging-text" style="padding:0 4px;color:#646970;">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="button" style="background:#2271b1;border-color:#2271b1;color:#fff;font-weight:600;" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="button" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a class="button" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('Next page') }}">›</a>
            @else
                <span class="button disabled" aria-disabled="true" style="opacity:0.5;cursor:default;">›</span>
            @endif
        </div>
    </nav>
@endif
