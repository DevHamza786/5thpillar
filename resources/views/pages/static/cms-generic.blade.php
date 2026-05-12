@extends('pages.layouts.structured-page')

@section('structured_meta_title', $page->trans('meta_title') ?? $page->trans('title'))
@section('structured_page_title', $page->trans('title'))

@section('structured_hero_title', $page->trans('hero_title') ?? $page->trans('title'))

@section('structured_primary')
    @php
        // Debugging
        $currentLocale = app()->getLocale();
        $hasUrduContent = !empty($page->content_ur);
    @endphp
    <!-- Debug: Locale={{ $currentLocale }}, HasUrduContent={{ $hasUrduContent ? 'Yes' : 'No' }} -->
    @if (filled($page->trans('content')))
        <div class="laravel-cms-generic-body">
            {!! \App\Support\PublicPath::rewriteHtmlPublicPaths($page->trans('content')) !!}
        </div>
    @endif
@endsection

