@php
    /** @var \App\Services\CmsSectionRegistry $registry */
    /** @var array<string, mixed> $content */
    $isUrdu = in_array(app()->getLocale(), ['ur', 'urdu'], true);
    $heading = $registry->transContent($content, 'heading', __('Our Sponsors'));
    $intro = $registry->transContent($content, 'intro', '');
    $closing = $registry->transContent($content, 'closing', '');
    $blocks = (array) ($content['blocks'] ?? []);
    $bgPath = $isUrdu && ! empty($content['bg_image_ur']) ? $content['bg_image_ur'] : ($content['bg_image'] ?? '');
    $bgUrl = $bgPath !== '' ? $registry->assetUrl($bgPath) : '';
@endphp
<section
    class="laravel-about-wp-band shaha_about_quote laravel-about-wp-band--sponsors"
    aria-labelledby="about-sponsors-heading"
>
    @if ($bgUrl !== '')
        <img class="laravel-about-wp-band__bg" src="{{ $bgUrl }}" alt="" aria-hidden="true" decoding="async">
    @endif
    <div class="content_wrap">
        <div class="sc_content color_style_default sc_content_default sc_float_center extra-about-quote-mr-negative">
            <h2 id="about-sponsors-heading" class="laravel-about-wp-band__h2 {{ $isUrdu ? 'laravel-about-wp-band__h2--right' : 'laravel-about-wp-band__h2--left' }}">{{ $heading }}</h2>
            @if ($intro !== '')
                <p class="laravel-about-wp-prose-dark">{{ $intro }}</p>
            @endif
            @foreach ($blocks as $block)
                @php
                    $strong = $isUrdu && ! empty($block['strong_ur']) ? $block['strong_ur'] : ($block['strong'] ?? '');
                    $text = $isUrdu && ! empty($block['text_ur']) ? $block['text_ur'] : ($block['text'] ?? '');
                @endphp
                <p class="laravel-about-wp-prose-dark">
                    @if ($strong !== '')<strong>{{ $strong }}</strong>@endif{{ $text }}
                </p>
            @endforeach
            @if ($closing !== '')
                <p class="laravel-about-wp-prose-dark">{{ $closing }}</p>
            @endif
        </div>
    </div>
</section>
