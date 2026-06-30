@php
    /** @var \App\Services\CmsSectionRegistry $registry */
    /** @var array<string, mixed> $content */
    $image = $registry->transContent($content, 'image', '');
    $alt = $registry->transContent($content, 'alt', '');
    $caption = $registry->transContent($content, 'caption', '');
@endphp
@if ($image !== '')
    <section class="laravel-cms-section laravel-cms-section--image">
        <figure class="laravel-cms-section__figure">
            <img src="{{ $registry->assetUrl($image) }}" alt="{{ $alt }}" loading="lazy" decoding="async" class="laravel-cms-section__image">
            @if ($caption !== '')
                <figcaption class="laravel-cms-section__caption">{{ $caption }}</figcaption>
            @endif
        </figure>
    </section>
@endif
