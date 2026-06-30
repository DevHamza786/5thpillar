@php
    $row = isset($row) && is_array($row) ? $row : null;
@endphp

@if ($row && isset($row['date']))
    <div class="laravel-fund-daily-snapshot">
        <div class="laravel-fund-daily-snapshot__table-wrap">
            <table class="laravel-fund-daily-table" role="grid">
                <thead>
                    <tr class="laravel-fund-daily-table__banner">
                        <th colspan="5" scope="colgroup">Daily Fund Prices</th>
                    </tr>
                    <tr class="laravel-fund-daily-table__banner laravel-fund-daily-table__banner--date">
                        <th colspan="5" scope="colgroup">{{ $row['date'] }}</th>
                    </tr>
                    <tr class="laravel-fund-daily-table__cols laravel-fund-daily-table__cols--main">
                        <th rowspan="2" scope="col" class="laravel-fund-daily-table__col-fund">Participant Investment Funds</th>
                        <th rowspan="2" scope="col">Category</th>
                        <th rowspan="2" scope="col">Risk Profile</th>
                        <th scope="col">Bid</th>
                        <th scope="col">Offer</th>
                    </tr>
                    <tr class="laravel-fund-daily-table__cols laravel-fund-daily-table__cols--pkr">
                        <th scope="col">PKR</th>
                        <th scope="col">PKR</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">5th Pillar Aggressive Fund</th>
                        <td>Aggressive</td>
                        <td>High Risk</td>
                        <td>{{ $row['agg_bid'] }}</td>
                        <td>{{ $row['agg_offer'] }}</td>
                    </tr>
                    <tr>
                        <th scope="row">5th Pillar Balanced Fund</th>
                        <td>Balanced</td>
                        <td>Medium Risk</td>
                        <td>{{ $row['bal_bid'] }}</td>
                        <td>{{ $row['bal_offer'] }}</td>
                    </tr>
                    <tr>
                        <th scope="row">5th Pillar Conservative Fund</th>
                        <td>Money Market</td>
                        <td>Low Risk</td>
                        <td>{{ $row['con_bid'] }}</td>
                        <td>{{ $row['con_offer'] }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="laravel-fund-daily-table__footnote">
                        <td colspan="5">*Fund Prices are rounded to nearest four digit number*</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@else
    <p class="laravel-fund-daily-snapshot__empty">Daily fund prices are not available at the moment.</p>
@endif
