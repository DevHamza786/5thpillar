<?php

namespace App\Services;

use App\Models\NavMenuItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class SiteNavigationService
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function menuTree(bool $isHome, string $currentSlug, bool $isNewsSection): array
    {
        if (Schema::hasTable('nav_menu_items') && NavMenuItem::query()->exists()) {
            return $this->fromDatabase($isHome, $currentSlug, $isNewsSection);
        }

        return $this->legacyMenu($isHome, $currentSlug, $isNewsSection);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fromDatabase(bool $isHome, string $currentSlug, bool $isNewsSection): array
    {
        /** @var Collection<int, NavMenuItem> $rows */
        $rows = NavMenuItem::query()
            ->with('cmsMedia')
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->get();

        return $this->buildBranch($rows, null, $isHome, $currentSlug, $isNewsSection);
    }

    /**
     * @param  Collection<int, NavMenuItem>  $rows
     * @return array<int, array<string, mixed>>
     */
    private function buildBranch(Collection $rows, ?int $parentId, bool $isHome, string $currentSlug, bool $isNewsSection): array
    {
        return $rows->where('parent_id', $parentId)->values()->map(function (NavMenuItem $item) use ($rows, $isHome, $currentSlug, $isNewsSection) {
            $label = (app()->getLocale() === 'ur' && filled($item->label_ur)) ? $item->label_ur : $item->label;
            $node = ['label' => __($label)];


            if ($item->open_new_tab) {
                $node['external'] = true;
            }

            switch ($item->link_type) {
                case NavMenuItem::LINK_HOME:
                    $node['url'] = route('home');
                    if ($isHome) {
                        $node['active'] = true;
                    }
                    break;
                case NavMenuItem::LINK_PAGE_SLUG:
                    if ($item->page_slug) {
                        $node['slug'] = $item->page_slug;
                    }
                    break;
                case NavMenuItem::LINK_NAMED_ROUTE:
                    if ($item->route_name) {
                        $node['route'] = $item->route_name;
                        if ($item->route_name === 'news-events.index'
                            || $item->route_name === 'news-events.paginated'
                            || str_starts_with((string) $item->route_name, 'news-events.')) {
                            $node['active'] = $isNewsSection;
                        }
                    }
                    break;
                case NavMenuItem::LINK_CUSTOM_URL:
                    if ($item->custom_url) {
                        $node['url'] = $item->custom_url;
                    }
                    break;
                case NavMenuItem::LINK_MEDIA:
                    $media = $item->relationLoaded('cmsMedia') ? $item->cmsMedia : $item->cmsMedia()->first();
                    if ($media !== null) {
                        $node['url'] = $media->publicUrl();
                        $node['external'] = true;
                    }
                    break;
                case NavMenuItem::LINK_NONE:
                default:
                    break;
            }

            $children = $this->buildBranch($rows, $item->id, $isHome, $currentSlug, $isNewsSection);
            if ($children !== []) {
                $node['children'] = $children;
            }

            return $node;
        })->all();
    }

    /**
     * Static menu definition for seeding (no “current page” flags).
     *
     * @return array<int, array<string, mixed>>
     */
    public function defaultMenuSeedTree(): array
    {
        return $this->legacyMenu(false, '', false);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function legacyMenu(bool $isHome, string $currentSlug, bool $isNewsSection): array
    {
        return [
            [
                'label' => __('Home'),
                'url' => route('home'),
                'active' => $isHome,
            ],
            [
                'label' => __('Company'),
                'children' => [
                    ['label' => __('About Us'), 'slug' => 'about-us'],
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
                'label' => __('Governance'),
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
                'label' => __('Products'),
                'children' => [
                    ['label' => 'Hajj Savings Plan', 'slug' => 'hajj-savings-plan'],
                    ['label' => 'Umrah Savings Plan', 'slug' => 'umrah-savings-plan'],
                    ['label' => __('Hajj Planner'), 'route' => app()->getLocale() === 'ur' ? 'urdu.hajj-planner.index' : 'hajj-planner.index'],
                    ['label' => __('Umrah Planner'), 'route' => app()->getLocale() === 'ur' ? 'urdu.umrah-planner.index' : 'umrah-planner.index'],
                    ['label' => 'Regular Savings Plan', 'slug' => 'regular-savings-plan'],
                    ['label' => 'Group Life Takaful', 'slug' => 'group-life-takaful'],
                ],
            ],
            [
                'label' => __('Investors Relations'),
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
                'label' => __('Media'),
                'children' => [
                    ['label' => 'News & Events', 'route' => 'news-events.index', 'active' => $isNewsSection],
                    ['label' => 'Memberships', 'slug' => 'memberships'],
                ],
            ],
            [
                'label' => __('Contact'),
                'slug' => 'contact',
            ],
            [
                'label' => __('Downloads'),
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
    }
}
