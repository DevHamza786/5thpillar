@extends('pages.layouts.structured-page')

@section('structured_meta_title', $page->trans('meta_title') ?? $page->trans('title'))
@section('structured_page_title', $page->trans('title'))

@section('structured_hero_title', $page->trans('hero_title') ?? $page->trans('title'))

@section('structured_primary')
    @php
        $renderer = app(\App\Services\CmsSectionRenderer::class);
        $hasPrimarySections = $renderer->hasPrimarySections($page);
    @endphp

    @if ($hasPrimarySections)
        @include('pages.partials.cms-primary-sections', ['page' => $page])
    @elseif (filled($page->trans('content')))
        <div class="laravel-cms-generic-body">
            {!! \App\Support\PublicPath::rewriteHtmlPublicPaths($page->trans('content')) !!}
        </div>
    @endif
@endsection
