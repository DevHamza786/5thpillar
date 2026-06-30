<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="wp-toolbar">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('Dashboard')) › {{ __('Content') }} — {{ config('app.name') }}</title>
    @include('layouts.partials.favicon')
    <link rel="stylesheet" href="https://s.w.org/wp-includes/css/dashicons.min.css?ver=6.7" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&family=Libre+Baskerville:ital@0;1&display=swap" rel="stylesheet">
    @php
        $__adminAppCss = public_path('assets/css/admin/admin-app.css');
        $__adminAppCssVer = is_file($__adminAppCss) ? (string) filemtime($__adminAppCss) : '1';
        $__adminDashCss = public_path('assets/css/admin/admin-dashboard.css');
        $__adminDashCssVer = is_file($__adminDashCss) ? (string) filemtime($__adminDashCss) : '1';
        $__adminCmsCss = public_path('assets/css/admin/admin-cms.css');
        $__adminCmsCssVer = is_file($__adminCmsCss) ? (string) filemtime($__adminCmsCss) : '1';
        $__adminNoticesJs = public_path('assets/js/admin/admin-notices.js');
        $__adminNoticesJsVer = is_file($__adminNoticesJs) ? (string) filemtime($__adminNoticesJs) : '1';
        $__adminShellJs = public_path('assets/js/admin/admin-shell.js');
        $__adminShellJsVer = is_file($__adminShellJs) ? (string) filemtime($__adminShellJs) : '1';
        $__adminDashJs = public_path('assets/js/admin/admin-dashboard.js');
        $__adminDashJsVer = is_file($__adminDashJs) ? (string) filemtime($__adminDashJs) : '1';
        $__adminCopyJs = public_path('assets/js/admin/admin-copy-url.js');
        $__adminCopyJsVer = is_file($__adminCopyJs) ? (string) filemtime($__adminCopyJs) : '1';
    @endphp
    <link rel="stylesheet" href="{{ asset('assets/css/admin/admin-app.css') }}?v={{ $__adminAppCssVer }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin/admin-dashboard.css') }}?v={{ $__adminDashCssVer }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin/admin-cms.css') }}?v={{ $__adminCmsCssVer }}">
    @stack('styles')
