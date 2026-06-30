@extends('admin.layouts.guest')

@section('title', __('Log In'))
@section('heading', __('Welcome back'))
@section('subtitle', __('Sign in to manage pages, SEO, media, and daily fund prices.'))

@section('content')
    <form class="admin-auth__form" method="post" action="{{ route('admin.login.store') }}" novalidate>
        @csrf

        <div class="admin-auth__field">
            <label class="admin-auth__label" for="user_login">{{ __('Email address') }}</label>
            <input
                type="email"
                name="email"
                id="user_login"
                class="admin-auth__input"
                value="{{ old('email') }}"
                autocomplete="username"
                required
                autofocus
            >
        </div>

        <div class="admin-auth__field">
            <div class="admin-auth__label-row">
                <label class="admin-auth__label" for="user_pass">{{ __('Password') }}</label>
                <a class="admin-auth__link" href="{{ route('admin.password.request') }}">{{ __('Forgot password?') }}</a>
            </div>
            <input
                type="password"
                name="password"
                id="user_pass"
                class="admin-auth__input"
                autocomplete="current-password"
                required
            >
        </div>

        <label class="admin-auth__checkbox">
            <input type="checkbox" name="remember" value="1" id="rememberme" @checked(old('remember'))>
            <span>{{ __('Remember me') }}</span>
        </label>

        <button type="submit" class="admin-auth__submit">{{ __('Sign in') }}</button>
    </form>
@endsection
