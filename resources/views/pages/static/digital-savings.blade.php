@extends('pages.layouts.structured-page')

@push('head')
    @php
        $__dsCss = public_path('assets/css/pages/digital-savings.css');
        $__dsCssVer = is_file($__dsCss) ? (string) filemtime($__dsCss) : '1';
        $__dsCanonical = url('/digital-savings');
        $__dsOgImage = asset('assets/images/main-bannner-64d5d132c369d.webp');
        $__dsOgTitle = isset($page) ? ($page->trans('meta_title') ?: $page->trans('title')) : 'Digital Savings - 5th Pillar Family Takaful';
        $__dsOgDesc = isset($page) && filled($page->trans('meta_description'))
            ? $page->trans('meta_description')
            : 'Start your Hajj and Umrah savings journey digitally with the 5th Pillar Niyyat app.';
    @endphp
    <link rel="canonical" href="{{ $__dsCanonical }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="5th Pillar Family Takaful">
    <meta property="og:title" content="{{ $__dsOgTitle }}">
    <meta property="og:description" content="{{ $__dsOgDesc }}">
    <meta property="og:url" content="{{ $__dsCanonical }}">
    <meta property="og:image" content="{{ $__dsOgImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $__dsOgTitle }}">
    <meta name="twitter:description" content="{{ $__dsOgDesc }}">
    <meta name="twitter:image" content="{{ $__dsOgImage }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/digital-savings.css') }}?v={{ $__dsCssVer }}">
@endpush

@section('structured_meta_title', 'Digital Savings - 5th Pillar Family Takaful')
@section('structured_page_title', 'Digital Savings')
@section('structured_hero_title', 'Digital Savings')

