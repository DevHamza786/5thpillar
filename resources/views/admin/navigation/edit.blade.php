@extends('admin.layouts.app')

@section('title', __('Edit menu item'))

@section('content')
    <h1 class="wp-heading-inline">{{ __('Edit menu item') }}</h1>
    <a href="{{ route('admin.navigation.index') }}" class="page-title-action">{{ __('← All items') }}</a>
    <hr class="wp-header-end" style="border:0;border-top:1px solid #c3c4c7;margin:12px 0 16px;">

    <div class="postbox">
        <div class="inside">
            <form method="post" action="{{ route('admin.navigation.update', $item) }}">
                @csrf
                @method('PUT')
                @include('admin.navigation.form-fields', ['item' => $item, 'parentOptions' => $parentOptions, 'mediaOptions' => $mediaOptions])
                <p class="submit" style="margin:0;padding-top:12px;border-top:1px solid #f0f0f1;">
                    <button type="submit" class="button button-primary">{{ __('Update') }}</button>
                </p>
            </form>
        </div>
    </div>
@endsection
