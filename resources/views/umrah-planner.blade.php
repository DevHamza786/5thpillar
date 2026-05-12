@extends('layouts.app')

@section('title', __('Umrah Planner - 5th Pillar Family Takaful'))

@php
    $hpHomeRoute = app()->getLocale() === 'ur' ? 'urdu.home' : 'home';
    $hpCalcRoute = app()->getLocale() === 'ur' ? 'urdu.umrah-planner.calculate' : 'umrah-planner.calculate';
    $hpHajjPlannerRoute = app()->getLocale() === 'ur' ? 'urdu.hajj-planner.index' : 'hajj-planner.index';
@endphp

@section('body_class', 'laravel-inner-page laravel-hajj-planner-page laravel-umrah-planner-page')

@push('head')
    @php
        $__upCss = public_path('assets/css/pages/umrah-planner.css');
        $__upCssVer = is_file($__upCss) ? (string) filemtime($__upCss) : '1';
    @endphp
    <link rel="stylesheet" href="{{ asset('assets/css/pages/umrah-planner.css') }}?v={{ $__upCssVer }}">
@endpush

@section('header_masthead')
    <div
        class="laravel-inner-masthead laravel-hajj-planner-masthead"
        role="region"
        aria-labelledby="umrah-planner-masthead-title"
    >
        <div class="laravel-inner-masthead__overlay laravel-hajj-planner-masthead__overlay" aria-hidden="true"></div>
        <div class="content_wrap laravel-inner-masthead__inner laravel-hajj-planner-masthead__inner">
            <h1 id="umrah-planner-masthead-title" class="laravel-inner-masthead__title laravel-hajj-planner-masthead__title">{{ __('Umrah Planner') }}</h1>
            <nav class="laravel-inner-breadcrumbs laravel-hajj-planner-masthead__breadcrumbs" aria-label="{{ __('Breadcrumb') }}">
                <a href="{{ route($hpHomeRoute) }}" class="laravel-inner-breadcrumbs__link laravel-hajj-planner-masthead__crumb-link">{{ __('Home') }}</a>
                <span class="laravel-inner-breadcrumbs__sep laravel-hajj-planner-masthead__crumb-sep" aria-hidden="true">/</span>
                <span class="laravel-inner-breadcrumbs__current laravel-hajj-planner-masthead__crumb-current">{{ __('Umrah Planner') }}</span>
            </nav>
        </div>
    </div>
@endsection

