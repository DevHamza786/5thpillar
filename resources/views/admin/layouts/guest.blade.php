<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('Log In')) — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://s.w.org/wp-includes/css/dashicons.min.css?ver=6.7" crossorigin="anonymous">
    <style>
        html { background: #f0f0f1; }
        body.login {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            font-size: 14px; line-height: 1.4; color: #3c434a; margin: 0; min-height: 100vh;
            display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 24px 12px;
        }
        #login { width: 100%; max-width: 320px; }
        #login h1 { text-align: center; margin: 0 0 24px; }
        #login h1 a {
            display: inline-block; text-indent: -9999px; overflow: hidden;
            width: 84px; height: 84px; background: #2271b1; border-radius: 8px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath fill='%23fff' d='M10 2L2 7v11h6v-6h4v6h6V7l-8-5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: center; background-size: 40px;
            box-shadow: 0 1px 3px rgba(0,0,0,.12);
        }
        #login h1 .login-logo-text {
            display: block; text-indent: 0; width: auto; height: auto; padding: 16px 12px;
            font-size: 18px; font-weight: 600; color: #1d2327; text-decoration: none; line-height: 1.3;
            background: #fff; border: 1px solid #c3c4c7; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,.04);
        }
        .login form {
            margin-top: 0; margin-left: 0; padding: 26px 24px 34px; background: #fff;
            border: 1px solid #c3c4c7; box-shadow: 0 1px 3px rgba(0,0,0,.04);
        }
        .login form .field { margin-bottom: 16px; }
        .login label { font-size: 14px; line-height: 1.5; display: inline-block; margin-bottom: 4px; font-weight: 600; color: #1d2327; }
        .login input[type="text"], .login input[type="email"], .login input[type="password"] {
            font-size: 16px; width: 100%; padding: 6px 8px; margin: 0; min-height: 40px;
            border: 1px solid #8c8f94; border-radius: 4px; box-shadow: 0 0 0 transparent;
        }
        .login input:focus { border-color: #2271b1; box-shadow: 0 0 0 1px #2271b1; outline: 2px solid transparent; }
        .login .forgetmenot { margin: 12px 0 24px; font-weight: 400; float: none; }
        .login .forgetmenot label { font-weight: 400; }
        .login .forgetmenot input { width: auto; margin-right: 6px; vertical-align: middle; }
        .login .submit { display: block; float: none; width: 100%; }
        .login .button-primary {
            float: none; width: 100%; min-height: 40px; line-height: 2.30769231; padding: 0 12px;
            background: #2271b1; border-color: #2271b1; color: #fff; text-shadow: none;
            border-radius: 3px; border-width: 1px; border-style: solid; cursor: pointer; font-size: 14px;
        }
        .login .button-primary:hover { background: #135e96; border-color: #135e96; }
        .login #nav { text-align: center; margin: 24px 0 0; padding: 0; }
        .login #nav a { color: #50575e; text-decoration: none; font-size: 13px; }
        .login #nav a:hover { color: #135e96; }
        .notice {
            border-left: 4px solid #d63638; background: #fff; border-top: 1px solid #c3c4c7;
            border-right: 1px solid #c3c4c7; border-bottom: 1px solid #c3c4c7;
            padding: 12px; margin: 0 0 16px; font-size: 13px;
        }
        .notice p { margin: 0.4em 0; }
        .login .intro { text-align: center; color: #646970; font-size: 13px; margin: -8px 0 16px; }
    </style>
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
