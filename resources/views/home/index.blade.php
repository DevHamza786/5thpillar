@extends('layouts.app')

@section('title', '5th Pillar Family Takaful')
@section('body_class', 'home wp-singular page-template-default page page-id-2 custom-background wp-custom-logo wp-theme-shaha wp-child-theme-shaha-child frontpage body_tag scheme_default blog_mode_front body_style_wide is_stream blog_style_excerpt sidebar_hide expand_content remove_margins header_type_custom header_style_header-custom-644 header_position_over header_mobile_disabled menu_style_ no_layout')

@section('page_content')
    @php
        $heroSlides = [
            [
                'subtitle' => '',
                'title' => 'Hajj Made Easy',
                'title_line2' => 'Aaiye Saath Chalein',
                'bg' => asset('assets/images/imgi_2_xHome-Banner-2.webp.pagespeed.ic.I94T3v3MOH.webp'),
            ],
            [
                'subtitle' => '',
                'title' => 'Highest initial paid-up ',
                'title_line2' => 'capital of PKR 2 Billion',
                'bg' => asset('assets/images/imgi_3_x1-2.webp.pagespeed.ic.-s8QkwEh_F.webp'),
            ],
        ];
    @endphp

    <section class="laravel-hero-slider" data-hero-slider>
        <div class="laravel-hero-slider__track" data-hero-track>
            @foreach ($heroSlides as $index => $slide)
                <div class="laravel-hero-slide @if ($index === 0) is-active @endif" data-hero-slide style="--hero-bg: url('{{ $slide['bg'] }}');">
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
                            <a href="{{ url('/hajj-planner') }}" class="sc_button sc_button_default laravel-button-primary">
                                <span class="sc_button_text"><span class="sc_button_title">Hajj Planner</span></span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <button type="button" class="laravel-hero-arrow laravel-hero-arrow--prev" data-hero-prev aria-label="Previous slide"></button>
        <button type="button" class="laravel-hero-arrow laravel-hero-arrow--next" data-hero-next aria-label="Next slide"></button>
    </section>

    {{-- Welcome / About (WP: shaha_welcome_to row after slider) --}}
    <section class="laravel-home-about-banner" style="--about-bg: url('{{ asset('uploads/2023/2023/08/Sec-bg.webp') }}');" aria-labelledby="home-about-title">
        <div class="laravel-home-about-banner__overlay" aria-hidden="true"></div>
        <div class="content_wrap laravel-home-about-banner__grid">
            <div class="laravel-home-about-banner__col laravel-home-about-banner__col--spacer" aria-hidden="true"></div>
            <div class="laravel-home-about-banner__col laravel-home-about-banner__col--main">
                <p class="laravel-home-about-banner__kicker">ABOUT</p>
                <h2 id="home-about-title" class="laravel-home-about-banner__title">5th Pillar<br>Family Takaful</h2>
                <p class="laravel-home-about-banner__text">
                    5th Pillar Family Takaful Limited is a new entrant into the Family<br>
                    Takaful sector of Pakistan which is supported by eminent<br>
                    business houses from Kuwait and Pakistan.
                </p>
                <a href="{{ route('pages.show', ['slug' => 'about-us']) }}" class="sc_button sc_button_default laravel-home-about-banner__btn">
                    <span class="sc_button_text"><span class="sc_button_title">More About Us</span></span>
                </a>
            </div>
            <div class="laravel-home-about-banner__col laravel-home-about-banner__col--spacer" aria-hidden="true"></div>
        </div>
    </section>

    <section class="laravel-mission-vision">
        <div class="content_wrap">
            <h2 class="laravel-section-title laravel-section-title--mission">Mission &amp; Vision</h2>
            <div class="laravel-mission-vision__grid">
                <article class="laravel-mv-card">
                    <div class="laravel-mv-card__icon">
                        <img src="{{ asset('uploads/2023/2023/08/1-New.webp') }}" alt="Our Vision" width="124" height="110" loading="lazy" decoding="async">
                    </div>
                    <h3 class="laravel-mv-card__title">Our Vision</h3>
                    <p class="laravel-mv-card__text">
                        Strengthen the financial capacity of our clients through innovative Shariah compliant Takaful products empowering them to achieve their cherished goals in life.
                    </p>
                </article>
                <article class="laravel-mv-card">
                    <div class="laravel-mv-card__icon">
                        <img src="{{ asset('uploads/2023/2023/08/2-New.webp') }}" alt="Our Mission" width="124" height="110" loading="lazy" decoding="async">
                    </div>
                    <h3 class="laravel-mv-card__title">Our Mission</h3>
                    <p class="laravel-mv-card__text">
                        Provide structured Takaful savings and protection solutions specifically to Muslims in Pakistan to perform Hajj, the 5th Pillar of Islam.
                    </p>
                </article>
                <article class="laravel-mv-card">
                    <div class="laravel-mv-card__icon laravel-mv-card__icon--wide">
                        <img src="{{ asset('uploads/2023/2023/08/3-1-1.webp') }}" alt="Value chain" width="173" height="110" loading="lazy" decoding="async">
                    </div>
                    <h3 class="laravel-mv-card__title">Value Chain</h3>
                    <p class="laravel-mv-card__text">
                        5th Pillar Takaful Limited provides a complete end-to-end value chain, supporting you from the moment you start saving till the moment you’ve performed Hajj and are back home.
                    </p>
                </article>
            </div>
        </div>
    </section>

    <section class="laravel-value-chain">
        <div class="content_wrap">
            <h2 class="laravel-section-title laravel-section-title--gold laravel-section-title--mission">5th Pillar End-to-End Value Chain Explained</h2>
            <div class="laravel-value-chain__panel">
                <figure class="laravel-value-chain__figure">
                    <div class="laravel-value-chain__image-wrap">
                        <img
                            src="{{ asset('uploads/2025/2025/01/Takaful-5th-Pillar-Animation-V4-1-1.gif') }}"
                            width="1920"
                            height="1080"
                            class="laravel-value-chain__image"
                            alt="5th Pillar end-to-end value chain animation"
                            title="Takaful-5th-Pillar-Animation-V4-1"
                            loading="lazy"
                            decoding="async"
                        >
                    </div>
                </figure>
                <div class="laravel-value-chain__actions sc_item_button sc_button_wrap sc_align_center">
                    <a href="{{ asset('uploads/2024/2024/03/5th-Pillar-End-To-End-Value-Chain-v1.5.pdf') }}" class="sc_button sc_button_default laravel-value-chain__btn" target="_blank" rel="noopener noreferrer">
                        <span class="sc_button_text"><span class="sc_button_title">Download the Value Chain</span></span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    @if ($newsSliderPosts->isNotEmpty())
        <section
            class="laravel-news-events"
            data-news-slider
            data-news-autoplay="3000"
            data-news-speed="500"
            data-news-slides-desktop="3"
            data-news-slides-tablet="2"
            data-news-slides-mobile="1"
            aria-label="News and events carousel"
        >
            <div class="content_wrap">
                <h2 class="laravel-section-title laravel-section-title--news-events">News &amp; Events</h2>

                <div class="laravel-news-carousel-row">
                    <button type="button" class="laravel-news-arrow laravel-news-arrow--prev" data-news-prev aria-label="Previous slide"></button>
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
                                            <a href="{{ route('news-events.index') }}">News &amp; Events</a>
                                        </div>
                                        <h3 class="laravel-news-item__title">
                                            <a href="{{ $href }}">{{ $post['title'] }}</a>
                                        </h3>
                                        <div class="laravel-news-item__date">
                                            {{ $post['date_label'] }}
                                        </div>
                                        <a class="laravel-news-item__btn" href="{{ $href }}">Read More</a>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                    <button type="button" class="laravel-news-arrow laravel-news-arrow--next" data-news-next aria-label="Next slide"></button>
                </div>
            </div>
        </section>
    @endif
@endsection
