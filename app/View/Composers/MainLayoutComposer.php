<?php

namespace App\View\Composers;

use App\Services\SiteNavigationService;
use App\Support\NewsEventsRepository;
use Illuminate\View\View;

class MainLayoutComposer
{
    public function __construct(
        private SiteNavigationService $navigation,
    ) {}

    public function compose(View $view): void
    {
        $isHome = request()->routeIs('home');
        $currentSlug = (string) request()->route('slug', '');
        $isNewsSection = request()->routeIs('news-events.index', 'news-events.paginated')
            || ($currentSlug !== '' && NewsEventsRepository::find($currentSlug) !== null);
        $currentPath = ltrim((string) request()->path(), '/');
        $isUrdu = $currentPath === 'urdu' || str_starts_with($currentPath, 'urdu/');
        $englishPath = $isUrdu ? ltrim(preg_replace('/^urdu\\/?/i', '', $currentPath) ?? '', '/') : $currentPath;
        $urduPath = $isUrdu ? $currentPath : trim('urdu/'.$currentPath, '/');
        $englishUrl = url($englishPath === '' ? '/' : $englishPath);
        $urduUrl = url($urduPath === '' ? '/urdu' : $urduPath);
        $urduLogo = asset('assets/images/5th-pillar-takaful-urdu-logo-64d5ec61bc591.webp');
        $englishLogo = asset('uploads/2025/2025/08/cropped-5th-pilar-logo-64d5d6041dbd1.webp');
        $logoUrl = $isUrdu ? $urduLogo : $englishLogo;


        $menu = $this->navigation->menuTree($isHome, $currentSlug, $isNewsSection);

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

        $requestPath = strtolower(trim((string) request()->path(), '/'));
        $urlMatchesCurrent = static function (string $url) use ($requestPath): bool {
            if ($url === '' || $url === '#') {
                return false;
            }
            $path = parse_url($url, PHP_URL_PATH);
            if ($path === null || $path === '') {
                return false;
            }
            $path = strtolower(trim($path, '/'));

            return $path === $requestPath;
        };

        $groupIsActive = static function (array $item) use (&$groupIsActive, $currentSlug, $itemUrl, $urlMatchesCurrent): bool {
            if (! empty($item['active'])) {
                return true;
            }

            if (($item['slug'] ?? null) === $currentSlug) {
                return true;
            }

            $href = $itemUrl($item);
            if ($href !== '#' && $urlMatchesCurrent($href)) {
                return true;
            }

            foreach ($item['children'] ?? [] as $child) {
                if ($groupIsActive($child)) {
                    return true;
                }
            }

            return false;
        };

        $view->with([
            'isHome' => $isHome,
            'currentSlug' => $currentSlug,
            'isNewsSection' => $isNewsSection,
            'currentPath' => $currentPath,
            'isUrdu' => $isUrdu,
            'englishPath' => $englishPath,
            'urduPath' => $urduPath,
            'englishUrl' => $englishUrl,
            'urduUrl' => $urduUrl,
            'logoUrl' => $logoUrl,
            'menu' => $menu,
            'itemUrl' => $itemUrl,
            'itemTarget' => $itemTarget,
            'itemRel' => $itemRel,
            'groupIsActive' => $groupIsActive,
        ]);
    }
}
