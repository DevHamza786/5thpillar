@php
    $recaptchaSiteKey = config('services.recaptcha.site_key');
    $cities = config('brochures.cities', []);
@endphp

<div
    id="laravel-brochure-modal"
    class="laravel-brochure-modal{{ $errors->any() ? ' is-open' : '' }}"
    role="dialog"
    aria-modal="true"
    aria-labelledby="laravel-brochure-modal-title"
    aria-hidden="{{ $errors->any() ? 'false' : 'true' }}"
>
    <div class="laravel-brochure-modal__backdrop laravel-brochure-close" tabindex="-1"></div>
    <div class="laravel-brochure-modal__panel">
        <button type="button" class="laravel-brochure-modal__close laravel-brochure-close" aria-label="Close">&times;</button>
        <h2 id="laravel-brochure-modal-title" class="laravel-brochure-modal__title">Let&rsquo;s start with a quick intro!</h2>

        @if ($errors->any())
            <div class="laravel-brochure-modal__alert" role="alert">
                <ul class="laravel-brochure-modal__errors">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="laravel-brochure-form" method="post" action="{{ route('brochure-lead.store') }}" novalidate>
            @csrf
            <input type="hidden" name="brochure_key" id="brochure_key" value="{{ old('brochure_key', 'haazir') }}">

            <div class="laravel-brochure-form__grid">
                <label class="laravel-brochure-form__field">
                    <span class="laravel-brochure-form__label">Your name <abbr title="required">*</abbr></span>
                    <input type="text" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Your name*">
                </label>
                <label class="laravel-brochure-form__field">
                    <span class="laravel-brochure-form__label">Your e-mail <abbr title="required">*</abbr></span>
                    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Your e-mail*">
                </label>
                <label class="laravel-brochure-form__field">
                    <span class="laravel-brochure-form__label">Your Contact Number <abbr title="required">*</abbr></span>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required autocomplete="tel" placeholder="Your Contact Number*">
                </label>
                <label class="laravel-brochure-form__field">
                    <span class="laravel-brochure-form__label">Address</span>
                    <input type="text" name="address" value="{{ old('address') }}" autocomplete="street-address" placeholder="Address">
                </label>
                <label class="laravel-brochure-form__field">
                    <span class="laravel-brochure-form__label">Gender</span>
                    <span class="laravel-brochure-form__select-wrap">
                        <select name="gender" required>
                            <option value="male" @selected(old('gender', 'male') === 'male')>Male</option>
                            <option value="female" @selected(old('gender') === 'female')>Female</option>
                        </select>
                    </span>
                </label>
                <label class="laravel-brochure-form__field">
                    <span class="laravel-brochure-form__label">City</span>
                    <span class="laravel-brochure-form__select-wrap">
                        <select name="city" required>
                            <option value="" disabled @selected(old('city') === null)>Select city</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city }}" @selected(old('city') === $city)>{{ $city }}</option>
                            @endforeach
                        </select>
                    </span>
                </label>
                <label class="laravel-brochure-form__field">
                    <span class="laravel-brochure-form__label">Company</span>
                    <input type="text" name="company" value="{{ old('company') }}" autocomplete="organization" placeholder="Company">
                </label>
                <label class="laravel-brochure-form__field">
                    <span class="laravel-brochure-form__label">Job Title</span>
                    <input type="text" name="job_title" value="{{ old('job_title') }}" autocomplete="organization-title" placeholder="Job Title">
                </label>
            </div>

            @if ($recaptchaSiteKey)
                <div class="laravel-brochure-form__recaptcha">
                    <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}"></div>
                </div>
            @endif

            <div class="laravel-brochure-form__submit">
                <button type="submit" class="laravel-brochure-form__btn">Submit</button>
            </div>
        </form>
    </div>
</div>
