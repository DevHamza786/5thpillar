@extends('pages.layouts.structured-page')

@section('structured_meta_title', $post['title'].' - 5th Pillar Family Takaful')
@section('structured_page_title', $post['title'])
@section('structured_hero_title', $post['title'])

@section('structured_breadcrumb_trail')
    <a href="{{ route('home') }}" class="laravel-inner-breadcrumbs__link">Home</a>
    <span class="laravel-inner-breadcrumbs__sep" aria-hidden="true">/</span>
    <a href="{{ route('news-events.index') }}" class="laravel-inner-breadcrumbs__link">News &amp; Events</a>
    <span class="laravel-inner-breadcrumbs__sep" aria-hidden="true">/</span>
    <span class="laravel-inner-breadcrumbs__current">{{ \Illuminate\Support\Str::limit($post['title'], 72) }}</span>
@endsection

@section('structured_primary')
    <article class="laravel-news-single">
        <div class="laravel-news-single__layout {{ empty($post['image']) ? 'laravel-news-single__layout--text-only' : '' }}">
            @if (! empty($post['image']))
                <figure class="laravel-news-single__media">
                    <img src="{{ $post['image'] }}" alt="" loading="lazy" decoding="async">
                </figure>
            @endif
            <div class="laravel-news-single__body laravel-news-single__body--plain">
                {!! \App\Support\NewsArticleContent::toPlainParagraphs($post['content']) !!}
            </div>
        </div>
    </article>
@endsection
