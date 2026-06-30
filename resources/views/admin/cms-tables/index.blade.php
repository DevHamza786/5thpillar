@extends('admin.layouts.app')

@section('title', __('Data tables'))

@section('content')
    <h1 class="wp-heading-inline">{{ __('Data tables') }}</h1>
    <hr class="wp-header-end">

    <p class="subtitle">{{ __('Spreadsheet-style editors for structured site data. Page sections still manage PDF lists on individual pages.') }}</p>

    <div class="postbox admin-postbox-mb">
        <h2 class="postbox-header">{{ __('Related tools') }}</h2>
        <div class="inside">
            <p class="admin-flex-row">
                <a class="button" href="{{ route('admin.fund-snapshots.index') }}">{{ __('Daily fund prices (latest row)') }}</a>
                <a class="button" href="{{ route('admin.financial-data.index') }}">{{ __('Hajj & Umrah planner data') }}</a>
            </p>
        </div>
    </div>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th scope="col">{{ __('Table') }}</th>
                <th scope="col">{{ __('Description') }}</th>
                <th scope="col">{{ __('Rows') }}</th>
                <th scope="col" class="admin-col-actions--120">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tables as $table)
                <tr>
                    <td><strong>{{ __($table['label']) }}</strong><br><code>{{ $table['key'] }}</code></td>
                    <td class="description">{{ __($table['description']) }}</td>
                    <td>{{ number_format($table['row_count']) }}</td>
                    <td>
                        <a class="button button-primary button-small" href="{{ route('admin.cms-tables.edit', $table['key']) }}">{{ __('Edit') }}</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="postbox admin-postbox-mt-lg">
        <h2 class="postbox-header">{{ __('Import legacy data') }}</h2>
        <div class="inside">
            <p class="description">{{ __('One-time import from') }} <code>resources/data/fund_price_archives.php</code> {{ __('into the database. Use') }} <code>--force</code> {{ __('to replace existing rows.') }}</p>
            <pre class="admin-pre-block">php artisan cms:import-tables fund_prices_archive --force</pre>
        </div>
    </div>
@endsection
