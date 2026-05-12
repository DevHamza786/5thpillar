<!DOCTYPE html>
<html dir="{{ app()->getLocale() === 'ur' ? 'rtl' : 'ltr' }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js scheme_default">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="format-detection" content="telephone=no">
    <title>@yield('title', '5th Pillar Family Takaful')</title>
    <link rel="icon" href="{{ asset('uploads/2023/2023/05/cropped-5th-Pilar-Logo-32x32.png') }}" sizes="32x32" type="image/png">
    <link rel="icon" href="{{ asset('uploads/2023/2023/05/cropped-5th-Pilar-Logo-192x192.png') }}" sizes="192x192" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('uploads/2023/2023/05/cropped-5th-Pilar-Logo-180x180.png') }}">
    <meta name="msapplication-TileImage" content="{{ asset('uploads/2023/2023/05/cropped-5th-Pilar-Logo-270x270.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Noto+Sans+Urdu:wght@100..900&display=swap">

    <link rel="stylesheet" href="{{ asset('assets/vendor/useanyfont/uaf.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/v4-shims.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/original-theme/plugin-css/trx_addons_icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/original-theme/plugin-css/trx_addons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/trx_addons/css/trx_addons.animation.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/trx_addons/css/magnific-popup.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/trx_addons/css/swiper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/original-theme/css/fontello-embedded.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/original-theme/css/__styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/original-theme/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/original-theme/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/original-theme/css/__colors.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/original-theme/css/child-style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/original-theme/css/responsive.css') }}">
    @php
        $__bridgeCss = public_path('assets/vendor/original-theme/css/laravel-bridge.css');
        $__bridgeVer = is_file($__bridgeCss) ? (string) filemtime($__bridgeCss) : '1';
    @endphp
    <link rel="stylesheet" href="{{ asset('assets/vendor/original-theme/css/laravel-bridge.css') }}?v={{ $__bridgeVer }}">
    @if(app()->getLocale() === 'ur')
    <style>
        body, h1, h2, h3, h4, h5, h6, p, a, span, li, button, input {
            font-family: 'Noto Sans Urdu', sans-serif !important;
        }
    </style>
    @endif
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

    <script src="{{ asset('assets/vendor/original-theme/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/original-theme/js/jquery-migrate.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/original-theme/plugin-js/superfish.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/original-theme/js/__scripts.js') }}"></script>
    <script src="{{ asset('assets/vendor/original-theme/js/laravel-bridge.js') }}"></script>
    
    @if(app()->getLocale() === 'ur')
    <div id="google_translate_element" style="display:none"></div>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'ur',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate_element');
            
            setTimeout(function() {
                var select = document.querySelector('select.goog-te-combo');
                if (select) {
                    select.value = 'ur';
                    select.dispatchEvent(new Event('change'));
                }
            }, 1000);
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <style>
        .goog-te-banner-frame.skiptranslate, .goog-te-gadget-icon, .goog-logo-link { display: none !important; }
        body { top: 0px !important; }
        .goog-tooltip { display: none !important; }
        .goog-tooltip:hover { display: none !important; }
        .goog-text-highlight { background-color: transparent !important; border: none !important; box-shadow: none !important; }
    </style>
    @endif

    @stack('scripts')

</body>
</html>
