@extends('pages.layouts.structured-page')

@push('head')
    @if (config('services.recaptcha.site_key'))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endpush

@section('structured_meta_title', 'Group Life Takaful - 5th Pillar Family Takaful')
@section('structured_page_title', 'Group Life Takaful')

@section('structured_hero_title', 'Group Life Takaful')

@section('structured_primary')
    <article class="post_item_single type-page hentry laravel-hajj-page">
        <div class="post_content entry-content">
            <section class="laravel-hajj-hero-intro">
                <p class="laravel-hajj-lead">
                    5th Pillar Family Takaful Limited is presently offering Group Family Takaful Plan to its valuable customers. We aim to launch innovative Unit-Linked Savings &amp; Protection Plans catering to Hajj, Education, Marriage, Retirement etc. in the near future.
                </p>
            </section>

            <section class="laravel-hajj-block">
                <h2 class="laravel-hajj-section-title">Group Family Takaful Plan</h2>
                <p>
                    Group Family Takaful is a risk coverage plan that provides protection to participant (employer) employees in the event of death or disability, so that a multiple of that employee&rsquo;s yearly salary can be paid to his/her family or dependents to ease their financial difficulties. This plan is for employers who want to provide the maximum benefits in this competitive world to ensure job satisfaction to their valuable employees. It&rsquo;s the key to attract and keep quality employees.
                </p>
                <p>
                    The basic coverage can also be enhanced by adding coverage of risks that arise out of a natural calamity or by unpredictable accidents. The Plan is either contributory or sponsored by the employer/company as a yearly renewable agreement. The benefit provides payment of the agreed coverage amount in the event of death, due to any cause, of a participant employee.
                </p>
                <p>
                    Along with this plan employees can be provided with any of the following additional coverage.
                </p>

                <p><strong>Group Accidental Death Family Takaful (ADB)</strong></p>
                <p>
                    Accidents are always sudden and sometimes fatal. You can&rsquo;t lessen the emotional shock, but you can certainly soften the financial one. Group Accidental Death Family Takaful Benefit gives the loved ones something to start with after the permanent loss of income by paying an additional coverage amount.
                </p>

                <p><strong>Group Permanent Partial Disability Family Takaful (PPD)</strong></p>
                <p>
                    Accidents are unpredictable and so are the consequences. They may lead to a disability which is partial but permanent. This benefit provides a financial cushion against such events; the participant gets a particular amount in case of an eventuality as per the specified schedule.
                </p>

                <p><strong>Group Permanent Partial Disability Family Takaful (PPD) (Sickness)</strong></p>
                <p>
                    Sickness can expose individuals to unforeseen circumstances which may lead to disabilities due to which the individual covered is prevented partially and permanently from engaging in his or her usual business or occupation. This benefit provides protection against such events; the participant receives a stated percentage of the amount for which the individual is covered for the benefit as specified in &ldquo;Schedule of Indemnities&rdquo;.
                </p>

                <p><strong>Group Permanent Total Disability Family Takaful (PTD)</strong></p>
                <p>
                    An accident can be the cause of a disability which is permanent and total. This benefit provides a financial cushion in such situations to a participant where he/she is unable to earn a living. The permanent disability payouts are a percentage, according to a specified schedule, or the full amount of coverage to the participant according to the nature of loss.
                </p>

                <p><strong>Group Temporary Total Disability Family Takaful (TTD)</strong></p>
                <p>
                    In a situation where the participant is fortunate enough to escape from a major injury of a permanent nature and is temporarily disabled for a short period in an accident, the benefit will cover the loss of wages in the shape of a weekly payment of an agreed amount during the whole period of disability, until the time he regains health and resumes work.
                </p>

                <p><strong>Group Natural Disability Family Takaful (PTD) (N)</strong></p>
                <p>
                    Health is a gift of Allah, but it&rsquo;s unpredictable when one will be deprived of this blessing. The benefit provides coverage to a participant if he becomes permanently and totally disabled due to natural causes after which he is unable to perform his duty for which he is trained or experienced. This benefit provides payment of the whole sum covered.
                </p>

                <p><strong>Group Accidental Medical Expense Family Takaful (Med Ex)</strong></p>
                <p>
                    If a participant suffers an injury due to an accident, this benefit will cover the medical expense, up to a specified amount, during the period of hospitalization and/or medical treatment.
                </p>

                <p><strong>Group Critical Illness Takaful (CI)</strong></p>
                <p>
                    In case an employee is diagnosed as having one of the specified critical illnesses (e.g. artery surgery, stroke, cancer, renal failure etc.), this benefit will advance the amount payable on death to assist the covered person to pay off the expenses incurred against the treatment of critical illness.
                </p>

                <p class="laravel-hajj-lead" style="margin-top:1.25rem">
                    <strong>Disclaimer:</strong> The above plans give a general outline of the available benefits. For further plan details and eligibility criteria you may consult our Corporate Takaful Distribution Department.
                </p>

                <div class="laravel-hajj-actions">
                    <button type="button" class="laravel-hajj-btn laravel-brochure-trigger" data-brochure-key="group-family-takaful">Download Our Brochure</button>
                </div>
            </section>
        </div>

        @include('pages.partials.brochure-lead-modal')
    </article>
@endsection

@push('scripts')
    @include('pages.partials.brochure-modal-script')
@endpush
