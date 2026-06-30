@extends('admin.layouts.guest')

@section('title', __('Set new password'))
@section('heading', __('Create a new password'))
@section('subtitle', __('Choose a strong password for your admin account.'))

@section('content')
    <form class="admin-auth__form" method="post" action="{{ route('admin.password.update') }}" novalidate>
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="admin-auth__field">
            <label class="admin-auth__label" for="reset_email">{{ __('Email address') }}</label>
            <input
                type="email"
                name="email"
                id="reset_email"
                class="admin-auth__input"
                value="{{ old('email', $email) }}"
                autocomplete="email"
                required
                autofocus
            >
        </div>

        <div class="admin-auth__field">
            <label class="admin-auth__label" for="password">{{ __('New password') }}</label>
            <input
                type="password"
                name="password"
                id="password"
                class="admin-auth__input"
                autocomplete="new-password"
                required
            >
        </div>

        <div class="admin-auth__field">
            <label class="admin-auth__label" for="password_confirmation">{{ __('Confirm password') }}</label>
            <input
                type="password"
                name="password_confirmation"
                id="password_confirmation"
                class="admin-auth__input"
                autocomplete="new-password"
                required
            >
        </div>

        <button type="submit" class="admin-auth__submit">{{ __('Update password') }}</button>

        <p class="admin-auth__meta">
            <a class="admin-auth__link" href="{{ route('admin.login') }}">{{ __('Back to sign in') }}</a>
        </p>
    </form>
@endsection
