@extends('pages.layouts.structured-page')

@section('structured_meta_title', 'Search - 5th Pillar Family Takaful')
@section('structured_page_title', 'Search')
@section('structured_hero_title', 'Search')

@section('structured_primary')
    @php
        $searchAction = app()->getLocale() === 'ur' ? route('urdu.search') : route('search');
    @endphp
    <article class="post_item_single type-page laravel-search-page">
        <div class="post_content entry-content">
            <form role="search" method="get" action="{{ $searchAction }}" class="laravel-search-form">
                <input
                    type="text"
                    name="s"
                    value="{{ $query }}"
                    class="laravel-search-form__field"
                    placeholder="{{ __('Search the site…') }}"
                    aria-label="{{ __('Search') }}"
                    autofocus
                >
                <button type="submit" class="laravel-hajj-btn laravel-search-form__submit">{{ __('Search') }}</button>
            </form>

            @if ($query === '')
                <p class="laravel-search-hint">{{ __('Type a keyword above and press Search.') }}</p>
            @elseif ($results->isEmpty())
                <p class="laravel-search-hint">{{ __('No results found for') }} <strong>&ldquo;{{ $query }}&rdquo;</strong>.</p>
            @else
                <p class="laravel-search-summary">
                    {{ trans_choice('{1}:count result for|[2,*]:count results for', $results->count(), ['count' => $results->count()]) }}
                    <strong>&ldquo;{{ $query }}&rdquo;</strong>
                </p>
                <ul class="laravel-search-results">
                    @foreach ($results as $result)
                        @php
                            $title = $result->trans('title') ?: $result->title;
                            $excerpt = $result->trans('meta_description');
                            if (blank($excerpt)) {
                                $excerpt = \Illuminate\Support\Str::limit(trim(strip_tags((string) $result->trans('content'))), 180);
                            }
                        @endphp
                        <li class="laravel-search-results__item">
                            <a class="laravel-search-results__link" href="{{ $result->publicUrl() }}">{{ $title }}</a>
                            @if (filled($excerpt))
                                <p class="laravel-search-results__excerpt">{{ \Illuminate\Support\Str::limit($excerpt, 180) }}</p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </article>
@endsection
