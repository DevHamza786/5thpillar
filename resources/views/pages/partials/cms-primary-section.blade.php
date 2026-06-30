@php
    /** @var \App\Models\Page|null $page */
    /** @var string $sectionType */
    $renderer = app(\App\Services\CmsSectionRenderer::class);
    $primarySection = isset($page) ? $renderer->primarySection($page, $sectionType) : null;
@endphp
@if ($primarySection)
    {!! $renderer->render($primarySection) !!}
@endif
