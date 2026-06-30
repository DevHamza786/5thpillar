@php
    /** @var \App\Services\CmsSectionRegistry $registry */
    /** @var array<string, mixed> $content */
    $isUrdu = in_array(app()->getLocale(), ['ur', 'urdu'], true);
    $heading = $registry->transContent($content, 'heading', '');
    $headingHtml = filter_var($content['heading_html'] ?? false, FILTER_VALIDATE_BOOLEAN);
    $imagePath = $isUrdu && ! empty($content['image_ur']) ? $content['image_ur'] : ($content['image'] ?? '');
    $image = $registry->assetUrl((string) $imagePath);
    $bgPath = $isUrdu && ! empty($content['bg_image_ur']) ? $content['bg_image_ur'] : ($content['bg_image'] ?? '');
    $bgUrl = $bgPath !== '' ? $registry->assetUrl($bgPath) : '';
    $alt = $content['alt'] ?? '5th Pillar End-to-End Value Chain';
@endphp
<section
    class="laravel-about-wp-band laravel-about-wp-band--value-chain vc_row-has-fill"
    aria-labelledby="about-value-chain-heading"
>
    @if ($bgUrl !== '')
        <img class="laravel-about-wp-band__bg" src="{{ $bgUrl }}" alt="" aria-hidden="true" decoding="async">
    @endif
    <div class="content_wrap laravel-about-wp-value-chain__inner">
        @if ($heading !== '')
            <h2 id="about-value-chain-heading" class="laravel-about-wp-value-title">
                @if ($headingHtml)
                    {!! $heading !!}
                @else
                    {{ $heading }}
                @endif
            </h2>
        @endif
        @if ($image !== '')
            <figure class="laravel-about-wp-value-figure">
                <div class="laravel-about-wp-value-frame">
                    <img
                        src="{{ $image }}"
                        width="1920"
                        height="1080"
                        class="laravel-about-wp-value-img"
                        alt="{{ $alt }}"
                        loading="lazy"
                        decoding="async"
                    >
                </div>
            </figure>
        @endif
    </div>
</section>
