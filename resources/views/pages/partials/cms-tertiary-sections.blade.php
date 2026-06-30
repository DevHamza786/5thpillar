@php
    /** @var \App\Models\Page|null $page */
    $renderer = app(\App\Services\CmsSectionRenderer::class);
    $sections = isset($page) ? $renderer->tertiarySections($page) : collect();
@endphp

@if ($sections->isNotEmpty())
    <div class="laravel-cms-tertiary-sections">
        @foreach ($sections as $section)
            {!! $renderer->render($section) !!}
        @endforeach
    </div>
@endif
