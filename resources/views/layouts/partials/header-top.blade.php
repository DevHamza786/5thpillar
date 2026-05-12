<header class="site-header top_panel top_panel_custom {{ $isHome ? 'top_panel_custom_644 top_panel_custom_header-centered-without-breadcrumbs without_bg_image' : 'top_panel_custom_417 top_panel_custom_header-centered with_bg_image shaha_inline_page_header' }} scheme_default">
    <div class="laravel-header-sticky-cluster">
    <div class="laravel-header-topbar scheme_dark">
        <div class="content_wrap laravel-header-topbar__inner">
            <div class="laravel-lang-switcher icons">
                <a href="{{ $englishUrl }}" @class(['active' => app()->getLocale() === 'en'])>English</a>
                <a href="{{ $urduUrl }}" @class(['active' => app()->getLocale() === 'ur'])>اردو</a>
            </div>

        </div>
    </div>

    <div class="sc_layouts_row sc_layouts_row_type_compact scheme_dark laravel-header-logo-row">
        <div class="content_wrap">
            <div class="sc_layouts_iconed_text sc_layouts_menu_mobile_button laravel-header-mobile-toggle">
                <a class="sc_layouts_item_link sc_layouts_iconed_text_link" href="#" aria-label="Open mobile menu">
                    <span class="sc_layouts_item_icon sc_layouts_iconed_text_icon trx_addons_icon-menu"></span>
                </a>
            </div>
            <div class="sc_layouts_item laravel-header-logo">
                <a href="{{ route('home') }}" class="sc_layouts_logo sc_layouts_logo_default">
                    <img class="logo_image" src="{{ $logoUrl }}" alt="5th Pillar Takaful" width="230" height="81">
                </a>
            </div>
        </div>
    </div>

    <div class="sc_layouts_row sc_layouts_row_type_compact sc_layouts_row_fixed scheme_dark laravel-header-menu-row">
        <div class="content_wrap laravel-header-menu-shell">
            @if(app()->getLocale() !== 'ur')
                <div class="laravel-header-menu-spacer" aria-hidden="true"></div>
            @endif
            
            <nav class="sc_layouts_menu sc_layouts_menu_default menu_hover_slide_line hide_on_mobile" id="sc_layouts_menu_main" data-animation-in="fadeInUpSmall" data-animation-out="fadeOutDownSmall">
                <ul id="menu_main" class="sc_layouts_menu_nav menu_main_nav">
                    @foreach ($menu as $item)
                        @php($isActive = $groupIsActive($item))
                        <li @class([
                            'menu-item',
                            'menu-item-has-children' => ! empty($item['children']),
                            'current-menu-item current_page_item' => ! empty($item['active']) || (($item['slug'] ?? null) === $currentSlug),
                            'current-menu-parent current-menu-ancestor' => ! empty($item['children']) && $isActive,
                        ])>
                            <a href="{{ ! empty($item['children']) ? '#' : $itemUrl($item) }}"
                               @if (! empty($item['external'])) target="{{ $itemTarget($item) }}" rel="{{ $itemRel($item) }}" @endif
                               @if (($item['slug'] ?? null) === $currentSlug || ! empty($item['active'])) aria-current="page" @endif>
                                <span>{{ $item['label'] }}</span>
                            </a>
                            @if (! empty($item['children']))
                                <ul class="sub-menu">
                                    @foreach ($item['children'] as $child)
                                        @php($childActive = $groupIsActive($child))
                                        <li @class([
                                            'menu-item',
                                            'menu-item-has-children' => ! empty($child['children']),
                                            'current-menu-item current_page_item' => ($child['slug'] ?? null) === $currentSlug,
                                            'current-menu-parent current-menu-ancestor' => ! empty($child['children']) && $childActive,
                                        ])>
                                            <a href="{{ ! empty($child['children']) ? '#' : $itemUrl($child) }}"
                                               @if (! empty($child['external'])) target="{{ $itemTarget($child) }}" rel="{{ $itemRel($child) }}" @endif
                                               @if (($child['slug'] ?? null) === $currentSlug) aria-current="page" @endif>
                                                <span>{{ $child['label'] }}</span>
                                            </a>
                                            @if (! empty($child['children']))
                                                <ul class="sub-menu">
                                                    @foreach ($child['children'] as $nestedChild)
                                                        <li @class([
                                                            'menu-item',
                                                            'current-menu-item current_page_item' => ($nestedChild['slug'] ?? null) === $currentSlug,
                                                        ])>
                                                            <a href="{{ $itemUrl($nestedChild) }}"
                                                               @if (! empty($nestedChild['external'])) target="{{ $itemTarget($nestedChild) }}" rel="{{ $itemRel($nestedChild) }}" @endif
                                                               @if (($nestedChild['slug'] ?? null) === $currentSlug) aria-current="page" @endif>
                                                                <span>{{ $nestedChild['label'] }}</span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </nav>
            @if(app()->getLocale() !== 'ur')
            <div class="laravel-header-actions hide_on_mobile">
                <div class="laravel-header-search search_wrap search_style_normal">
                    <div class="search_form_wrap">
                        <form role="search" method="get" class="search_form" action="{{ url('/') }}">
                            <label>
                                <input type="text" class="search_field" placeholder="{{ __('Search') }}" value="{{ request('s', '') }}" name="s">
                            </label>
                            <button type="submit" class="search_submit trx_addons_icon-search" aria-label="Search"></button>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            <div class="sc_layouts_iconed_text sc_layouts_menu_mobile_button">
                <a class="sc_layouts_item_link sc_layouts_iconed_text_link" href="#" aria-label="Open mobile menu">
                    <span class="sc_layouts_item_icon sc_layouts_iconed_text_icon trx_addons_icon-menu"></span>
                </a>
            </div>
        </div>

    </div>

    <div class="sc_layouts_row_fixed_placeholder" aria-hidden="true"></div>
    </div>
