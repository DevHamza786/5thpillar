@extends('pages.layouts.structured-page')

@push('head')
    @if (config('services.recaptcha.site_key'))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endpush

@section('structured_meta_title', 'Sukoon Takaful Saving Plan - 5th Pillar Family Takaful')
@section('structured_page_title', 'Regular Savings Plan')

@section('structured_hero_title', 'Regular Savings Plan')

@section('structured_primary')
    <article class="post_item_single type-page hentry laravel-hajj-page">
        <div class="post_content entry-content">
            <section class="laravel-hajj-hero-intro">
                <h2 class="laravel-hajj-section-title" style="margin-top:0">Sukoon Savings Plan</h2>
                <p class="laravel-hajj-lead">
                    The 5th Pillar Family Takaful Sukoon Savings Plan offers a comprehensive solution for securing your family&rsquo;s financial future while maintaining their quality of life. This Shariah-compliant plan allows you to save, invest, and grow your wealth, with family Takaful coverage ensuring financial security in your absence. Key features include:
                </p>
                <ul class="laravel-product-feature-list">
                    <li><strong>Structured Savings:</strong> Build a financial cushion through disciplined savings.</li>
                    <li><strong>Investment Opportunities:</strong> Shariah-compliant options to grow your wealth.</li>
                    <li><strong>Family Takaful Coverage:</strong> Provides financial protection for your loved ones.</li>
                    <li><strong>Shariah Compliance:</strong> Aligns your financial goals with your faith.</li>
                    <li><strong>Quality of Life:</strong> Secures your family&rsquo;s future without compromising lifestyle.</li>
                </ul>
                <p class="laravel-hajj-lead">
                    This plan gives you confidence in managing your family&rsquo;s financial well-being.
                </p>
                <div class="laravel-hajj-actions">
                    <button type="button" class="laravel-hajj-btn laravel-brochure-trigger" data-brochure-key="sukoon">Download Brochure</button>
                </div>
                <div class="laravel-hajj-actions" style="margin-top:0.75rem">
                    <button type="button" class="laravel-hajj-btn laravel-brochure-trigger" data-brochure-key="sukoon">Download Our Brochure</button>
                </div>
            </section>
        </div>

        @include('pages.partials.brochure-lead-modal')
    </article>
@endsection

@push('scripts')
    @include('pages.partials.brochure-modal-script')
@endpush
