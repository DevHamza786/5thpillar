@php
    /** @var \App\Services\CmsSectionRegistry $registry */
    /** @var array<string, mixed> $content */
    $images = collect($content['images'] ?? [])->sortBy('sort_order')->values();
@endphp
@if ($images->isNotEmpty())
    <section class="laravel-cms-section laravel-cms-section--gallery" aria-label="{{ __('Image gallery') }}">
        <div class="laravel-cms-gallery">
            @foreach ($images as $image)
                @php
                    $path = $registry->transContent($image, 'path', $image['path'] ?? '');
                    $alt = $registry->transContent($image, 'alt', '');
                @endphp
                @if ($path !== '')
                    <figure class="laravel-cms-gallery__item">
                        <img src="{{ $registry->assetUrl($path) }}" alt="{{ $alt }}" loading="lazy" decoding="async">
                    </figure>
                @endif
            @endforeach
        </div>
    </section>
@endif
