@extends('pages.layouts.structured-page')

@php
    $recaptchaSiteKey = config('services.recaptcha.site_key');
@endphp

@section('structured_meta_title', 'Delete my account - 5th Pillar Family Takaful')
@section('structured_page_title', 'Delete my account')

@section('structured_hero_title', 'Delete my account')

@push('head')
    @if ($recaptchaSiteKey)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endpush

@section('structured_primary')
    <article class="post_item_single page type-page laravel-account-deletion-page">
        <div class="post_content entry-content">
            <header class="laravel-account-deletion__intro">
                <h3 class="laravel-account-deletion__title">Account deletion request</h3>
                <h5 class="laravel-account-deletion__subtitle">Fill in the details linked with your 5th Pillar account</h5>
            </header>

            <div class="laravel-account-deletion__card">
                @if (session('account_deletion_status'))
                    <div class="laravel-contact__alert laravel-contact__alert--success" role="status">
                        {{ session('account_deletion_status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="laravel-contact__alert laravel-contact__alert--error" role="alert">
                        <ul class="laravel-contact__error-list">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    class="laravel-account-deletion__form"
                    method="post"
                    action="{{ route('delete-account.send') }}"
                    novalidate
                >
                    @csrf

                    <div class="laravel-account-deletion__field">
                        <label class="laravel-account-deletion__label" for="account-deletion-email">
                            Email <abbr class="laravel-account-deletion__req" title="required">*</abbr>
                        </label>
                        <input
                            id="account-deletion-email"
                            class="laravel-account-deletion__input"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required
                        >
                    </div>

                    <div class="laravel-account-deletion__field">
                        <label class="laravel-account-deletion__label" for="account-deletion-phone">
                            Phone <abbr class="laravel-account-deletion__req" title="required">*</abbr>
                        </label>
                        <input
                            id="account-deletion-phone"
                            class="laravel-account-deletion__input"
                            type="tel"
                            name="phone"
                            value="{{ old('phone') }}"
                            autocomplete="tel"
                            required
                        >
                    </div>

                    <div class="laravel-account-deletion__field">
                        <label class="laravel-account-deletion__label" for="account-deletion-reason">
                            Reason For Deletion <abbr class="laravel-account-deletion__req" title="required">*</abbr>
                        </label>
                        <textarea
                            id="account-deletion-reason"
                            class="laravel-account-deletion__textarea"
                            name="reason"
                            rows="6"
                            required
                        >{{ old('reason') }}</textarea>
                    </div>

                    @if ($recaptchaSiteKey)
                        <div class="laravel-contact__recaptcha laravel-account-deletion__recaptcha">
                            <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}"></div>
                        </div>
                    @endif

                    <button type="submit" class="laravel-account-deletion__submit">Submit</button>
                </form>
            </div>
        </div>
    </article>
@endsection
