@if ($paginator->hasPages())
    <nav class="laravel-admin-pagination tablenav" role="navigation" aria-label="{{ __('Pagination navigation') }}">
        <div class="tablenav-pages">
            @if ($paginator->onFirstPage())
                <span class="button disabled">‹ {{ __('Previous') }}</span>
            @else
                <a class="button" href="{{ $paginator->previousPageUrl() }}" rel="prev">‹ {{ __('Previous') }}</a>
            @endif
            @if ($paginator->hasMorePages())
                <a class="button" href="{{ $paginator->nextPageUrl() }}" rel="next">{{ __('Next') }} ›</a>
            @else
                <span class="button disabled">{{ __('Next') }} ›</span>
            @endif
        </div>
    </nav>
@endif
