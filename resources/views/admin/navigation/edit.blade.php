@extends('admin.layouts.app')

@section('title', __('Edit menu item'))

@section('content')
    <h1 class="wp-heading-inline">{{ __('Edit menu item') }}</h1>
    <a href="{{ route('admin.navigation.index') }}" class="page-title-action">{{ __('← All items') }}</a>
    <hr class="wp-header-end">

    <div class="postbox">
        <div class="inside">
            <form method="post" action="{{ route('admin.navigation.update', $item) }}">
                @csrf
                @method('PUT')
                @include('admin.navigation.form-fields', ['item' => $item, 'parentOptions' => $parentOptions, 'mediaOptions' => $mediaOptions])
                <p class="submit admin-submit-row admin-submit-row--12">
                    <button type="submit" class="button button-primary">{{ __('Update') }}</button>
                </p>
            </form>
        </div>
    </div>
@endsection
