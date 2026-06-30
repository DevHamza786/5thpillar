@php
    /** @var \App\Services\CmsSectionRegistry $registry */
    /** @var array<string, mixed> $content */
    $isUrdu = in_array(app()->getLocale(), ['ur', 'urdu'], true);
    $title = $registry->transContent($content, 'title', __('5th Pillar End-to-End Value Chain Explained'));
    $imagePath = $isUrdu && ! empty($content['image_ur']) ? $content['image_ur'] : ($content['image'] ?? '');
    $image = $registry->assetUrl((string) $imagePath);
    $pdfPath = $isUrdu && ! empty($content['pdf_path_ur']) ? $content['pdf_path_ur'] : ($content['pdf_path'] ?? '');
    $pdfHref = $pdfPath !== '' ? \App\Support\PublicPath::uploadHref($pdfPath) : '#';
    $buttonLabel = $registry->transContent($content, 'button_label', __('Download the Value Chain'));
@endphp
@if ($image !== '')
    <section class="laravel-value-chain">
        <div class="content_wrap">
            @if ($title !== '')
                <h2 class="laravel-section-title laravel-section-title--gold laravel-section-title--mission">{{ $title }}</h2>
            @endif
            <div class="laravel-value-chain__panel">
                <figure class="laravel-value-chain__figure">
                    <div class="laravel-value-chain__image-wrap">
                        <img
                            src="{{ $image }}"
                            width="1920"
                            height="1080"
                            class="laravel-value-chain__image"
                            alt="{{ __('5th Pillar end-to-end value chain animation') }}"
                            loading="lazy"
                            decoding="async"
                        >
                    </div>
                </figure>
                @if ($pdfPath !== '')
                    <div class="laravel-value-chain__actions sc_item_button sc_button_wrap sc_align_center">
                        <a href="{{ $pdfHref }}" class="sc_button sc_button_default laravel-value-chain__btn" target="_blank" rel="noopener noreferrer">
                            <span class="sc_button_text"><span class="sc_button_title">{{ $buttonLabel }}</span></span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif
