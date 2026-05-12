@if ($paginator->hasPages())
    <nav class="laravel-admin-pagination tablenav" role="navigation" aria-label="{{ __('Pagination navigation') }}">
        <div class="tablenav-pages" style="display:flex;flex-wrap:wrap;align-items:center;gap:6px;margin:8px 0;">
            @if ($paginator->onFirstPage())
                <span class="button disabled" style="opacity:0.5;cursor:default;">‹ {{ __('Previous') }}</span>
            @else
                <a class="button" href="{{ $paginator->previousPageUrl() }}" rel="prev">‹ {{ __('Previous') }}</a>
            @endif
            @if ($paginator->hasMorePages())
                <a class="button" href="{{ $paginator->nextPageUrl() }}" rel="next">{{ __('Next') }} ›</a>
            @else
                <span class="button disabled" style="opacity:0.5;cursor:default;">{{ __('Next') }} ›</span>
            @endif
        </div>
    </nav>
@endif