</head>
<body class="wp-admin wp-core-ui js">
    <div id="wpadminbar" role="navigation" aria-label="{{ __('Toolbar') }}">
        <button type="button" class="ab-item admin-shell-toggle" id="admin-shell-toggle" aria-expanded="false" aria-controls="adminmenumain">
            <span class="dashicons dashicons-menu" aria-hidden="true"></span>
            <span class="screen-reader-text">{{ __('Open menu') }}</span>
        </button>
        <a class="ab-item" href="{{ route('admin.dashboard') }}">
            <span class="ab-icon dashicons dashicons-admin-home ab-icon--home" aria-hidden="true"></span>
            <span>{{ config('app.name') }}</span>
        </a>
        <a class="ab-item" href="{{ route('home') }}" target="_blank" rel="noopener noreferrer">
            <span class="dashicons dashicons-external ab-icon--sm" aria-hidden="true"></span>
            {{ __('Visit site') }}
        </a>
        <span class="ab-spacer"></span>
        <div class="ab-sub-wrapper">
            @auth
                <span class="ab-item ab-greeting" tabindex="0">{{ sprintf(__('Howdy, %s'), Auth::user()->name) }}</span>
                <a class="ab-item" href="{{ route('admin.password.change') }}">
                    <span class="dashicons dashicons-lock ab-icon--sm" aria-hidden="true"></span>
                    {{ __('Change password') }}
                </a>
                <form action="{{ route('admin.logout') }}" method="post" class="admin-form-inline">
                    @csrf
                    <button type="submit" class="ab-item ab-logout-btn">
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
                    <li class="menu-top {{ request()->routeIs('admin.navigation.*') ? 'current wp-has-current-submenu' : '' }}">
                        <a class="menu-top" href="{{ route('admin.navigation.index') }}">
                            <span class="wp-menu-image dashicons-before dashicons-menu" aria-hidden="true"></span>
                            <span class="wp-menu-name">{{ __('Menu') }}</span>
                        </a>
                    </li>
                    <li class="menu-top menu-icon-page {{ request()->routeIs('admin.pages.*') ? 'current wp-has-current-submenu' : '' }}">
                        <a class="menu-top" href="{{ route('admin.pages.index') }}">
                            <span class="wp-menu-image dashicons-before dashicons-admin-page" aria-hidden="true"></span>
                            <span class="wp-menu-name">{{ __('Pages') }}</span>
                        </a>
                    </li>
                    @php $homeCmsPage = \App\Models\Page::query()->where('slug', 'home')->first(); @endphp
                    @if ($homeCmsPage)
                        <li class="menu-top {{ request()->routeIs('admin.pages.edit') && request()->route('page')?->slug === 'home' ? 'current wp-has-current-submenu' : '' }}">
                            <a class="menu-top" href="{{ route('admin.pages.edit', $homeCmsPage) }}">
                                <span class="wp-menu-image dashicons-before dashicons-admin-home" aria-hidden="true"></span>
                                <span class="wp-menu-name">{{ __('Homepage') }}</span>
                            </a>
                        </li>
                    @endif
                    <li class="menu-top {{ request()->routeIs('admin.media.*') ? 'current wp-has-current-submenu' : '' }}">
                        <a class="menu-top" href="{{ route('admin.media.index') }}">
                            <span class="wp-menu-image dashicons-before dashicons-admin-media" aria-hidden="true"></span>
                            <span class="wp-menu-name">{{ __('Media Library') }}</span>
                        </a>
                    </li>
                    <li class="menu-top {{ request()->routeIs('admin.cms-tables.*') ? 'current wp-has-current-submenu' : '' }}">
                        <a class="menu-top" href="{{ route('admin.cms-tables.index') }}">
                            <span class="wp-menu-image dashicons-before dashicons-editor-table" aria-hidden="true"></span>
                            <span class="wp-menu-name">{{ __('Data tables') }}</span>
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
                            <span class="wp-menu-name">{{ __('Hajj & Umrah planner data') }}</span>
                        </a>
                    </li>
                    <li class="menu-top {{ request()->routeIs('admin.settings.email*') ? 'current wp-has-current-submenu' : '' }}">
                        <a class="menu-top" href="{{ route('admin.settings.email') }}">
                            <span class="wp-menu-image dashicons-before dashicons-email" aria-hidden="true"></span>
                            <span class="wp-menu-name">{{ __('Email settings') }}</span>
                        </a>
                    </li>
                    <li class="menu-top {{ request()->routeIs('admin.settings.urdu*') ? 'current wp-has-current-submenu' : '' }}">
                        <a class="menu-top" href="{{ route('admin.settings.urdu') }}">
                            <span class="wp-menu-image dashicons-before dashicons-translation" aria-hidden="true"></span>
                            <span class="wp-menu-name">{{ __('Urdu URLs') }}</span>
                        </a>
                    </li>
                    <li class="menu-top {{ request()->routeIs('admin.backups.*') ? 'current wp-has-current-submenu' : '' }}">
                        <a class="menu-top" href="{{ route('admin.backups.index') }}">
                            <span class="wp-menu-image dashicons-before dashicons-backup" aria-hidden="true"></span>
                            <span class="wp-menu-name">{{ __('Backups') }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div id="wpcontent">
            <div id="wpbody" role="main">
                <div id="wpbody-content">
                    <div class="wrap admin-cms">
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

    <script src="{{ asset('assets/js/admin/admin-shell.js') }}?v={{ $__adminShellJsVer }}" defer></script>
    <script src="{{ asset('assets/js/admin/admin-notices.js') }}?v={{ $__adminNoticesJsVer }}" defer></script>
    <script src="{{ asset('assets/js/admin/admin-dashboard.js') }}?v={{ $__adminDashJsVer }}" defer></script>
    <script src="{{ asset('assets/js/admin/admin-copy-url.js') }}?v={{ $__adminCopyJsVer }}" defer></script>
    @stack('scripts')
</body>
</html>
