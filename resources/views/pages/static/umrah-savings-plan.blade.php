@extends('pages.layouts.structured-page')

@push('head')
    @if (config('services.recaptcha.site_key'))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
    <style>
        .laravel-hajj-bank-partner--align-left {
            text-align: left;
        }
        .laravel-hajj-bank-partner--align-left .laravel-hajj-bank-partner__logo {
            display: inline-block;
            margin-left: 0;
            margin-right: auto;
        }
        .laravel-hajj-bank-partner--align-left .laravel-hajj-bank-partner__logo img {
            display: block;
        }

        .laravel-umrah-savings-page .post_content.entry-content {
            text-align: left;
            font-family: 'Raleway', sans-serif;
            font-weight: 400;
            font-style: normal;
        }

        .laravel-umrah-savings-page .laravel-hajj-section-title {
            font-weight: 700 !important;
            font-family: 'Raleway', sans-serif;
            font-size: 38px !important;
        }

        .laravel-umrah-savings-page .post_content.entry-content p,
        .laravel-umrah-savings-page .post_content.entry-content .laravel-hajj-lead,
        .laravel-umrah-savings-page .laravel-product-feature-list,
        .laravel-umrah-savings-page .laravel-product-feature-list li {
            font-size: 1.125rem;
            line-height: 1.65;
            color: #000000;
        }

        .laravel-umrah-savings-page .laravel-hajj-av__caption,
        .laravel-umrah-savings-page .laravel-hajj-av__caption a {
            font-size: 1.05rem;
            color: #000000;
        }
    </style>
@endpush

@section('structured_meta_title', 'Umrah Savings Plan - 5th Pillar Family Takaful')
@section('structured_page_title', 'Umrah Savings Plan')

@section('structured_hero_title', 'Umrah Savings Plan')

