@extends('pages.layouts.structured-page')

@section('structured_meta_title', $page->trans('meta_title') ?: ($page->trans('title') . ' - 5th Pillar Family Takaful'))
@section('structured_page_title', $page->trans('title'))
@section('structured_hero_title', $page->trans('hero_title') ?: $page->trans('title'))

@php
    $isUrdu = app()->getLocale() === 'ur';

    $mastheadBg = $isUrdu 
        ? asset('assets/images/inner-banners-2-64d5da6709c98-e1691742724167.webp')
        : asset('uploads/2017/2017/09/main-bannner-64d5d132c369d.webp');
@endphp

@section('structured_masthead_bg', "url('$mastheadBg')")
@section('structured_body_class', 'ur-rtl ' . ($isUrdu ? 'ur-mode' : ''))

@php
    $labels = [
        'information' => $isUrdu ? 'معلومات' : 'Information',
        'company_name' => $isUrdu ? 'کمپنی کا نام:' : 'Name of Company:',
        'incorporation_no' => $isUrdu ? 'کمپنی رجسٹریشن نمبر:' : 'Company Incorporation Number:',
        'ntn' => $isUrdu ? 'کمپنی کا نیشنل ٹیکس نمبر:' : 'Company National Tax Number:',
        'license_no' => $isUrdu ? 'لائسنس نمبر:' : 'License No:',
        'license_date' => $isUrdu ? 'A 004333 بتاریخ 4 اکتوبر 2021' : 'A 004333 dated October 4, 2021',
        'status' => $isUrdu ? 'کمپنی کی حیثیت:' : 'Status of Company:',
        'status_desc' => $isUrdu ? 'پبلک ان لسٹڈ کمپنی / پبلک انٹرسٹ کمپنی' : 'Public Unlisted Company/Public Interest Company',
        'address' => $isUrdu ? 'ہیڈ آفس کا پتہ:' : 'Address of Head Office:',
        'phone' => $isUrdu ? 'فون نمبر – رجسٹرڈ آفس:' : 'Phone Number – Registered Office:',
        'email' => $isUrdu ? 'ای میل ایڈریس:' : 'Email Address:',
        'website' => $isUrdu ? 'ویب سائٹ:' : 'Website:',
        'ceo' => $isUrdu ? 'چیف ایگزیکٹو آفیسر:' : 'Chief Executive Officer:',
        'regulators' => $isUrdu ? 'کمپنی کے ریگولیٹرز' : 'Regulators of Company',
    ];
@endphp

