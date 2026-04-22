@extends('pages.layouts.structured-page')

@section('structured_meta_title', 'Memberships - 5th Pillar Family Takaful Limited')
@section('structured_page_title', 'Memberships')

@section('structured_hero_title', 'Memberships')

@section('structured_primary')
    <article class="post_item_single page type-page laravel-memberships-page">
        <div class="post_content entry-content">
            <div class="laravel-memberships-cert">
                <h2 class="laravel-memberships-cert__title">IAP Membership Certificate</h2>
                <a
                    href="{{ asset('uploads/2026/2026/04/IAP-Certificate.png') }}"
                    class="laravel-memberships-cert__btn sc_button sc_button_default"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <span class="sc_button_text"><span class="sc_button_title">Click Here</span></span>
                </a>
            </div>
        </div>
    </article>
@endsection
