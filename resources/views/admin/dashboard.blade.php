@extends('admin.layouts.app')

@section('title', __('Dashboard'))

@push('styles')
    @php
        $__adminDashCss = public_path('assets/css/admin/admin-dashboard.css');
        $__adminDashCssVer = is_file($__adminDashCss) ? (string) filemtime($__adminDashCss) : '1';
    @endphp
    <link rel="stylesheet" href="{{ asset('assets/css/admin/admin-dashboard.css') }}?v={{ $__adminDashCssVer }}">
@endpush

@section('content')
    <div class="admin-dash">
        <header class="admin-dash__hero">
            <div class="admin-dash__hero-text">
                <p class="admin-dash__kicker">{{ __('Content') }}</p>
                <h1 class="admin-dash__title">{{ config('app.name') }}</h1>
                <p class="admin-dash__lede">{{ __('Pages, navigation, fund data, and media—everything that appears on the public site lives here.') }}</p>
            </div>
            <div class="admin-dash__hero-actions">
                <a class="admin-dash__link-site" href="{{ route('home') }}" target="_blank" rel="noopener noreferrer">
                    <span class="dashicons dashicons-external" aria-hidden="true"></span>
                    {{ __('Open website') }}
                </a>
            </div>
        </header>

        <section class="admin-dash__stats" aria-label="{{ __('Overview') }}">
            <div class="admin-dash__stat">
                <div class="admin-dash__stat-value">{{ number_format($pageCount) }}</div>
                <div class="admin-dash__stat-label">{{ __('Pages') }}</div>
            </div>
            <div class="admin-dash__stat">
                <div class="admin-dash__stat-value">{{ number_format($snapshotCount) }}</div>
                <div class="admin-dash__stat-label">{{ __('Fund snapshots') }}</div>
            </div>
        </section>

        <section class="admin-dash__grid" aria-label="{{ __('Shortcuts') }}">
            <article class="admin-dash__tile">
                <div class="admin-dash__tile-icon" aria-hidden="true"><span class="dashicons dashicons-admin-page"></span></div>
                <h2>{{ __('Pages') }}</h2>
                <p>{{ __('Titles, permalinks, SEO, sections, and per-page downloads.') }}</p>
                <div class="admin-dash__tile-actions">
                    <a class="admin-dash__cta admin-dash__cta--primary" href="{{ route('admin.pages.index') }}">{{ __('Browse pages') }}</a>
                    <a class="admin-dash__cta admin-dash__cta--quiet" href="{{ route('admin.pages.create') }}">{{ __('Add new') }}</a>
                </div>
            </article>

            <article class="admin-dash__tile">
                <div class="admin-dash__tile-icon" aria-hidden="true"><span class="dashicons dashicons-menu"></span></div>
                <h2>{{ __('Navigation') }}</h2>
                <p>{{ __('Header links: internal routes, full URLs, or files you upload for PDFs and notices.') }}</p>
                <div class="admin-dash__tile-actions">
                    <a class="admin-dash__cta admin-dash__cta--primary" href="{{ route('admin.navigation.index') }}">{{ __('Edit menu') }}</a>
                    <a class="admin-dash__cta admin-dash__cta--quiet" href="{{ route('admin.navigation.media.index') }}">{{ __('Menu files') }}</a>
                </div>
            </article>

            <article class="admin-dash__tile">
                <div class="admin-dash__tile-icon" aria-hidden="true"><span class="dashicons dashicons-chart-area"></span></div>
                <h2>{{ __('Daily fund prices') }}</h2>
                <p>{{ __('The newest snapshot row drives the public table; older rows stay in the archive.') }}</p>
                <div class="admin-dash__tile-actions">
                    <a class="admin-dash__cta admin-dash__cta--primary" href="{{ route('admin.fund-snapshots.index') }}">{{ __('Manage snapshots') }}</a>
                </div>
            </article>

            @if ($fundManagersReportPage)
                <article class="admin-dash__tile">
                    <div class="admin-dash__tile-icon" aria-hidden="true"><span class="dashicons dashicons-media-document"></span></div>
                    <h2>{{ __('Fund Manager’s Report') }}</h2>
                    <p>{{ __('Same page visitors see on the site—edit copy, meta, and extra blocks here.') }}</p>
                    <div class="admin-dash__tile-actions">
                        <a class="admin-dash__cta admin-dash__cta--primary" href="{{ route('admin.pages.edit', $fundManagersReportPage) }}">{{ __('Edit page') }}</a>
                        <a class="admin-dash__cta admin-dash__cta--quiet" href="{{ route('pages.show', ['slug' => $fundManagersReportPage->slug]) }}" target="_blank" rel="noopener">{{ __('Live view') }}</a>
                    </div>
                </article>
            @else
                <article class="admin-dash__tile admin-dash__tile--alert">
                    <div class="admin-dash__tile-icon" aria-hidden="true"><span class="dashicons dashicons-warning"></span></div>
                    <h2>{{ __('Fund Manager’s Report') }}</h2>
                    <p>{{ __('No page with slug “:slug” exists yet. Create or import it so this shortcut works.', ['slug' => config('cms.fund_managers_report_slug')]) }}</p>
                    <div class="admin-dash__tile-actions">
                        <a class="admin-dash__cta admin-dash__cta--quiet" href="{{ route('admin.pages.create') }}">{{ __('Add page') }}</a>
                    </div>
                </article>
            @endif
        </section>
    </div>
@endsection
