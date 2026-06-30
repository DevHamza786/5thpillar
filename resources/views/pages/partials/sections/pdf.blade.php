@php
    /** @var \App\Services\CmsSectionRegistry $registry */
    /** @var array<string, mixed> $content */
    $pdfPath = $registry->transContent($content, 'pdf_path', '');
    $label = $registry->transContent($content, 'download_label', __('Download'));
@endphp
@if ($pdfPath !== '')
    <section class="laravel-cms-section laravel-cms-section--pdf">
        <a href="{{ \App\Support\PublicPath::uploadHref($pdfPath) }}" class="laravel-cms-section__button" target="_blank" rel="noopener noreferrer">
            {{ $label }}
        </a>
    </section>
@endif
