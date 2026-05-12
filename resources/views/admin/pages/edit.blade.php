@extends('admin.layouts.app')

@section('title', __('Edit page'))

@section('content')
    <h1 class="wp-heading-inline">{{ __('Edit page') }}</h1>
    <a href="{{ route('pages.show', ['slug' => $page->slug]) }}" class="page-title-action" target="_blank" rel="noopener">{{ __('View page') }}</a>
    <hr class="wp-header-end" style="border:0;border-top:1px solid #c3c4c7;margin:12px 0 16px;">

    <p class="subtitle">
        {{ __('Permalink:') }} <strong><a href="{{ route('pages.show', ['slug' => $page->slug]) }}" target="_blank" rel="noopener">{{ url('/'.$page->slug) }}</a></strong>
    </p>

    <div class="postbox">
        <h2 class="postbox-header">{{ __('Page settings') }}</h2>
        <div class="inside">
            <form method="post" action="{{ route('admin.pages.update', $page) }}">
                @csrf
                @method('PUT')
                <div class="row cols-2">
                    <div>
                        <label for="title">{{ __('Title') }}</label>
                        <input id="title" type="text" name="title" value="{{ old('title', $page->title) }}" required class="large-text">
                    </div>
                    <div>
                        <label for="slug">{{ __('URL slug') }}</label>
                        <input id="slug" type="text" name="slug" value="{{ old('slug', $page->slug) }}" required pattern="[a-z0-9]+(-[a-z0-9]+)*" class="large-text">
                    </div>
                </div>
                <div class="row cols-2">
                    <div>
                        <label for="view_key">{{ __('Template key') }}</label>
                        <input id="view_key" type="text" name="view_key" value="{{ old('view_key', $page->view_key) }}" pattern="[a-z0-9]+(-[a-z0-9]+)*" placeholder="{{ __('Blank = same as slug') }}" class="large-text">
                        <p class="description">{{ __('Keeps the same Blade view when you change the permalink. Example: slug') }} <code>fund-prices</code> {{ __('with template key') }} <code>daily-fund-prices</code>.</p>
                    </div>
                    <div>
                        <label for="meta_title">{{ __('Meta title') }}</label>
                        <input id="meta_title" type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" class="large-text">
                    </div>
                </div>
                <div class="row">
                    <div>
                        <label for="meta_description">{{ __('Meta description') }}</label>
                        <textarea id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $page->meta_description) }}</textarea>
                    </div>
                </div>
                <div class="row cols-2">
                    <div>
                        <label for="hero_title">{{ __('Hero / H1') }}</label>
                        <input id="hero_title" type="text" name="hero_title" value="{{ old('hero_title', $page->hero_title) }}" class="large-text">
                    </div>
                    <div>
                        <label for="masthead_bg">{{ __('Masthead background (CSS)') }}</label>
                        <input id="masthead_bg" type="text" name="masthead_bg" value="{{ old('masthead_bg', $page->masthead_bg) }}" class="large-text">
                    </div>
                </div>
                <div class="row">
                    <div>
                        <label for="content">{{ __('Main HTML') }} <span class="description">({{ __('generic template & extra body') }})</span></label>
                        <textarea id="content" name="content" rows="8">{{ old('content', $page->content) }}</textarea>
                    </div>
                </div>
                <p class="check">
                    <input id="is_published" type="checkbox" name="is_published" value="1" @checked(old('is_published', $page->is_published))>
                    <label for="is_published">{{ __('Published') }}</label>
                </p>
                <p class="submit" style="margin:0;padding-top:12px;border-top:1px solid #f0f0f1;">
                    <button type="submit" class="button button-primary">{{ __('Update') }}</button>
                </p>
            </form>
        </div>
    </div>

    <div class="postbox">
        <h2 class="postbox-header">{{ __('Content sections') }}</h2>
        <div class="inside">
            <p class="description">{{ __('Optional blocks rendered below the main template output. HTML is allowed (trusted editors only).') }}</p>

            @foreach ($page->sections as $section)
                <div class="cms-section-box">
                    <form method="post" action="{{ route('admin.pages.sections.update', [$page, $section]) }}">
                        @csrf
                        @method('PUT')
                        <div class="row cols-2">
                            <div>
                                <label>{{ __('Heading') }}</label>
                                <input type="text" name="heading" value="{{ old('heading', $section->heading) }}">
                            </div>
                            <div>
                                <label>{{ __('Order') }}</label>
                                <input type="number" name="sort_order" value="{{ old('sort_order', $section->sort_order) }}" min="0">
                            </div>
                        </div>
                        <div class="row">
                            <div>
                                <label>{{ __('Type') }}</label>
                                <input type="text" name="section_type" value="{{ old('section_type', $section->section_type) }}" placeholder="content">
                            </div>
                        </div>
                        <div class="row">
                            <div>
                                <label>{{ __('Body HTML') }}</label>
                                <textarea name="body_html" rows="6">{{ old('body_html', $section->body_html) }}</textarea>
                            </div>
                        </div>
                        <p style="margin:0;display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
                            <button type="submit" class="button">{{ __('Update section') }}</button>
                        </p>
                    </form>
                    <form method="post" action="{{ route('admin.pages.sections.destroy', [$page, $section]) }}" style="margin-top:10px;padding-top:10px;border-top:1px dashed #c3c4c7;" onsubmit='return confirm({{ json_encode(__('Remove this section?')) }});'>
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="button button-link-delete">{{ __('Delete section') }}</button>
                    </form>
                </div>
            @endforeach

            <h3 style="font-size:14px;margin:20px 0 10px;">{{ __('Add new section') }}</h3>
            <form method="post" action="{{ route('admin.pages.sections.store', $page) }}">
                @csrf
                <div class="row cols-2">
                    <div>
                        <label for="new_heading">{{ __('Heading') }}</label>
                        <input id="new_heading" type="text" name="heading" value="{{ old('heading') }}">
                    </div>
                    <div>
                        <label for="new_type">{{ __('Type') }}</label>
                        <input id="new_type" type="text" name="section_type" value="{{ old('section_type', 'content') }}">
                    </div>
                </div>
                <div class="row">
                    <div>
                        <label for="new_body">{{ __('Body HTML') }}</label>
                        <textarea id="new_body" name="body_html" rows="5">{{ old('body_html') }}</textarea>
                    </div>
                </div>
                <p class="submit" style="margin:0;">
                    <button type="submit" class="button button-primary">{{ __('Add section') }}</button>
                </p>
            </form>
        </div>
    </div>

    <div class="postbox">
        <h2 class="postbox-header">{{ __('Media library') }} <span class="description" style="font-weight:400;">({{ __('PDF & images') }})</span></h2>
        <div class="inside">
            <p class="description">{{ __('Files are stored in storage/app/public and shown as downloads on this page.') }}</p>

            @if ($page->media->isNotEmpty())
                <ul style="margin:0 0 12px; padding-left:1.2rem; list-style:disc;">
                    @foreach ($page->media as $file)
                        <li style="margin-bottom:6px;">
                            <a href="{{ $file->publicUrl() }}" target="_blank" rel="noopener">{{ $file->label }}</a>
                            <span class="description">({{ $file->mime }})</span>
                            <form action="{{ route('admin.pages.media.destroy', [$page, $file]) }}" method="post" style="display:inline;" onsubmit='return confirm({{ json_encode(__('Delete this file?')) }});'>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button-link-delete" style="background:none;border:none;padding:0;margin-left:6px;cursor:pointer;font:inherit;">{{ __('Delete') }}</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif

            <form method="post" action="{{ route('admin.pages.media.store', $page) }}" enctype="multipart/form-data">
                @csrf
                <div class="row cols-2">
                    <div>
                        <label for="file">{{ __('Upload file') }}</label>
                        <input id="file" type="file" name="file" required>
                    </div>
                    <div>
                        <label for="label">{{ __('Title / label') }}</label>
                        <input id="label" type="text" name="label" placeholder="{{ __('Optional') }}">
                    </div>
                </div>
                <p class="submit" style="margin:0;">
                    <button type="submit" class="button button-primary">{{ __('Upload') }}</button>
                </p>
            </form>
        </div>
    </div>

    <p><a href="{{ route('admin.pages.index') }}">← {{ __('All pages') }}</a></p>
@endsection