@section('structured_primary')
    <article class="post_item_single post_type_page page type-page status-publish hentry laravel-corp-info-page">
        <div class="post_content entry-content">
            <section class="wpb-content-wrapper">
                <div class="vc_row wpb_row vc_row-fluid vc_custom_1695127517890">
                    <div class="wpb_column vc_column_container vc_col-sm-12 sc_layouts_column_icons_position_left">
                        <div class="vc_column-inner">
                            <div class="wpb_wrapper">
                                <h2 style="text-align: {{ $isUrdu ? 'right' : 'left' }};font-family:{{ $isUrdu ? 'Noto Sans Urdu' : 'Raleway' }};font-weight:700;font-style:normal" class="vc_custom_heading">{{ $labels['information'] }}</h2>

                                <div class="vc_row wpb_row vc_inner vc_row-fluid laravel-corp-info-page__inner-row">
                                    <div class="wpb_column vc_column_container vc_col-sm-6 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="wpb_text_column wpb_content_element">
                                                    <div class="wpb_wrapper">
                                                        <p><span style="font-family: raleway; font-size: 14pt;"><strong>{{ $labels['company_name'] }}</strong></span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wpb_column vc_column_container vc_col-sm-6 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="wpb_text_column wpb_content_element">
                                                    <div class="wpb_wrapper">
                                                        <p><span style="font-family: raleway; font-size: 14pt;">5th Pillar Family Takaful Limited</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="vc_row wpb_row vc_inner vc_row-fluid">
                                    <div class="wpb_column vc_column_container vc_col-sm-12 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="vc_empty_space height_small" style="height: 32px">
                                                    <span class="vc_empty_space_inner"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="vc_row wpb_row vc_inner vc_row-fluid laravel-corp-info-page__inner-row">
                                    <div class="wpb_column vc_column_container vc_col-sm-6 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="wpb_text_column wpb_content_element">
                                                    <div class="wpb_wrapper">
                                                        <p><span style="font-family: raleway; font-size: 14pt;"><strong>{{ $labels['incorporation_no'] }}</strong></span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wpb_column vc_column_container vc_col-sm-6 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="wpb_text_column wpb_content_element">
                                                    <div class="wpb_wrapper">
                                                        <p><span style="font-family: raleway; font-size: 14pt;">0148761</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="vc_row wpb_row vc_inner vc_row-fluid">
                                    <div class="wpb_column vc_column_container vc_col-sm-12 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="vc_empty_space height_small" style="height: 32px">
                                                    <span class="vc_empty_space_inner"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="vc_row wpb_row vc_inner vc_row-fluid laravel-corp-info-page__inner-row">
                                    <div class="wpb_column vc_column_container vc_col-sm-6 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="wpb_text_column wpb_content_element">
                                                    <div class="wpb_wrapper">
                                                        <p><span style="font-family: raleway; font-size: 14pt;"><strong>{{ $labels['ntn'] }}</strong></span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wpb_column vc_column_container vc_col-sm-6 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="wpb_text_column wpb_content_element">
                                                    <div class="wpb_wrapper">
                                                        <p><span style="font-family: raleway; font-size: 14pt;">6994191-3</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="vc_row wpb_row vc_inner vc_row-fluid">
                                    <div class="wpb_column vc_column_container vc_col-sm-12 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="vc_empty_space height_small" style="height: 32px">
                                                    <span class="vc_empty_space_inner"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="vc_row wpb_row vc_inner vc_row-fluid laravel-corp-info-page__inner-row">
                                    <div class="wpb_column vc_column_container vc_col-sm-6 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="wpb_text_column wpb_content_element">
                                                    <div class="wpb_wrapper">
                                                        <p><span style="font-family: raleway; font-size: 14pt;"><strong>{{ $labels['license_no'] }}</strong></span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wpb_column vc_column_container vc_col-sm-6 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="wpb_text_column wpb_content_element">
                                                    <div class="wpb_wrapper">
                                                        <p><span style="font-family: raleway; font-size: 14pt;">{{ $labels['license_date'] }}</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="vc_row wpb_row vc_inner vc_row-fluid laravel-corp-info-page__inner-row">
                                    <div class="wpb_column vc_column_container vc_col-sm-6 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="wpb_text_column wpb_content_element">
                                                    <div class="wpb_wrapper">
                                                        <p><span style="font-family: raleway; font-size: 14pt;"><strong>{{ $labels['status'] }}</strong></span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wpb_column vc_column_container vc_col-sm-6 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="wpb_text_column wpb_content_element">
                                                    <div class="wpb_wrapper">
                                                        <p><span style="font-family: raleway; font-size: 14pt;">{{ $labels['status_desc'] }}</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="vc_row wpb_row vc_inner vc_row-fluid">
                                    <div class="wpb_column vc_column_container vc_col-sm-12 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="vc_empty_space height_small" style="height: 32px">
                                                    <span class="vc_empty_space_inner"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="vc_row wpb_row vc_inner vc_row-fluid laravel-corp-info-page__inner-row">
                                    <div class="wpb_column vc_column_container vc_col-sm-6 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="wpb_text_column wpb_content_element">
                                                    <div class="wpb_wrapper">
                                                        <p><span style="font-family: raleway; font-size: 14pt;"><strong>{{ $labels['address'] }}</strong></span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wpb_column vc_column_container vc_col-sm-6 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="wpb_text_column wpb_content_element">
                                                    <div class="wpb_wrapper">
                                                        <p><span style="font-family: raleway; font-size: 14pt;">{{ $isUrdu ? 'سویٹ # 01-06، دوسری منزل، ایمرالڈ ٹاور، کلفٹن – 5، کراچی' : 'Suite # 01-06, Second Floor, Emerald Tower, Clifton – 5, Karachi' }}</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="vc_row wpb_row vc_inner vc_row-fluid">
                                    <div class="wpb_column vc_column_container vc_col-sm-12 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="vc_empty_space height_small" style="height: 32px">
                                                    <span class="vc_empty_space_inner"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="vc_row wpb_row vc_inner vc_row-fluid laravel-corp-info-page__inner-row">
                                    <div class="wpb_column vc_column_container vc_col-sm-6 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="wpb_text_column wpb_content_element">
                                                    <div class="wpb_wrapper">
                                                        <p><span style="font-family: raleway; font-size: 14pt;"><strong>{{ $labels['phone'] }}</strong></span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wpb_column vc_column_container vc_col-sm-6 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="wpb_text_column wpb_content_element">
                                                    <div class="wpb_wrapper">
                                                        <p><span style="font-family: raleway; font-size: 14pt;">Ph: 021-36492074</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="vc_row wpb_row vc_inner vc_row-fluid">
                                    <div class="wpb_column vc_column_container vc_col-sm-12 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="vc_empty_space height_small" style="height: 32px">
                                                    <span class="vc_empty_space_inner"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="vc_row wpb_row vc_inner vc_row-fluid laravel-corp-info-page__inner-row">
                                    <div class="wpb_column vc_column_container vc_col-sm-6 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="wpb_text_column wpb_content_element">
                                                    <div class="wpb_wrapper">
                                                        <p><span style="font-family: raleway; font-size: 14pt;"><strong>{{ $labels['email'] }}</strong></span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wpb_column vc_column_container vc_col-sm-6 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="wpb_text_column wpb_content_element">
                                                    <div class="wpb_wrapper">
                                                        <p><span style="font-family: raleway; font-size: 14pt;"><strong><a href="mailto:info@5thpillartakaful.com">info@5thpillartakaful.com</a></strong></span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="vc_row wpb_row vc_inner vc_row-fluid">
                                    <div class="wpb_column vc_column_container vc_col-sm-12 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="vc_empty_space height_small" style="height: 32px">
                                                    <span class="vc_empty_space_inner"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="vc_row wpb_row vc_inner vc_row-fluid laravel-corp-info-page__inner-row">
                                    <div class="wpb_column vc_column_container vc_col-sm-6 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="wpb_text_column wpb_content_element">
                                                    <div class="wpb_wrapper">
                                                        <p><span style="font-family: raleway; font-size: 14pt;"><strong>{{ $labels['website'] }}</strong></span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wpb_column vc_column_container vc_col-sm-6 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="wpb_text_column wpb_content_element">
                                                    <div class="wpb_wrapper">
                                                        <p><span style="font-family: raleway; font-size: 14pt;"><strong><a href="https://5thpillartakaful.com" rel="noopener noreferrer">www.5thpillartakaful.com</a></strong></span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="vc_row wpb_row vc_inner vc_row-fluid">
                                    <div class="wpb_column vc_column_container vc_col-sm-12 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="vc_empty_space height_small" style="height: 32px">
                                                    <span class="vc_empty_space_inner"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="vc_row wpb_row vc_inner vc_row-fluid laravel-corp-info-page__inner-row">
                                    <div class="wpb_column vc_column_container vc_col-sm-6 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="wpb_text_column wpb_content_element">
                                                    <div class="wpb_wrapper">
                                                        <p><span style="font-family: raleway; font-size: 14pt;"><strong>{{ $labels['ceo'] }}</strong></span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wpb_column vc_column_container vc_col-sm-6 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="wpb_text_column wpb_content_element">
                                                    <div class="wpb_wrapper">
                                                        <p><span style="font-family: raleway; font-size: 14pt;">Nasar us Samad Qureshi</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="vc_row wpb_row vc_row-fluid vc_custom_1685704820936">
                    <div class="wpb_column vc_column_container vc_col-sm-12 sc_layouts_column_icons_position_left">
                        <div class="vc_column-inner">
                            <div class="wpb_wrapper">
                                <h2 style="text-align: {{ $isUrdu ? 'right' : 'left' }};font-family:{{ $isUrdu ? 'Noto Sans Urdu' : 'Raleway' }};font-weight:700;font-style:normal" class="vc_custom_heading">{{ $labels['regulators'] }}</h2>
                                <div class="vc_row wpb_row vc_inner vc_row-fluid laravel-corp-info-page__inner-row">
                                    <div class="wpb_column vc_column_container vc_col-sm-6 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="wpb_text_column wpb_content_element laravel-corp-info-page__regulators">
                                                    <div class="wpb_wrapper">
                                                        <p>
                                                            <span style="font-size: 14pt;"><strong>{{ $isUrdu ? 'سیکیورٹیز اینڈ ایکسچینج کمیشن آف پاکستان (ایس ای سی پی)' : 'Securities and Exchange Commission of Pakistan (SECP)' }}</strong></span><br>
                                                            <span style="font-size: 14pt;"><strong><u><a href="https://www.secp.gov.pk/" target="_blank" rel="noopener noreferrer">http://www.secp.gov.pk/</a></u></strong></span>
                                                        </p>
                                                        <p>
                                                            <span style="font-size: 14pt;"><strong>{{ $isUrdu ? 'فیڈرل بورڈ آف ریونیو' : 'Federal Board of Revenue' }}</strong></span><br>
                                                            <span style="font-size: 14pt;"><strong><u><a href="https://www.fbr.gov.pk/" target="_blank" rel="noopener noreferrer">http://www.fbr.gov.pk/</a></u></strong></span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </article>
@endsection
