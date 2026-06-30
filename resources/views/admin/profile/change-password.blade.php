@extends('admin.layouts.app')

@section('title', __('Change password'))

@section('content')
    @php($user = Auth::user())

    <div class="admin-dash">
        <header class="admin-dash__hero admin-dash__hero--compact admin-dash__reveal">
            <div class="admin-dash__hero-glow" aria-hidden="true"></div>
            <div class="admin-dash__hero-inner">
                <p class="admin-dash__kicker">{{ __('Account security') }}</p>
                <h1 class="admin-dash__title">{{ __('Change password') }}</h1>
                <p class="admin-dash__lede">{{ __('Keep your CMS access secure with a strong, unique password.') }}</p>
            </div>
            <div class="admin-dash__hero-actions">
                <a class="admin-dash__link-site admin-dash__link-site--quiet" href="{{ route('admin.dashboard') }}">
                    <span class="dashicons dashicons-arrow-left-alt" aria-hidden="true"></span>
                    {{ __('Back to dashboard') }}
                </a>
            </div>
        </header>

        <div class="admin-dash__profile-layout">
            <aside class="admin-dash__profile-card admin-dash__reveal" data-reveal-index="1" aria-label="{{ __('Profile') }}">
                <div class="admin-dash__profile-avatar" aria-hidden="true">{{ strtoupper(mb_substr($user->name, 0, 1)) }}</div>
                <h2 class="admin-dash__profile-name">{{ $user->name }}</h2>
                <p class="admin-dash__profile-email">{{ $user->email }}</p>
                <ul class="admin-dash__profile-meta">
                    <li>
                        <span class="dashicons dashicons-shield" aria-hidden="true"></span>
                        {{ __('CMS administrator') }}
                    </li>
                    <li>
                        <span class="dashicons dashicons-lock" aria-hidden="true"></span>
                        {{ __('Password protected area') }}
                    </li>
                </ul>
            </aside>

            <section class="admin-dash__form-card admin-dash__reveal" data-reveal-index="2" aria-labelledby="change-password-heading">
                <header class="admin-dash__form-card-head">
                    <div class="admin-dash__form-card-icon" aria-hidden="true">
                        <span class="dashicons dashicons-lock"></span>
                    </div>
                    <div>
                        <h2 id="change-password-heading">{{ __('Update your password') }}</h2>
                        <p>{{ __('Enter your current password, then choose a new one (minimum 8 characters).') }}</p>
                    </div>
                </header>

                <form class="admin-dash__password-form" method="post" action="{{ route('admin.password.change.update') }}" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="admin-dash__field">
                        <label class="admin-dash__label" for="current_password">{{ __('Current password') }}</label>
                        <input
                            id="current_password"
                            class="admin-dash__input"
                            type="password"
                            name="current_password"
                            autocomplete="current-password"
                            placeholder="{{ __('Enter current password') }}"
                            required
                        >
                    </div>

                    <div class="admin-dash__field-row">
                        <div class="admin-dash__field">
                            <label class="admin-dash__label" for="password">{{ __('New password') }}</label>
                            <input
                                id="password"
                                class="admin-dash__input"
                                type="password"
                                name="password"
                                autocomplete="new-password"
                                placeholder="{{ __('At least 8 characters') }}"
                                required
                            >
                        </div>
                        <div class="admin-dash__field">
                            <label class="admin-dash__label" for="password_confirmation">{{ __('Confirm new password') }}</label>
                            <input
                                id="password_confirmation"
                                class="admin-dash__input"
                                type="password"
                                name="password_confirmation"
                                autocomplete="new-password"
                                placeholder="{{ __('Repeat new password') }}"
                                required
                            >
                        </div>
                    </div>

                    <div class="admin-dash__form-actions">
                        <button type="submit" class="admin-dash__cta admin-dash__cta--primary">
                            <span class="dashicons dashicons-yes" aria-hidden="true"></span>
                            {{ __('Update password') }}
                        </button>
                        <a class="admin-dash__cta admin-dash__cta--quiet" href="{{ route('admin.dashboard') }}">{{ __('Cancel') }}</a>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection
