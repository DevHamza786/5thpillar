<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('Log In')) — {{ config('app.name') }}</title>
    @include('layouts.partials.favicon')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap">
    @php
        $__adminLoginCss = public_path('assets/css/admin/admin-login.css');
        $__adminLoginCssVer = is_file($__adminLoginCss) ? (string) filemtime($__adminLoginCss) : '1';
    @endphp
    <link rel="stylesheet" href="{{ asset('assets/css/admin/admin-login.css') }}?v={{ $__adminLoginCssVer }}">
    @stack('styles')
</head>
<body class="admin-auth">
    <div class="admin-auth__shell">
        <aside class="admin-auth__brand" aria-hidden="true">
            <div class="admin-auth__brand-inner">
                <div class="admin-auth__brand-mark">
                    <img src="{{ asset('assets/images/logos/cropped-5th-pilar-logo-64d5d6041dbd1.webp') }}" alt="" width="120" height="auto">
                </div>
                <p class="admin-auth__brand-tagline">{{ __('Content management for 5th Pillar Family Takaful') }}</p>
                <ul class="admin-auth__brand-points">
                    <li>{{ __('Pages & SEO') }}</li>
                    <li>{{ __('Navigation & media') }}</li>
                    <li>{{ __('Fund prices & data') }}</li>
                </ul>
            </div>
            <div class="admin-auth__brand-pattern"></div>
        </aside>

        <main class="admin-auth__main">
            <div class="admin-auth__card">
                <header class="admin-auth__header">
                    <a href="{{ route('home') }}" class="admin-auth__logo-mobile" title="{{ __('Go to site') }}">
                        <img src="{{ asset('assets/images/logos/cropped-5th-pilar-logo-64d5d6041dbd1.webp') }}" alt="{{ config('app.name') }}" width="96" height="auto">
                    </a>
                    <h1 class="admin-auth__title">@yield('heading', __('Sign in'))</h1>
                    @hasSection('subtitle')
                        <p class="admin-auth__subtitle">@yield('subtitle')</p>
                    @endif
                </header>

                @if (session('status'))
                    <div class="admin-auth__alert admin-auth__alert--success" role="status">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="admin-auth__alert admin-auth__alert--error" role="alert">
                        @foreach ($errors->all() as $err)
                            <p>{{ $err }}</p>
                        @endforeach
                    </div>
                @endif

                @yield('content')
            </div>

            <footer class="admin-auth__footer">
                <a href="{{ route('home') }}" class="admin-auth__back">← {{ __('Back to site') }}</a>
            </footer>
        </main>
    </div>
    @stack('scripts')
</body>
</html>
