<!DOCTYPE html>
<html dir="ltr" lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js scheme_default">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="format-detection" content="telephone=no">
    <title>@yield('title', '5th Pillar Family Takaful')</title>
    <link rel="icon" href="{{ asset('uploads/2023/2023/05/cropped-5th-Pilar-Logo-32x32.png') }}" sizes="32x32" type="image/png">
    <link rel="icon" href="{{ asset('uploads/2023/2023/05/cropped-5th-Pilar-Logo-192x192.png') }}" sizes="192x192" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('uploads/2023/2023/05/cropped-5th-Pilar-Logo-180x180.png') }}">
    <meta name="msapplication-TileImage" content="{{ asset('uploads/2023/2023/05/cropped-5th-Pilar-Logo-270x270.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap">
    <link rel="stylesheet" href="{{ asset('assets/vendor/useanyfont/uaf.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/v4-shims.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/original-theme/plugin-css/trx_addons_icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/original-theme/plugin-css/trx_addons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/trx_addons/css/trx_addons.animation.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/trx_addons/css/magnific-popup.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/trx_addons/css/swiper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/original-theme/css/fontello-embedded.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/original-theme/css/__styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/original-theme/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/original-theme/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/original-theme/css/__colors.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/original-theme/css/child-style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/original-theme/css/responsive.css') }}">
    @php
        $__bridgeCss = public_path('assets/vendor/original-theme/css/laravel-bridge.css');
        $__bridgeVer = is_file($__bridgeCss) ? (string) filemtime($__bridgeCss) : '1';
    @endphp
    <link rel="stylesheet" href="{{ asset('assets/vendor/original-theme/css/laravel-bridge.css') }}?v={{ $__bridgeVer }}">
    @stack('head')
