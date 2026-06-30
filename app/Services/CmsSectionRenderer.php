<?php

namespace App\Services;

use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewContract;

class CmsSectionRenderer
{
    public function __construct(
        private readonly CmsSectionRegistry $registry
    ) {}

    public function render(PageSection $section): ViewContract|string
    {
        $type = $section->section_type ?: 'content';
        $view = 'pages.partials.sections.'.$this->registry->viewName($type);

        if (! View::exists($view)) {
            $view = 'pages.partials.sections.content';
        }

        return view($view, [
            'section' => $section,
            'content' => $section->content ?? [],
            'settings' => $section->settings ?? [],
            'registry' => $this->registry,
        ]);
    }

    public function primarySection(?Page $page, string $type): ?PageSection
    {
        if ($page === null || ! $page->relationLoaded('sections')) {
            return null;
        }

        return $page->sections
            ->filter(fn (PageSection $section) => $section->is_enabled
                && $section->section_type === $type
                && ($section->settings['role'] ?? 'append') === 'primary')
            ->sortBy('sort_order')
            ->first();
    }

    public function homeSection(?Page $page, string $slot): ?PageSection
    {
        if ($page === null || ! $page->relationLoaded('sections')) {
            return null;
        }

        return $page->sections
            ->filter(fn (PageSection $section) => $section->is_enabled
                && ($section->settings['role'] ?? '') === 'home'
                && ($section->settings['slot'] ?? '') === $slot)
            ->sortBy('sort_order')
            ->first();
    }

    /**
     * @return Collection<int, PageSection>
     */
    public function appendSections(?Page $page): Collection
    {
        if ($page === null || ! $page->relationLoaded('sections')) {
            return collect();
        }

        return $page->sections
            ->filter(fn (PageSection $section) => $section->is_enabled
                && ($section->settings['role'] ?? 'append') === 'append')
            ->sortBy('sort_order')
            ->values();
    }

    /**
     * @return Collection<int, PageSection>
     */
    public function tertiarySections(?Page $page): Collection
    {
        if ($page === null || ! $page->relationLoaded('sections')) {
            return collect();
        }

        return $page->sections
            ->filter(fn (PageSection $section) => $section->is_enabled
                && ($section->settings['role'] ?? '') === 'tertiary')
            ->sortBy('sort_order')
            ->values();
    }

    /**
     * @return Collection<int, PageSection>
     */
    public function primarySections(?Page $page): Collection
    {
        return $this->sectionsForRole($page, 'primary');
    }

    /**
     * @return Collection<int, PageSection>
     */
    public function sectionsForRole(?Page $page, string $role): Collection
    {
        if ($page === null || ! $page->relationLoaded('sections')) {
            return collect();
        }

        return $page->sections
            ->filter(fn (PageSection $section) => $section->is_enabled
                && ($section->settings['role'] ?? 'append') === $role)
            ->sortBy('sort_order')
            ->values();
    }

    public function hasPrimarySections(?Page $page): bool
    {
        return $this->primarySections($page)->isNotEmpty();
    }

    public function hasCmsTertiary(?Page $page): bool
    {
        return $this->tertiarySections($page)->isNotEmpty();
    }
}
