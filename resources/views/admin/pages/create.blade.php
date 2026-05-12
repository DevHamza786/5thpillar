@extends('admin.layouts.app')

@section('title', __('Add page'))

@section('content')
    <h1 class="wp-heading-inline">{{ __('Add New Page') }}</h1>
    <a href="{{ route('admin.pages.index') }}" class="page-title-action">{{ __('← All pages') }}</a>
    <hr class="wp-header-end" style="border:0;border-top:1px solid #c3c4c7;margin:12px 0 16px;">

    <p class="subtitle">{{ __('The slug becomes the permalink. If there is no Blade file under resources/views/pages/static/, the generic CMS template is used.') }}</p>

    <div class="postbox">
        <h2 class="postbox-header">{{ __('Page details') }}</h2>
        <div class="inside">
            <form method="post" action="{{ route('admin.pages.store') }}">
                @csrf
                <div class="row cols-2">
                    <div>
                        <label for="title">{{ __('Title') }}</label>
                        <input id="title" type="text" name="title" value="{{ old('title') }}" required class="large-text">
                    </div>
                    <div>
                        <label for="slug">{{ __('URL slug') }}</label>
                        <input id="slug" type="text" name="slug" value="{{ old('slug') }}" required pattern="[a-z0-9]+(-[a-z0-9]+)*" placeholder="{{ __('e.g. my-new-page') }}" class="large-text">
                    </div>
                </div>
                <div class="row cols-2">
                    <div>
                        <label for="view_key">{{ __('Template key') }} <span class="description">({{ __('optional') }})</span></label>
                        <input id="view_key" type="text" name="view_key" value="{{ old('view_key') }}" pattern="[a-z0-9]+(-[a-z0-9]+)*" placeholder="{{ __('e.g. daily-fund-prices') }}" class="large-text">
                        <p class="description">{{ __('If you change the slug later, keep the original Blade name here so the same layout still loads.') }}</p>
                    </div>
                    <div>
                        <label for="meta_title">{{ __('Meta title') }}</label>
                        <input id="meta_title" type="text" name="meta_title" value="{{ old('meta_title') }}" class="large-text">
                    </div>
                </div>
                <div class="row">
                    <div>
                        <label for="meta_description">{{ __('Meta description') }}</label>
                        <textarea id="meta_description" name="meta_description" rows="3">{{ old('meta_description') }}</textarea>
                    </div>
                </div>
                <div class="row cols-2">
                    <div>
                        <label for="hero_title">{{ __('Hero / H1') }}</label>
                        <input id="hero_title" type="text" name="hero_title" value="{{ old('hero_title') }}" class="large-text">
                    </div>
                    <div>
                        <label for="masthead_bg">{{ __('Masthead background (CSS)') }}</label>
                        <input id="masthead_bg" type="text" name="masthead_bg" value="{{ old('masthead_bg') }}" placeholder="url('…')" class="large-text">
                    </div>
                </div>
                <div class="row">
                    <div>
                        <label for="content">{{ __('Main HTML') }} <span class="description">({{ __('generic template') }})</span></label>
                        <textarea id="content" name="content" rows="8">{{ old('content') }}</textarea>
                    </div>
                </div>
                <p class="check">
                    <input id="is_published" type="checkbox" name="is_published" value="1" @checked(old('is_published', true))>
                    <label for="is_published">{{ __('Published') }}</label>
                </p>
                <p class="submit" style="margin:0;padding-top:8px;border-top:1px solid #f0f0f1;">
                    <button type="submit" class="button button-primary">{{ __('Publish') }}</button>
                    <a href="{{ route('admin.pages.index') }}" class="button" style="margin-left:6px;">{{ __('Cancel') }}</a>
                </p>
            </form>
        </div>
    </div>
@endsection
