@extends('admin.layouts.app')

@section('title', __('Pages'))

@section('content')
    @php
        $urduPrefix = config('site.locale.prefix', 'urdu');
    @endphp
    <h1 class="wp-heading-inline">{{ __('Pages') }}</h1>
    <a href="{{ route('admin.pages.create') }}" class="page-title-action">{{ __('Add New') }}</a>
    <hr class="wp-header-end">

    <p class="subtitle">{{ __('Search by title or URL slug. English and Urdu versions can be published or kept as draft independently.') }}</p>

    <div class="postbox">
        <div class="inside">
            <form method="get" action="{{ route('admin.pages.index') }}" class="tablenav cms-page-filters">
                <div class="cms-page-filters__field cms-page-filters__field--search">
                    <label for="q" class="screen-reader-text">{{ __('Search pages') }}</label>
                    <input id="q" type="search" name="q" value="{{ $q }}" placeholder="{{ __('Title or slug…') }}">
                </div>

                <div class="cms-page-filters__field">
                    <label for="status">{{ __('English status') }}</label>
                    <select id="status" name="status">
                        <option value="">{{ __('All') }}</option>
                        @foreach (config('cms.page_statuses', []) as $value => $label)
                            <option value="{{ $value }}" @selected($status === $value)>{{ __($label) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="cms-page-filters__field">
                    <label for="status_ur">{{ __('Urdu status') }}</label>
                    <select id="status_ur" name="status_ur">
                        <option value="">{{ __('All') }}</option>
                        @foreach (config('cms.page_statuses', []) as $value => $label)
                            <option value="{{ $value }}" @selected($statusUr === $value)>{{ __($label) }}</option>
                        @endforeach
                    </select>
                </div>

                <input type="submit" class="button cms-page-filters__submit" value="{{ __('Filter') }}">
            </form>
        </div>
    </div>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th scope="col" class="manage-column column-title">{{ __('Title') }}</th>
                <th scope="col" class="manage-column">{{ __('English URL') }}</th>
                <th scope="col" class="manage-column">{{ __('Urdu URL') }}</th>
                <th scope="col" class="manage-column admin-col-order--90">{{ __('Order') }}</th>
                <th scope="col" class="manage-column">{{ __('English') }}</th>
                <th scope="col" class="manage-column">{{ __('Urdu') }}</th>
                <th scope="col" class="manage-column admin-col-actions">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pages as $page)
                @php
                    $urduSlug = $page->slug_ur ?: $page->slug;
                @endphp
                <tr>
                    <td class="title column-title has-row-actions">
                        <strong><a href="{{ route('admin.pages.edit', $page) }}">{{ $page->title }}</a></strong>
                        @if (filled($page->title_ur))
                            <br><span class="description" dir="rtl">{{ $page->title_ur }}</span>
                        @endif
                        <div class="row-actions">
                            <span class="edit"><a href="{{ route('admin.pages.edit', $page) }}">{{ __('Edit') }}</a> |</span>
                            <span class="preview"><a href="{{ route('admin.pages.preview', $page) }}" target="_blank" rel="noopener">{{ __('Preview EN') }}</a> |
                                <a href="{{ route('admin.pages.preview', ['page' => $page, 'locale' => 'ur']) }}" target="_blank" rel="noopener">{{ __('Preview UR') }}</a>
                            @if ($page->isPublished())
                                |</span>
                                <span class="view"><a href="{{ route('pages.show', ['slug' => $page->slug]) }}" target="_blank" rel="noopener">{{ __('View EN') }}</a></span>
                            @else
                                </span>
                            @endif
                            @if ($page->isPublishedUr())
                                <span class="view"> | <a href="{{ route('urdu.pages.show', ['slug' => $urduSlug]) }}" target="_blank" rel="noopener">{{ __('View UR') }}</a></span>
                            @endif
                        </div>
                    </td>
                    <td><code>/{{ $page->slug }}</code></td>
                    <td><code dir="rtl">/{{ $urduPrefix }}/{{ $urduSlug }}</code></td>
                    <td>{{ $page->sort_order }}</td>
                    <td>
                        @if ($page->isPublished())
                            <span class="cms-status cms-status--published">{{ __('Published') }}</span>
                        @else
                            <span class="cms-status cms-status--draft">{{ __('Draft') }}</span>
                        @endif
                    </td>
                    <td>
                        @if ($page->isPublishedUr())
                            <span class="cms-status cms-status--published">{{ __('Published') }}</span>
                        @else
                            <span class="cms-status cms-status--draft">{{ __('Draft') }}</span>
                        @endif
                    </td>
                    <td><a class="button button-small" href="{{ route('admin.pages.edit', $page) }}">{{ __('Edit') }}</a></td>
                </tr>
            @empty
                <tr><td colspan="7" class="description">{{ __('No pages found.') }}</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="pager tablenav bottom">
        {{ $pages->links() }}
    </div>
@endsection
