@php
    $rows = isset($rows) && is_array($rows) ? $rows : [];
    $hasRows = count($rows) > 0;
@endphp

@if ($hasRows)
    <div class="laravel-fund-price-table-wrap">
        <table class="laravel-fund-price-table" role="grid">
            <thead>
                <tr>
                    <th scope="col" class="laravel-fund-price-table__col1">Fund Name</th>
                    <th scope="colgroup" colspan="2" class="laravel-fund-price-table__fund">5th Pillar Aggressive Fund</th>
                    <th scope="colgroup" colspan="2" class="laravel-fund-price-table__fund">5th Pillar Balanced Fund</th>
                    <th scope="colgroup" colspan="2" class="laravel-fund-price-table__fund">5th Pillar Conservative Fund</th>
                </tr>
                <tr>
                    <th scope="col" class="laravel-fund-price-table__col1">Category</th>
                    <th scope="colgroup" colspan="2" class="laravel-fund-price-table__sub">Aggressive</th>
                    <th scope="colgroup" colspan="2" class="laravel-fund-price-table__sub">Balanced</th>
                    <th scope="colgroup" colspan="2" class="laravel-fund-price-table__sub">Conservative</th>
                </tr>
                <tr>
                    <th scope="col" class="laravel-fund-price-table__col1">Risk Profile</th>
                    <th scope="colgroup" colspan="2" class="laravel-fund-price-table__sub">High Risk</th>
                    <th scope="colgroup" colspan="2" class="laravel-fund-price-table__sub">Medium Risk</th>
                    <th scope="colgroup" colspan="2" class="laravel-fund-price-table__sub">Low Risk</th>
                </tr>
                <tr>
                    <th scope="col" class="laravel-fund-price-table__col1">Day &amp; Date</th>
                    <th scope="col" class="laravel-fund-price-table__bidoffer">Bid Prices</th>
                    <th scope="col" class="laravel-fund-price-table__bidoffer">Offer Prices</th>
                    <th scope="col" class="laravel-fund-price-table__bidoffer">Bid Prices</th>
                    <th scope="col" class="laravel-fund-price-table__bidoffer">Offer Prices</th>
                    <th scope="col" class="laravel-fund-price-table__bidoffer">Bid Prices</th>
                    <th scope="col" class="laravel-fund-price-table__bidoffer">Offer Prices</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $r)
                    <tr>
                        <th scope="row" class="laravel-fund-price-table__date">{{ $r['date'] }}</th>
                        <td>{{ $r['agg_bid'] }}</td>
                        <td>{{ $r['agg_offer'] }}</td>
                        <td>{{ $r['bal_bid'] }}</td>
                        <td>{{ $r['bal_offer'] }}</td>
                        <td>{{ $r['con_bid'] }}</td>
                        <td>{{ $r['con_offer'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
