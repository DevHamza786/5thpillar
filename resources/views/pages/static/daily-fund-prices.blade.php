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
            $latestRow = \App\Services\FundPriceArchiveRepository::latestLegacyRow();
        }
    @endphp

    <section class="laravel-fund-daily-page" aria-labelledby="unit-prices-heading">
        <h2 id="unit-prices-heading" class="laravel-fund-daily-page__unit-heading">Unit Prices</h2>

        @include('pages.partials.fund-price-daily-snapshot', ['row' => $latestRow])

        <nav class="laravel-fund-archive-nav" aria-label="Fund price archives by year">
            @foreach (\App\Services\FundPriceArchiveRepository::availableYears() as $archiveYear)
                <a class="laravel-fund-archive-nav__btn" href="{{ route('pages.show', ['slug' => 'fund-price-archive-'.$archiveYear]) }}">
                    Fund Prices Archive {{ $archiveYear }}
                </a>
            @endforeach
        </nav>
    </section>
@endsection
