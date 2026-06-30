@extends('pages.layouts.structured-page')

@section('structured_meta_title', 'Careers - 5th Pillar Family Takaful')
@section('structured_page_title', 'Careers')

@section('structured_hero_title', 'Careers')

@section('structured_primary')
    <article class="post_item_single page type-page laravel-careers-page">
        <div class="post_content entry-content">
            <p class="laravel-careers-page__intro">
                Interested candidates may send their CVs to
                <a href="mailto:careers@5thpillartakaful.com">careers@5thpillartakaful.com</a>
            </p>

            <figure class="laravel-careers-page__figure">
                <img
                    class="laravel-careers-page__image"
                    src="{{ asset('assets/images/careers/executive-officer-customer-services.jpg') }}"
                    width="1024"
                    height="1024"
                    alt="We are hiring: Executive Officer / Senior Executive Officer - Customer Services"
                    loading="lazy"
                    decoding="async"
                >
            </figure>
        </div>
    </article>
@endsection
