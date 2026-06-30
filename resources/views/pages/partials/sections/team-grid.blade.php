@php
    /** @var \App\Services\CmsSectionRegistry $registry */
    /** @var array<string, mixed> $content */
    $isUrdu = in_array(app()->getLocale(), ['ur', 'urdu'], true);
    $members = (array) ($content['members'] ?? []);
@endphp
@if ($members !== [])
    <article class="post_item_single post_type_page page type-page status-publish hentry laravel-team-page">
        <div class="post_content entry-content">
            <section class="wpb-content-wrapper">
                <div class="vc_row wpb_row vc_row-fluid vc_custom_1691560509533">
                    <div class="wpb_column vc_column_container vc_col-sm-12 sc_layouts_column_icons_position_left">
                        <div class="vc_column-inner">
                            <div class="wpb_wrapper">
                                <div class="sc_team color_style_default sc_team_short">
                                    <div class="sc_team_columns_wrap sc_item_columns trx_addons_columns_wrap columns_padding_bottom">
                                        @foreach ($members as $member)
                                            @php
                                                $subtitle = $isUrdu && ! empty($member['subtitle_ur']) ? $member['subtitle_ur'] : ($member['subtitle'] ?? '');
                                                $image = $registry->assetUrl((string) ($member['image'] ?? ''));
                                                $linkedin = trim((string) ($member['linkedin'] ?? ''));
                                            @endphp
                                            <div class="trx_addons_column-1_4">
                                                <div class="sc_team_item">
                                                    <div class="post_featured sc_team_item_thumb trx_addons_hover trx_addons_hover_style_info">
                                                        <a href="#" aria-hidden="true" tabindex="-1">
                                                            @if ($image !== '')
                                                                <img
                                                                    @if ($loop->first) fetchpriority="high" @else loading="lazy" @endif
                                                                    decoding="async"
                                                                    width="370"
                                                                    height="370"
                                                                    src="{{ $image }}"
                                                                    class="attachment-trx_addons-thumb-avatar size-trx_addons-thumb-avatar wp-post-image"
                                                                    alt="{{ $member['name'] ?? '' }}"
                                                                >
                                                            @endif
                                                        </a>
                                                        @if ($linkedin !== '')
                                                            <div class="trx_addons_hover_content">
                                                                <div class="sc_team_item_socials socials_wrap trx_addons_hover_info">
                                                                    <a
                                                                        target="_blank"
                                                                        href="{{ $linkedin }}"
                                                                        class="social_item social_item_style_icons social_item_type_icons"
                                                                        rel="noopener noreferrer"
                                                                    >
                                                                        <span class="social_icon social_linkedin"><span class="icon-linkedin"></span></span>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="trx_addons_hover_mask"></div>
                                                    </div>
                                                    <div class="sc_team_item_info">
                                                        <div class="sc_team_item_header">
                                                            <h4 class="sc_team_item_title">{{ $member['name'] ?? '' }}</h4>
                                                            @if ($subtitle !== '')
                                                                <div class="sc_team_item_subtitle">{{ $subtitle }}</div>
                                                            @endif
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
@endif
