@extends('admin.layouts.guest')

@section('title', __('Log In'))

@section('content')
    <p class="intro">{{ __('Sign in to manage pages, SEO, media, and daily fund prices.') }}</p>

    <form name="loginform" method="post" action="{{ route('admin.login.store') }}">
        @csrf
        <p class="field">
            <label for="user_login">{{ __('Email address') }}</label>
            <input type="email" name="email" id="user_login" class="input" value="{{ old('email') }}" size="20" autocomplete="username" required autofocus>
        </p>
        <p class="field">
            <label for="user_pass">{{ __('Password') }}</label>
            <input type="password" name="password" id="user_pass" class="input" value="" size="20" autocomplete="current-password" required>
        </p>
        <p class="forgetmenot">
            <input name="remember" type="checkbox" id="rememberme" value="1">
            <label for="rememberme">{{ __('Remember me') }}</label>
        </p>
        <p class="submit">
            <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary" value="{{ __('Log In') }}">
        </p>
    </form>
@endsection
