@extends('pages.layouts.structured-page')

@push('head')
    @php
        $__groupLifeCss = public_path('assets/css/pages/group-life-takaful.css');
        $__groupLifeVer = is_file($__groupLifeCss) ? (string) filemtime($__groupLifeCss) : '1';
    @endphp
    <link rel="stylesheet" href="{{ asset('assets/css/pages/group-life-takaful.css') }}?v={{ $__groupLifeVer }}">
@endpush

@section('structured_meta_title', 'Group Life Takaful - 5th Pillar Family Takaful')
@section('structured_page_title', 'Group Life Takaful')

@section('structured_hero_title', 'Group Life Takaful')

@section('structured_primary')
    <article class="post_item_single type-page hentry laravel-hajj-page laravel-group-life-page">
        <div class="post_content entry-content">
            <section class="laravel-gl-band laravel-gl-band--plan-centered" aria-labelledby="gl-plan-heading">
                <div class="laravel-gl-band__inner">
                    <div class="laravel-gl-plan-centered">
                        <p class="laravel-gl-plan-centered__lead">
                            5th Pillar Family Takaful Limited is presently offering Group Family Takaful Plan to its valuable customers. We aim to launch innovative Unit-Linked Savings &amp; Protection Plans catering to Hajj, Education, Marriage, Retirement etc. in near future.
                        </p>
                        <h2 class="laravel-hajj-section-title">Group Family Takaful Plan</h2>
                        <p class="laravel-gl-plan-centered__body">
                            Group Family Takaful is a risk coverage plan that provides protection to participant (employer) employees in the event of death or disability, so that a multiple of that employee&rsquo;s yearly salary can be paid to his/her family or dependents to ease their financial difficulties. This plan is for employers who wants to provide the maximum benefits in this competitive world to ensure job satisfaction to their valuable employees. It&rsquo;s the key to attract and keep quality employees.
                        </p>
                        <p class="laravel-gl-plan-centered__body">
                            The basic coverage can also be enhanced by adding coverage of risks that are arising out of a natural calamity or by unpredictable accidents. The Plan is either contributory or sponsored by the employer/company as a yearly renewable agreement. The benefit provides payment of the agreed coverage amount in the event of death, due to any cause, of a participant employee.
                            <br>
                            Along with this plan employees can be provided with any of the following additional coverage.
                        </p>
                    </div>
                </div>
            </section>

            <section class="laravel-gl-band laravel-gl-benefits-banner5" aria-label="{{ __('Group coverage: ADB, PPD') }}">
                <div class="laravel-gl-benefits-banner5__bg" aria-hidden="true"></div>
                <div class="laravel-gl-benefits-banner5__tint" aria-hidden="true"></div>
                <div class="laravel-gl-benefits-banner5__inner">
                    <div class="laravel-gl-benefits-banner5__copy">
                        <div class="laravel-gl-benefits">
                            <article class="laravel-gl-benefit">
                                <h3 class="laravel-gl-benefit__title">Group Accidental Death Family Takaful-ADB</h3>
                                <p>
                                    Accidents are always sudden and sometimes fatal. You can&rsquo;t lessen the emotional shock, but you can certainly soften the financial one. Group Accidental Death Family Takaful Benefit gives the loved ones something to start with after the permanent loss of income by paying an additional coverage amount.
                                </p>
                            </article>

                            <article class="laravel-gl-benefit">
                                <h3 class="laravel-gl-benefit__title">Group Permanent Partial Disability Family Takaful-PPD</h3>
                                <p>
                                    Accidents are unpredictable and so are the consequences. They may lead to a disability which is partial but permanent. This benefit provides a financial cushion against such events; the participant gets a particular amount in case of an eventuality as per the specified schedule.
                                </p>
                            </article>

                            <article class="laravel-gl-benefit">
                                <h3 class="laravel-gl-benefit__title">Group Permanent Partial Disability Family Takaful-PPD (Sickness)</h3>
                                <p>
                                    Sickness(es) can expose the individuals to unforeseen circumstances which may lead to disabilities due to which the individual covered is prevented partially and permanently from engaging in his or her usual business or occupation. This benefit provides protection against such events; the participant receives a stated percentage of the amount for which the individual is covered for the benefit as specified in &ldquo;Schedule of Indemnities&rdquo;.
                                </p>
                            </article>
                        </div>
                    </div>
                </div>
            </section>

            <section class="laravel-gl-band laravel-gl-benefits-panel" aria-label="{{ __('Additional group coverage') }}">
                <div class="laravel-gl-benefits-panel__bg" aria-hidden="true"></div>
                <div class="laravel-gl-benefits-panel__tint" aria-hidden="true"></div>
                <div class="laravel-gl-benefits-panel__inner">
                    <div class="laravel-gl-benefits-panel__copy">
                        <div class="laravel-gl-benefits">
                            <article class="laravel-gl-benefit">
                                <h3 class="laravel-gl-benefit__title">Group Permanent Total Disability Family Takaful-PTD</h3>
                                <p>
                                    An accident can be the cause of a disability which is permanent and total. This benefit provides a financial cushion in such situations to a participant where he/she is unable to earn his living. The permanent disability payouts are a percentage, according to a specified schedule, or the full amount of coverage to the participant according to the nature of loss.
                                </p>
                            </article>

                            <article class="laravel-gl-benefit">
                                <h3 class="laravel-gl-benefit__title">Group Temporary Total Disability Family Takaful-TTD</h3>
                                <p>
                                    In a situation where the participant is fortunate enough to escape from a major injury of a permanent nature and is temporarily disabled for a short period in an accident, the benefit will cover for the loss of wages in the shape of a weekly payment of an agreed amount during the whole period of disability, till the time he regains health and resumes work.
                                </p>
                            </article>

                            <article class="laravel-gl-benefit">
                                <h3 class="laravel-gl-benefit__title">Group Natural Disability Family Takaful-PTD (N)</h3>
                                <p>
                                    Health is a gift of Allah, but it&rsquo;s unpredictable when one will be deprived of this blessing. The benefit provides coverage to a participant if he becomes permanently and totally disabled due to natural causes after which he is unable to perform his duty for which he is trained or experienced. This benefit provides payment of the whole sum covered.
                                </p>
                            </article>

                            <article class="laravel-gl-benefit">
                                <h3 class="laravel-gl-benefit__title">Group Accidental Medical Expense Family Takaful-Med Ex</h3>
                                <p>
                                    If a participant suffers an injury due to an accident, this benefit will cover the medical expense, up to a specified amount, during the period of hospitalization and or medical treatment.
                                </p>
                            </article>

                            <article class="laravel-gl-benefit">
                                <h3 class="laravel-gl-benefit__title">Group Critical Illness Takaful (CI)</h3>
                                <p>
                                    In case, an employee is diagnosed as having one of the specified critical illnesses: Artery Surgery, Stroke, Cancer, Renal Failure etc. This benefit will advance the amount payable on death to assist the covered person to pay off the expenses incurred against the treatment of critical illness.
                                </p>
                            </article>
                        </div>
                    </div>
                </div>
            </section>

            <section class="laravel-gl-band laravel-gl-band--disclaimer">
                <div class="laravel-gl-band__inner">
                    <p class="laravel-gl-disclaimer">
                        <strong>Disclaimer:</strong> The above plans give a general outline of the available benefits. For further plan details and eligibility criteria you may consult our Corporate Takaful Distribution Department.
                    </p>
                </div>
            </section>
        </div>
    </article>
@endsection
