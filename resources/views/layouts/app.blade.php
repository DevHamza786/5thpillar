<!DOCTYPE html>
<html dir="{{ app()->getLocale() === 'ur' ? 'rtl' : 'ltr' }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js scheme_default">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="format-detection" content="telephone=no">
    <title>@yield('title', config('app.name'))</title>
    @include('layouts.partials.favicon')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Noto+Sans+Urdu:wght@100..900&display=swap">

    <link rel="stylesheet" href="{{ asset('assets/css/uaf.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome/all.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome/v4-shims.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugin/trx_addons_icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugin/trx_addons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/trx_addons/trx_addons.animation.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/trx_addons/magnific-popup.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/trx_addons/swiper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/site/fontello-embedded.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/site/__styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/site/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/site/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/site/__colors.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/site/child-style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/site/responsive.css') }}">
    @php
        $__bridgeCss = public_path('assets/css/site/laravel-bridge.css');
        $__bridgeVer = is_file($__bridgeCss) ? (string) filemtime($__bridgeCss) : '1';
    @endphp
    <link rel="stylesheet" href="{{ asset('assets/css/site/laravel-bridge.css') }}?v={{ $__bridgeVer }}">
    @stack('head')

</head>
<body class="@yield('body_class', 'custom-background wp-custom-logo wp-theme-shaha wp-child-theme-shaha-child body_tag scheme_default body_style_wide sidebar_hide expand_content remove_margins header_type_custom menu_style_ no_layout')">
    @php
        $pageTitle = trim($__env->yieldContent('page_title'));
        $bodyClassYield = trim($__env->yieldContent('body_class'));
        $useStructuredInnerChrome = str_contains($bodyClassYield, 'laravel-inner-page');
    @endphp
    <div class="body_wrap">
        <div class="page_wrap">
            @include('layouts.partials.header-top')
            @hasSection('header_masthead')
                @yield('header_masthead')
            @endif
            @include('layouts.partials.header-bottom')
            @yield('page_content')
            @include('layouts.partials.footer')
        </div>
    </div>

    <a href="#" class="trx_addons_scroll_to_top trx_addons_icon-up" title="Scroll to top"></a>

    <script src="{{ asset('assets/js/site/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/site/jquery-migrate.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/superfish.min.js') }}"></script>
    <script src="{{ asset('assets/js/site/__scripts.js') }}"></script>
    <script src="{{ asset('assets/js/site/laravel-bridge.js') }}"></script>
    
    @if(app()->getLocale() === 'ur')
        <div id="google_translate_element" hidden aria-hidden="true"></div>
        @php
            $__gtJs = public_path('assets/js/laravel-google-translate.js');
            $__gtVer = is_file($__gtJs) ? (string) filemtime($__gtJs) : '1';
        @endphp
        <script src="{{ asset('assets/js/laravel-google-translate.js') }}?v={{ $__gtVer }}"></script>
        <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" defer></script>
    @endif

    @stack('scripts')

</body>
</html>
