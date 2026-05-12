@extends('pages.layouts.structured-page')

@section('structured_meta_title', $page->trans('meta_title') ?: ($page->trans('title') . ' - 5th Pillar Family Takaful'))
@section('structured_page_title', $page->trans('title'))
@section('structured_hero_title', $page->trans('hero_title') ?: $page->trans('title'))

@php
    $isUrdu = app()->getLocale() === 'ur';

    if ($isUrdu) {
        $visionTitle = 'ہمارا وژن';
        $visionText = 'جدید شریعہ کے مطابق تکافل مصنوعات کے ذریعے اپنے کلائنٹس کی مالی گنجائش کو مضبوط بنانا اور انہیں زندگی میں ان کے عزیز مقاصد حاصل کرنے کے لیے بااختیار بنانا۔';
        $missionTitle = 'ہمارا مشن';
        $missionText = 'پاکستان میں مسلمانوں کو حج، جو کہ اسلام کا پانچواں ستون ہے، ادا کرنے کے لیے خاص طور پر منظم تکافل بچت اور تحفظ کے حل فراہم کرنا۔';
    } else {
        $visionTitle = 'Our Vision';
        $visionText = 'Strengthen the financial capacity of our clients through innovative Shariah compliant Takaful products empowering them to achieve their cherished goals in life.';
        $missionTitle = 'Our Mission';
        $missionText = 'Provide structured Takaful savings and protection solutions specifically to Muslims in Pakistan to perform Hajj, the 5th Pillar of Islam.';
    }

    $mastheadBg = $isUrdu 
        ? asset('assets/images/inner-banners-2-64d5da6709c98-e1691742724167.webp')
        : asset('uploads/2017/2017/09/main-bannner-64d5d132c369d.webp');
@endphp

@section('structured_masthead_bg', "url('$mastheadBg')")
@section('structured_body_class', 'ur-rtl ' . ($isUrdu ? 'ur-mode' : ''))

@section('structured_primary')
    <article id="post-4106" class="post_item_single post_type_page post-4106 page type-page status-publish hentry laravel-vm-page">
        <div class="post_content entry-content">
            <section class="wpb-content-wrapper">
                <div class="vc_row wpb_row vc_row-fluid scheme_dark laravel-vm-outer">
                    <div class="wpb_column vc_column_container vc_col-sm-12 sc_layouts_column_icons_position_left">
                        <div class="vc_column-inner">
                            <div class="wpb_wrapper">
                                <div class="vc_row wpb_row vc_inner vc_row-fluid scheme_dark laravel-vm-inner">
                                    <div class="wpb_column vc_column_container vc_col-sm-6 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="sc_icons sc_icons_default sc_icons_size_medium sc_align_center">
                                                    <div class="sc_icons_item">
                                                        <div class="sc_icons_image">
                                                            <img
                                                                src="{{ asset('uploads/2023/2023/08/1-New.webp') }}"
                                                                alt="{{ $visionTitle }}"
                                                                width="124"
                                                                height="110"
                                                                decoding="async"
                                                            >
                                                        </div>
                                                        <h4 class="sc_icons_item_title"><span>{{ $visionTitle }}</span></h4>
                                                    </div>
                                                </div>
                                                <div class="vc_empty_space height_small laravel-vm-spacer" style="height: 28px">
                                                    <span class="vc_empty_space_inner"></span>
                                                </div>
                                                <div class="laravel-vm-desc">
                                                    <div class="wpb_text_column wpb_content_element">
                                                        <div class="wpb_wrapper">
                                                            <p class="laravel-vm-body">
                                                                {{ $visionText }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wpb_column vc_column_container vc_col-sm-6 sc_layouts_column_icons_position_left">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="sc_icons sc_icons_default sc_icons_size_medium sc_align_center">
                                                    <div class="sc_icons_item">
                                                        <div class="sc_icons_image">
                                                            <img
                                                                src="{{ asset('uploads/2023/2023/08/2-New.webp') }}"
                                                                alt="{{ $missionTitle }}"
                                                                width="124"
                                                                height="110"
                                                                decoding="async"
                                                            >
                                                        </div>
                                                        <h4 class="sc_icons_item_title"><span>{{ $missionTitle }}</span></h4>
                                                    </div>
                                                </div>
                                                <div class="vc_empty_space height_small laravel-vm-spacer" style="height: 28px">
                                                    <span class="vc_empty_space_inner"></span>
                                                </div>
                                                <div class="laravel-vm-desc">
                                                    <div class="wpb_text_column wpb_content_element">
                                                        <div class="wpb_wrapper">
                                                            <p class="laravel-vm-body">
                                                                {{ $missionText }}
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
                </div>
            </section>
        </div>
    </article>
@endsection