</head>
<body class="@yield('body_class', 'custom-background wp-custom-logo wp-theme-shaha wp-child-theme-shaha-child body_tag scheme_default body_style_wide sidebar_hide expand_content remove_margins header_type_custom menu_style_ no_layout')">
    @php
        $isHome = request()->routeIs('home');
        $currentSlug = (string) request()->route('slug', '');
        $isNewsSection = request()->routeIs('news-events.index', 'news-events.paginated')
            || ($currentSlug !== '' && \App\Support\NewsEventsRepository::find($currentSlug) !== null);
        $currentPath = ltrim((string) request()->path(), '/');
        $isUrdu = $currentPath === 'urdu' || str_starts_with($currentPath, 'urdu/');
        $englishPath = $isUrdu ? ltrim(preg_replace('/^urdu\\/?/i', '', $currentPath) ?? '', '/') : $currentPath;
        $urduPath = $isUrdu ? $currentPath : trim('urdu/' . $currentPath, '/');
        $englishUrl = url($englishPath === '' ? '/' : $englishPath);
        $urduUrl = url($urduPath === '' ? '/urdu' : $urduPath);
        $pageTitle = trim($__env->yieldContent('page_title'));
        $bodyClassYield = trim($__env->yieldContent('body_class'));
        $useStructuredInnerChrome = str_contains($bodyClassYield, 'laravel-inner-page');
        $logoUrl = asset('uploads/2025/2025/08/cropped-5th-pilar-logo-64d5d6041dbd1.webp');

        $menu = [
            [
                'label' => 'Home',
                'url' => route('home'),
                'active' => $isHome,
            ],
            [
                'label' => 'Company',
                'children' => [
                    ['label' => 'About Us', 'slug' => 'about-us'],
                    ['label' => 'Vision & Mission', 'slug' => 'vision-mission'],
                    ['label' => 'Management Team', 'slug' => 'management-team'],
                    ['label' => 'Corporate Information', 'slug' => 'corporate-information'],
                    ['label' => 'PACRA Rating', 'url' => asset('uploads/2026/2026/03/IFS-Rating-of-5th-Pillar-Family-Takaful-Limited.pdf'), 'external' => true],
                    ['label' => 'Code of Conduct', 'url' => asset('uploads/2023/2023/09/Code-of-Conduct-Corporate.pdf'), 'external' => true],
                    ['label' => 'Waqf Deed', 'url' => asset('uploads/2026/2026/04/Waqf-Deed-with-addition-of-Amendment-2-4-26.pdf'), 'external' => true],
                    ['label' => 'PTF Policies', 'url' => asset('uploads/2023/2023/12/PTF-Policies.pdf'), 'external' => true],
                    ['label' => 'Privacy Policy', 'slug' => 'privacy-policy'],
                ],
            ],
            [
                'label' => 'Governance',
                'children' => [
                    ['label' => 'Board of Directors', 'slug' => 'board-of-directors'],
                    ['label' => 'Board Committees', 'slug' => 'board-committees'],
                    ['label' => 'Management Committees', 'slug' => 'management-committees'],
                    ['label' => 'Shariah Advisor', 'slug' => 'shariah-advisor'],
                    ['label' => 'External Auditors', 'slug' => 'external-auditors'],
                    ['label' => 'Legal Advisor', 'slug' => 'legal-advisor'],
                    ['label' => 'Pattern of Shareholding', 'slug' => 'pattern-of-shareholding'],
                ],
            ],
            [
                'label' => 'Products',
                'children' => [
                    ['label' => 'Hajj Savings Plan', 'slug' => 'hajj-savings-plan'],
                    ['label' => 'Umrah Savings Plan', 'slug' => 'umrah-savings-plan'],
                    ['label' => 'Regular Savings Plan', 'slug' => 'regular-savings-plan'],
                    ['label' => 'Group Life Takaful', 'slug' => 'group-life-takaful'],
                ],
            ],
            [
                'label' => 'Investors Relations',
                'children' => [
                    ['label' => 'Financial Statements', 'slug' => 'financial-statements'],
                    [
                        'label' => 'Notices of General Meetings',
                        'children' => [
                            ['label' => 'Notice of AGM 2026', 'url' => asset('uploads/2026/2026/04/Notice-of-AGM-2026.pdf'), 'external' => true],
                            ['label' => 'Notice of EoGM 2025', 'url' => asset('uploads/2026/2026/04/Notice-of-EoGM-2025.pdf'), 'external' => true],
                            ['label' => 'Notice of AGM 2025', 'url' => asset('uploads/2026/2026/04/Notice-of-AGM-2025.pdf'), 'external' => true],
                        ],
                    ],
                    ['label' => 'Online Complaint Form', 'slug' => 'online-complaint-form'],
                    ['label' => 'Contact Details for Investors', 'slug' => 'contact-details-for-investors'],
                    ['label' => 'Complaints in Respect of Takaful Membership', 'slug' => 'complaints-in-respect-of-takaful-membership'],
                    ['label' => 'SECP SDMS', 'slug' => 'secp-sdms'],
                    [
                        'label' => 'Takaful Unit-Linked Funds',
                        'children' => [
                            ['label' => 'Target Asset Mix and Charges', 'url' => asset('uploads/2025/2025/01/web-page.pdf'), 'external' => true],
                            ['label' => 'Daily Fund Prices', 'slug' => 'daily-fund-prices'],
                            ['label' => "Fund Manager's Report", 'slug' => 'fund-managers-report'],
                            ['label' => 'Accounts of Unit Linked Funds', 'slug' => 'accounts-of-unit-linked-funds'],
                        ],
                    ],
                    ['label' => 'Sitemap', 'slug' => 'sitemap'],
                ],
            ],
            [
                'label' => 'Media',
                'children' => [
                    ['label' => 'News & Events', 'route' => 'news-events.index', 'active' => $isNewsSection],
                    ['label' => 'Memberships', 'slug' => 'memberships'],
                ],
            ],
            [
                'label' => 'Contact',
                'slug' => 'contact',
            ],
            [
                'label' => 'Downloads',
                'children' => [
                    ['label' => 'List of Participants Having Unclaimed / Un-Enchased Benefits', 'url' => asset('uploads/2026/2026/04/Unclaimed-Un-Enchased-Benefits-lIst-March-2026.pdf'), 'external' => true],
                    ['label' => 'Forms', 'slug' => 'forms'],
                ],
            ],
            [
                'label' => '5th Pillar Travel',
                'url' => 'https://5thpillartravel.com/',
                'external' => true,
            ],
        ];

        $itemUrl = static function (array $item): string {
            if (isset($item['route'])) {
                return route($item['route']);
            }
            if (isset($item['slug'])) {
                return route('pages.show', ['slug' => $item['slug']]);
            }

            return $item['url'] ?? '#';
        };

        $itemTarget = static fn (array $item): string => ! empty($item['external']) ? '_blank' : '_self';
        $itemRel = static fn (array $item): ?string => ! empty($item['external']) ? 'noopener noreferrer' : null;

        $groupIsActive = static function (array $item) use (&$groupIsActive, $currentSlug): bool {
            if (! empty($item['active'])) {
                return true;
            }

            if (($item['slug'] ?? null) === $currentSlug) {
                return true;
            }

            foreach ($item['children'] ?? [] as $child) {
                if ($groupIsActive($child)) {
                    return true;
                }
            }

            return false;
        };
    @endphp
    <div class="body_wrap">
        <div class="page_wrap">
            @include('layouts.partials.header-top')
            @hasSection('header_masthead')
                @yield('header_masthead')
            @endif
            @include('layouts.partials.header-bottom')
            @yield('page_content')
            @include('layouts.partials.footer')
        </div>
    </div>

    <a href="#" class="trx_addons_scroll_to_top trx_addons_icon-up" title="Scroll to top"></a>

    <script src="{{ asset('assets/vendor/original-theme/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/original-theme/js/jquery-migrate.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/original-theme/plugin-js/superfish.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/original-theme/js/__scripts.js') }}"></script>
    <script src="{{ asset('assets/vendor/original-theme/js/laravel-bridge.js') }}"></script>
    @stack('scripts')
</body>
</html>