@section('structured_primary')
    @php
        $umrahLogoBankAlfalah = asset('assets/images/BAFL-02-03-300x239.png');
        $umrahLogoBankIslami = asset('assets/images/Bank-Islami-Edited.webp');
        $umrahLogo5thPillar = asset('assets/images/5th.webp');
    @endphp
    <article class="post_item_single type-page hentry laravel-hajj-page laravel-umrah-savings-page">
        <div class="post_content entry-content">
            <section class="laravel-hajj-hero-intro">
                <p class="laravel-hajj-lead">
                    5th Pillar Family Takaful Umrah Savings Plan understands the deep spiritual significance of the Umrah pilgrimage and aims to provide a complete solution that ensures your journey is peaceful and spiritually rewarding.
                </p>
                <ul class="laravel-product-feature-list">
                    <li><strong>Structured Savings</strong> — Our plan allows for regular contributions over time leading up to your Umrah pilgrimage, making the costs more manageable.</li>
                    <li><strong>Travel Arrangements</strong> — We take care of your travel needs, enabling you to focus on fulfilling your spiritual duties.</li>
                    <li><strong>Accommodation</strong> — We provide pre-arranged accommodations that cater to your comfort, even during peak pilgrimage seasons.</li>
                    <li><strong>Peace and Devotion</strong> — With the logistical and financial aspects of your journey taken care of, our plan allows you to concentrate on your spiritual devotion.</li>
                </ul>
                <p class="laravel-hajj-lead">
                    Embark on your Umrah pilgrimage worry-free with the 5th Pillar Family Takaful Umrah Savings Plan. Experience peace, tranquility, and focus on your spiritual journey, secure in the knowledge that your earthly needs are handled.
                </p>
                <div class="laravel-hajj-bank-partner laravel-hajj-bank-partner--align-left">
                    <div class="laravel-hajj-bank-partner__logo">
                        <img
                            src="{{ $umrahLogoBankAlfalah }}"
                            width="200"
                            height="159"
                            loading="lazy"
                            decoding="async"
                            alt="Bank Alfalah"
                        >
                    </div>
                </div>
            </section>

            <section class="laravel-hajj-block">
                <h2 class="laravel-hajj-section-title">5th Pillar Family Takaful Amanat Savings Plan</h2>
                <p>
                    If you are looking to provide your family with the best lifestyle, protect them against any unforeseen events or looking for the family to fulfil their financial obligations, then 5th Pillar Family Takaful Amanat Savings Plan is the right solution for you. Our Takaful plans work in a Shariah-compliant manner and facilitate saving, investing and growing your savings while enjoying a significant level of Family Takaful coverage.
                </p>
                <div class="laravel-hajj-bank-partner laravel-hajj-bank-partner--align-left laravel-hajj-bank-partner--spaced">
                    <div class="laravel-hajj-bank-partner__logo">
                        <img
                            src="{{ $umrahLogoBankIslami }}"
                            width="200"
                            height="155"
                            loading="lazy"
                            decoding="async"
                            title="Bank-Islami-Edited"
                            alt="BankIslami"
                        >
                    </div>
                </div>
            </section>

            <section class="laravel-hajj-block">
                <h2 class="laravel-hajj-section-title">5th Pillar Family Takaful Saadat Umrah Savings Plan</h2>
                <p>
                    With 5th Pillar Family Takaful Saadat Umrah Savings Plan, we understand how important and costly this journey can be. That&rsquo;s why we&rsquo;re dedicated to making Umrah accessible and affordable. We know that going to the holy cities of Makkah and Medina is not just a spiritual calling but also a financial commitment. To make your Umrah experience convenient and hassle-free, we&rsquo;ve created an inclusive solution with all the services you need.
                </p>
                <div class="laravel-hajj-bank-partner laravel-hajj-bank-partner--align-left laravel-hajj-bank-partner--spaced">
                    <div class="laravel-hajj-bank-partner__logo laravel-hajj-bank-partner__logo--wide">
                        <img
                            src="{{ $umrahLogo5thPillar }}"
                            width="200"
                            loading="lazy"
                            decoding="async"
                            alt="5th Pillar Family Takaful"
                        >
                    </div>
                </div>
                <button type="button" class="laravel-hajj-btn laravel-brochure-trigger" data-brochure-key="saadat-umrah">Saadat Umrah Savings Plan Brochure</button>
            </section>

            <section class="laravel-hajj-block">
                <h2 class="laravel-hajj-section-title">5th Pillar Family Takaful Umrah Noor Plan</h2>
                <p>
                    Umrah is a cherished dream for countless Muslims, transcending age and circumstance. Yet in Pakistan, daily challenges and financial pressures often make this dream feel out of reach. Through our 5th Pillar Family Takaful Umrah Noor Plan, we guide you from the moment you make the intention until you return home, ensuring every step of your journey is smooth and secure.
                </p>
                <p>
                    Our End-to-End Value Chain offers a seamless experience, from structured savings and Takaful coverage to travel and accommodation arrangements in the holy cities of Makkah and Medina. All of this is managed under Shariah-compliant principles, transforming your dream of Umrah into an achievable reality.
                </p>
                <button type="button" class="laravel-hajj-btn laravel-brochure-trigger" data-brochure-key="umrah-noor">Umrah Savings Plan Brochure</button>
            </section>

            <section class="laravel-hajj-block">
                <h2 class="laravel-hajj-section-title">5th Pillar Family Takaful Safar Asaan Umrah Savings Plan</h2>
                <p>
                    Embarking on the sacred journey of Umrah is a dream cherished by millions of Muslims around the world. At 5th Pillar Family Takaful Limited, we understand the significance of this spiritual journey and are dedicated to making it accessible and affordable to all Muslims.
                </p>
                <p>
                    We recognize that the journey to the holy cities of Makkah and Medina is not only a spiritual calling but also a financial commitment. To ensure a smooth hassle-free Umrah experience, we have created a comprehensive solution that combines structured savings and a complete value chain of services.
                </p>
                <p>
                    From helping you build a dedicated savings plan to guiding you through the entire journey, our commitment is to be your trusted partner throughout your spiritual journey.
                </p>
                <button type="button" class="laravel-hajj-btn laravel-brochure-trigger" data-brochure-key="safar-aasan-umrah">Safar Aasan Umrah Saving Plan Brochure</button>
            </section>

            <section class="laravel-hajj-av">
                <h2 class="laravel-hajj-section-title">Audio / Visual Information:</h2>
                <div class="laravel-hajj-video">
                    <iframe
                        src="https://www.youtube-nocookie.com/embed/6yIFkzcln0k?rel=0"
                        title="Regular Contribution Plan | 5th Pillar Family Takaful Limited"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen
                        referrerpolicy="strict-origin-when-cross-origin"
                        loading="lazy"
                    ></iframe>
                </div>
                <p class="laravel-hajj-av__caption">
                    <a href="https://youtu.be/6yIFkzcln0k?si=oOA0IoLowUU91YhE" target="_blank" rel="noopener noreferrer">Watch on YouTube</a>
                    — Regular Contribution Plan | 5th Pillar Family Takaful Limited
                </p>
            </section>
        </div>

        @include('pages.partials.brochure-lead-modal')
    </article>
@endsection

@push('scripts')
    @include('pages.partials.brochure-modal-script')
@endpush