@section('page_content')
<div class="hajj-planner-container hp-page">
    <div class="content_wrap hp-page__inner">
        <div id="planner-form-wrapper" class="hp-shell">
            <p class="hp-intro">
                {{ __('The Umrah Planner helps you estimate Saadat Umrah savings and projected fund growth so you can plan your journey with clarity and peace of mind.') }}
            </p>

            <form id="umrah-planner-form" class="hp-form" autocomplete="on">
                @csrf
                <div class="hp-field">
                    <label class="hp-label" for="name">{{ __('Your name') }}*</label>
                    <input type="text" name="name" id="name" required class="hp-input" placeholder="{{ __('Your name') }}*">
                </div>
                <div class="hp-field">
                    <label class="hp-label" for="email">{{ __('Your e-mail') }}*</label>
                    <input type="email" name="email" id="email" required class="hp-input" placeholder="{{ __('Your e-mail') }}*">
                </div>
                <div class="hp-field">
                    <label class="hp-label" for="phone">{{ __('Your phone') }}*</label>
                    <input type="text" name="phone" id="phone" required class="hp-input" placeholder="{{ __('Your phone') }}*">
                </div>
                <div class="hp-field">
                    <label class="hp-label" for="city">{{ __('Your City') }}*</label>
                    <div class="hp-select-wrap">
                        <select name="city" id="city" required class="hp-input hp-input--select" aria-label="{{ __('Your City') }}">
                            <option value="" disabled selected>{{ __('Select city') }}</option>
                            @foreach ($plannerCities as $cityOption)
                                <option value="{{ $cityOption }}">{{ $cityOption }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="hp-field">
                    <label class="hp-label" for="plan-select">{{ __('Select Plan') }}</label>
                    <div class="hp-select-wrap">
                        <select id="plan-select" class="hp-input hp-input--select" aria-label="{{ __('Select Plan') }}">
                            <option value="umrah" selected>{{ __('Umrah Savings Plan') }}</option>
                            <option value="hajj">{{ __('Hajj Savings Plan') }}</option>
                        </select>
                    </div>
                </div>

                <div class="hp-slider-block">
                    <div class="hp-slider-head">
                        <label class="hp-slider-label" for="age-slider">{{ __('How old are you?') }}</label>
                        <span id="age-display" class="hp-value-pill" aria-live="polite">18 {{ __('Years') }}</span>
                    </div>
                    <input type="range" name="age" id="age-slider" min="18" max="55" value="18" class="hp-range">
                    <div class="hp-range-ticks">
                        <span>18</span>
                        <span>55</span>
                    </div>
                </div>

                <div class="hp-slider-block">
                    <div class="hp-slider-head">
                        <label class="hp-label" for="plan-term">{{ __('When do you want to go for Umrah?') }}</label>
                        <span id="term-display" class="hp-value-pill" aria-live="polite">10 {{ __('Years') }}</span>
                    </div>
                    <div class="hp-select-wrap">
                        <select name="plan_term" id="plan-term" class="hp-input hp-input--select" aria-label="{{ __('Plan term in years') }}">
                            <option value="5">5 {{ __('Years') }}</option>
                            <option value="7">7 {{ __('Years') }}</option>
                            <option value="10" selected>10 {{ __('Years') }}</option>
                            <option value="15">15 {{ __('Years') }}</option>
                        </select>
                    </div>
                </div>

                <div class="hp-actions">
                    <button type="submit" id="submit-btn" class="hp-submit">
                        <span id="btn-text">{{ __('Calculate My Umrah Plan') }}</span>
                        <span id="btn-loader" class="hp-submit__loader hidden" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Results — layout aligned with design ref (congrats + two panels + chart in second panel) --}}
        <div id="planner-results" class="hp-results hp-res hidden animate-fade-in">
            <div class="hp-res__layout">
                <header class="hp-res__header">
                    <h2 class="hp-res__congrats">{{ __('Congratulations!') }}</h2>
                    <p class="hp-res__sub">{{ __('You are one step closer to your journey.') }}</p>
                </header>

                <section class="hp-res__panel">
                    <h3 class="hp-res__h3">{{ __('Your Umrah Plan') }}</h3>
                    <dl class="hp-res__kv">
                        <div class="hp-res__kv-row">
                            <dt>{{ __('Age') }}</dt>
                            <dd><span id="res-age">0</span> {{ __('Years') }}</dd>
                        </div>
                        <div class="hp-res__kv-row">
                            <dt>{{ __('Term') }}</dt>
                            <dd><span id="res-term">0</span> {{ __('Years') }}</dd>
                        </div>
                        <div class="hp-res__kv-row">
                            <dt>{{ __('Annual Contribution') }}</dt>
                            <dd><span class="hp-res__rs">{{ __('Rs.') }}</span> <span id="res-annual">0</span></dd>
                        </div>
                        <div class="hp-res__kv-row">
                            <dt>{{ __('Takaful Benefit') }}</dt>
                            <dd><span class="hp-res__rs">{{ __('Rs.') }}</span> <span id="res-benefit">0</span></dd>
                        </div>
                    </dl>
                </section>

                <section class="hp-res__panel hp-res__panel--stack">
                    <p class="hp-res__est-lead">
                        {{ __('Estimated cost of Umrah after') }}
                        <span id="res-hero-term">0</span>
                        {{ __('years') }}
                    </p>
                    <div class="hp-res__est-rule" aria-hidden="true"></div>
                    <p class="hp-res__hero-pkr">PKR <span id="res-hero-amount">0</span></p>

                    <dl class="hp-res__kv hp-res__kv--wide">
                        <div class="hp-res__kv-row">
                            <dt>{{ __('Total Contribution') }}</dt>
                            <dd>PKR <span id="res-total">0</span></dd>
                        </div>
                        <div class="hp-res__kv-row">
                            <dt>{{ __('Estimated rate of return 9%') }}</dt>
                            <dd>PKR <span id="res-return-9">0</span></dd>
                        </div>
                        <div class="hp-res__kv-row">
                            <dt>{{ __('Estimated rate of return 13%') }}</dt>
                            <dd>PKR <span id="res-return-13">0</span></dd>
                        </div>
                    </dl>

                    <div class="hp-res__chart-panel">
                        <h3 class="hp-res__h3 hp-res__h3--chart">{{ __('Projected Fund Growth') }}</h3>
                        <div class="hp-res__chart-box">
                            <div class="hp-res__chart-wrap hp-res__chart-wrap--line">
                                <canvas id="umrahLineChart" aria-label="{{ __('Projected fund growth chart') }}"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="hp-res__pie-section">
                        <h3 class="hp-res__h3 hp-res__h3--gain">{{ __('Contribution and Gain') }}</h3>
                        <div class="hp-res__pie-box">
                            <div class="hp-res__pie-col">
                                <div class="hp-res__donut-wrap">
                                    <canvas id="umrahDoughnut9" aria-label="{{ __('Contribution vs gain at 9%') }}"></canvas>
                                    <div class="hp-res__donut-center" aria-hidden="true">
                                        <span class="hp-res__donut-center-line1">{{ __('Rate of return') }}</span>
                                        <span class="hp-res__donut-center-line2">9%</span>
                                    </div>
                                </div>
                                <div id="doughnut9-legend" class="hp-res__pie-legend"></div>
                            </div>
                            <div class="hp-res__pie-col">
                                <div class="hp-res__donut-wrap">
                                    <canvas id="umrahDoughnut13" aria-label="{{ __('Contribution vs gain at 13%') }}"></canvas>
                                    <div class="hp-res__donut-center" aria-hidden="true">
                                        <span class="hp-res__donut-center-line1">{{ __('Rate of return') }}</span>
                                        <span class="hp-res__donut-center-line2">13%</span>
                                    </div>
                                </div>
                                <div id="doughnut13-legend" class="hp-res__pie-legend"></div>
                            </div>
                        </div>
                    </div>
                </section>

                <footer class="hp-res__footer">
                    <p class="hp-res__disclaimer">
                        {{ __('Disclaimer: All plan values are estimated and may change according to various factors at the time of actual purchase.') }}
                    </p>
                    <button type="button" id="replan-btn" class="hp-res__replan">{{ __('Replan') }}</button>
                </footer>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    @php
        $__umJs = public_path('assets/js/pages/planner-umrah.js');
        $__umJsVer = is_file($__umJs) ? (string) filemtime($__umJs) : '1';
    @endphp
    <script type="application/json" id="planner-page-config">{!! json_encode([
        'calculateUrl' => route($hpCalcRoute),
        'hajjPlannerUrl' => route($hpHajjPlannerRoute),
        'yearsLabel' => __('Years'),
        'msgProcessing' => __('Processing...'),
        'msgCalculate' => __('Calculate My Umrah Plan'),
        'msgErrorGeneric' => __('An error occurred during calculation.'),
        'msgErrorNetwork' => __('Failed to connect to server.'),
        'chartLegendLines' => [__('Total Contribution'), __('Estimated Return 9%'), __('Estimated Return 13%')],
        'lblContribution' => __('Contribution'),
        'lblGain' => __('Gain'),
        'lblRs' => __('Rs.'),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('assets/js/pages/planner-umrah.js') }}?v={{ $__umJsVer }}" defer></script>
@endpush
@endpush
