@extends('pages.layouts.structured-page')

@section('structured_meta_title', 'News & Events - 5th Pillar Family Takaful')
@section('structured_page_title', 'News & Events')
@section('structured_hero_title', 'News & Events')

@section('structured_primary')
    <section class="laravel-news-board-section" aria-label="News and events archive">
        <div class="laravel-news-board">
            @foreach ($posts as $index => $post)
                @php
                    /** Row 0,2,…: T I T I — row 1,3,…: I T I T (checkerboard). Two posts per row × 4 columns. */
                    $row = intdiv($index, 2);
                    $textFirst = ($row % 2) === 0;
                    $href = route('pages.show', ['slug' => $post['slug']]);
                @endphp
                @if ($textFirst)
                    <article class="laravel-news-board__cell laravel-news-board__cell--text">
                        <a href="{{ $href }}" class="laravel-news-board__hit">
                            <h2 class="laravel-news-board__title">{{ $post['title'] }}</h2>
                            <span class="laravel-news-board__btn"><span class="laravel-news-board__btn-text">Read more</span></span>
                        </a>
                    </article>
                    <article class="laravel-news-board__cell laravel-news-board__cell--image">
                        <a href="{{ $href }}" class="laravel-news-board__hit laravel-news-board__hit--image">
                            @if (! empty($post['image']))
                                <img src="{{ $post['image'] }}" alt="" loading="lazy" decoding="async">
                            @else
                                <div class="laravel-news-board__image-fallback" aria-hidden="true"></div>
                            @endif
                        </a>
                    </article>
                @else
                    <article class="laravel-news-board__cell laravel-news-board__cell--image">
                        <a href="{{ $href }}" class="laravel-news-board__hit laravel-news-board__hit--image">
                            @if (! empty($post['image']))
                                <img src="{{ $post['image'] }}" alt="" loading="lazy" decoding="async">
                            @else
                                <div class="laravel-news-board__image-fallback" aria-hidden="true"></div>
                            @endif
                        </a>
                    </article>
                    <article class="laravel-news-board__cell laravel-news-board__cell--text">
                        <a href="{{ $href }}" class="laravel-news-board__hit">
                            <h2 class="laravel-news-board__title">{{ $post['title'] }}</h2>
                            <span class="laravel-news-board__btn"><span class="laravel-news-board__btn-text">Read more</span></span>
                        </a>
                    </article>
                @endif
            @endforeach
        </div>

        @if ($paginator->hasPages())
            <div class="laravel-news-pagination-wrap">
                {{ $paginator->links('pagination.news-events') }}
            </div>
        @endif
    </section>
@endsection
