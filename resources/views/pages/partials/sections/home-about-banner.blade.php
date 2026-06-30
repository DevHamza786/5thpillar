@php
    /** @var \App\Services\CmsSectionRegistry $registry */
    /** @var array<string, mixed> $content */
    $kicker = $registry->transContent($content, 'kicker', __('ABOUT'));
    $title = $registry->transContent($content, 'title', __('5th Pillar'));
    $titleLine2 = $registry->transContent($content, 'title_line2', __('Family Takaful'));
    $text = $registry->transContent($content, 'text', '');
    $bgImage = $registry->assetUrl((string) ($content['bg_image'] ?? 'assets/images/home/Sec-bg.webp'));
    $ctaText = $registry->transContent($content, 'cta_text', __('More About Us'));
    $ctaLink = trim((string) ($content['cta_link'] ?? '/about-us'));
    if (! preg_match('#^https?://#i', $ctaLink)) {
        $ctaLink = route('pages.show', ['slug' => ltrim($ctaLink, '/')]);
    }
@endphp
<section class="laravel-home-about-banner" aria-labelledby="home-about-title">
    <img class="laravel-home-about-banner__bg" src="{{ $bgImage }}" alt="" aria-hidden="true" decoding="async">
    <div class="laravel-home-about-banner__overlay" aria-hidden="true"></div>
    <div class="content_wrap laravel-home-about-banner__grid">
        <div class="laravel-home-about-banner__col laravel-home-about-banner__col--spacer" aria-hidden="true"></div>
        <div class="laravel-home-about-banner__col laravel-home-about-banner__col--main">
            <p class="laravel-home-about-banner__kicker">{{ $kicker }}</p>
            <h2 id="home-about-title" class="laravel-home-about-banner__title">{{ $title }}{{ $titleLine2 }}</h2>
            @if ($text !== '')
                <p class="laravel-home-about-banner__text">{{ $text }}</p>
            @endif
            <a href="{{ $ctaLink }}" class="sc_button sc_button_default laravel-home-about-banner__btn">
                <span class="sc_button_text"><span class="sc_button_title">{{ $ctaText }}</span></span>
            </a>
        </div>
        <div class="laravel-home-about-banner__col laravel-home-about-banner__col--spacer" aria-hidden="true"></div>
    </div>
</section>
