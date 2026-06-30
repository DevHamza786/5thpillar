@extends('admin.layouts.app')

@section('title', __('Add fund snapshot'))

@section('content')
    <h1 class="wp-heading-inline">{{ __('Add daily fund row') }}</h1>
    <a href="{{ route('admin.fund-snapshots.index') }}" class="page-title-action">{{ __('← Back') }}</a>
    <hr class="wp-header-end">

    <div class="postbox">
        <div class="inside">
            <form method="post" action="{{ route('admin.fund-snapshots.store') }}">
                @csrf
                <div class="row cols-2">
                    <div>
                        <label for="price_date">{{ __('Price date') }}</label>
                        <input id="price_date" type="date" name="price_date" value="{{ old('price_date') }}" required>
                    </div>
                </div>

                <h3 class="postbox-header admin-fund-header">{{ __('5th Pillar Aggressive Fund') }}</h3>
                <div class="row cols-2">
                    <div>
                        <label for="agg_bid">{{ __('Bid (PKR)') }}</label>
                        <input id="agg_bid" type="text" name="agg_bid" value="{{ old('agg_bid') }}">
                    </div>
                    <div>
                        <label for="agg_offer">{{ __('Offer (PKR)') }}</label>
                        <input id="agg_offer" type="text" name="agg_offer" value="{{ old('agg_offer') }}">
                    </div>
                </div>

                <h3 class="postbox-header admin-fund-header">{{ __('5th Pillar Balanced Fund') }}</h3>
                <div class="row cols-2">
                    <div>
                        <label for="bal_bid">{{ __('Bid (PKR)') }}</label>
                        <input id="bal_bid" type="text" name="bal_bid" value="{{ old('bal_bid') }}">
                    </div>
                    <div>
                        <label for="bal_offer">{{ __('Offer (PKR)') }}</label>
                        <input id="bal_offer" type="text" name="bal_offer" value="{{ old('bal_offer') }}">
                    </div>
                </div>

                <h3 class="postbox-header admin-fund-header">{{ __('5th Pillar Conservative Fund') }}</h3>
                <div class="row cols-2">
                    <div>
                        <label for="con_bid">{{ __('Bid (PKR)') }}</label>
                        <input id="con_bid" type="text" name="con_bid" value="{{ old('con_bid') }}">
                    </div>
                    <div>
                        <label for="con_offer">{{ __('Offer (PKR)') }}</label>
                        <input id="con_offer" type="text" name="con_offer" value="{{ old('con_offer') }}">
                    </div>
                </div>

                <p class="submit admin-submit-row admin-submit-row--12">
                    <button type="submit" class="button button-primary">{{ __('Save') }}</button>
                    <a href="{{ route('admin.fund-snapshots.index') }}" class="button admin-cancel-btn">{{ __('Cancel') }}</a>
                </p>
            </form>
        </div>
    </div>
@endsection
