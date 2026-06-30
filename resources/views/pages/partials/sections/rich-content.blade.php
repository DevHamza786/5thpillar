@php
    /** @var \App\Services\CmsSectionRegistry $registry */
    /** @var array<string, mixed> $content */
    $html = $registry->transContent($content, 'html', '');
@endphp
@if ($html !== '')
    <section class="laravel-cms-section laravel-cms-section--rich-content">
        <div class="laravel-cms-section__body laravel-cms-rich-content">
            {!! \App\Support\PublicPath::rewriteHtmlPublicPaths($html) !!}
        </div>
    </section>
@endif
