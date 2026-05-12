<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="wp-toolbar">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('Dashboard')) › {{ __('Content') }} — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://s.w.org/wp-includes/css/dashicons.min.css?ver=6.7" crossorigin="anonymous">
    @php
        $__adminAppCss = public_path('assets/css/admin/admin-app.css');
        $__adminAppCssVer = is_file($__adminAppCss) ? (string) filemtime($__adminAppCss) : '1';
        $__adminNoticesJs = public_path('assets/js/admin/admin-notices.js');
        $__adminNoticesJsVer = is_file($__adminNoticesJs) ? (string) filemtime($__adminNoticesJs) : '1';
    @endphp
    <link rel="stylesheet" href="{{ asset('assets/css/admin/admin-app.css') }}?v={{ $__adminAppCssVer }}">
    @stack('styles')
</head>
<body class="wp-admin wp-core-ui js">
    <div id="wpadminbar" role="navigation" aria-label="{{ __('Toolbar') }}">
        <a class="ab-item" href="{{ route('admin.dashboard') }}">
            <span class="ab-icon dashicons dashicons-admin-home" style="font-size:18px;width:20px;" aria-hidden="true"></span>
            <span>{{ config('app.name') }}</span>
        </a>
        <a class="ab-item" href="{{ route('home') }}" target="_blank" rel="noopener noreferrer">
            <span class="dashicons dashicons-external" style="font-size:16px;" aria-hidden="true"></span>
            {{ __('Visit site') }}
        </a>
        <span class="ab-spacer"></span>
        <div class="ab-sub-wrapper">
            @auth
                <span class="ab-item" tabindex="0">{{ sprintf(__('Howdy, %s'), Auth::user()->name) }}</span>
                <form action="{{ route('admin.logout') }}" method="post" style="display:inline;margin:0;">
                    @csrf
                    <button type="submit" class="ab-item" style="border:none;background:transparent;cursor:pointer;font:inherit;">
                        {{ __('Log out') }}
                    </button>
                </form>
            @endauth
        </div>
    </div>

    <div id="wpwrap">
        <div id="adminmenumain" role="navigation" aria-label="{{ __('Main menu') }}">
            <div id="adminmenuwrap">
                <ul id="adminmenu">
                    <li class="menu-top menu-icon-dashboard {{ request()->routeIs('admin.dashboard') ? 'current wp-has-current-submenu' : '' }}">
                        <a class="menu-top" href="{{ route('admin.dashboard') }}">
                            <span class="wp-menu-image dashicons-before dashicons-dashboard" aria-hidden="true"></span>
                            <span class="wp-menu-name">{{ __('Dashboard') }}</span>
                        </a>
                    </li>
                    <li class="menu-top menu-icon-page {{ request()->routeIs('admin.pages.*') ? 'current wp-has-current-submenu' : '' }}">
                        <a class="menu-top" href="{{ route('admin.pages.index') }}">
                            <span class="wp-menu-image dashicons-before dashicons-admin-page" aria-hidden="true"></span>
                            <span class="wp-menu-name">{{ __('Pages') }}</span>
                        </a>
                    </li>
                    <li class="menu-top {{ request()->routeIs('admin.navigation.*') ? 'current wp-has-current-submenu' : '' }}">
                        <a class="menu-top" href="{{ route('admin.navigation.index') }}">
                            <span class="wp-menu-image dashicons-before dashicons-menu" aria-hidden="true"></span>
                            <span class="wp-menu-name">{{ __('Navigation') }}</span>
                        </a>
                    </li>
                    <li class="menu-top {{ request()->routeIs('admin.fund-snapshots.*') ? 'current wp-has-current-submenu' : '' }}">
                        <a class="menu-top" href="{{ route('admin.fund-snapshots.index') }}">
                            <span class="wp-menu-image dashicons-before dashicons-chart-area" aria-hidden="true"></span>
                            <span class="wp-menu-name">{{ __('Daily fund prices') }}</span>
                        </a>
                    </li>
                    <li class="menu-top {{ request()->routeIs('admin.financial-data.*') ? 'current wp-has-current-submenu' : '' }}">
                        <a class="menu-top" href="{{ route('admin.financial-data.index') }}">
                            <span class="wp-menu-image dashicons-before dashicons-media-spreadsheet" aria-hidden="true"></span>
                            <span class="wp-menu-name">{{ __('Hajj Planner Data') }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div id="wpcontent">
            <div id="wpbody" role="main">
                <div id="wpbody-content">
                    <div class="wrap">
                        @if (session('status'))
                            <div class="notice notice-success is-dismissible" role="alert">
                                <p>{{ session('status') }}</p>
                                <button type="button" class="notice-dismiss" aria-label="{{ __('Dismiss this notice.') }}">
                                    <span class="screen-reader-text">{{ __('Dismiss') }}</span>
                                </button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="notice notice-error" role="alert">
                                @foreach ($errors->all() as $err)
                                    <p>{{ $err }}</p>
                                @endforeach
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/admin/admin-notices.js') }}?v={{ $__adminNoticesJsVer }}" defer></script>
    @stack('scripts')
</body>
</html>
