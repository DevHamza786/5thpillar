@php
    /** @var \App\Services\CmsSectionRegistry $registry */
    /** @var array<string, mixed> $content */
    $isUrdu = in_array(app()->getLocale(), ['ur', 'urdu'], true);
    $slides = (array) ($content['slides'] ?? []);
@endphp
@if ($slides !== [])
    <section class="laravel-hero-slider" data-hero-slider>
        <div class="laravel-hero-slider__track" data-hero-track>
            @foreach ($slides as $index => $slide)
                @php
                    $subtitle = $isUrdu && ! empty($slide['subtitle_ur']) ? $slide['subtitle_ur'] : ($slide['subtitle'] ?? '');
                    $title = $isUrdu && ! empty($slide['title_ur']) ? $slide['title_ur'] : ($slide['title'] ?? '');
                    $titleLine2 = $isUrdu && ! empty($slide['title_line2_ur']) ? $slide['title_line2_ur'] : ($slide['title_line2'] ?? '');
                    $bg = $registry->assetUrl((string) ($slide['bg'] ?? ''));
                    $ctaText = $isUrdu && ! empty($slide['cta_text_ur']) ? $slide['cta_text_ur'] : ($slide['cta_text'] ?? '');
                    $ctaLink = $slide['cta_link'] ?? '#';
                    if ($ctaLink !== '#' && ! preg_match('#^https?://#i', $ctaLink)) {
                        $ctaLink = url($ctaLink);
                    }
                @endphp
                <div class="laravel-hero-slide @if ($index === 0) is-active @endif" data-hero-slide>
                    <img class="laravel-hero-slide__bg" src="{{ $bg }}" alt="" aria-hidden="true" decoding="async">
                    <div class="laravel-hero-slide__overlay" aria-hidden="true"></div>
                    <div class="laravel-hero-slide__fg" aria-hidden="true"></div>
                    <div class="content_wrap laravel-hero-slide__content">
                        @if ($subtitle !== '')
                            <p class="sc_item_subtitle sc_item_subtitle_above laravel-hero-slide__subtitle">{{ $subtitle }}</p>
                        @endif
                        <h1 class="sc_item_title sc_title_title laravel-hero-slide__title">
                            {{ $title }}@if ($titleLine2 !== '')<br>{{ $titleLine2 }}@endif
                        </h1>
                        @if ($ctaText !== '')
                            <div class="laravel-hero-slide__cta">
                                <a href="{{ $ctaLink }}" class="sc_button sc_button_default laravel-button-primary">
                                    <span class="sc_button_text"><span class="sc_button_title">{{ $ctaText }}</span></span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <button type="button" class="laravel-hero-arrow laravel-hero-arrow--prev" data-hero-prev aria-label="{{ __('Previous slide') }}"></button>
        <button type="button" class="laravel-hero-arrow laravel-hero-arrow--next" data-hero-next aria-label="{{ __('Next slide') }}"></button>
    </section>
@endif
