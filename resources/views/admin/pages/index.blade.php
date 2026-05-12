@extends('admin.layouts.app')

@section('title', __('Pages'))

@section('content')
    <h1 class="wp-heading-inline">{{ __('Pages') }}</h1>
    <a href="{{ route('admin.pages.create') }}" class="page-title-action">{{ __('Add New') }}</a>
    <hr class="wp-header-end" style="border:0;border-top:1px solid #c3c4c7;margin:12px 0 16px;">

    <p class="subtitle">{{ __('Search by title or URL slug. Edit SEO, permalink, template key, HTML sections, and file uploads.') }}</p>

    <div class="postbox">
        <div class="inside">
            <form method="get" action="{{ route('admin.pages.index') }}" class="tablenav">
                <label for="q" class="screen-reader-text">{{ __('Search pages') }}</label>
                <input id="q" type="search" name="q" value="{{ $q }}" placeholder="{{ __('Title or slug…') }}" style="max-width:280px;">
                <input type="submit" class="button" value="{{ __('Search') }}">
            </form>
        </div>
    </div>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th scope="col" class="manage-column column-title">{{ __('Title') }}</th>
                <th scope="col" class="manage-column">{{ __('Slug') }}</th>
                <th scope="col" class="manage-column">{{ __('Published') }}</th>
                <th scope="col" class="manage-column" style="width:80px;">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pages as $page)
                <tr>
                    <td class="title column-title has-row-actions">
                        <strong><a href="{{ route('admin.pages.edit', $page) }}">{{ $page->title }}</a></strong>
                        <div class="row-actions">
                            <span class="edit"><a href="{{ route('admin.pages.edit', $page) }}">{{ __('Edit') }}</a> |</span>
                            <span class="view"><a href="{{ route('pages.show', ['slug' => $page->slug]) }}" target="_blank" rel="noopener">{{ __('View') }}</a></span>
                        </div>
                    </td>
                    <td><code>/{{ $page->slug }}</code></td>
                    <td>{{ $page->is_published ? __('Yes') : __('No') }}</td>
                    <td><a class="button button-small" href="{{ route('admin.pages.edit', $page) }}">{{ __('Edit') }}</a></td>
                </tr>
            @empty
                <tr><td colspan="4" class="description">{{ __('No pages found.') }}</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="pager tablenav bottom">
        {{ $pages->links() }}
    </div>
@endsection
