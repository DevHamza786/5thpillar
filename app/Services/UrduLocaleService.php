<?php

namespace App\Services;

use App\Models\Page;

class UrduLocaleService
{
    public function __construct(
        private readonly SiteSettingsService $settings
    ) {}

    public function isEnabled(): bool
    {
        return (bool) ($this->localeConfig()['enabled'] ?? true);
    }

    public function prefix(): string
    {
        $prefix = trim((string) ($this->localeConfig()['prefix'] ?? 'urdu'), '/');

        return $prefix !== '' ? $prefix : 'urdu';
    }

    /**
     * @return array<string, string>
     */
    public function routeSlugs(): array
    {
        return array_merge(
            config('site.locale.urdu_route_slugs', []),
            (array) ($this->localeConfig()['route_slugs'] ?? [])
        );
    }

    public function routeSlug(string $key, string $fallback): string
    {
        $slug = trim((string) ($this->routeSlugs()[$key] ?? ''));

        return $slug !== '' ? $slug : $fallback;
    }

    public function isUrduLocale(?string $locale = null): bool
    {
        $locale ??= app()->getLocale();

        return in_array($locale, ['ur', 'urdu'], true);
    }

    public function isUrduPath(string $path): bool
    {
        if (! $this->isEnabled()) {
            return false;
        }

        $normalized = trim($path, '/');
        $prefix = $this->prefix();

        return $normalized === $prefix || str_starts_with($normalized, $prefix.'/');
    }

    public function routeNamePrefix(): string
    {
        return $this->isUrduLocale() ? 'urdu.' : '';
    }

    public function namedRoute(string $baseName): string
    {
        return $this->routeNamePrefix().ltrim($baseName, '.');
    }

    public function englishPath(string $path): string
    {
        $normalized = trim($path, '/');

        if (! $this->isUrduPath($normalized)) {
            return $normalized;
        }

        $prefix = $this->prefix();
        $stripped = $normalized === $prefix
            ? ''
            : ltrim((string) preg_replace('/^'.preg_quote($prefix, '/').'\/?/u', '', $normalized), '/');

        return $this->translatePathSegmentToEnglish($stripped);
    }

    public function urduPath(string $path): string
    {
        $normalized = trim($path, '/');

        if (! $this->isEnabled()) {
            return $normalized;
        }

        if ($this->isUrduPath($normalized)) {
            return $normalized;
        }

        $translated = $this->translatePathSegmentToUrdu($normalized);
        $prefix = $this->prefix();

        return $translated === '' ? $prefix : trim($prefix.'/'.$translated, '/');
    }

    public function redirectsToLive(): bool
    {
        return (bool) ($this->localeConfig()['redirect_to_live'] ?? false);
    }

    public function liveBaseUrl(): string
    {
        $base = rtrim((string) ($this->localeConfig()['live_base_url'] ?? 'https://5thpillartakaful.com'), '/');

        return $base !== '' ? $base : 'https://5thpillartakaful.com';
    }

    public function liveUrduUrl(string $suffix = ''): string
    {
        $prefix = $this->prefix();
        $suffix = trim($suffix, '/');
        $base = $this->liveBaseUrl();

        if ($suffix === '') {
            return $base.'/'.$prefix.'/';
        }

        return $base.'/'.$prefix.'/'.$suffix.'/';
    }

    public function homeUrl(?string $locale = null): string
    {
        if ($this->isUrduLocale($locale)) {
            return $this->redirectsToLive()
                ? $this->liveUrduUrl()
                : url('/'.$this->prefix());
        }

        return url('/');
    }

    public function switcherUrls(): array
    {
        $currentPath = ltrim((string) request()->path(), '/');
        $isUrdu = $this->isUrduPath($currentPath);
        $englishPath = $isUrdu ? $this->englishPath($currentPath) : $currentPath;

        if ($this->redirectsToLive()) {
            $urduSuffix = $isUrdu
                ? ltrim((string) preg_replace('/^'.preg_quote($this->prefix(), '/').'\/?/u', '', $currentPath), '/')
                : $englishPath;

            return [
                'english' => url($englishPath === '' ? '/' : $englishPath),
                'urdu' => $this->liveUrduUrl($urduSuffix),
                'is_urdu' => $isUrdu,
            ];
        }

        $urduPath = $isUrdu ? $currentPath : $this->urduPath($currentPath);

        return [
            'english' => url($englishPath === '' ? '/' : $englishPath),
            'urdu' => url($urduPath === '' ? '/'.$this->prefix() : $urduPath),
            'is_urdu' => $isUrdu,
        ];
    }

