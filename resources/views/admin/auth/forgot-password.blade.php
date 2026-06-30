@extends('admin.layouts.guest')

@section('title', __('Forgot password'))
@section('heading', __('Reset your password'))
@section('subtitle', __('Enter your admin email and we will send you a secure reset link.'))

@section('content')
    <form class="admin-auth__form" method="post" action="{{ route('admin.password.email') }}" novalidate>
        @csrf

        <div class="admin-auth__field">
            <label class="admin-auth__label" for="reset_email">{{ __('Email address') }}</label>
            <input
                type="email"
                name="email"
                id="reset_email"
                class="admin-auth__input"
                value="{{ old('email') }}"
                autocomplete="email"
                required
                autofocus
            >
        </div>

        <button type="submit" class="admin-auth__submit">{{ __('Send reset link') }}</button>

        <p class="admin-auth__meta">
            <a class="admin-auth__link" href="{{ route('admin.login') }}">{{ __('Back to sign in') }}</a>
        </p>
    </form>
@endsection
