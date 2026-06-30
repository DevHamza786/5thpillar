@extends('admin.layouts.app')

@section('title', __('Add page'))

@section('content')
    <h1 class="wp-heading-inline">{{ __('Add New Page') }}</h1>
    <a href="{{ route('admin.pages.index') }}" class="page-title-action">{{ __('← All pages') }}</a>
    <hr class="wp-header-end">

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
                        <label for="sort_order">{{ __('Sort order') }}</label>
                        <input id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="large-text">
                    </div>
                </div>
                <div class="row cols-2">
                    <div>
                        <label for="status">{{ __('English status') }}</label>
                        <select id="status" name="status" required>
                            @foreach ($statuses as $value => $label)
                                <option value="{{ $value }}" @selected(old('status', 'draft') === $value)>{{ __($label) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="status_ur">{{ __('Urdu status') }}</label>
                        <select id="status_ur" name="status_ur" required>
                            @foreach ($statuses as $value => $label)
                                <option value="{{ $value }}" @selected(old('status_ur', 'draft') === $value)>{{ __($label) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row cols-2">
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
                        <label for="meta_keywords">{{ __('Meta keywords') }}</label>
                        <input id="meta_keywords" type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" placeholder="{{ __('comma, separated, terms') }}" class="large-text">
                    </div>
                    <div>
                        <label for="og_image">{{ __('Open Graph image') }}</label>
                        <input id="og_image" type="text" name="og_image" value="{{ old('og_image') }}" placeholder="assets/images/…" class="large-text">
                        <p class="description">{!! __('Path under public/ or pick from <a href=":url">Media Library</a>.', ['url' => route('admin.media.index')]) !!}</p>
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
                <p class="description">{{ __('After creating the page, add content blocks from the section navigator on the edit screen.') }}</p>
                <p class="submit admin-submit-row">
                    <button type="submit" class="button button-primary">{{ __('Create page') }}</button>
                    <a href="{{ route('admin.pages.index') }}" class="button admin-cancel-btn">{{ __('Cancel') }}</a>
                </p>
            </form>
        </div>
    </div>
@endsection
