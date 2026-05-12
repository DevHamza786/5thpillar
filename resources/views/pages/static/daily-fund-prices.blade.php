@extends('pages.layouts.structured-page')

@section('structured_meta_title', 'Daily Fund Prices - 5th Pillar Family Takaful')
@section('structured_page_title', 'Daily Fund Prices')

@section('structured_hero_title', 'Daily Fund Prices')

@section('structured_primary')
    @php
        $latestRow = null;
        $cmsSnapshot = \App\Models\FundDailySnapshot::query()->orderByDesc('price_date')->first();
        if ($cmsSnapshot !== null) {
            $latestRow = [
                'date' => $cmsSnapshot->price_date->format('F j, Y'),
                'agg_bid' => $cmsSnapshot->agg_bid,
                'agg_offer' => $cmsSnapshot->agg_offer,
                'bal_bid' => $cmsSnapshot->bal_bid,
                'bal_offer' => $cmsSnapshot->bal_offer,
                'con_bid' => $cmsSnapshot->con_bid,
                'con_offer' => $cmsSnapshot->con_offer,
            ];
        }
        if ($latestRow === null) {
            $fundArchives = [];
            $archivePath = resource_path('data/fund_price_archives.php');
            if (is_file($archivePath)) {
                $loaded = require $archivePath;
                $fundArchives = is_array($loaded) ? $loaded : [];
            }
            if ($fundArchives !== []) {
                krsort($fundArchives, SORT_NUMERIC);
                foreach ($fundArchives as $months) {
                    if (! is_array($months)) {
                        continue;
                    }
                    $monthKeys = array_keys($months);
                    for ($mi = count($monthKeys) - 1; $mi >= 0; $mi--) {
                        $rows = $months[$monthKeys[$mi]] ?? null;
                        if (is_array($rows) && count($rows) > 0) {
                            $latestRow = end($rows);
                            break 2;
                        }
                    }
                }
            }
        }
    @endphp

    <section class="laravel-fund-daily-page" aria-labelledby="unit-prices-heading">
        <h2 id="unit-prices-heading" class="laravel-fund-daily-page__unit-heading">Unit Prices</h2>

        @include('pages.partials.fund-price-daily-snapshot', ['row' => $latestRow])

        <nav class="laravel-fund-archive-nav" aria-label="Fund price archives by year">
            <a class="laravel-fund-archive-nav__btn" href="{{ route('pages.show', ['slug' => 'fund-price-archive-2023']) }}">
                Fund Prices Archive 2023
            </a>
            <a class="laravel-fund-archive-nav__btn" href="{{ route('pages.show', ['slug' => 'fund-price-archive-2024']) }}">
                Fund Prices Archive 2024
            </a>
            <a class="laravel-fund-archive-nav__btn" href="{{ route('pages.show', ['slug' => 'fund-price-archive-2025']) }}">
                Fund Prices Archive 2025
            </a>
            <a class="laravel-fund-archive-nav__btn" href="{{ route('pages.show', ['slug' => 'fund-price-archive-2026']) }}">
                Fund Prices Archive 2026
            </a>
        </nav>
    </section>
@endsection
