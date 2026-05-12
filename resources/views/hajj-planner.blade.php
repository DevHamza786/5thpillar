@extends('layouts.app')

@section('title', ($initialProduct ?? 'hajj') === 'umrah' ? __('Umrah Planner - 5th Pillar Family Takaful') : __('Hajj Planner - 5th Pillar Family Takaful'))

@php
    $hpHomeRoute = app()->getLocale() === 'ur' ? 'urdu.home' : 'home';
    $hpCalcRouteHajj = app()->getLocale() === 'ur' ? 'urdu.hajj-planner.calculate' : 'hajj-planner.calculate';
    $hpCalcRouteUmrah = app()->getLocale() === 'ur' ? 'urdu.umrah-planner.calculate' : 'umrah-planner.calculate';
    $initialProduct = $initialProduct ?? 'hajj';
@endphp

@section('body_class', 'laravel-inner-page laravel-hajj-planner-page')

@push('head')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&display=swap">
@endpush

@section('header_masthead')
    <div
        class="laravel-inner-masthead laravel-hajj-planner-masthead"
        role="region"
        aria-labelledby="planner-masthead-title"
        style="--laravel-inner-masthead-bg: url('{{ asset('uploads/2023/2023/05/Hajj-Planner-Banner.webp') }}');"
    >
        <div class="laravel-inner-masthead__overlay laravel-hajj-planner-masthead__overlay" aria-hidden="true"></div>
        <div class="content_wrap laravel-inner-masthead__inner laravel-hajj-planner-masthead__inner">
            <h1 id="planner-masthead-title" class="laravel-inner-masthead__title laravel-hajj-planner-masthead__title">{{ $initialProduct === 'umrah' ? __('Umrah Planner') : __('Hajj Planner') }}</h1>
            <nav class="laravel-inner-breadcrumbs laravel-hajj-planner-masthead__breadcrumbs" aria-label="{{ __('Breadcrumb') }}">
                <a href="{{ route($hpHomeRoute) }}" class="laravel-inner-breadcrumbs__link laravel-hajj-planner-masthead__crumb-link">{{ __('Home') }}</a>
                <span class="laravel-inner-breadcrumbs__sep laravel-hajj-planner-masthead__crumb-sep" aria-hidden="true">/</span>
                <span id="planner-breadcrumb-current" class="laravel-inner-breadcrumbs__current laravel-hajj-planner-masthead__crumb-current">{{ $initialProduct === 'umrah' ? __('Umrah Planner') : __('Hajj Planner') }}</span>
            </nav>
        </div>
    </div>
@endsection

