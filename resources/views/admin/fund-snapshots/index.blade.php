@extends('admin.layouts.app')

@section('title', __('Daily fund prices'))

@section('content')
    <h1 class="wp-heading-inline">{{ __('Daily fund prices') }}</h1>
    <a href="{{ route('admin.fund-snapshots.create') }}" class="page-title-action">{{ __('Add New') }}</a>
    <hr class="wp-header-end" style="border:0;border-top:1px solid #c3c4c7;margin:12px 0 16px;">

    <div class="notice notice-info" style="margin-top:0;">
        <p>{{ __('The row with the latest price date is shown on the public Daily Fund Prices page. If there are no rows, the site uses the imported PHP archive.') }}</p>
    </div>

    @if ($fundManagersReportPage)
        <div class="postbox" style="margin-bottom:16px;">
            <h2 class="postbox-header">{{ __('Fund Manager’s Report (page)') }}</h2>
            <div class="inside">
                <p class="description">{{ __('Edit the CMS page visitors see for the fund manager report (same as under Pages).') }}</p>
                <p>
                    <a class="button button-primary" href="{{ route('admin.pages.edit', $fundManagersReportPage) }}">{{ __('Edit Fund Manager’s Report') }}</a>
                    <a class="button" href="{{ route('pages.show', ['slug' => $fundManagersReportPage->slug]) }}" target="_blank" rel="noopener">{{ __('View live') }}</a>
                </p>
            </div>
        </div>
    @endif

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th scope="col">{{ __('Date') }}</th>
                <th scope="col">{{ __('Aggressive (bid / offer)') }}</th>
                <th scope="col">{{ __('Balanced (bid / offer)') }}</th>
                <th scope="col">{{ __('Conservative (bid / offer)') }}</th>
                <th scope="col" style="width:140px;">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($snapshots as $s)
                <tr>
                    <td><strong>{{ $s->price_date->format('Y-m-d') }}</strong></td>
                    <td>{{ $s->agg_bid }} / {{ $s->agg_offer }}</td>
                    <td>{{ $s->bal_bid }} / {{ $s->bal_offer }}</td>
                    <td>{{ $s->con_bid }} / {{ $s->con_offer }}</td>
                    <td>
                        <a class="button button-small" href="{{ route('admin.fund-snapshots.edit', $s) }}">{{ __('Edit') }}</a>
                        <form action="{{ route('admin.fund-snapshots.destroy', $s) }}" method="post" style="display:inline;" onsubmit='return confirm({{ json_encode(__('Delete this row?')) }});'>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button button-small button-link-delete">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="description">{{ __('No rows yet. Add one to override the snapshot on the website.') }}</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="pager tablenav bottom">{{ $snapshots->links() }}</div>
@endsection
