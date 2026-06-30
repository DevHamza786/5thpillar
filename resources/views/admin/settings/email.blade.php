@extends('admin.layouts.app')

@section('title', __('Email & form notifications'))

@section('content')
    <h1 class="wp-heading-inline">{{ __('Email & form notifications') }}</h1>
    <hr class="wp-header-end">

    <p class="subtitle">{{ __('Configure SMTP delivery and notification recipients for every public form and modal on the website.') }}</p>

    <form method="post" action="{{ route('admin.settings.email.update') }}">
        @csrf
        @method('PUT')

        <div class="postbox">
            <h2 class="postbox-header">{{ __('Mail server') }}</h2>
            <div class="inside">
                <div class="row cols-2">
                    <div>
                        <label for="mail_mailer">{{ __('Mailer') }}</label>
                        <select id="mail_mailer" name="mail[mailer]">
                            @foreach ($mailers as $value => $label)
                                <option value="{{ $value }}" @selected(old('mail.mailer', $mail['mailer'] ?? 'log') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="mail_from_address">{{ __('From email') }}</label>
                        <input id="mail_from_address" type="email" name="mail[from_address]" value="{{ old('mail.from_address', $mail['from_address'] ?? '') }}" class="large-text" required>
                    </div>
                </div>
                <div class="row cols-2">
                    <div>
                        <label for="mail_from_name">{{ __('From name') }}</label>
                        <input id="mail_from_name" type="text" name="mail[from_name]" value="{{ old('mail.from_name', $mail['from_name'] ?? '') }}" class="large-text" required>
                    </div>
                    <div>
                        <label for="mail_encryption">{{ __('Encryption') }}</label>
                        <select id="mail_encryption" name="mail[encryption]">
                            @foreach (['tls' => 'TLS', 'ssl' => 'SSL', '' => __('None')] as $value => $label)
                                <option value="{{ $value }}" @selected(old('mail.encryption', $mail['encryption'] ?? 'tls') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row cols-2">
                    <div>
                        <label for="mail_host">{{ __('SMTP host') }}</label>
                        <input id="mail_host" type="text" name="mail[host]" value="{{ old('mail.host', $mail['host'] ?? '') }}" class="large-text" placeholder="smtp.example.com">
                    </div>
                    <div>
                        <label for="mail_port">{{ __('SMTP port') }}</label>
                        <input id="mail_port" type="number" name="mail[port]" value="{{ old('mail.port', $mail['port'] ?? 587) }}" min="1" max="65535">
                    </div>
                </div>
                <div class="row cols-2">
                    <div>
                        <label for="mail_username">{{ __('SMTP username') }}</label>
                        <input id="mail_username" type="text" name="mail[username]" value="{{ old('mail.username', $mail['username'] ?? '') }}" class="large-text" autocomplete="off">
                    </div>
                    <div>
                        <label for="mail_password">{{ __('SMTP password') }}</label>
                        <input id="mail_password" type="password" name="mail[password]" value="" class="large-text" placeholder="{{ filled($mail['password'] ?? '') ? __('Leave blank to keep current password') : '' }}" autocomplete="new-password">
                    </div>
                </div>
            </div>
        </div>

        <div class="postbox">
            <h2 class="postbox-header">{{ __('Form notifications') }}</h2>
            <div class="inside">
                <p class="description">{{ __('Enable admin alerts and optional confirmation emails to visitors for each form.') }}</p>

                @foreach ($forms as $key => $form)
                    @php $oldPrefix = 'forms.'.$key; @endphp
                    <div class="cms-settings-form-block admin-settings-block">
                        <h3 class="admin-heading-sm--block">{{ __($form['label'] ?? $key) }}</h3>
                        <div class="row cols-2">
                            <div>
                                <p class="check">
                                    <input type="hidden" name="forms[{{ $key }}][enabled]" value="0">
                                    <input id="form-{{ $key }}-enabled" type="checkbox" name="forms[{{ $key }}][enabled]" value="1" @checked(old($oldPrefix.'.enabled', $form['enabled'] ?? true))>
                                    <label for="form-{{ $key }}-enabled">{{ __('Send admin notification') }}</label>
                                </p>
                            </div>
                            <div>
                                <p class="check">
                                    <input type="hidden" name="forms[{{ $key }}][user_confirmation]" value="0">
                                    <input id="form-{{ $key }}-confirm" type="checkbox" name="forms[{{ $key }}][user_confirmation]" value="1" @checked(old($oldPrefix.'.user_confirmation', $form['user_confirmation'] ?? false))>
                                    <label for="form-{{ $key }}-confirm">{{ __('Send confirmation email to visitor') }}</label>
                                </p>
                            </div>
                        </div>
                        <div class="row cols-2">
                            <div>
                                <label>{{ __('Notify email(s)') }}</label>
                                <input type="text" name="forms[{{ $key }}][to]" value="{{ old($oldPrefix.'.to', $form['to'] ?? '') }}" class="large-text" placeholder="info@example.com">
                                <p class="description">{{ __('Separate multiple with comma or semicolon.') }}</p>
                            </div>
                            <div>
                                <label>{{ __('CC email(s)') }}</label>
                                <input type="text" name="forms[{{ $key }}][cc]" value="{{ old($oldPrefix.'.cc', $form['cc'] ?? '') }}" class="large-text">
                            </div>
                        </div>
                        <div class="row cols-2">
                            <div>
                                <label>{{ __('Visitor confirmation subject') }}</label>
                                <input type="text" name="forms[{{ $key }}][user_subject]" value="{{ old($oldPrefix.'.user_subject', $form['user_subject'] ?? '') }}" class="large-text">
                            </div>
                            <div>
                                <label>{{ __('Visitor confirmation message') }}</label>
                                <textarea name="forms[{{ $key }}][user_message]" rows="3">{{ old($oldPrefix.'.user_message', $form['user_message'] ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <p class="submit">
            <button type="submit" class="button button-primary">{{ __('Save email settings') }}</button>
        </p>
    </form>

    <div class="postbox admin-postbox-mt">
        <h2 class="postbox-header">{{ __('Send test email') }}</h2>
        <div class="inside">
            <form method="post" action="{{ route('admin.settings.email.test') }}" class="cms-page-filters">
                <div class="cms-page-filters__field cms-page-filters__field--search">
                    <label for="test_email">{{ __('Email address') }}</label>
                    <input id="test_email" type="email" name="test_email" value="{{ old('test_email', auth()->user()->email ?? '') }}" required>
                </div>
                @csrf
                <input type="submit" class="button cms-page-filters__submit" value="{{ __('Send test') }}">
            </form>
        </div>
    </div>
@endsection
