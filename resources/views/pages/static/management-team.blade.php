@extends('pages.layouts.structured-page')

@section('structured_meta_title', 'Management Team - 5th Pillar Family Takaful')
@section('structured_page_title', 'Management Team')

@section('structured_hero_title', 'Management Team')

@php
    $teamBase = 'uploads/2023/2023/06';
    $managementTeam = [
        [
            'name' => 'Nasar us Samad Qureshi',
            'subtitle' => 'Chief Executive Officer',
            'image' => $teamBase . '/nasar-us-samad-qureshi-edited-64d4b85147fc6-370x370.webp',
            'linkedin' => 'https://www.linkedin.com/in/nasar-qureshi-090a0117/',
        ],
        [
            'name' => 'Muhammad Nasir Ali Syed',
            'subtitle' => 'Executive Director - Operations',
            'image' => $teamBase . '/Nasir-Ali-Syed-updated-_2_-370x370.webp',
            'linkedin' => 'https://www.linkedin.com/in/muhammad-nasir-ali-syed-b936834/',
        ],
        [
            'name' => 'Awais Hanif',
            'subtitle' => 'Chief Financial Officer & Company Secretary',
            'image' => $teamBase . '/awais-hanif-edited-64d4b852b7435-370x370.webp',
            'linkedin' => 'https://www.linkedin.com/in/awais-hanif-aca-91893572/',
        ],
        [
            'name' => 'Imran Irshad',
            'subtitle' => 'Head of Group Operations',
            'image' => $teamBase . '/imran-irshad-edited-flipped-64d4b8538cc37-370x370.webp',
            'linkedin' => 'https://www.linkedin.com/in/imran-irshad-acii-flmi-1989a14a/',
        ],
        [
            'name' => 'Naseer Ahmed',
            'subtitle' => 'Head of IT',
            'image' => $teamBase . '/naseer-ahmed-edited-1-64d4b853d570b-370x370.webp',
            'linkedin' => 'https://www.linkedin.com/in/naseer-ahmed-8596383/',
        ],
        [
            'name' => 'Raja Muhammad Adnan Ali',
            'subtitle' => 'National Sales Head',
            'image' => $teamBase . '/RajaAdnan640x640-1-370x370.jpg',
            'linkedin' => 'https://www.linkedin.com/in/raja-muhammad-adnan-ali/',
        ],
        [
            'name' => 'Samrah Anis',
            'subtitle' => 'Group Head Brand Strategy',
            'image' => $teamBase . '/WhatsApp-Image-2026-04-07-at-3.48.44-PM-370x370.jpeg',
            'linkedin' => 'https://www.linkedin.com/in/samranez',
        ],
        [
            'name' => 'Muhammad Adnan',
            'subtitle' => 'Manager – Actuarial Services',
            'image' => $teamBase . '/muhammad-adnan-edited-64d4b854f0b56-370x370.webp',
            'linkedin' => 'https://www.linkedin.com/in/muhammad-adnan-31720/',
        ],
        [
            'name' => 'Kamran Ali Khan',
            'subtitle' => 'Head of Underwriting',
            'image' => $teamBase . '/kamran-ali-khan-edited-64d4b8562d2e3-370x370.webp',
            'linkedin' => 'https://www.linkedin.com/in/kamran-khan-31b819189/',
        ],
        [
            'name' => 'Aneel Akhtar',
            'subtitle' => 'Head of Compliance',
            'image' => $teamBase . '/Aneel-Akhtar-370x370.webp',
            'linkedin' => 'https://www.linkedin.com/in/aneel-akhtar-47467221/',
        ],
    ];
@endphp

@section('structured_primary')
    <article class="post_item_single post_type_page page type-page status-publish hentry laravel-team-page">
        <div class="post_content entry-content">
            <section class="wpb-content-wrapper">
                <div class="vc_row wpb_row vc_row-fluid vc_custom_1691560509533">
                    <div class="wpb_column vc_column_container vc_col-sm-12 sc_layouts_column_icons_position_left">
                        <div class="vc_column-inner">
                            <div class="wpb_wrapper">
                                <div class="sc_team color_style_default sc_team_short">
                                    <div class="sc_team_columns_wrap sc_item_columns trx_addons_columns_wrap columns_padding_bottom">
                                        @foreach ($managementTeam as $member)
                                            <div class="trx_addons_column-1_4">
                                                <div class="sc_team_item">
                                                    <div class="post_featured sc_team_item_thumb trx_addons_hover trx_addons_hover_style_info">
                                                        <a href="#" aria-hidden="true" tabindex="-1">
                                                            <img
                                                                @if ($loop->first) fetchpriority="high" @else loading="lazy" @endif
                                                                decoding="async"
                                                                width="370"
                                                                height="370"
                                                                src="{{ asset($member['image']) }}"
                                                                class="attachment-trx_addons-thumb-avatar size-trx_addons-thumb-avatar wp-post-image"
                                                                alt="{{ $member['name'] }}"
                                                            >
                                                        </a>
                                                        <div class="trx_addons_hover_content">
                                                            <div class="sc_team_item_socials socials_wrap trx_addons_hover_info">
                                                                <a
                                                                    target="_blank"
                                                                    href="{{ $member['linkedin'] }}"
                                                                    class="social_item social_item_style_icons social_item_type_icons"
                                                                    rel="noopener noreferrer"
                                                                >
                                                                    <span class="social_icon social_linkedin"><span class="icon-linkedin"></span></span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="trx_addons_hover_mask"></div>
                                                    </div>
                                                    <div class="sc_team_item_info">
                                                        <div class="sc_team_item_header">
                                                            <h4 class="sc_team_item_title">
                                                                <a href="#">{{ $member['name'] }}</a>
                                                            </h4>
                                                            <div class="sc_team_item_subtitle">{{ $member['subtitle'] }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
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
