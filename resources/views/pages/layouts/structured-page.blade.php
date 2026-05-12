@extends('layouts.app')

@php
    $__cmsMetaTitle = isset($page)
        ? $page->trans('meta_title') ?: $page->trans('title')
        : trim($__env->yieldContent('structured_meta_title'));
    $__cmsPageTitle = isset($page)
        ? $page->trans('hero_title') ?: $page->trans('title')
        : trim($__env->yieldContent('structured_page_title'));
    $__cmsHeroTitle = isset($page)
        ? $page->trans('hero_title') ?: $page->trans('title')
        : trim($__env->yieldContent('structured_hero_title'));
    $__cmsMastheadBg = isset($page) && filled($page->trans('masthead_bg'))
        ? $page->trans('masthead_bg')
        : trim($__env->yieldContent('structured_masthead_bg'));
@endphp

@push('head')
    @if (isset($page) && filled($page->trans('meta_description')))
        <meta name="description" content="{{ $page->trans('meta_description') }}">
    @endif
@endpush


@section('title', str_replace('&amp;', '&', $__cmsMetaTitle))
@section('body_class', trim($__env->yieldContent('structured_body_class', 'wp-singular page-template-default page custom-background wp-custom-logo wp-theme-shaha wp-child-theme-shaha-child body_tag scheme_default blog_mode_page body_style_wide is_single sidebar_hide expand_content remove_margins header_type_custom header_position_default header_mobile_disabled menu_style_ no_layout laravel-inner-page laravel-college-theme')))
@section('page_title', str_replace('&amp;', '&', $__cmsPageTitle !== '' ? $__cmsPageTitle : $__cmsMetaTitle))

@section('header_masthead')
    @php
        $innerHeading = $__cmsHeroTitle;
        if ($innerHeading === '') {
            $innerHeading = $__cmsPageTitle !== '' ? $__cmsPageTitle : $__cmsMetaTitle;
        }
        $innerHeading = str_replace('&amp;', '&', $innerHeading);

        $mastheadBg = $__cmsMastheadBg;
        if ($mastheadBg === '') {
            $mastheadBg = "url('" . asset('uploads/2017/2017/09/main-bannner-64d5d132c369d.webp') . "')";
        }
    @endphp
    <div class="laravel-inner-masthead" role="region" aria-label="Page heading" style="--laravel-inner-masthead-bg: {{ $mastheadBg }};">
        <div class="laravel-inner-masthead__overlay" aria-hidden="true"></div>
        <div class="content_wrap laravel-inner-masthead__inner">
            <h1 class="laravel-inner-masthead__title">{{ $innerHeading }}</h1>
            <nav class="laravel-inner-breadcrumbs" aria-label="Breadcrumb">
                @hasSection('structured_breadcrumb_trail')
                    @yield('structured_breadcrumb_trail')
                @else
                    <a href="{{ route('home') }}" class="laravel-inner-breadcrumbs__link">{{ __('Home') }}</a>
                    <span class="laravel-inner-breadcrumbs__sep" aria-hidden="true">/</span>
                    <span class="laravel-inner-breadcrumbs__current">{{ $innerHeading }}</span>
                @endif
            </nav>
        </div>
    </div>
@endsection

@section('page_content')
    <div class="page_content_wrap laravel-structured-page">
        <div class="content_wrap">
            <div class="content">
                @yield('structured_primary')

                @includeWhen(isset($page), 'pages.partials.cms-page-blocks', ['page' => $page ?? null])

                @hasSection('structured_secondary')
                    @yield('structured_secondary')
                @endif
            </div>
        </div>

        @hasSection('structured_tertiary')
            @yield('structured_tertiary')
        @endif
    </div>
@endsection
