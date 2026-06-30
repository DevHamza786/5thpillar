@extends('admin.layouts.app')

@section('title', __('Dashboard'))

@section('content')
    <div class="admin-dash">
        <header class="admin-dash__hero admin-dash__reveal">
            <div class="admin-dash__hero-glow" aria-hidden="true"></div>
            <div class="admin-dash__hero-inner">
                <p class="admin-dash__kicker">{{ __('Operations overview') }}</p>
                <h1 class="admin-dash__title">{{ config('app.name') }}</h1>
                <p class="admin-dash__lede">{{ __('Leads, form submissions, published pages, and the latest fund prices—at a glance.') }}</p>
            </div>
            <div class="admin-dash__hero-actions">
                <a class="admin-dash__link-site" href="{{ route('home') }}" target="_blank" rel="noopener noreferrer">
                    <span class="dashicons dashicons-external" aria-hidden="true"></span>
                    {{ __('Open website') }}
                </a>
            </div>
        </header>

        <section class="admin-dash__metrics" aria-label="{{ __('Key metrics') }}">
            <article class="admin-dash__metric admin-dash__metric--pages admin-dash__reveal" data-reveal-index="1">
                <div class="admin-dash__metric-icon" aria-hidden="true"><span class="dashicons dashicons-admin-page"></span></div>
                <div class="admin-dash__metric-body">
                    <span class="admin-dash__metric-value" data-count="{{ $publishedPageCount }}">{{ number_format($publishedPageCount) }}</span>
                    <span class="admin-dash__metric-label">{{ __('Published pages') }}</span>
                    <span class="admin-dash__metric-sub">{{ __('EN :en · UR :ur · :total total', ['en' => number_format($publishedPageCount), 'ur' => number_format($publishedUrduPageCount), 'total' => number_format($totalPageCount)]) }}</span>
                </div>
            </article>

            <article class="admin-dash__metric admin-dash__metric--brochure admin-dash__reveal" data-reveal-index="2">
                <div class="admin-dash__metric-icon admin-dash__metric-icon--gold" aria-hidden="true"><span class="dashicons dashicons-download"></span></div>
                <div class="admin-dash__metric-body">
                    <span class="admin-dash__metric-value" data-count="{{ $brochureLeadCount }}">{{ number_format($brochureLeadCount) }}</span>
                    <span class="admin-dash__metric-label">{{ __('Product brochure leads') }}</span>
                    <span class="admin-dash__metric-sub">{{ __('Download form submissions') }}</span>
                </div>
            </article>

            <article class="admin-dash__metric admin-dash__metric--planner admin-dash__reveal" data-reveal-index="3">
                <div class="admin-dash__metric-icon" aria-hidden="true"><span class="dashicons dashicons-calendar-alt"></span></div>
                <div class="admin-dash__metric-body">
                    <span class="admin-dash__metric-value" data-count="{{ $plannerLeadCount }}">{{ number_format($plannerLeadCount) }}</span>
                    <span class="admin-dash__metric-label">{{ __('Planner leads') }}</span>
                    <span class="admin-dash__metric-sub">{{ __('Hajj :hajj · Umrah :umrah', ['hajj' => number_format($hajjPlanLeadCount), 'umrah' => number_format($umrahPlanLeadCount)]) }}</span>
                </div>
            </article>

            <article class="admin-dash__metric admin-dash__metric--forms admin-dash__reveal" data-reveal-index="4">
                <div class="admin-dash__metric-icon" aria-hidden="true"><span class="dashicons dashicons-email-alt"></span></div>
                <div class="admin-dash__metric-body">
                    <span class="admin-dash__metric-value" data-count="{{ $formSubmissionTotal }}">{{ number_format($formSubmissionTotal) }}</span>
                    <span class="admin-dash__metric-label">{{ __('Form submissions') }}</span>
                    <span class="admin-dash__metric-sub">
                        {{ __('Inquiry :inq · Complaint :cmp · Online :onl', [
                            'inq' => number_format($formSubmissionCounts[\App\Models\FormSubmission::TYPE_INQUIRY]),
                            'cmp' => number_format($formSubmissionCounts[\App\Models\FormSubmission::TYPE_COMPLAINT]),
                            'onl' => number_format($formSubmissionCounts[\App\Models\FormSubmission::TYPE_ONLINE_COMPLAINT]),
                        ]) }}
                    </span>
                </div>
            </article>

            <article class="admin-dash__metric admin-dash__metric--wide admin-dash__metric--funds admin-dash__reveal" data-reveal-index="5">
                <div class="admin-dash__metric-icon" aria-hidden="true"><span class="dashicons dashicons-chart-area"></span></div>
                <div class="admin-dash__metric-body">
                    @if ($latestSnapshot)
                        <span class="admin-dash__metric-value admin-dash__metric-value--sm">{{ $latestSnapshot->price_date->format('d M Y') }}</span>
                        <span class="admin-dash__metric-label">{{ __('Latest daily fund prices') }}</span>
                        <span class="admin-dash__metric-sub">
                            Agg {{ number_format((float) $latestSnapshot->agg_bid, 2) }} /
                            {{ number_format((float) $latestSnapshot->agg_offer, 2) }}
                            · Bal {{ number_format((float) $latestSnapshot->bal_bid, 2) }} /
                            {{ number_format((float) $latestSnapshot->bal_offer, 2) }}
                            · Con {{ number_format((float) $latestSnapshot->con_bid, 2) }} /
                            {{ number_format((float) $latestSnapshot->con_offer, 2) }}
                        </span>
                    @else
                        <span class="admin-dash__metric-value admin-dash__metric-value--sm">{{ __('No data') }}</span>
                        <span class="admin-dash__metric-label">{{ __('Daily fund prices') }}</span>
                        <span class="admin-dash__metric-sub">{{ __('Add a snapshot to publish prices on the site.') }}</span>
                    @endif
                </div>
                <a class="admin-dash__metric-link" href="{{ route('admin.fund-snapshots.index') }}">{{ __('Manage') }}</a>
            </article>
        </section>

        <div class="admin-dash__panels">
            <section class="admin-dash__panel admin-dash__reveal" data-reveal-index="6" aria-labelledby="dash-brochure-heading">
                <header class="admin-dash__panel-head">
                    <h2 id="dash-brochure-heading">{{ __('Recent product leads') }}</h2>
                    <span class="admin-dash__panel-count">{{ number_format($brochureLeadCount) }}</span>
                </header>
                @if ($recentBrochureLeads->isEmpty())
                    <div class="admin-dash__empty-state">
                        <span class="dashicons dashicons-download" aria-hidden="true"></span>
                        <p>{{ __('No brochure download leads yet.') }}</p>
                    </div>
                @else
                    <ul class="admin-dash__feed">
                        @foreach ($recentBrochureLeads as $lead)
                            <li class="admin-dash__feed-item">
                                <span class="admin-dash__avatar" aria-hidden="true">{{ strtoupper(mb_substr($lead->name, 0, 1)) }}</span>
                                <div class="admin-dash__feed-main">
                                    <strong>{{ $lead->name }}</strong>
                                    <span class="admin-dash__feed-meta">{{ ucwords(str_replace(['-', '_'], ' ', $lead->brochure_key)) }} · {{ $lead->city }}</span>
                                </div>
                                <time class="admin-dash__feed-time" datetime="{{ $lead->created_at->toIso8601String() }}">{{ $lead->created_at->format('d M, H:i') }}</time>
                            </li>
                        @endforeach
                    </ul>
                @endif
                @if ($brochureLeadsByProduct->isNotEmpty())
                    <div class="admin-dash__breakdown">
                        <p class="admin-dash__breakdown-title">{{ __('By product') }}</p>
                        <ul class="admin-dash__breakdown-list">
                            @foreach ($brochureLeadsByProduct as $row)
                                <li>
                                    <span>{{ ucwords(str_replace(['-', '_'], ' ', $row->brochure_key)) }}</span>
                                    <span class="admin-dash__pill">{{ number_format($row->total) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </section>

            <section class="admin-dash__panel admin-dash__reveal" data-reveal-index="7" aria-labelledby="dash-forms-heading">
                <header class="admin-dash__panel-head">
                    <h2 id="dash-forms-heading">{{ __('Form submissions') }}</h2>
                    <span class="admin-dash__panel-count">{{ number_format($formSubmissionTotal) }}</span>
                </header>
                <div class="admin-dash__form-stats">
                    <div class="admin-dash__form-stat admin-dash__form-stat--inquiry">
                        <span class="admin-dash__form-stat-value">{{ number_format($formSubmissionCounts[\App\Models\FormSubmission::TYPE_INQUIRY]) }}</span>
                        <span class="admin-dash__form-stat-label">{{ __('Inquiries') }}</span>
                    </div>
                    <div class="admin-dash__form-stat admin-dash__form-stat--complaint">
                        <span class="admin-dash__form-stat-value">{{ number_format($formSubmissionCounts[\App\Models\FormSubmission::TYPE_COMPLAINT]) }}</span>
                        <span class="admin-dash__form-stat-label">{{ __('Complaints') }}</span>
                    </div>
                    <div class="admin-dash__form-stat admin-dash__form-stat--online">
                        <span class="admin-dash__form-stat-value">{{ number_format($formSubmissionCounts[\App\Models\FormSubmission::TYPE_ONLINE_COMPLAINT]) }}</span>
                        <span class="admin-dash__form-stat-label">{{ __('Online complaints') }}</span>
                    </div>
                </div>
                @if ($recentFormSubmissions->isEmpty())
                    <div class="admin-dash__empty-state">
                        <span class="dashicons dashicons-email-alt" aria-hidden="true"></span>
                        <p>{{ __('No form submissions recorded yet.') }}</p>
                    </div>
                @else
                    <ul class="admin-dash__feed">
                        @foreach ($recentFormSubmissions as $submission)
                            <li class="admin-dash__feed-item">
                                <span class="admin-dash__avatar" aria-hidden="true">{{ strtoupper(mb_substr($submission->name, 0, 1)) }}</span>
                                <div class="admin-dash__feed-main">
                                    <strong>{{ $submission->name }}</strong>
                                    <span class="admin-dash__feed-meta">
                                        @if ($submission->form_type === \App\Models\FormSubmission::TYPE_INQUIRY)
                                            {{ __('Inquiry') }}
                                        @elseif ($submission->form_type === \App\Models\FormSubmission::TYPE_COMPLAINT)
                                            {{ __('Complaint') }}
                                        @else
                                            {{ __('Online complaint') }}
                                        @endif
                                        · {{ $submission->email }}
                                    </span>
                                </div>
                                <time class="admin-dash__feed-time" datetime="{{ $submission->created_at->toIso8601String() }}">{{ $submission->created_at->format('d M, H:i') }}</time>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            <section class="admin-dash__panel admin-dash__reveal" data-reveal-index="8" aria-labelledby="dash-planner-heading">
                <header class="admin-dash__panel-head">
                    <h2 id="dash-planner-heading">{{ __('Planner leads') }}</h2>
                    <span class="admin-dash__panel-count">{{ number_format($plannerLeadCount) }}</span>
                </header>
                @if ($recentPlannerLeads->isEmpty())
                    <div class="admin-dash__empty-state">
                        <span class="dashicons dashicons-calendar-alt" aria-hidden="true"></span>
                        <p>{{ __('No Hajj or Umrah planner leads yet.') }}</p>
                    </div>
                @else
                    <ul class="admin-dash__feed">
                        @foreach ($recentPlannerLeads as $lead)
                            <li class="admin-dash__feed-item">
                                <span class="admin-dash__avatar admin-dash__avatar--planner" aria-hidden="true">{{ strtoupper(mb_substr($lead->name, 0, 1)) }}</span>
                                <div class="admin-dash__feed-main">
                                    <strong>{{ $lead->name }}</strong>
                                    <span class="admin-dash__feed-meta">
                                        <span class="admin-dash__tag">{{ ($lead->plan_type ?? 'hajj') === 'umrah' ? __('Umrah') : __('Hajj') }}</span>
                                        · Age {{ $lead->age }} · {{ $lead->hajj_year }} yr
                                    </span>
                                </div>
                                <time class="admin-dash__feed-time" datetime="{{ $lead->created_at->toIso8601String() }}">{{ $lead->created_at->format('d M, H:i') }}</time>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>

        <section class="admin-dash__grid" aria-label="{{ __('Shortcuts') }}">
            <article class="admin-dash__tile admin-dash__reveal" data-reveal-index="9">
                <div class="admin-dash__tile-icon" aria-hidden="true"><span class="dashicons dashicons-menu"></span></div>
                <h2>{{ __('Menu') }}</h2>
                <p>{{ __('Header links: internal routes, full URLs, or uploaded PDFs and notices.') }}</p>
                <div class="admin-dash__tile-actions">
                    <a class="admin-dash__cta admin-dash__cta--primary" href="{{ route('admin.navigation.index') }}">{{ __('Edit menu') }}</a>
                    <a class="admin-dash__cta admin-dash__cta--quiet" href="{{ route('admin.navigation.media.index') }}">{{ __('Menu files') }}</a>
                </div>
            </article>

            <article class="admin-dash__tile admin-dash__reveal" data-reveal-index="10">
                <div class="admin-dash__tile-icon" aria-hidden="true"><span class="dashicons dashicons-admin-page"></span></div>
                <h2>{{ __('Pages') }}</h2>
                <p>{{ __(':published published of :total — titles, SEO, sections, and downloads.', ['published' => number_format($publishedPageCount), 'total' => number_format($totalPageCount)]) }}</p>
                <div class="admin-dash__tile-actions">
                    <a class="admin-dash__cta admin-dash__cta--primary" href="{{ route('admin.pages.index') }}">{{ __('Browse pages') }}</a>
                    <a class="admin-dash__cta admin-dash__cta--quiet" href="{{ route('admin.pages.create') }}">{{ __('Add new') }}</a>
                </div>
            </article>

            <article class="admin-dash__tile admin-dash__reveal" data-reveal-index="11">
                <div class="admin-dash__tile-icon" aria-hidden="true"><span class="dashicons dashicons-chart-area"></span></div>
                <h2>{{ __('Daily fund prices') }}</h2>
                <p>{{ __(':count snapshots on record. The newest row drives the public table.', ['count' => number_format($snapshotCount)]) }}</p>
                <div class="admin-dash__tile-actions">
                    <a class="admin-dash__cta admin-dash__cta--primary" href="{{ route('admin.fund-snapshots.index') }}">{{ __('Manage snapshots') }}</a>
                </div>
            </article>

            <article class="admin-dash__tile admin-dash__reveal" data-reveal-index="12">
                <div class="admin-dash__tile-icon" aria-hidden="true"><span class="dashicons dashicons-media-spreadsheet"></span></div>
                <h2>{{ __('Hajj & Umrah planner data') }}</h2>
                <p>{{ __('Import Excel or CSV workbooks and export current planner tables.') }}</p>
                <div class="admin-dash__tile-actions">
                    <a class="admin-dash__cta admin-dash__cta--primary" href="{{ route('admin.financial-data.index') }}">{{ __('Import data') }}</a>
                    <a class="admin-dash__cta admin-dash__cta--quiet" href="{{ route('admin.financial-data.export', ['product' => 'hajj']) }}">{{ __('Export Hajj') }}</a>
                    <a class="admin-dash__cta admin-dash__cta--quiet" href="{{ route('admin.financial-data.export', ['product' => 'umrah']) }}">{{ __('Export Umrah') }}</a>
                </div>
            </article>

            <article class="admin-dash__tile admin-dash__reveal" data-reveal-index="13">
                <div class="admin-dash__tile-icon" aria-hidden="true"><span class="dashicons dashicons-email"></span></div>
                <h2>{{ __('Email & form notifications') }}</h2>
                <p>{{ __('SMTP settings, admin recipients, CC, and user confirmation emails for every website form and modal.') }}</p>
                <div class="admin-dash__tile-actions">
                    <a class="admin-dash__cta admin-dash__cta--primary" href="{{ route('admin.settings.email') }}">{{ __('Configure email') }}</a>
                </div>
            </article>

            <article class="admin-dash__tile admin-dash__reveal" data-reveal-index="15">
                <div class="admin-dash__tile-icon" aria-hidden="true"><span class="dashicons dashicons-translation"></span></div>
                <h2>{{ __('Urdu URLs') }}</h2>
                <p>{{ __('Prefix, system paths, and per-page Urdu slugs for the bilingual site.') }}</p>
                <div class="admin-dash__tile-actions">
                    <a class="admin-dash__cta admin-dash__cta--primary" href="{{ route('admin.settings.urdu') }}">{{ __('Manage Urdu URLs') }}</a>
                </div>
            </article>

            <article class="admin-dash__tile admin-dash__reveal" data-reveal-index="16">
                <div class="admin-dash__tile-icon" aria-hidden="true"><span class="dashicons dashicons-backup"></span></div>
                <h2>{{ __('Website backups') }}</h2>
                <p>{{ __(':count saved ZIP archives. Download database and assets before major changes.', ['count' => number_format($backupCount)]) }}</p>
                <div class="admin-dash__tile-actions">
                    <a class="admin-dash__cta admin-dash__cta--primary" href="{{ route('admin.backups.index') }}">{{ __('Manage backups') }}</a>
                </div>
            </article>

            @if ($fundManagersReportPage)
                <article class="admin-dash__tile admin-dash__reveal" data-reveal-index="17">
                    <div class="admin-dash__tile-icon" aria-hidden="true"><span class="dashicons dashicons-media-document"></span></div>
                    <h2>{{ __('Fund Manager’s Report') }}</h2>
                    <p>{{ __('Edit copy, meta, and extra blocks for the live report page.') }}</p>
                    <div class="admin-dash__tile-actions">
                        <a class="admin-dash__cta admin-dash__cta--primary" href="{{ route('admin.pages.edit', $fundManagersReportPage) }}">{{ __('Edit page') }}</a>
                        <a class="admin-dash__cta admin-dash__cta--quiet" href="{{ route('pages.show', ['slug' => $fundManagersReportPage->slug]) }}" target="_blank" rel="noopener">{{ __('Live view') }}</a>
                    </div>
                </article>
            @endif

            @if ($homePage)
                <article class="admin-dash__tile admin-dash__reveal" data-reveal-index="18">
                    <div class="admin-dash__tile-icon" aria-hidden="true"><span class="dashicons dashicons-admin-home"></span></div>
                    <h2>{{ __('Homepage') }}</h2>
                    <p>{{ __('Edit popup, hero slider, about banner, mission cards, and value chain from the CMS.') }}</p>
                    <div class="admin-dash__tile-actions">
                        <a class="admin-dash__cta admin-dash__cta--primary" href="{{ route('admin.pages.edit', $homePage) }}">{{ __('Edit homepage') }}</a>
                        <a class="admin-dash__cta admin-dash__cta--quiet" href="{{ route('home') }}" target="_blank" rel="noopener">{{ __('Live view') }}</a>
                    </div>
                </article>
            @endif

            @if ($legacyPageCount > 0)
                <article class="admin-dash__tile admin-dash__reveal" data-reveal-index="19">
                    <div class="admin-dash__tile-icon" aria-hidden="true"><span class="dashicons dashicons-trash"></span></div>
                    <h2>{{ __('Legacy placeholder pages') }}</h2>
                    <p>{{ __(':count published WordPress demo pages can be unpublished in one step.', ['count' => number_format($legacyPageCount)]) }}</p>
                    <div class="admin-dash__tile-actions">
                        <form method="post" action="{{ route('admin.pages.purge-legacy') }}" onsubmit='return confirm({{ json_encode(__('Unpublish all legacy placeholder pages? They will be set to draft.')) }});'>
                            @csrf
                            <button type="submit" class="admin-dash__cta admin-dash__cta--primary">{{ __('Unpublish legacy pages') }}</button>
                        </form>
                    </div>
                </article>
            @endif
        </section>
    </div>
@endsection
