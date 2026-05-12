@extends('pages.layouts.structured-page')

@php
    $recaptchaSiteKey = config('services.recaptcha.site_key');
@endphp

@section('structured_meta_title', 'Contact - 5th Pillar Family Takaful Limited')
@section('structured_page_title', 'Contact')

@section('structured_hero_title', 'Contact')

@push('head')
    @if ($recaptchaSiteKey)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endpush

@section('structured_primary')
    <article class="post_item_single page type-page laravel-contact-page">
        <div class="post_content entry-content">
            @if (session('contact_status'))
                <div class="laravel-contact__alert laravel-contact__alert--success" role="status">
                    {{ session('contact_status') }}
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

            <div class="laravel-contact__grid">
                <div class="laravel-contact__col laravel-contact__col--form">
                    <h2 class="laravel-contact__heading">Get in Touch</h2>
                    <p class="laravel-contact__lead">For queries, please write to us</p>

                    <div class="laravel-contact__tabs" data-contact-tabs data-initial-contact-type="{{ old('contact_type', 'complaint') }}">
                        <div class="laravel-contact__tablist" role="tablist" aria-label="Contact type">
                            <button type="button" class="laravel-contact__tab is-active" role="tab" aria-selected="true" data-contact-tab="complaint" id="contact-tab-complaint">
                                Complaint
                            </button>
                            <button type="button" class="laravel-contact__tab" role="tab" aria-selected="false" data-contact-tab="inquiry" id="contact-tab-inquiry">
                                Inquiry
                            </button>
                        </div>

                        <div class="laravel-contact__tab-panel" role="tabpanel" aria-labelledby="contact-tab-complaint">
                            <form class="laravel-contact__form" method="post" action="{{ route('contact.send') }}" novalidate>
                                @csrf
                                <input type="hidden" name="contact_type" value="{{ old('contact_type', 'complaint') }}" data-contact-type-input>

                                <label class="laravel-contact__field">
                                    <span class="laravel-contact__label visually-hidden">Your name</span>
                                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Your name*" autocomplete="name" required>
                                </label>
                                <label class="laravel-contact__field">
                                    <span class="laravel-contact__label visually-hidden">Your e-mail</span>
                                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Your e-mail*" autocomplete="email" required>
                                </label>
                                <label class="laravel-contact__field">
                                    <span class="laravel-contact__label visually-hidden">Your phone</span>
                                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="Your phone*" autocomplete="tel" required>
                                </label>
                                <label class="laravel-contact__field">
                                    <span class="laravel-contact__label visually-hidden">Your city</span>
                                    <input type="text" name="city" value="{{ old('city') }}" placeholder="Your city*" autocomplete="address-level2" required>
                                </label>
                                <label class="laravel-contact__field laravel-contact__field--message">
                                    <span class="laravel-contact__label visually-hidden">Your message</span>
                                    <textarea name="message" rows="6" placeholder="Your message*" required>{{ old('message') }}</textarea>
                                </label>

                                @if ($recaptchaSiteKey)
                                    <div class="laravel-contact__recaptcha">
                                        <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}"></div>
                                    </div>
                                @endif

                                <button type="submit" class="laravel-contact__submit sc_button sc_button_default">
                                    <span class="sc_button_text"><span class="sc_button_title">Send</span></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="laravel-contact__col laravel-contact__col--info">
                    <h2 class="laravel-contact__heading">Contact Info</h2>

                    <div class="laravel-contact__offices">
                        <div class="laravel-contact__office">
                            <h3 class="laravel-contact__office-title">Head Office:</h3>
                            <p class="laravel-contact__office-text">
                                Office No. SF-01 to SF-06,<br>
                                2nd Floor, Emerald Mall,<br>
                                Near Do-Talwar,<br>
                                Clifton, Block 5,<br>
                                Karachi, Pakistan.
                            </p>
                            <p class="laravel-contact__office-meta">
                                UAN: <a href="tel:021111786573">021-111-786-573</a><br>
                                Phone: <a href="tel:02136492074">021-36492074</a><br>
                                Email: <a href="mailto:info@5thpillartakaful.com">info@5thpillartakaful.com</a>
                            </p>
                        </div>
                        <div class="laravel-contact__office">
                            <h3 class="laravel-contact__office-title">Lahore Branch:</h3>
                            <p class="laravel-contact__office-text">
                                Office No. 618 – 620,<br>
                                6th Floor, Al-Hafeez Executive,<br>
                                30-C-3, Gulberg III,<br>
                                Lahore, Pakistan.
                            </p>
                            <p class="laravel-contact__office-meta">
                                Phone: <a href="tel:04238022704">042-38022704</a>
                            </p>
                        </div>
                    </div>

                    <div class="laravel-contact__map">
                        <iframe
                            title="5th Pillar Family Takaful Limited — Head Office map"
                            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14484.91520674259!2d67.0340525!3d24.8218479!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3eb33de7bd00d03%3A0xc5eab8e589e5cc13!2s5th%20Pillar%20Family%20Takaful%20Limited!5e0!3m2!1sen!2s!4v1686651161641!5m2!1sen!2s"
                            width="600"
                            height="450"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                        ></iframe>
                    </div>
                </div>
            </div>
        </div>
    </article>
@endsection
