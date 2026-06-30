@extends('layouts.app')

@section('title', config('app.name'))
@section('body_class', 'home wp-singular page-template-default page page-id-2 custom-background wp-custom-logo wp-theme-shaha wp-child-theme-shaha-child frontpage body_tag scheme_default blog_mode_front body_style_wide is_stream blog_style_excerpt sidebar_hide expand_content remove_margins header_type_custom header_style_header-custom-644 header_position_over header_mobile_disabled menu_style_ no_layout')

@section('page_content')
    @php
        $renderer = app(\App\Services\CmsSectionRenderer::class);
        $homePage = $homePage ?? null;
        $useCms = $homePage && $homePage->relationLoaded('sections') && $homePage->sections->isNotEmpty();

        $popupSection = $useCms ? $renderer->homeSection($homePage, 'popup') : null;
        $heroSection = $useCms ? $renderer->homeSection($homePage, 'hero') : null;
        $aboutSection = $useCms ? $renderer->homeSection($homePage, 'about') : null;
        $missionSection = $useCms ? $renderer->homeSection($homePage, 'mission') : null;
        $valueChainSection = $useCms ? $renderer->homeSection($homePage, 'value_chain') : null;
    @endphp

    @if ($popupSection)
        {!! $renderer->render($popupSection) !!}
    @else
        <div class="laravel-home-popup" data-home-popup role="dialog" aria-modal="true" aria-label="Policy Finder Service announcement" hidden>
            <div class="laravel-home-popup__dialog" data-home-popup-dialog>
                <button type="button" class="laravel-home-popup__close" data-home-popup-close aria-label="{{ __('Close popup') }}">&times;</button>
                <img
                    class="laravel-home-popup__image"
                    src="{{ asset('assets/images/home/cdc-web-banner.webp') }}"
                    width="810"
                    height="672"
                    alt="Locate Life Insurance Policies With Ease. The Policy Finder Service is now live. SMS the CNIC number to 99833."
                    decoding="async"
                >
            </div>
        </div>
    @endif

    @if ($heroSection)
        {!! $renderer->render($heroSection) !!}
    @else
        @php
            $urduLocale = app(\App\Services\UrduLocaleService::class);
            $isUrdu = app()->getLocale() === 'ur';
            $heroSlides = $isUrdu ? [
                ['subtitle' => '', 'title' => 'حج کی ادائیگی اب آسان', 'title_line2' => 'آئیے ساتھ چلیں', 'bg' => asset('assets/images/imgi_2_xHome-Banner-2.webp.pagespeed.ic.I94T3v3MOH.webp'), 'cta_text' => 'حج پلانر', 'cta_link' => route('urdu.hajj-planner.index')],
                ['subtitle' => '', 'title' => 'سب سے زیادہ ابتدائی پیڈ اپ', 'title_line2' => '2 ارب روپے کا سرمایہ', 'bg' => asset('assets/images/imgi_3_x1-2.webp.pagespeed.ic.-s8QkwEh_F.webp'), 'cta_text' => 'حج پلانر', 'cta_link' => route('urdu.hajj-planner.index')],
            ] : [
                ['subtitle' => '', 'title' => 'Hajj Made Easy', 'title_line2' => 'Aaiye Saath Chalein', 'bg' => asset('assets/images/imgi_2_xHome-Banner-2.webp.pagespeed.ic.I94T3v3MOH.webp'), 'cta_text' => 'Hajj Planner', 'cta_link' => route('hajj-planner.index')],
                ['subtitle' => '', 'title' => 'Highest initial paid-up ', 'title_line2' => 'capital of PKR 2 Billion', 'bg' => asset('assets/images/imgi_3_x1-2.webp.pagespeed.ic.-s8QkwEh_F.webp'), 'cta_text' => 'Hajj Planner', 'cta_link' => route('hajj-planner.index')],
            ];
        @endphp
        <section class="laravel-hero-slider" data-hero-slider>
            <div class="laravel-hero-slider__track" data-hero-track>
                @foreach ($heroSlides as $index => $slide)
                    <div class="laravel-hero-slide @if ($index === 0) is-active @endif" data-hero-slide>
                        <img class="laravel-hero-slide__bg" src="{{ $slide['bg'] }}" alt="" aria-hidden="true" decoding="async">
                        <div class="laravel-hero-slide__overlay" aria-hidden="true"></div>
                        <div class="laravel-hero-slide__fg" aria-hidden="true"></div>
                        <div class="content_wrap laravel-hero-slide__content">
                            @if (!empty($slide['subtitle']))
                                <p class="sc_item_subtitle sc_item_subtitle_above laravel-hero-slide__subtitle">{{ $slide['subtitle'] }}</p>
                            @endif
                            <h1 class="sc_item_title sc_title_title laravel-hero-slide__title">
                                {{ $slide['title'] }}@isset($slide['title_line2'])<br>{{ $slide['title_line2'] }}@endisset
                            </h1>
                            <div class="laravel-hero-slide__cta">
                                <a href="{{ $slide['cta_link'] }}" class="sc_button sc_button_default laravel-button-primary">
                                    <span class="sc_button_text"><span class="sc_button_title">{{ $slide['cta_text'] }}</span></span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="button" class="laravel-hero-arrow laravel-hero-arrow--prev" data-hero-prev aria-label="{{ __('Previous slide') }}"></button>
            <button type="button" class="laravel-hero-arrow laravel-hero-arrow--next" data-hero-next aria-label="{{ __('Next slide') }}"></button>
        </section>
    @endif

    @if ($aboutSection)
        {!! $renderer->render($aboutSection) !!}
    @else
        <section class="laravel-home-about-banner" aria-labelledby="home-about-title">
            <img class="laravel-home-about-banner__bg" src="{{ asset('assets/images/home/Sec-bg.webp') }}" alt="" aria-hidden="true" decoding="async">
            <div class="laravel-home-about-banner__overlay" aria-hidden="true"></div>
            <div class="content_wrap laravel-home-about-banner__grid">
                <div class="laravel-home-about-banner__col laravel-home-about-banner__col--spacer" aria-hidden="true"></div>
                <div class="laravel-home-about-banner__col laravel-home-about-banner__col--main">
                    <p class="laravel-home-about-banner__kicker">{{ __('ABOUT') }}</p>
                    <h2 id="home-about-title" class="laravel-home-about-banner__title">{{ __('5th Pillar') }}{{ __('Family Takaful') }}</h2>
                    <p class="laravel-home-about-banner__text">
                        {{ __('5th Pillar Family Takaful Limited is a new entrant into the Family Takaful sector of Pakistan which is supported by eminent business houses from Kuwait and Pakistan.') }}
                    </p>
                    <a href="{{ route('pages.show', ['slug' => 'about-us']) }}" class="sc_button sc_button_default laravel-home-about-banner__btn">
                        <span class="sc_button_text"><span class="sc_button_title">{{ __('More About Us') }}</span></span>
                    </a>
                </div>
                <div class="laravel-home-about-banner__col laravel-home-about-banner__col--spacer" aria-hidden="true"></div>
            </div>
        </section>
    @endif

    @if ($missionSection)
        {!! $renderer->render($missionSection) !!}
    @else
        <section class="laravel-mission-vision">
            <div class="content_wrap">
                <h2 class="laravel-section-title laravel-section-title--mission">{{ __('Mission & Vision') }}</h2>
                <div class="laravel-mission-vision__grid">
                    <article class="laravel-mv-card">
                        <div class="laravel-mv-card__icon">
                            <img src="{{ asset('assets/images/home/1-New.webp') }}" alt="{{ __('Our Vision') }}" width="124" height="110" loading="lazy" decoding="async">
                        </div>
                        <h3 class="laravel-mv-card__title">{{ __('Our Vision') }}</h3>
                        <p class="laravel-mv-card__text">{{ __('Strengthen the financial capacity of our clients through innovative Shariah compliant Takaful products empowering them to achieve their cherished goals in life.') }}</p>
                    </article>
                    <article class="laravel-mv-card">
                        <div class="laravel-mv-card__icon">
                            <img src="{{ asset('assets/images/home/2-New.webp') }}" alt="{{ __('Our Mission') }}" width="124" height="110" loading="lazy" decoding="async">
                        </div>
                        <h3 class="laravel-mv-card__title">{{ __('Our Mission') }}</h3>
                        <p class="laravel-mv-card__text">{{ __('Provide structured Takaful savings and protection solutions specifically to Muslims in Pakistan to perform Hajj, the 5th Pillar of Islam.') }}</p>
                    </article>
                    <article class="laravel-mv-card">
                        <div class="laravel-mv-card__icon laravel-mv-card__icon--wide">
                            <img src="{{ asset('assets/images/home/3-1-1.webp') }}" alt="{{ __('Value Chain') }}" width="173" height="110" loading="lazy" decoding="async">
                        </div>
                        <h3 class="laravel-mv-card__title">{{ __('Value Chain') }}</h3>
                        <p class="laravel-mv-card__text">{{ __('5th Pillar Takaful Limited provides a complete end-to-end value chain, supporting you from the moment you start saving till the moment you’ve performed Hajj and are back home.') }}</p>
                    </article>
                </div>
            </div>
        </section>
    @endif

    @if ($valueChainSection)
        {!! $renderer->render($valueChainSection) !!}
    @else
        @php $isUrdu = app()->getLocale() === 'ur'; @endphp
        <section class="laravel-value-chain">
            <div class="content_wrap">
                <h2 class="laravel-section-title laravel-section-title--gold laravel-section-title--mission">{{ __('5th Pillar End-to-End Value Chain Explained') }}</h2>
                <div class="laravel-value-chain__panel">
                    <figure class="laravel-value-chain__figure">
                        <div class="laravel-value-chain__image-wrap">
                            <img
                                src="{{ $isUrdu ? asset('assets/images/Comp-1_2.gif') : asset('assets/images/home/Takaful-5th-Pillar-Animation-V4-1-1.gif') }}"
                                width="1920"
                                height="1080"
                                class="laravel-value-chain__image"
                                alt="{{ __('5th Pillar end-to-end value chain animation') }}"
                                loading="lazy"
                                decoding="async"
                            >
                        </div>
                    </figure>
                    <div class="laravel-value-chain__actions sc_item_button sc_button_wrap sc_align_center">
                        <a href="{{ $isUrdu ? \App\Support\PublicPath::uploadHref('assets/pdf/5th-Pillar-Urdu-Animation-1.pdf') : \App\Support\PublicPath::uploadHref('assets/pdf/funds/5th-Pillar-End-To-End-Value-Chain-v1.5.pdf') }}" class="sc_button sc_button_default laravel-value-chain__btn" target="_blank" rel="noopener noreferrer">
                            <span class="sc_button_text"><span class="sc_button_title">{{ __('Download the Value Chain') }}</span></span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if ($newsSliderPosts->isNotEmpty())
        <section
            class="laravel-news-events"
            data-news-slider
            data-news-autoplay="3000"
            data-news-speed="500"
            data-news-slides-desktop="3"
            data-news-slides-tablet="2"
            data-news-slides-mobile="1"
            aria-label="{{ __('News and events carousel') }}"
        >
            <div class="content_wrap">
                <h2 class="laravel-section-title laravel-section-title--news-events">{{ __('News & Events') }}</h2>
                <div class="laravel-news-carousel-row">
                    <button type="button" class="laravel-news-arrow laravel-news-arrow--prev" data-news-prev aria-label="{{ __('Previous slide') }}"></button>
                    <div class="laravel-news-viewport" data-news-viewport>
                        <div class="laravel-news-track" data-news-track>
                            @foreach ($newsSliderPosts as $post)
                                @php
                                    $href = route('pages.show', ['slug' => $post['slug']]);
                                    $img = $post['image'] ?? null;
                                @endphp
                                <article class="laravel-news-item">
                                    <div class="laravel-news-item__image-bg">
                                        <a href="{{ $href }}" class="laravel-news-item__image-link" tabindex="-1">
                                            @if (! empty($img))
                                                <img src="{{ $img }}" alt="{{ $post['title'] }}" loading="lazy" decoding="async">
                                            @else
                                                <div class="laravel-news-item__image-fallback" aria-hidden="true"></div>
                                            @endif
                                        </a>
                                    </div>
                                    <div class="laravel-news-item__body">
                                        <div class="laravel-news-item__categories">
                                            <a href="{{ route('news-events.index') }}">{{ __('News & Events') }}</a>
                                        </div>
                                        <h3 class="laravel-news-item__title">
                                            <a href="{{ $href }}">{{ $post['title'] }}</a>
                                        </h3>
                                        <div class="laravel-news-item__date">{{ $post['date_label'] }}</div>
                                        <a class="laravel-news-item__btn" href="{{ $href }}">{{ __('Read More') }}</a>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                    <button type="button" class="laravel-news-arrow laravel-news-arrow--next" data-news-next aria-label="{{ __('Next slide') }}"></button>
                </div>
            </div>
        </section>
    @endif
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const popup = document.querySelector('[data-home-popup]');
            const dialog = document.querySelector('[data-home-popup-dialog]');
            const closeButton = document.querySelector('[data-home-popup-close]');

            if (!popup || !dialog || !closeButton) {
                return;
            }

            const closePopup = () => {
                popup.setAttribute('hidden', '');
                document.body.classList.remove('laravel-home-popup-open');
            };

            popup.removeAttribute('hidden');
            document.body.classList.add('laravel-home-popup-open');

            closeButton.addEventListener('click', closePopup);
            popup.addEventListener('click', (event) => {
                if (!dialog.contains(event.target)) {
                    closePopup();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !popup.hasAttribute('hidden')) {
                    closePopup();
                }
            });
        });
    </script>
@endpush
