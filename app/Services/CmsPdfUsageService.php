<?php

namespace App\Services;

use App\Models\CmsMedia;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Support\Collection;

class CmsPdfUsageService
{
    public function __construct(
        private readonly CmsSectionRegistry $registry
    ) {}

    /**
     * PDF rows used on a single page (sections + attachments).
     *
     * @return list<array{label: string, path: string, source: string, media_id: int|null}>
     */
    public function forPage(Page $page): array
    {
        $page->loadMissing(['sections', 'media']);

        $rows = [];
        $seen = [];

        foreach ($page->sections as $section) {
            foreach ($this->pathsFromSection($section) as $item) {
                $key = $item['path'].'|'.$item['label'];
                if (isset($seen[$key])) {
                    continue;
                }
                $seen[$key] = true;
                $rows[] = $item;
            }
        }

        foreach ($page->media as $file) {
            if (! $file->isPdf()) {
                continue;
            }
            $path = ltrim((string) $file->path, '/');
            if ($file->disk === CmsMedia::DISK_PUBLIC && ! str_starts_with($path, 'assets/')) {
                $path = $path;
            }
            $key = $path.'|'.($file->label ?: $file->original_name);
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            $rows[] = [
                'label' => (string) ($file->label ?: $file->original_name),
                'path' => $path,
                'source' => __('Page attachment'),
                'media_id' => $file->id,
            ];
        }

        $libraryByPath = CmsMedia::query()
            ->library()
            ->pdfs()
            ->get()
            ->keyBy(fn (CmsMedia $m) => ltrim((string) $m->path, '/'));

        return array_map(function (array $row) use ($libraryByPath) {
            $path = ltrim((string) $row['path'], '/');
            $match = $libraryByPath->get($path);
            $row['media_id'] = $row['media_id'] ?? $match?->id;
            $row['path'] = $path;

            return $row;
        }, $rows);
    }

    /**
     * Map library PDF path => pages that reference it.
     *
     * @param  Collection<int, CmsMedia>  $mediaItems
     * @return array<string, list<array{id: int, title: string}>>
     */
    public function usageByPath(Collection $mediaItems): array
    {
        $paths = $mediaItems
            ->filter(fn (CmsMedia $m) => $m->isPdf())
            ->map(fn (CmsMedia $m) => ltrim((string) $m->path, '/'))
            ->filter()
            ->unique()
            ->values()
            ->all();

        if ($paths === []) {
            return [];
        }

        $usage = array_fill_keys($paths, []);

        $pages = Page::query()
            ->with(['sections', 'media'])
            ->orderBy('title')
            ->get();

        foreach ($pages as $page) {
            $pagePaths = [];

            foreach ($page->sections as $section) {
                foreach ($this->pathsFromSection($section) as $item) {
                    $pagePaths[ltrim($item['path'], '/')] = true;
                }
            }

            foreach ($page->media as $file) {
                if ($file->isPdf()) {
                    $pagePaths[ltrim((string) $file->path, '/')] = true;
                }
            }

            foreach (array_keys($pagePaths) as $path) {
                if (! array_key_exists($path, $usage)) {
                    continue;
                }
                $usage[$path][] = [
                    'id' => $page->id,
                    'title' => (string) ($page->title ?: $page->slug),
                ];
            }
        }

        return $usage;
    }

    /**
     * @return list<array{label: string, path: string, source: string, media_id: null}>
     */
    public function pathsFromSection(PageSection $section): array
    {
        $type = (string) $section->section_type;
        $content = is_array($section->content) ? $section->content : [];
        $label = $this->registry->label($type);
        $rows = [];

        if ($type === 'pdf') {
            foreach (['pdf_path', 'pdf_path_ur'] as $key) {
                $path = $this->registry->normalizeAssetPath((string) ($content[$key] ?? ''));
                if ($path === '') {
                    continue;
                }
                $rows[] = [
                    'label' => (string) ($content['download_label'] ?? basename($path)),
                    'path' => $path,
                    'source' => $label,
                    'media_id' => null,
                ];
            }
        }

        if ($type === 'pdf_table') {
            foreach ((array) ($content['rows'] ?? []) as $row) {
                if (! is_array($row)) {
                    continue;
                }
                $path = $this->registry->normalizeAssetPath((string) ($row['path'] ?? ''));
                if ($path === '') {
                    continue;
                }
                $rows[] = [
                    'label' => (string) ($row['label'] ?: basename($path)),
                    'path' => $path,
                    'source' => $label,
                    'media_id' => null,
                ];
            }
        }

        if ($type === 'forms_catalog') {
            foreach ((array) ($content['columns'] ?? []) as $column) {
                if (! is_array($column)) {
                    continue;
                }
                foreach ((array) ($column['groups'] ?? []) as $group) {
                    if (! is_array($group)) {
                        continue;
                    }
                    $groupHeading = (string) ($group['heading'] ?? '');
                    foreach ((array) ($group['items'] ?? []) as $item) {
                        if (! is_array($item)) {
                            continue;
                        }
                        $path = $this->registry->normalizeAssetPath((string) ($item['path'] ?? ''));
                        if ($path === '') {
                            continue;
                        }
                        $rows[] = [
                            'label' => (string) ($item['label'] ?: basename($path)),
                            'path' => $path,
                            'source' => trim($label.($groupHeading !== '' ? ' · '.$groupHeading : '')),
                            'media_id' => null,
                        ];
                    }
                }
            }
        }

        if ($type === 'value_chain') {
            foreach (['pdf_path', 'pdf_path_ur'] as $key) {
                $path = $this->registry->normalizeAssetPath((string) ($content[$key] ?? ''));
                if ($path === '') {
                    continue;
                }
                $rows[] = [
                    'label' => (string) ($content['button_label'] ?? basename($path)),
                    'path' => $path,
                    'source' => $label,
                    'media_id' => null,
                ];
            }
        }

        return $rows;
    }
}