@section('page_content')
<div class="hajj-planner-container hp-page">
    <div class="content_wrap hp-page__inner">
        <div id="planner-form-wrapper" class="hp-shell">
            <p id="hp-intro-hajj" class="hp-intro {{ $initialProduct === 'umrah' ? 'hp-hidden' : '' }}">
                {{ __('The Hajj Planner simplifies your journey by calculating your savings so that you can easily manage your finances, ensuring a well-planned, stress-free, and spiritually enriching Hajj.') }}
            </p>
            <p id="hp-intro-umrah" class="hp-intro {{ $initialProduct === 'hajj' ? 'hp-hidden' : '' }}">
                {{ __('The Umrah Planner helps you estimate Saadat Umrah savings and projected fund growth so you can plan your journey with clarity and peace of mind.') }}
            </p>

            <form id="hajj-planner-form" class="hp-form" autocomplete="on">
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
                            <option value="hajj" @selected($initialProduct === 'hajj')>{{ __('Hajj Savings Plan') }}</option>
                            <option value="umrah" @selected($initialProduct === 'umrah')>{{ __('Umrah Savings Plan') }}</option>
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

                <div id="term-block-hajj" class="hp-slider-block {{ $initialProduct === 'umrah' ? 'hp-hidden' : '' }}">
                    <div class="hp-slider-head">
                        <label class="hp-slider-label" for="term-slider">{{ __('When do you want to go for Hajj?') }}</label>
                        <span id="term-display-hajj" class="hp-value-pill" aria-live="polite">10 {{ __('Years') }}</span>
                    </div>
                    <input type="range" name="hajj_year" id="term-slider" min="10" max="25" step="5" value="10" class="hp-range" @disabled($initialProduct === 'umrah')>
                    <div class="hp-range-ticks hp-range-ticks--quad">
                        <span>10</span>
                        <span>15</span>
                        <span>20</span>
                        <span>25</span>
                    </div>
                </div>

                <div id="term-block-umrah" class="hp-slider-block {{ $initialProduct === 'hajj' ? 'hp-hidden' : '' }}">
                    <div class="hp-slider-head">
                        <label class="hp-slider-label" for="umrah-term-slider">{{ __('When do you want to go for Umrah?') }}</label>
                        <span id="term-display-umrah" class="hp-value-pill" aria-live="polite">10 {{ __('Years') }}</span>
                    </div>
                    <input type="range" id="umrah-term-slider" min="0" max="3" step="1" value="2" class="hp-range" aria-valuetext="10 {{ __('Years') }}" @disabled($initialProduct === 'hajj')>
                    <input type="hidden" name="plan_term" id="plan-term-value" value="10" @disabled($initialProduct === 'hajj')>
                    <div class="hp-range-ticks hp-range-ticks--umrah">
                        <span>5</span>
                        <span>7</span>
                        <span>10</span>
                        <span>15</span>
                    </div>
                </div>

                <div class="hp-actions">
                    <button type="submit" id="submit-btn" class="hp-submit">
                        <span id="btn-text">{{ $initialProduct === 'umrah' ? __('Calculate Umrah Plan') : __('Calculate My Hajj Plan') }}</span>
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
                    <h3 id="res-plan-heading" class="hp-res__h3">{{ $initialProduct === 'umrah' ? __('Your Umrah Plan') : __('Your Hajj Plan') }}</h3>
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
                        <span id="res-est-lead-start">{{ $initialProduct === 'umrah' ? __('Estimated cost of Umrah after') : __('Estimated cost of Hajj after') }}</span>
                        <span id="res-hero-term">0</span>
                        <span id="res-est-lead-end">{{ __('years') }}</span>
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
                                <canvas id="hajjLineChart" aria-label="{{ __('Projected fund growth chart') }}"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="hp-res__pie-section">
                        <h3 class="hp-res__h3 hp-res__h3--gain">{{ __('Contribution and Gain') }}</h3>
                        <div class="hp-res__pie-box">
                            <div class="hp-res__pie-col">
                                <div class="hp-res__donut-wrap">
                                    <canvas id="hajjDoughnut9" aria-label="{{ __('Contribution vs gain at 9%') }}"></canvas>
                                    <div class="hp-res__donut-center" aria-hidden="true">
                                        <span class="hp-res__donut-center-line1">{{ __('Rate of return') }}</span>
                                        <span class="hp-res__donut-center-line2">9%</span>
                                    </div>
                                </div>
                                <div id="doughnut9-legend" class="hp-res__pie-legend"></div>
                            </div>
                            <div class="hp-res__pie-col">
                                <div class="hp-res__donut-wrap">
                                    <canvas id="hajjDoughnut13" aria-label="{{ __('Contribution vs gain at 13%') }}"></canvas>
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

