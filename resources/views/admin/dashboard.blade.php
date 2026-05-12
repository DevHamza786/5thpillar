@extends('admin.layouts.app')

@section('title', __('Dashboard'))

@push('styles')
<style>
    /* Dashboard only — editorial / studio feel, scoped */
    .admin-dash { --dash-ink: #1a1f24; --dash-muted: #5c656d; --dash-line: #dfe3e6; --dash-paper: #faf9f7; --dash-accent: #0f766e; --dash-accent-hover: #0d9488; --dash-warm: #b45309; --dash-radius: 12px; font-size: 15px; line-height: 1.5; color: var(--dash-ink); margin: -6px 0 48px; }
    .admin-dash * { box-sizing: border-box; }
    .admin-dash__hero {
        display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 1.25rem;
        padding: 1.75rem 1.5rem 1.5rem; margin: 0 0 1.5rem; border-radius: var(--dash-radius);
        background: linear-gradient(145deg, #f7f5f0 0%, #eef6f5 45%, #e8f0ef 100%);
        border: 1px solid var(--dash-line); box-shadow: 0 1px 0 rgba(255,255,255,.8) inset;
    }
    .admin-dash__kicker {
        margin: 0 0 0.35rem; font-size: 11px; font-weight: 600; letter-spacing: .14em; text-transform: uppercase; color: var(--dash-accent);
    }
    .admin-dash__title {
        margin: 0; font-family: Georgia, "Times New Roman", Times, serif; font-size: clamp(1.65rem, 4vw, 2.05rem); font-weight: 400; line-height: 1.15; letter-spacing: -0.02em;
    }
    .admin-dash__lede { margin: 0.65rem 0 0; max-width: 36rem; color: var(--dash-muted); font-size: 0.95rem; }
    .admin-dash__hero-actions { display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center; }
    .admin-dash__link-site {
        display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.55rem 1rem; border-radius: 8px;
        font-size: 13px; font-weight: 600; color: var(--dash-accent); background: #fff; border: 1px solid var(--dash-line);
        text-decoration: none; transition: border-color .15s, color .15s, box-shadow .15s;
    }
    .admin-dash__link-site:hover { border-color: var(--dash-accent); color: var(--dash-accent-hover); box-shadow: 0 2px 8px rgba(15,118,110,.12); text-decoration: none; }
    .admin-dash__link-site .dashicons { font-size: 18px; width: 18px; height: 18px; opacity: .85; }

    .admin-dash__stats {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 0.75rem; margin-bottom: 1.5rem;
    }
    .admin-dash__stat {
        padding: 1rem 1.1rem; border-radius: 10px; background: var(--dash-paper); border: 1px solid var(--dash-line);
    }
    .admin-dash__stat-value { font-family: Georgia, serif; font-size: 1.85rem; font-weight: 400; line-height: 1; color: var(--dash-accent); }
    .admin-dash__stat-label { margin-top: 0.35rem; font-size: 12px; font-weight: 600; letter-spacing: .04em; text-transform: uppercase; color: var(--dash-muted); }

    .admin-dash__grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem;
    }
    .admin-dash__tile {
        position: relative; display: flex; flex-direction: column; min-height: 200px; padding: 1.25rem 1.25rem 1.1rem;
        border-radius: var(--dash-radius); background: #fff; border: 1px solid var(--dash-line);
        box-shadow: 0 2px 12px rgba(26,31,36,.06); transition: box-shadow .2s, border-color .2s;
    }
    .admin-dash__tile:hover { box-shadow: 0 8px 28px rgba(26,31,36,.1); border-color: #cfd6da; }
    .admin-dash__tile-icon {
        width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center;
        background: rgba(15,118,110,.1); color: var(--dash-accent); margin-bottom: 0.85rem;
    }
    .admin-dash__tile-icon .dashicons { font-size: 22px; width: 22px; height: 22px; }
    .admin-dash__tile h2 { margin: 0 0 0.4rem; font-size: 1.05rem; font-weight: 600; letter-spacing: -0.01em; }
    .admin-dash__tile p { margin: 0 0 1rem; flex: 1; font-size: 13px; color: var(--dash-muted); line-height: 1.45; }
    .admin-dash__tile-actions { display: flex; flex-wrap: wrap; gap: 0.45rem; margin-top: auto; }
    .admin-dash__cta {
        display: inline-flex; align-items: center; justify-content: center; padding: 0.45rem 0.9rem; border-radius: 8px;
        font-size: 13px; font-weight: 600; text-decoration: none; border: 1px solid transparent; cursor: pointer; transition: background .15s, border-color .15s, color .15s;
    }
    .admin-dash__cta--primary { background: var(--dash-accent); color: #fff; border-color: var(--dash-accent); }
    .admin-dash__cta--primary:hover { background: var(--dash-accent-hover); border-color: var(--dash-accent-hover); color: #fff; text-decoration: none; }
    .admin-dash__cta--quiet { background: #fff; color: var(--dash-ink); border-color: var(--dash-line); }
    .admin-dash__cta--quiet:hover { border-color: var(--dash-accent); color: var(--dash-accent); text-decoration: none; }

    .admin-dash__tile--alert { border-color: #e7d4c8; background: linear-gradient(180deg, #fffdfb 0%, #fff 100%); }
    .admin-dash__tile--alert .admin-dash__tile-icon { background: rgba(180,83,9,.12); color: var(--dash-warm); }
    .admin-dash__tile--alert h2 { color: #7c2d12; }

    @media (max-width: 600px) {
        .admin-dash__hero { padding: 1.25rem 1.1rem; }
        .admin-dash__tile { min-height: 0; }
    }
</style>
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
