@extends('pages.layouts.structured-page')

@section('structured_meta_title', 'SECP SDMS - 5th Pillar Family Takaful Limited')
@section('structured_page_title', 'SECP SDMS')

@section('structured_hero_title', 'SECP SDMS')

@section('structured_primary')
    @php
        $sdmsUrl = 'https://sdms.secp.gov.pk/~sdmsadmn/';
    @endphp
    <article class="post_item_single page type-page laravel-secp-sdms-page">
        <div class="post_content entry-content">
            <div class="laravel-secp-sdms">
                <a
                    class="laravel-secp-sdms__logo-link"
                    href="{{ $sdmsUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <img
                        class="laravel-secp-sdms__logo"
                        src="{{ asset('uploads/2017/2017/07/SECP-Logo-Footer.webp') }}"
                        alt="Service Desk Management System Logo"
                        width="419"
                        height="85"
                        loading="lazy"
                        decoding="async"
                    >
                </a>

                <p class="laravel-secp-sdms__disclaimer">
                    <strong><em>Disclaimer:</em></strong>
                    In case your complaint has not been properly redressed by us, you may lodge your complaint with
                    Securities and Exchange Commission of Pakistan (the &ldquo;SECP&rdquo;). However, please note that
                    SECP will entertain only those complaints which were at first directly requested to be redressed by
                    the company and the company has failed to redress the same. Further, the complaints that are not
                    relevant to SECP&rsquo;s regulatory domain/competence shall not be entertained by the SECP.
                </p>

                <p class="laravel-secp-sdms__cta-wrap">
                    <a class="laravel-secp-sdms__cta sc_button sc_button_default" href="{{ $sdmsUrl }}" target="_blank" rel="noopener noreferrer">
                        <span class="sc_button_text"><span class="sc_button_title">View More</span></span>
                    </a>
                </p>
            </div>
        </div>
    </article>
@endsection