<style>

    .hp-page {
        --hp-olive: #5c4f3e;
        --hp-olive-mid: #6d5e4c;
        --hp-cream: #f4f0e6;
        --hp-cream-field: #faf7f0;
        --hp-border: #8a7a66;
        --hp-muted: #6d5e4c;
    }

    .hp-hidden {
        display: none !important;
    }

    .hp-page__inner {
        width: 100%;
    }

    .hp-shell {
        width: 1170px;
        margin: 0 auto;
    }

    .hp-results {
        margin-top: 2.5rem;
    }

    @media (min-width: 640px) {
        .hp-shell {
            padding: 6.25rem 2.5rem 2.5rem;
        }
    }

    .hp-intro {
        text-align: left;
        font-size: 18px;
        font-family: 'raleway';
        letter-spacing: 1.1px;
        color: #9d9087;
    }

    .hp-form {
        display: flex;
        flex-direction: column;
        gap: 1.15rem;
    }

    .hp-field {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .hp-label {
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--hp-muted);
    }

    .hp-input {
        width: 100%;
        box-sizing: border-box;
        padding: 0.72rem 0.95rem;
        border: 1px solid var(--hp-border);
        border-radius: 10px;
        background: var(--hp-cream-field);
        color: var(--hp-olive);
        font-size: 0.95rem;
        font-family: inherit;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .hp-input::placeholder {
        color: rgba(92, 79, 62, 0.55);
    }

    .hp-input:focus {
        outline: none;
        border-color: var(--hp-olive-mid);
        box-shadow: 0 0 0 2px rgba(92, 79, 62, 0.12);
    }

    .hp-input--textarea {
        min-height: 120px;
        resize: vertical;
    }

    .hp-select-wrap {
        position: relative;
    }

    .hp-input--select {
        appearance: none;
        padding-right: 2.5rem;
        cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%235c4f3e' d='M1 1.5L6 6l5-4.5'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
    }

    .hp-slider-block {
        margin-top: 0.35rem;
        padding-top: 1.1rem;
        border-top: 1px solid rgba(92, 79, 62, 0.12);
    }

    .hp-slider-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 0.65rem;
    }

    .hp-slider-label {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--hp-olive);
        line-height: 1.35;
    }

    .hp-value-pill {
        flex-shrink: 0;
        min-width: 5.5rem;
        text-align: center;
        padding: 0.35rem 0.65rem;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--hp-olive);
        background: #e8e3d8;
        border: 1px solid rgba(92, 79, 62, 0.25);
        border-radius: 8px;
    }

    .hp-range {
        width: 100%;
        height: 6px;
        border-radius: 6px;
        background: rgba(92, 79, 62, 0.15);
        accent-color: #b4a55c;
        cursor: pointer;
    }

    .hp-range:disabled {
        cursor: not-allowed;
    }

    .hp-range--dimmed {
        opacity: 0.5;
    }

    .hp-range-ticks {
        display: flex;
        justify-content: space-between;
        margin-top: 0.45rem;
        color: rgba(92, 79, 62, 0.65);
    }

    .hp-range-ticks--quad span {
        text-align: center;
    }

    .hp-range-ticks--quad span:first-child {
        text-align: left;
    }

    .hp-range-ticks--quad span:last-child {
        text-align: right;
    }

    .hp-range-ticks--umrah span {
        text-align: center;
    }

    .hp-range-ticks--umrah span:first-child {
        text-align: left;
    }

    .hp-range-ticks--umrah span:last-child {
        text-align: right;
    }

    .hp-actions {
        margin-top: 0.5rem;
        padding-top: 0.25rem;
    }

    .hp-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.65rem;
        min-width: 11rem;
        padding: 0.85rem 1.75rem;
        border: none;
        border-radius: 10px;
        background: var(--hp-olive);
        color: #fff;
        font-size: 0.95rem;
        font-weight: 700;
        font-family: inherit;
        cursor: pointer;
        transition: background 0.2s ease, transform 0.15s ease;
    }

    .hp-submit:disabled {
        opacity: 0.75;
        cursor: wait;
    }

    .hp-submit__loader {
        width: 1.1rem;
        height: 1.1rem;
        border: 2px solid rgba(255, 255, 255, 0.35);
        border-top-color: #fff;
        border-radius: 50%;
        animation: hp-spin 0.7s linear infinite;
    }

    @keyframes hp-spin {
        to { transform: rotate(360deg); }
    }

    /* ——— Results (second reference: white panels, gold serif headings, chart legend bottom) ——— */
    .hp-res {
        --hp-res-gold: #b08d57;
        --hp-res-gold-dark: #926f42;
        --hp-res-ink: #1a1a1a;
        --hp-res-muted: #6b6b6b;
        --hp-res-brown-label: #7d6b58;
        --hp-res-panel-border: #e3ded6;
    }

    .hp-res__layout {
        max-width: 1170px;
        margin: 0 auto;
        padding: 0 0 1rem;
    }

    .hp-res__header {
        text-align: left;
        margin-bottom: 2rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid #e5e0d8;
    }

    .hp-res__congrats {
        font-family: 'Cormorant Garamond', 'Times New Roman', serif;
        font-size: clamp(2.1rem, 4.2vw, 2.85rem);
        font-weight: 700;
        color: var(--hp-res-gold);
        margin: 0 0 0.4rem;
        line-height: 1.12;
    }

    .hp-res__sub {
        margin: 0;
        font-size: 0.95rem;
        color: var(--hp-res-muted);
        font-family: 'Raleway', sans-serif;
        font-weight: 400;
    }

    .hp-res__panel {
        background: #ffffff;
        border: 1px solid var(--hp-res-panel-border);
        border-radius: 8px;
        padding: 1.5rem 1.35rem 1.65rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 0 rgba(0, 0, 0, 0.03);
    }

    @media (min-width: 640px) {
        .hp-res__panel {
            padding: 1.75rem 2rem 2rem;
        }
    }

    .hp-res__panel--stack {
        padding-bottom: 1.5rem;
    }

    .hp-res__h3 {
        font-family: 'Cormorant Garamond', 'Times New Roman', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--hp-res-gold);
        margin: 0 0 1.1rem;
        text-align: left;
    }

    .hp-res__h3--chart {
        text-align: center;
        margin: 0 0 1rem;
        font-size: 1.45rem;
    }

    .hp-res__est-lead {
        margin: 0 0 0.65rem;
        font-family: 'Raleway', sans-serif;
        font-size: 0.98rem;
        font-weight: 600;
        color: var(--hp-res-gold);
        letter-spacing: 0.02em;
    }

    .hp-res__est-rule {
        height: 0;
        border: none;
        border-bottom: 3px solid #1a1a1a;
        margin: 0 0 1rem;
        max-width: 100%;
    }

    .hp-res__hero-pkr {
        font-family: 'Cormorant Garamond', 'Times New Roman', serif;
        font-size: clamp(2.5rem, 6vw, 3.75rem);
        font-weight: 700;
        color: var(--hp-res-gold);
        margin: 0 0 1.35rem;
        line-height: 1.05;
        letter-spacing: 0.02em;
    }

    .hp-res__kv {
        margin: 0;
        max-width: 100%;
    }

    .hp-res__kv--wide {
        max-width: 100%;
        margin-bottom: 2rem;
    }

    .hp-res__kv-row {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        gap: 1rem;
        padding: 0.62rem 0;
        border-bottom: 1px solid #ebe7e0;
        font-family: 'Raleway', sans-serif;
        font-size: 0.97rem;
    }

    .hp-res__kv-row:last-child {
        border-bottom: none;
    }

    .hp-res__kv-row dt {
        color: var(--hp-res-brown-label);
        font-weight: 500;
    }

    .hp-res__kv-row dd {
        margin: 0;
        font-weight: 700;
        color: var(--hp-res-ink);
        text-align: right;
    }

    .hp-res__rs {
        font-weight: 500;
        color: var(--hp-res-muted);
    }

    .hp-res__chart-panel {
        margin-top: 0.25rem;
        padding-top: 1.5rem;
        border-top: 1px solid #ebe7e0;
    }

    .hp-res__chart-box {
        border: 1px solid #dcd6cc;
        border-radius: 6px;
        background: #fdfcfa;
        padding: 1rem 0.75rem 0.5rem;
    }

    @media (min-width: 640px) {
        .hp-res__chart-box {
            padding: 1.25rem 1.25rem 0.65rem;
        }
    }

    .hp-res__chart-wrap {
        position: relative;
        width: 100%;
    }

    .hp-res__chart-wrap--line {
        height: 360px;
    }

    .hp-res__h3--gain {
        margin-top: 0;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #1a1a1a;
        color: var(--hp-res-ink);
        font-family: 'Raleway', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
    }

    .hp-res__pie-section {
        margin-top: 2rem;
        padding-top: 1.75rem;
        border-top: 1px solid #ebe7e0;
    }

    .hp-res__pie-box {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
        border: 1px solid #dcd6cc;
        border-radius: 6px;
        background: #fdfcfa;
        padding: 1.5rem 1rem 1.75rem;
    }

    @media (min-width: 720px) {
        .hp-res__pie-box {
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem 2rem;
            padding: 1.75rem 1.5rem 2rem;
        }
    }

    .hp-res__pie-col {
        display: flex;
        flex-direction: column;
        align-items: stretch;
    }

    .hp-res__donut-wrap {
        position: relative;
        width: 100%;
        max-width: 280px;
        height: 240px;
        margin: 0 auto;
    }

    .hp-res__donut-center {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -52%);
        text-align: center;
        pointer-events: none;
        max-width: 42%;
    }

    .hp-res__donut-center-line1 {
        display: block;
        font-family: 'Raleway', sans-serif;
        font-size: 0.68rem;
        font-weight: 600;
        color: #555555;
        line-height: 1.2;
        text-transform: capitalize;
    }

    .hp-res__donut-center-line2 {
        display: block;
        font-family: 'Cormorant Garamond', 'Times New Roman', serif;
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--hp-res-gold);
        line-height: 1.1;
        margin-top: 0.15rem;
    }

    .hp-res__pie-legend {
        margin-top: 1.1rem;
        font-family: 'Raleway', sans-serif;
        font-size: 0.82rem;
        color: var(--hp-res-ink);
        line-height: 1.65;
        padding: 0 0.25rem;
    }

    .hp-res__lg-row {
        display: flex;
        align-items: flex-start;
        gap: 0.45rem;
        margin-bottom: 0.4rem;
    }

    .hp-res__lg-row:last-child {
        margin-bottom: 0;
    }

    .hp-res__lg-swatch {
        width: 12px;
        height: 12px;
        border-radius: 2px;
        flex-shrink: 0;
        margin-top: 0.2rem;
    }

    .hp-res__lg-swatch--contrib {
        background: #d4d0c8;
    }

    .hp-res__lg-swatch--gain {
        background: var(--hp-res-gold);
    }

    .hp-res__footer {
        margin-top: 2rem;
        padding-top: 1.35rem;
        border-top: 1px solid #e5e0d8;
    }

    .hp-res__disclaimer {
        font-family: 'Raleway', sans-serif;
        font-size: 0.78rem;
        color: var(--hp-res-muted);
        line-height: 1.55;
        margin: 0 0 1.15rem;
        max-width: 52rem;
    }

    .hp-res__replan {
        display: inline-block;
        font-family: 'Raleway', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        color: #fff;
        background: var(--hp-res-gold);
        border: none;
        border-radius: 4px;
        padding: 0.75rem 2.25rem;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .hp-res__replan:hover {
        background: var(--hp-res-gold-dark);
    }

    [dir="rtl"] .hp-res__kv-row {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .hp-res__kv-row dd {
        text-align: left;
    }

    [dir="rtl"] .hp-res__congrats,
    [dir="rtl"] .hp-res__h3,
    [dir="rtl"] .hp-res__hero-pkr {
        font-family: 'Noto Sans Urdu', 'Cormorant Garamond', serif;
    }

    .accent-gold { accent-color: #B39246; }
    .text-gold { color: #B39246; }
    .bg-gold { background-color: #B39246; }
    .border-gold { border-color: #B39246; }

    @keyframes fade-in {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in { animation: fade-in 0.55s ease-out forwards; }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calculateUrlHajj = @json(route($hpCalcRouteHajj));
    const calculateUrlUmrah = @json(route($hpCalcRouteUmrah));
    const initialProduct = @json($initialProduct);
    const pageTitleHajj = @json(__('Hajj Planner - 5th Pillar Family Takaful'));
    const pageTitleUmrah = @json(__('Umrah Planner - 5th Pillar Family Takaful'));
    const txtMastheadHajj = @json(__('Hajj Planner'));
    const txtMastheadUmrah = @json(__('Umrah Planner'));
    const txtYourHajjPlan = @json(__('Your Hajj Plan'));
    const txtYourUmrahPlan = @json(__('Your Umrah Plan'));
    const txtEstHajj = @json(__('Estimated cost of Hajj after'));
    const txtEstUmrah = @json(__('Estimated cost of Umrah after'));

    const yearsLabel = @json(__('Years'));
    const msgProcessing = @json(__('Processing...'));
    const msgCalculateHajj = @json(__('Calculate My Hajj Plan'));
    const msgCalculateUmrah = @json(__('Calculate Umrah Plan'));
    const msgErrorGeneric = @json(__('An error occurred during calculation.'));
    const msgErrorNetwork = @json(__('Failed to connect to server.'));
    const chartLegendLines = [
        @json(__('Total Contribution')),
        @json(__('Estimated Return 9%')),
        @json(__('Estimated Return 13%')),
    ];
    const lblContribution = @json(__('Contribution'));
    const lblGain = @json(__('Gain'));
    const lblRs = @json(__('Rs.'));

    const form = document.getElementById('hajj-planner-form');
    const ageSlider = document.getElementById('age-slider');
    const ageDisplay = document.getElementById('age-display');
    const termSlider = document.getElementById('term-slider');
    const termDisplayHajj = document.getElementById('term-display-hajj');
    const termBlockHajj = document.getElementById('term-block-hajj');
    const termBlockUmrah = document.getElementById('term-block-umrah');
    const umrahTermSlider = document.getElementById('umrah-term-slider');
    const planTermHidden = document.getElementById('plan-term-value');
    const termDisplayUmrah = document.getElementById('term-display-umrah');
    const mastheadTitle = document.getElementById('planner-masthead-title');
    const breadcrumbCurrent = document.getElementById('planner-breadcrumb-current');
    const introHajj = document.getElementById('hp-intro-hajj');
    const introUmrah = document.getElementById('hp-intro-umrah');
    const submitBtn = document.getElementById('submit-btn');
    const btnText = document.getElementById('btn-text');
    const btnLoader = document.getElementById('btn-loader');

    const formWrapper = document.getElementById('planner-form-wrapper');
    const resultsWrapper = document.getElementById('planner-results');
    const replanBtn = document.getElementById('replan-btn');

    const planSelect = document.getElementById('plan-select');

    const UMRAH_TERM_YEARS = [5, 7, 10, 15];

    let chartLine = null;
    let chartDonut9 = null;
    let chartDonut13 = null;

    function getProduct() {
        return planSelect.value === 'umrah' ? 'umrah' : 'hajj';
    }

    function calculateLabel() {
        return getProduct() === 'umrah' ? msgCalculateUmrah : msgCalculateHajj;
    }

    function applyPlan(product) {
        const isUmrah = product === 'umrah';
        mastheadTitle.textContent = isUmrah ? txtMastheadUmrah : txtMastheadHajj;
        breadcrumbCurrent.textContent = isUmrah ? txtMastheadUmrah : txtMastheadHajj;
        document.title = isUmrah ? pageTitleUmrah : pageTitleHajj;

        introHajj.classList.toggle('hp-hidden', isUmrah);
        introUmrah.classList.toggle('hp-hidden', !isUmrah);

        termBlockHajj.classList.toggle('hp-hidden', isUmrah);
        termBlockUmrah.classList.toggle('hp-hidden', !isUmrah);

        termSlider.disabled = isUmrah;
        umrahTermSlider.disabled = !isUmrah;
        planTermHidden.disabled = !isUmrah;

        syncAgeTermRules();
        if (window.history && window.history.replaceState) {
            const path = window.location.pathname;
            window.history.replaceState({}, '', isUmrah ? (path + '?plan=umrah') : path);
        }

        btnText.textContent = calculateLabel();
    }

    function syncUmrahTermDisplay() {
        const i = parseInt(umrahTermSlider.value, 10);
        const y = UMRAH_TERM_YEARS[Math.max(0, Math.min(3, i))];
        planTermHidden.value = String(y);
        termDisplayUmrah.textContent = y + ' ' + yearsLabel;
        umrahTermSlider.setAttribute('aria-valuetext', y + ' ' + yearsLabel);
    }

    function syncAgeTermRules() {
        const age = parseInt(ageSlider.value, 10);
        ageDisplay.textContent = age + ' ' + yearsLabel;

        if (getProduct() === 'hajj') {
            if (age === 55) {
                termSlider.value = '10';
                termSlider.disabled = true;
                termDisplayHajj.textContent = '10 ' + yearsLabel;
                termSlider.classList.add('hp-range--dimmed');
            } else {
                termSlider.disabled = false;
                termSlider.classList.remove('hp-range--dimmed');
                termDisplayHajj.textContent = termSlider.value + ' ' + yearsLabel;
            }
        } else {
            if (age === 55) {
                umrahTermSlider.value = '0';
                planTermHidden.value = '5';
                umrahTermSlider.disabled = true;
                termDisplayUmrah.textContent = '5 ' + yearsLabel;
                umrahTermSlider.setAttribute('aria-valuetext', '5 ' + yearsLabel);
                umrahTermSlider.classList.add('hp-range--dimmed');
            } else {
                umrahTermSlider.disabled = false;
                umrahTermSlider.classList.remove('hp-range--dimmed');
                syncUmrahTermDisplay();
            }
        }
    }

    ageSlider.addEventListener('input', syncAgeTermRules);

    termSlider.addEventListener('input', function() {
        if (getProduct() === 'hajj') {
            termDisplayHajj.textContent = this.value + ' ' + yearsLabel;
        }
    });

    umrahTermSlider.addEventListener('input', syncUmrahTermDisplay);

    planSelect.addEventListener('change', function() {
        applyPlan(getProduct());
    });

    planSelect.value = initialProduct;
    applyPlan(initialProduct);

    // Form Submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // UI Loading State
        submitBtn.disabled = true;
        btnText.textContent = msgProcessing;
        btnLoader.classList.remove('hidden');

        try {
            const formData = new FormData(form);
            const url = getProduct() === 'umrah' ? calculateUrlUmrah : calculateUrlHajj;
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            let data;
            try {
                data = await response.json();
            } catch (parseErr) {
                alert(msgErrorNetwork);
                return;
            }

            if (data.success) {
                showResults(data);
            } else {
                let msg = data.message || '';
                if (data.errors && typeof data.errors === 'object') {
                    const parts = Object.values(data.errors).flat().filter(Boolean);
                    if (parts.length) {
                        msg = [msg, ...parts].filter(Boolean).join('\n');
                    }
                }
                alert(msg || msgErrorGeneric);
            }
        } catch (error) {
            console.error('Error:', error);
            alert(msgErrorNetwork);
        } finally {
            submitBtn.disabled = false;
            btnText.textContent = calculateLabel();
            btnLoader.classList.add('hidden');
        }
    });

    replanBtn.addEventListener('click', function() {
        resultsWrapper.classList.add('hidden');
        formWrapper.classList.remove('hidden');
        window.scrollTo({ top: formWrapper.offsetTop - 100, behavior: 'smooth' });
    });

    function showResults(data) {
        const s = data.summary;
        const t = data.totals;
        document.getElementById('res-plan-heading').textContent = getProduct() === 'umrah' ? txtYourUmrahPlan : txtYourHajjPlan;
        document.getElementById('res-est-lead-start').textContent = getProduct() === 'umrah' ? txtEstUmrah : txtEstHajj;

        document.getElementById('res-age').textContent = s.age;
        document.getElementById('res-term').textContent = s.term;
        document.getElementById('res-annual').textContent = Number(s.annual_contribution).toLocaleString();
        document.getElementById('res-benefit').textContent = Number(s.takaful_benefit).toLocaleString();
        document.getElementById('res-total').textContent = Number(t.contribution).toLocaleString();
        document.getElementById('res-return-9').textContent = Number(t.return_9).toLocaleString();
        document.getElementById('res-return-13').textContent = Number(t.return_13).toLocaleString();

        document.getElementById('res-hero-term').textContent = s.term;
        const hero = Math.round((Number(t.return_9) + Number(t.return_13)) / 2);
        document.getElementById('res-hero-amount').textContent = hero.toLocaleString();

        formWrapper.classList.add('hidden');
        resultsWrapper.classList.remove('hidden');
        window.scrollTo({ top: resultsWrapper.offsetTop - 80, behavior: 'smooth' });

        renderCharts(data.charts, data.totals);
    }

    function decorateLineLabels(labels) {
        const y0 = new Date().getFullYear();
        return (labels || []).map(function (lbl) {
            const m = /^Year\s+(\d+)/i.exec(String(lbl));
            if (m) {
                return String(y0 + parseInt(m[1], 10));
            }
            return lbl;
        });
    }

    function fillDoughnutLegend(elementId, contribution, totalReturn) {
        const el = document.getElementById(elementId);
        if (!el || totalReturn <= 0) {
            return;
        }
        const gain = Math.max(0, totalReturn - contribution);
        const pctC = Math.round((contribution / totalReturn) * 100);
        const pctG = Math.max(0, 100 - pctC);
        const fmt = function (n) {
            return Math.round(Number(n) || 0).toLocaleString();
        };
        el.innerHTML =
            '<div class="hp-res__lg-row"><span class="hp-res__lg-swatch hp-res__lg-swatch--contrib"></span><span>' +
            lblContribution + ' ' + pctC + '% ' + lblRs + ' ' + fmt(contribution) +
            '</span></div><div class="hp-res__lg-row"><span class="hp-res__lg-swatch hp-res__lg-swatch--gain"></span><span>' +
            lblGain + ' ' + pctG + '% ' + lblRs + ' ' + fmt(gain) +
            '</span></div>';
    }

    function renderCharts(chartData, totals) {
        if (chartLine) {
            chartLine.destroy();
            chartLine = null;
        }
        if (chartDonut9) {
            chartDonut9.destroy();
            chartDonut9 = null;
        }
        if (chartDonut13) {
            chartDonut13.destroy();
            chartDonut13 = null;
        }

        const lineColors = ['#9ca3af', '#b08d57', '#2563eb'];
        const lineData = JSON.parse(JSON.stringify(chartData.line_chart));
        lineData.labels = decorateLineLabels(lineData.labels);
        (lineData.datasets || []).forEach(function (ds, i) {
            ds.label = chartLegendLines[i] || ds.label;
            ds.borderColor = lineColors[i] || '#666666';
            ds.backgroundColor = 'transparent';
            ds.fill = false;
            ds.tension = 0.25;
            ds.borderWidth = 2;
            ds.pointRadius = 3;
            ds.pointHoverRadius = 5;
            ds.pointBackgroundColor = lineColors[i];
            ds.pointBorderColor = lineColors[i];
        });

        chartLine = new Chart(document.getElementById('hajjLineChart'), {
            type: 'line',
            data: lineData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        position: 'bottom',
                        align: 'center',
                        labels: {
                            font: { family: "'Raleway', sans-serif", size: 11, weight: '500' },
                            color: '#333333',
                            padding: 18,
                            boxWidth: 10,
                            boxHeight: 10,
                            usePointStyle: true,
                            pointStyle: 'circle',
                        },
                    },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                const v = ctx.parsed.y;
                                if (v === undefined || v === null) {
                                    return '';
                                }
                                return ' ' + ctx.dataset.label + ': PKR ' + Number(v).toLocaleString();
                            },
                        },
                    },
                },
                scales: {
                    x: {
                        ticks: { font: { family: "'Raleway', sans-serif", size: 11 }, color: '#555555' },
                        grid: { display: true, color: 'rgba(0,0,0,0.06)' },
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: { family: "'Raleway', sans-serif", size: 11 },
                            color: '#555555',
                            maxTicksLimit: 8,
                            callback: function (value) {
                                return 'PKR ' + Number(value).toLocaleString();
                            },
                        },
                        grid: { color: 'rgba(0,0,0,0.07)' },
                    },
                },
            },
        });

        const donutGrey = '#d4d0c8';
        const donutGold = '#b08d57';
        const donutOpts = {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            const v = ctx.raw;
                            return ' ' + (ctx.label || '') + ': ' + lblRs + ' ' + Number(v).toLocaleString();
                        },
                    },
                },
            },
        };

        const d9 = JSON.parse(JSON.stringify(chartData.doughnut_9));
        if (d9.datasets && d9.datasets[0]) {
            d9.datasets[0].backgroundColor = [donutGrey, donutGold];
        }
        chartDonut9 = new Chart(document.getElementById('hajjDoughnut9'), {
            type: 'doughnut',
            data: d9,
            options: donutOpts,
        });

        const d13 = JSON.parse(JSON.stringify(chartData.doughnut_13));
        if (d13.datasets && d13.datasets[0]) {
            d13.datasets[0].backgroundColor = [donutGrey, donutGold];
        }
        chartDonut13 = new Chart(document.getElementById('hajjDoughnut13'), {
            type: 'doughnut',
            data: d13,
            options: donutOpts,
        });

        fillDoughnutLegend('doughnut9-legend', totals.contribution, totals.return_9);
        fillDoughnutLegend('doughnut13-legend', totals.contribution, totals.return_13);
    }
});
</script>
@endpush