@section('structured_primary')
    <article class="post_item_single type-page hentry laravel-hajj-page laravel-digital-savings-page">
        <div class="post_content entry-content">

            {{-- Reusable 8-point Islamic star (khatam): two overlaid squares --}}
            <svg class="ds-defs" aria-hidden="true" focusable="false" width="0" height="0">
                <symbol id="ds-star" viewBox="0 0 100 100">
                    <rect x="20" y="20" width="60" height="60"></rect>
                    <rect x="20" y="20" width="60" height="60" transform="rotate(45 50 50)"></rect>
                </symbol>
            </svg>

            {{-- Section 1: Hero intro --}}
            <section class="laravel-hajj-hero-intro ds-hero">
                <p class="ds-eyebrow">
                    <svg class="ds-eyebrow__star" viewBox="0 0 100 100" aria-hidden="true"><use href="#ds-star"></use></svg>
                    The Niyyat App
                </p>
                <h2 class="laravel-hajj-section-title">Start Your Savings Journey Digitally</h2>
                <p class="laravel-hajj-lead">
                    Welcome to a simpler way of planning your Hajj and Umrah with 5th Pillar Family Takaful Limited.
                </p>
                <p class="laravel-hajj-lead">
                    We are making Takaful participation easier, faster and more convenient through secure digital
                    channels. Whether you are planning for Hajj or Umrah, you can now begin your journey without
                    visiting a branch.
                </p>
                @include('pages.partials.app-download-buttons')
            </section>

            {{-- Section 2: The digital journey (numbered pathway) --}}
            <section class="laravel-hajj-block ds-journey-section">
                <p class="ds-eyebrow">
                    <svg class="ds-eyebrow__star" viewBox="0 0 100 100" aria-hidden="true"><use href="#ds-star"></use></svg>
                    Your digital journey
                </p>
                <h2 class="laravel-hajj-section-title">Apply Through Our Mobile App Today</h2>

                <ol class="ds-journey">
                    <li class="ds-step">
                        <span class="ds-step__marker">
                            <svg class="ds-step__star" viewBox="0 0 100 100" aria-hidden="true"><use href="#ds-star"></use></svg>
                            <span class="ds-step__num">1</span>
                        </span>
                        <div class="ds-step__content">
                            <h3>Explore the plans</h3>
                            <p>Browse the available Hajj &amp; Umrah Takaful plans and find the one that fits your timeline.</p>
                        </div>
                    </li>
                    <li class="ds-step">
                        <span class="ds-step__marker">
                            <svg class="ds-step__star" viewBox="0 0 100 100" aria-hidden="true"><use href="#ds-star"></use></svg>
                            <span class="ds-step__num">2</span>
                        </span>
                        <div class="ds-step__content">
                            <h3>Use the planner</h3>
                            <p>Estimate your contributions and target date with the built-in Hajj &amp; Umrah planner.</p>
                        </div>
                    </li>
                    <li class="ds-step">
                        <span class="ds-step__marker">
                            <svg class="ds-step__star" viewBox="0 0 100 100" aria-hidden="true"><use href="#ds-star"></use></svg>
                            <span class="ds-step__num">3</span>
                        </span>
                        <div class="ds-step__content">
                            <h3>Complete your profile</h3>
                            <p>Add your details securely, once — no branch visit required.</p>
                        </div>
                    </li>
                    <li class="ds-step">
                        <span class="ds-step__marker">
                            <svg class="ds-step__star" viewBox="0 0 100 100" aria-hidden="true"><use href="#ds-star"></use></svg>
                            <span class="ds-step__num">4</span>
                        </span>
                        <div class="ds-step__content">
                            <h3>Apply digitally</h3>
                            <p>Submit your enrolment application straight from the app in a few taps.</p>
                        </div>
                    </li>
                    <li class="ds-step">
                        <span class="ds-step__marker">
                            <svg class="ds-step__star" viewBox="0 0 100 100" aria-hidden="true"><use href="#ds-star"></use></svg>
                            <span class="ds-step__num">5</span>
                        </span>
                        <div class="ds-step__content">
                            <h3>Track your progress</h3>
                            <p>Follow your savings and application status in real time.</p>
                        </div>
                    </li>
                    <li class="ds-step">
                        <span class="ds-step__marker">
                            <svg class="ds-step__star" viewBox="0 0 100 100" aria-hidden="true"><use href="#ds-star"></use></svg>
                            <span class="ds-step__num">6</span>
                        </span>
                        <div class="ds-step__content">
                            <h3>Stay connected</h3>
                            <p>Get updates and reach support whenever you need them.</p>
                        </div>
                    </li>
                </ol>
            </section>

            {{-- Section 3: Digital First statement band --}}
            <section class="ds-band" aria-label="Digital First">
                <svg class="ds-band__watermark" viewBox="0 0 100 100" aria-hidden="true"><use href="#ds-star"></use></svg>
                <p class="ds-band__eyebrow">Digital First</p>
                <h2 class="ds-band__title">Your Future.<br>Your Niyyat.<br>Your App.</h2>
                <p class="ds-band__text">
                    Simple, secure and Shariah-compliant savings at your fingertips.
                </p>
                @include('pages.partials.app-download-buttons', ['variant' => 'ondark'])
            </section>

            {{-- Section 4: SECP regulatory note --}}
            <section class="laravel-hajj-block ds-note-section">
                <p class="ds-eyebrow">
                    <svg class="ds-eyebrow__star" viewBox="0 0 100 100" aria-hidden="true"><use href="#ds-star"></use></svg>
                    Regulated &amp; compliant
                </p>
                <h2 class="laravel-hajj-section-title">Digital Savings under SECP Framework</h2>
                <div class="ds-note">
                    <i class="fa-solid fa-building-columns ds-note__icon" aria-hidden="true"></i>
                    <div class="ds-note__body">
                        <p>
                            Securities and Exchange Commission of Pakistan has introduced a framework enabling life
                            insurers and family Takaful operators to offer savings products through secure digital
                            platforms.
                        </p>
                        <p>
                            In line with this initiative, 5th Pillar Family Takaful Limited provides customers with a
                            secure digital enrolment journey through the Niyyat mobile application.
                        </p>
                    </div>
                </div>
            </section>

            {{-- Section 5: Need Help --}}
            <section class="laravel-hajj-block ds-help-section">
                <h2 class="laravel-hajj-section-title">Need Help?</h2>
                <ul class="ds-contact">
                    <li class="ds-contact__item">
                        <i class="fa-solid fa-envelope ds-contact__icon" aria-hidden="true"></i>
                        <span><strong>Support Email:</strong> <a href="mailto:info@5thpillartakaful.com">info@5thpillartakaful.com</a></span>
                    </li>
                    <li class="ds-contact__item">
                        <i class="fa-solid fa-phone ds-contact__icon" aria-hidden="true"></i>
                        <span><strong>Support Phone:</strong> <a href="tel:021111786573">021-111-786-573</a></span>
                    </li>
                </ul>
            </section>

            {{-- Section 6: FAQ (reuses the site knowledge accordion) --}}
            <section class="laravel-hajj-block ds-faq-section">
                <h2 class="laravel-hajj-section-title">Frequently Asked Questions</h2>
                <div class="laravel-knowledge-accordion" data-knowledge-accordion>
                    <details class="laravel-knowledge-accordion__item" open>
                        <summary class="laravel-knowledge-accordion__summary">How can I start my savings plan online?</summary>
                        <div class="laravel-knowledge-accordion__body">
                            <p>You can begin by downloading the Niyyat mobile app.</p>
                        </div>
                    </details>
                    <details class="laravel-knowledge-accordion__item">
                        <summary class="laravel-knowledge-accordion__summary">Can I apply directly from the website?</summary>
                        <div class="laravel-knowledge-accordion__body">
                            <p>Customer Web Portal will be available soon.</p>
                        </div>
                    </details>
                    <details class="laravel-knowledge-accordion__item">
                        <summary class="laravel-knowledge-accordion__summary">Is the digital process secure?</summary>
                        <div class="laravel-knowledge-accordion__body">
                            <p>Yes.</p>
                        </div>
                    </details>
                    <details class="laravel-knowledge-accordion__item">
                        <summary class="laravel-knowledge-accordion__summary">Are the plans Shariah-compliant?</summary>
                        <div class="laravel-knowledge-accordion__body">
                            <p>Yes.</p>
                        </div>
                    </details>
                    <details class="laravel-knowledge-accordion__item">
                        <summary class="laravel-knowledge-accordion__summary">How long does the application take?</summary>
                        <div class="laravel-knowledge-accordion__body">
                            <p>Usually within minutes.</p>
                        </div>
                    </details>
                    <details class="laravel-knowledge-accordion__item">
                        <summary class="laravel-knowledge-accordion__summary">Can I track my application?</summary>
                        <div class="laravel-knowledge-accordion__body">
                            <p>Yes.</p>
                        </div>
                    </details>
                    <details class="laravel-knowledge-accordion__item">
                        <summary class="laravel-knowledge-accordion__summary">What if I need help?</summary>
                        <div class="laravel-knowledge-accordion__body">
                            <p>Contact our support team.</p>
                        </div>
                    </details>
                </div>
            </section>

        </div>
    </article>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[data-knowledge-accordion]').forEach((accordion) => {
            accordion.querySelectorAll('details').forEach((item) => {
                item.addEventListener('toggle', () => {
                    if (!item.open) {
                        return;
                    }

                    accordion.querySelectorAll('details[open]').forEach((openItem) => {
                        if (openItem !== item) {
                            openItem.removeAttribute('open');
                        }
                    });
                });
            });
        });
    </script>
@endpush
