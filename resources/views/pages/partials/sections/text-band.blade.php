@php
    /** @var \App\Services\CmsSectionRegistry $registry */
    /** @var array<string, mixed> $content */
    $isUrdu = in_array(app()->getLocale(), ['ur', 'urdu'], true);
    $heading = $registry->transContent($content, 'heading', '');
    $text = $registry->transContent($content, 'text', '');
    $layout = (string) ($content['layout'] ?? 'default');
    $bandClass = $layout === 'retakaful' ? 'laravel-about-wp-band--retakaful' : '';
@endphp
<section class="laravel-about-wp-band shaha_about_quote {{ $bandClass }}" aria-labelledby="about-text-band-heading">
    <div class="content_wrap">
        @if ($layout === 'retakaful')
            <div class="laravel-about-wp-retakaful-row">
                <div class="laravel-about-wp-retakaful-row__main">
                    <div class="sc_content color_style_default sc_content_default sc_float_center extra-about-quote-mr-negative">
                        @if ($heading !== '')
                            <h2 id="about-text-band-heading" class="laravel-about-wp-band__h2 {{ $isUrdu ? 'laravel-about-wp-band__h2--right' : 'laravel-about-wp-band__h2--left' }}">{{ $heading }}</h2>
                        @endif
                        @if ($text !== '')
                            <p class="laravel-about-wp-prose-dark">{{ $text }}</p>
                        @endif
                    </div>
                </div>
                <div class="laravel-about-wp-retakaful-row__aside" aria-hidden="true"></div>
            </div>
        @else
            <div class="sc_content color_style_default sc_content_default sc_float_center extra-about-quote-mr-negative">
                @if ($heading !== '')
                    <h2 id="about-text-band-heading" class="laravel-about-wp-band__h2 {{ $isUrdu ? 'laravel-about-wp-band__h2--right' : 'laravel-about-wp-band__h2--left' }}">{{ $heading }}</h2>
                @endif
                @if ($text !== '')
                    <p class="laravel-about-wp-prose-dark">{{ $text }}</p>
                @endif
            </div>
        @endif
    </div>
</section>
