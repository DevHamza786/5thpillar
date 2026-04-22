@php
    $year = (int) ($year ?? 0);
    $path = resource_path('data/fund_price_archives.php');
    $all = is_file($path) ? require $path : [];
    $months = is_array($all) && isset($all[$year]) ? $all[$year] : [];
    $idx = 0;
@endphp

<section class="laravel-fund-archive" aria-label="Fund price archive {{ $year }}">
    <h2 class="laravel-fund-archive__unit-heading">Unit Prices</h2>

    <div class="laravel-fund-archive__accordion">
        @forelse ($months as $monthLabel => $rows)
            @php
                $panelId = 'fund-archive-'.$year.'-'.preg_replace('/[^a-z0-9]+/i', '-', $monthLabel);
                $isFirst = $idx === 0;
                $idx++;
            @endphp
            <details class="laravel-fund-acc" @if ($isFirst) open @endif id="{{ $panelId }}">
                <summary class="laravel-fund-acc__summary">
                    <span class="laravel-fund-acc__label">Daily Fund Prices – {{ $monthLabel }}</span>
                    <span class="laravel-fund-acc__chevron" aria-hidden="true"><i class="fa-solid fa-angle-down"></i></span>
                </summary>
                <div class="laravel-fund-acc__panel">
                    @include('pages.partials.fund-price-table', ['rows' => $rows])
                </div>
            </details>
        @empty
            <p class="laravel-fund-archive__empty">No archive data is available for this year yet.</p>
        @endforelse
    </div>
</section>