    public function pageSlug(Page $page, ?string $locale = null): string
    {
        if ($this->isUrduLocale($locale) && filled($page->slug_ur)) {
            return (string) $page->slug_ur;
        }

        return (string) $page->slug;
    }

    public function pageUrl(Page $page, ?string $locale = null): string
    {
        $slug = $this->pageSlug($page, $locale);

        if ($this->isUrduLocale($locale)) {
            return $this->redirectsToLive()
                ? $this->liveUrduUrl($slug)
                : url($this->prefix().'/'.$slug);
        }

        return url('/'.$slug);
    }

    public function resolveMenuSlug(string $englishSlug): string
    {
        if (! $this->isUrduLocale()) {
            return $englishSlug;
        }

        $page = Page::query()->where('slug', $englishSlug)->first();

        return $page ? $this->pageSlug($page) : $englishSlug;
    }

    public function resolvePageBySlug(string $slug): ?Page
    {
        if ($this->isUrduLocale()) {
            return Page::query()
                ->where('slug_ur', $slug)
                ->orWhere('slug', $slug)
                ->first();
        }

        return Page::query()->where('slug', $slug)->first();
    }

    private function localeConfig(): array
    {
        return (array) config('site.locale', []);
    }

    private function translatePathSegmentToUrdu(string $path): string
    {
        if ($path === '') {
            return '';
        }

        $segments = explode('/', $path);
        $first = $segments[0] ?? '';
        $urduFirst = $this->englishRouteKeyToUrduSlug($first);

        if ($urduFirst !== $first) {
            $segments[0] = $urduFirst;

            return implode('/', $segments);
        }

        $page = Page::query()->where('slug', $first)->first();
        if ($page && filled($page->slug_ur)) {
            $segments[0] = (string) $page->slug_ur;

            return implode('/', $segments);
        }

        return $path;
    }

    private function translatePathSegmentToEnglish(string $path): string
    {
        if ($path === '') {
            return '';
        }

        $segments = explode('/', $path);
        $first = $segments[0] ?? '';
        $englishFirst = $this->urduSlugToEnglishRouteKey($first);

        if ($englishFirst !== $first) {
            $segments[0] = $englishFirst;

            return implode('/', $segments);
        }

        $page = Page::query()->where('slug_ur', $first)->first();
        if ($page) {
            $segments[0] = (string) $page->slug;

            return implode('/', $segments);
        }

        return $path;
    }

    private function englishRouteKeyToUrduSlug(string $segment): string
    {
        foreach ($this->routeSlugs() as $key => $urduSlug) {
            if ($key === $segment && filled($urduSlug)) {
                return (string) $urduSlug;
            }
        }

        return $segment;
    }

    private function urduSlugToEnglishRouteKey(string $segment): string
    {
        foreach ($this->routeSlugs() as $key => $urduSlug) {
            if ($urduSlug === $segment) {
                return (string) $key;
            }
        }

        return $segment;
    }

    /**
     * @return array<string, mixed>
     */
    public function forAdminForm(): array
    {
        $locale = $this->localeConfig();

        return [
            'enabled' => (bool) ($locale['enabled'] ?? true),
            'prefix' => $this->prefix(),
            'route_slugs' => $this->routeSlugs(),
        ];
    }

    /**
     * @return list<array{key: string, label: string, default: string}>
     */
    public function routeSlugDefinitions(): array
    {
        return [
            ['key' => 'hajj-planner', 'label' => 'Hajj planner', 'default' => 'hajj-planner'],
            ['key' => 'umrah-planner', 'label' => 'Umrah planner', 'default' => 'umrah-planner'],
            ['key' => 'news-and-events', 'label' => 'News & events', 'default' => 'news-and-events'],
            ['key' => 'online-complaint-form', 'label' => 'Online complaint form', 'default' => 'online-complaint-form'],
        ];
    }
}
