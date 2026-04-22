@extends('pages.layouts.structured-page')

@section('structured_meta_title', 'About Us - 5th Pillar Family Takaful')
@section('structured_page_title', 'About Us')

@php
    $introLead = '5th Pillar Family Takaful Limited is a new entrant into the Family Takaful sector of Pakistan which is supported by eminent business houses from Kuwait and Pakistan. The company has set industry records with remarkable milestones such as:';

    $milestones = [
        'Largest FDI in Takaful sector of Pakistan',
        'Foreign shareholders own 68% of 5th Pillar Takaful and 32% is held by Pakistani interests',
        'Largest initial paid up capital of Rs 2.00 billion in Pakistan’s Takaful sector history',
        'Highest initial credit rating “A+ Stable outlook” from Pakistan Credit Rating Agency (PACRA)',
        'Licensed by the SECP to underwrite Shariah compliant Family Takaful business in Pakistan',
        'State of the art IT platform to support business operations throughout the membership lifecycle',
        'Upcoming customer engagement mobile app/web portal to provide 24/7 information and assistance to members from the comfort of their homes',
    ];

    $sponsorsIntro = '5th Pillar Family Takaful Limited is backed by distinguished sponsors:';

    $sponsorParagraphs = [
        [
            'strong' => 'Kuwait International Investment Holding Company (KIIC)',
            'text' => ' is a leading investment company headquartered in Kuwait City, Kuwait. Founded in 1973, KIIC is owned by leading business houses of Kuwait including Government of Kuwait owned Kuwait Investment Authority (KIA) which owns 31.90% share of KIIC.',
        ],
        [
            'strong' => 'Al Bahar Group',
            'text' => ' formerly known as IFA Group, is a Kuwait-based company incorporated in 1974. Al Bahar Group is a multi-billion US dollar consortium of several listed Kuwaiti companies with diverse investments in Takaful, Hospitality, Financial Services, and Real Estate. The Group owns multiple hotels across the Middle East, Africa, Europe, and the USA, comprising approximately 11,500 five-star room keys across four continents, providing it with unmatched expertise in the hospitality sector in particular.',
        ],
        [
            'strong' => '5th Pillar Holding DIFC Dubai, UAE',
            'text' => ' is a special purpose company which has been set up by renowned business houses from Kuwait to develop Takaful companies and value chain in major Muslim populations countries. Starting with Pakistan, the plan is to set up similar operations in Bangladesh, Indonesia, Turkey and Egypt.',
        ],
        [
            'strong' => 'Muhammadi Family & Associates',
            'text' => ' include the Muhammadi Family who have been doing business in the Takaful/Insurance sector for over three generations in Pakistan.',
        ],
    ];

    $sponsorsClosing = 'This significant financial backing has allowed the company to invest in cutting-edge technology and develop innovative products in order to provide unparalleled customer service to its clients. Our reputed sponsors possess the necessary financial resources and expertise to deliver their commitment to facilitate our members to perform Hajj from Pakistan and from other selected countries with ease and comfort at an affordable Hajj price in Pakistan.';

    $valueChainImage = asset('uploads/2024/2024/01/5th-Pillar-End-to-End-Value-Chain-1.webp');

    $reTakafulHeading = 'ReTakaful Arrangements';
    $reTakafulText = 'We have made ReTakaful arrangements with Hannover Re (world’s renowned ReTakaful Company) which allows us to enjoy the expertise of one of the most progressive institutions across the globe.';
@endphp

@section('structured_primary')
    <section class="laravel-about-wp-intro" aria-label="About 5th Pillar Family Takaful">
        <div class="laravel-about-wp-intro__row">
            <div class="laravel-about-wp-intro__col laravel-about-wp-intro__col--main">
                <p class="laravel-about-wp-intro__text">{{ $introLead }}</p>
                <ul class="laravel-about-wp-intro__list">
                    @foreach ($milestones as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="laravel-about-wp-intro__col laravel-about-wp-intro__col--aside" aria-hidden="true"></div>
        </div>
    </section>
@endsection

@section('structured_tertiary')
    <section class="laravel-about-wp-band shaha_about_quote laravel-about-wp-band--sponsors" aria-labelledby="about-sponsors-heading">
        <div class="content_wrap">
            <div class="sc_content color_style_default sc_content_default sc_float_center extra-about-quote-mr-negative">
                <h2 id="about-sponsors-heading" class="laravel-about-wp-band__h2 laravel-about-wp-band__h2--left">Our Sponsors</h2>
                <p class="laravel-about-wp-prose-dark">{{ $sponsorsIntro }}</p>
                @foreach ($sponsorParagraphs as $block)
                    <p class="laravel-about-wp-prose-dark">
                        <strong>{{ $block['strong'] }}</strong>{{ $block['text'] }}
                    </p>
                @endforeach
                <p class="laravel-about-wp-prose-dark">{{ $sponsorsClosing }}</p>
            </div>
        </div>
    </section>

    <section class="laravel-about-wp-band laravel-about-wp-band--value-chain vc_row-has-fill" aria-labelledby="about-value-chain-heading">
        <div class="content_wrap laravel-about-wp-value-chain__inner">
            <h2 id="about-value-chain-heading" class="laravel-about-wp-value-title">
                The Road Map To Our<br>
                End-to-End Value Chain
            </h2>
            <figure class="laravel-about-wp-value-figure">
                <div class="laravel-about-wp-value-frame">
                    <img
                        src="{{ $valueChainImage }}"
                        width="1920"
                        height="1080"
                        class="laravel-about-wp-value-img"
                        alt="5th Pillar End-to-End Value Chain"
                        loading="lazy"
                        decoding="async"
                    >
                </div>
            </figure>
        </div>
    </section>

    <section class="laravel-about-wp-band shaha_about_quote laravel-about-wp-band--retakaful" aria-labelledby="about-retakaful-heading">
        <div class="content_wrap">
            <div class="laravel-about-wp-retakaful-row">
                <div class="laravel-about-wp-retakaful-row__main">
                    <div class="sc_content color_style_default sc_content_default sc_float_center extra-about-quote-mr-negative">
                        <h2 id="about-retakaful-heading" class="laravel-about-wp-band__h2 laravel-about-wp-band__h2--left">{{ $reTakafulHeading }}</h2>
                        <p class="laravel-about-wp-prose-dark">{{ $reTakafulText }}</p>
                    </div>
                </div>
                <div class="laravel-about-wp-retakaful-row__aside" aria-hidden="true"></div>
            </div>
        </div>
    </section>
@endsection
