@extends('pages.layouts.structured-page')

@php
    $recaptchaSiteKey = config('services.recaptcha.site_key');
@endphp

@section('structured_meta_title', 'Online Complaint Form - 5th Pillar Family Takaful Limited')
@section('structured_page_title', 'Online Complaint Form')

@section('structured_hero_title', 'Online Complaint Form')

@push('head')
    @if ($recaptchaSiteKey)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endpush

@section('structured_primary')
    <article class="post_item_single page type-page laravel-online-complaint-page">
        <div class="post_content entry-content">
            @if (session('online_complaint_status'))
                <div class="laravel-contact__alert laravel-contact__alert--success" role="status">
                    {{ session('online_complaint_status') }}
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
                class="laravel-online-complaint__form"
                method="post"
                action="{{ route('online-complaint-form.send') }}"
                enctype="multipart/form-data"
                novalidate
            >
                @csrf

                <div class="laravel-online-complaint__field">
                    <label class="laravel-online-complaint__label" for="online-complaint-pmd">PMD</label>
                    <input
                        id="online-complaint-pmd"
                        class="laravel-online-complaint__input"
                        type="text"
                        name="pmd"
                        value="{{ old('pmd') }}"
                        placeholder="PMD"
                        autocomplete="off"
                    >
                </div>

                <div class="laravel-online-complaint__field">
                    <label class="laravel-online-complaint__label" for="online-complaint-cnic">
                        CNIC Number <abbr class="laravel-online-complaint__req" title="required">*</abbr>
                    </label>
                    <input
                        id="online-complaint-cnic"
                        class="laravel-online-complaint__input"
                        type="text"
                        name="cnic"
                        value="{{ old('cnic') }}"
                        placeholder="Your CNIC"
                        inputmode="numeric"
                        autocomplete="off"
                        required
                    >
                </div>

                <div class="laravel-online-complaint__field">
                    <span class="laravel-online-complaint__label laravel-online-complaint__label--block" id="online-complaint-name-label">
                        Participant Name <abbr class="laravel-online-complaint__req" title="required">*</abbr>
                    </span>
                    <div class="laravel-online-complaint__name-row" role="group" aria-labelledby="online-complaint-name-label">
                        <label class="laravel-online-complaint__sr-only" for="online-complaint-salutation">Title</label>
                        <select
                            id="online-complaint-salutation"
                            class="laravel-online-complaint__select"
                            name="salutation"
                            required
                        >
                            @foreach (['Mr.', 'Mrs.', 'Miss.'] as $title)
                                <option value="{{ $title }}" @selected(old('salutation', 'Mr.') === $title)>{{ $title }}</option>
                            @endforeach
                        </select>
                        <label class="laravel-online-complaint__sr-only" for="online-complaint-participant-name">Participant name</label>
                        <input
                            id="online-complaint-participant-name"
                            class="laravel-online-complaint__input laravel-online-complaint__input--grow"
                            type="text"
                            name="participant_name"
                            value="{{ old('participant_name') }}"
                            placeholder="Participant Name"
                            autocomplete="name"
                            required
                        >
                    </div>
                </div>

                <div class="laravel-online-complaint__field">
                    <label class="laravel-online-complaint__label" for="online-complaint-cell">
                        Cell <abbr class="laravel-online-complaint__req" title="required">*</abbr>
                    </label>
                    <input
                        id="online-complaint-cell"
                        class="laravel-online-complaint__input"
                        type="tel"
                        name="cell"
                        value="{{ old('cell') }}"
                        placeholder="Cell"
                        autocomplete="tel"
                        required
                    >
                </div>

                <div class="laravel-online-complaint__field">
                    <label class="laravel-online-complaint__label" for="online-complaint-email">
                        Email <abbr class="laravel-online-complaint__req" title="required">*</abbr>
                    </label>
                    <input
                        id="online-complaint-email"
                        class="laravel-online-complaint__input"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Email"
                        autocomplete="email"
                        required
                    >
                </div>

                <div class="laravel-online-complaint__field">
                    <label class="laravel-online-complaint__label" for="online-complaint-details">Complaint Details</label>
                    <textarea
                        id="online-complaint-details"
                        class="laravel-online-complaint__textarea"
                        name="complaint_details"
                        rows="8"
                        placeholder="Please give details of the complaint (you may attach documents to this form)"
                    >{{ old('complaint_details') }}</textarea>
                </div>

                <div class="laravel-online-complaint__field">
                    <label class="laravel-online-complaint__label" for="online-complaint-attachment">Attachment</label>
                    <input
                        id="online-complaint-attachment"
                        class="laravel-online-complaint__file"
                        type="file"
                        name="attachment"
                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,application/pdf,image/*"
                    >
                </div>

                @if ($recaptchaSiteKey)
                    <div class="laravel-contact__recaptcha laravel-online-complaint__recaptcha">
                        <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}"></div>
                    </div>
                @endif

                <button type="submit" class="laravel-online-complaint__submit sc_button sc_button_default">
                    <span class="sc_button_text"><span class="sc_button_title">Submit</span></span>
                </button>
            </form>
        </div>
    </article>
@endsection
