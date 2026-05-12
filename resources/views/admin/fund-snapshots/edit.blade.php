@extends('admin.layouts.app')

@section('title', __('Edit fund snapshot'))

@section('content')
    <h1 class="wp-heading-inline">{{ __('Edit daily fund row') }}</h1>
    <a href="{{ route('admin.fund-snapshots.index') }}" class="page-title-action">{{ __('← All rows') }}</a>
    <hr class="wp-header-end" style="border:0;border-top:1px solid #c3c4c7;margin:12px 0 16px;">

    <p class="subtitle">{{ __('Date:') }} <strong>{{ $snapshot->price_date->format('F j, Y') }}</strong></p>

    <div class="postbox">
        <div class="inside">
            <form method="post" action="{{ route('admin.fund-snapshots.update', $snapshot) }}">
                @csrf
                @method('PUT')
                <div class="row cols-2">
                    <div>
                        <label for="price_date">{{ __('Price date') }}</label>
                        <input id="price_date" type="date" name="price_date" value="{{ old('price_date', $snapshot->price_date->format('Y-m-d')) }}" required>
                    </div>
                </div>

                <h3 class="postbox-header" style="margin:16px -12px 12px;border-left:4px solid #2271b1;">{{ __('Aggressive') }}</h3>
                <div class="row cols-2">
                    <div>
                        <label for="agg_bid">{{ __('Bid') }}</label>
                        <input id="agg_bid" type="text" name="agg_bid" value="{{ old('agg_bid', $snapshot->agg_bid) }}">
                    </div>
                    <div>
                        <label for="agg_offer">{{ __('Offer') }}</label>
                        <input id="agg_offer" type="text" name="agg_offer" value="{{ old('agg_offer', $snapshot->agg_offer) }}">
                    </div>
                </div>

                <h3 class="postbox-header" style="margin:16px -12px 12px;border-left:4px solid #2271b1;">{{ __('Balanced') }}</h3>
                <div class="row cols-2">
                    <div>
                        <label for="bal_bid">{{ __('Bid') }}</label>
                        <input id="bal_bid" type="text" name="bal_bid" value="{{ old('bal_bid', $snapshot->bal_bid) }}">
                    </div>
                    <div>
                        <label for="bal_offer">{{ __('Offer') }}</label>
                        <input id="bal_offer" type="text" name="bal_offer" value="{{ old('bal_offer', $snapshot->bal_offer) }}">
                    </div>
                </div>

                <h3 class="postbox-header" style="margin:16px -12px 12px;border-left:4px solid #2271b1;">{{ __('Conservative') }}</h3>
                <div class="row cols-2">
                    <div>
                        <label for="con_bid">{{ __('Bid') }}</label>
                        <input id="con_bid" type="text" name="con_bid" value="{{ old('con_bid', $snapshot->con_bid) }}">
                    </div>
                    <div>
                        <label for="con_offer">{{ __('Offer') }}</label>
                        <input id="con_offer" type="text" name="con_offer" value="{{ old('con_offer', $snapshot->con_offer) }}">
                    </div>
                </div>

                <p class="submit" style="margin:0;padding-top:12px;border-top:1px solid #f0f0f1;">
                    <button type="submit" class="button button-primary">{{ __('Update') }}</button>
                    <a href="{{ route('admin.fund-snapshots.index') }}" class="button" style="margin-left:6px;">{{ __('Cancel') }}</a>
                </p>
            </form>
        </div>
    </div>
@endsection
