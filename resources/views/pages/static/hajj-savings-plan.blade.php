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
    </style>
@endpush

@section('structured_meta_title', 'Hajj Savings Plan - 5th Pillar Family Takaful')
@section('structured_page_title', 'Hajj Savings Plan')

@section('structured_hero_title', 'Hajj Savings Plan')

@section('structured_primary')
    @php
        $hajjLogoBankAlfalah = asset('assets/images/BAFL-02-03-300x239.png');
        $hajjLogoBankIslami = asset('assets/images/Bank-Islami-Edited.webp');
        $hajjLogo5thPillar = asset('assets/images/5th.webp');
    @endphp
    <article class="post_item_single type-page hentry laravel-hajj-page">
        <div class="post_content entry-content">
            <section class="laravel-hajj-hero-intro">
                <h2 class="laravel-hajj-section-title">Haazir Hajj Savings Plan</h2>
                <p class="laravel-hajj-lead">
                    The 5th Pillar&rsquo;s Family Takaful Haazir Hajj Saving Plan helps Muslims save for Hajj, addressing the challenge of rising costs. It offers a long-term, structured savings option where subscribers contribute regularly to fund their pilgrimage. Based on Takaful principles, the plan ensures Shariah compliance by pooling funds for mutual support. It makes saving for Hajj more affordable and manageable, allowing individuals to focus on the spiritual journey without financial stress.
                </p>
                <div class="laravel-hajj-actions">
                    <button type="button" class="laravel-hajj-btn laravel-brochure-trigger" data-brochure-key="haazir">Download Brochure</button>
                </div>
                <div class="laravel-hajj-bank-partner laravel-hajj-bank-partner--align-left">
                    <div class="laravel-hajj-bank-partner__logo">
                        <img
                            src="{{ $hajjLogoBankAlfalah }}"
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
                <h2 class="laravel-hajj-section-title">5th Pillar Family Takaful Bulawa Hajj Savings Plan</h2>
                <p>
                    The 5th Pillar Family Takaful Bulawa Hajj Savings Plan is an innovative product that helps you save for your pilgrimage. It is a long-term Unit Linked Policy (ULIP) coupled with Takaful protection, which will enable you to make regular contributions based on your financial capacity towards your Hajj savings supported with a complete end-to-end value chain, supporting you from the moment you start saving till the moment you&rsquo;ve performed Hajj and are back home.
                </p>
                <button type="button" class="laravel-hajj-btn laravel-brochure-trigger" data-brochure-key="bulawa">Bulawa Hajj Savings Plan Brochure</button>
            </section>

            <section class="laravel-hajj-block">
                <h2 class="laravel-hajj-section-title">5th Pillar Family Takaful Saadat Hajj Savings Plan</h2>
                <p>
                    At 5th Pillar Family Takaful Saadat Hajj Savings Plan we strive to be your guiding light on this sacred path. We believe that with genuine intention and thoughtful action, the dream of reaching the Holy lands of Makkah and Madina is well within reach. Offering our End-to-End Value Chain services, our mission is to support you from the moment you commit to your journey until you return home in joy, ensuring every step is filled with hope, faith, and unwavering support.
                </p>
                <p>
                    More than a financial service, we are your all-in-one Hajj partner, linking hassle-free savings, Takaful plans, and comprehensive travel support under Shariah-compliant principles.
                </p>
                <button type="button" class="laravel-hajj-btn laravel-brochure-trigger" data-brochure-key="saadat">Saadat Hajj Savings Plan Brochure</button>
                <div class="laravel-hajj-bank-partner laravel-hajj-bank-partner--align-left laravel-hajj-bank-partner--spaced">
                    <div class="laravel-hajj-bank-partner__logo">
                        <img
                            src="{{ $hajjLogoBankIslami }}"
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
                <h2 class="laravel-hajj-section-title">5th Pillar Family Takaful Hajj Safar Plan</h2>
                <p>
                    For Muslims all over the world, Hajj is the pinnacle of their spiritual journey, a dream they hold close regardless of their background. Yet, in Pakistan, the daily grind and economic pressures often make this dream seem out of reach. With 5th Pillar Family Takaful Hajj Safar Plan, we are dedicated to making this sacred journey possible. We believe that with sincere intention and the right support, Hajj is an achievable goal for all.
                </p>
                <p>
                    Our mission is to guide you through every phase of this life-changing experience. More than just a financial service, we offer a complete, Shariah-compliant solution that covers everything. Our End-to-End Value Chain starts from a tailored savings plan to smooth travel and accommodation arrangements in Makkah and Madinah, leaving you free to focus on your spiritual journey.
                </p>
                <button type="button" class="laravel-hajj-btn laravel-brochure-trigger" data-brochure-key="hajj-safar">Hajj Safar Plan Brochure</button>
                <div class="laravel-hajj-bank-partner laravel-hajj-bank-partner--align-left laravel-hajj-bank-partner--spaced">
                    <div class="laravel-hajj-bank-partner__logo laravel-hajj-bank-partner__logo--wide">
                        <img
                            src="{{ $hajjLogo5thPillar }}"
                            width="200"
                            loading="lazy"
                            decoding="async"
                            alt="5th Pillar Family Takaful"
                        >
                    </div>
                </div>
            </section>

            <section class="laravel-hajj-block">
                <h2 class="laravel-hajj-section-title">5th Pillar Family Takaful Fareeza Hajj Savings Plan</h2>
                <p>
                    Embarking on the sacred journey of Hajj is a dream cherished by millions of Muslims around the world. At 5th Pillar Family Takaful Limited, we understand the significance of this spiritual journey and are dedicated to making it accessible and affordable to all Muslims.
                </p>
                <p>
                    We recognize that the journey to the holy cities of Makkah and Medina is not only a spiritual calling but also a financial commitment. To ensure a smooth hassle-free Hajj experience, we have created a comprehensive solution that combines structured savings and a complete value chain of services.
                </p>
                <p>
                    From helping you build a dedicated savings plan to guiding you through the entire journey, our commitment is to be your trusted partner throughout your spiritual journey.
                </p>
                <button type="button" class="laravel-hajj-btn laravel-brochure-trigger" data-brochure-key="fareeza">Fareeza Hajj Saving Plan Brochure</button>
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
                    <a href="https://youtu.be/6yIFkzcln0k" target="_blank" rel="noopener noreferrer">Watch on YouTube</a>
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
