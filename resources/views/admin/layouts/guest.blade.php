<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('Log In')) — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://s.w.org/wp-includes/css/dashicons.min.css?ver=6.7" crossorigin="anonymous">
    @php
        $__adminLoginCss = public_path('assets/css/admin/admin-login.css');
        $__adminLoginCssVer = is_file($__adminLoginCss) ? (string) filemtime($__adminLoginCss) : '1';
    @endphp
    <link rel="stylesheet" href="{{ asset('assets/css/admin/admin-login.css') }}?v={{ $__adminLoginCssVer }}">
    @stack('styles')
</head>
<body class="login wp-core-ui">
    <div id="login">
        <h1>
            <a href="{{ route('home') }}" class="login-logo-text" title="{{ __('Go to site') }}">{{ config('app.name') }}</a>
        </h1>

        @if ($errors->any())
            <div class="notice" role="alert">
                @foreach ($errors->all() as $err)
                    <p><strong>{{ __('Error:') }}</strong> {{ $err }}</p>
                @endforeach
            </div>
        @endif

        @yield('content')

        <p id="nav">
            <a href="{{ route('home') }}">← {{ __('Back to site') }}</a>
        </p>
    </div>
    @stack('scripts')
</body>
</html>
