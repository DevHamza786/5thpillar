@php
    /** @var \App\Services\CmsSectionRegistry $registry */
    /** @var array<string, mixed> $content */
    $html = $registry->transContent($content, 'html', '');
@endphp
@if (filled($html))
    <div class="laravel-cms-section laravel-cms-section--html">
        {!! \App\Support\PublicPath::rewriteHtmlPublicPaths($html) !!}
    </div>
@endif
