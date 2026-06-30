<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use App\Services\CmsSectionRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PageSectionController extends Controller
{
    public function __construct(
        private readonly CmsSectionRegistry $registry
    ) {}

    public function store(Request $request, Page $page): RedirectResponse
    {
        $data = $this->validatedSection($request);

        $maxOrder = (int) $page->sections()->max('sort_order');

        $page->sections()->create([
            'heading' => $data['heading'] ?? null,
            'heading_ur' => $data['heading_ur'] ?? null,
            'body_html' => $data['body_html'] ?? null,
            'body_html_ur' => $data['body_html_ur'] ?? null,
            'section_type' => $data['section_type'] ?? 'text',
            'content' => $data['content'] ?? null,
            'settings' => $data['settings'] ?? null,
            'is_enabled' => $data['is_enabled'] ?? true,
            'sort_order' => $maxOrder + 1,
        ]);

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('status', __('Section added.'));
    }

    public function update(Request $request, Page $page, PageSection $pageSection): RedirectResponse
    {
        if ($pageSection->page_id !== $page->id) {
            abort(404);
        }

        $data = $this->validatedSection($request);

        $pageSection->update([
            'heading' => $data['heading'] ?? null,
            'heading_ur' => $data['heading_ur'] ?? null,
            'body_html' => $data['body_html'] ?? null,
            'body_html_ur' => $data['body_html_ur'] ?? null,
            'section_type' => $data['section_type'] ?? 'text',
            'content' => $data['content'] ?? null,
            'settings' => $data['settings'] ?? null,
            'is_enabled' => $data['is_enabled'] ?? false,
            'sort_order' => isset($data['sort_order']) ? (int) $data['sort_order'] : $pageSection->sort_order,
        ]);

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('status', __('Section updated.'));
    }

    public function destroy(Page $page, PageSection $pageSection): RedirectResponse
    {
        if ($pageSection->page_id !== $page->id) {
            abort(404);
        }

        $pageSection->delete();

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('status', __('Section removed.'));
    }

    public function duplicate(Page $page, PageSection $pageSection): RedirectResponse
    {
        if ($pageSection->page_id !== $page->id) {
            abort(404);
        }

        $maxOrder = (int) $page->sections()->max('sort_order');

        $page->sections()->create([
            'heading' => $pageSection->heading,
            'heading_ur' => $pageSection->heading_ur,
            'body_html' => $pageSection->body_html,
            'body_html_ur' => $pageSection->body_html_ur,
            'section_type' => $pageSection->section_type,
            'content' => $pageSection->content,
            'settings' => $pageSection->settings,
            'is_enabled' => false,
            'sort_order' => $maxOrder + 1,
        ]);

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('status', __('Section duplicated as draft.'));
    }

    public function reorder(Request $request, Page $page): JsonResponse
    {
        $validated = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer'],
        ]);

        $ids = $page->sections()->pluck('id')->all();

        foreach ($validated['order'] as $index => $sectionId) {
            if (! in_array((int) $sectionId, $ids, true)) {
                continue;
            }

            PageSection::query()
                ->where('page_id', $page->id)
                ->where('id', $sectionId)
                ->update(['sort_order' => $index]);
        }

        return response()->json(['ok' => true]);
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedSection(Request $request): array
    {
        $type = (string) $request->input('section_type', 'text');

        if (! $this->registry->hasType($type)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'section_type' => [__('Unknown section type.')],
            ]);
        }

        $validated = $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'heading_ur' => ['nullable', 'string', 'max:255'],
            'body_html' => ['nullable', 'string'],
            'body_html_ur' => ['nullable', 'string'],
            'section_type' => ['required', 'string', Rule::in(array_keys($this->registry->types()))],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:99999'],
            'is_enabled' => ['nullable', 'boolean'],
            'content' => ['nullable', 'array'],
            'settings' => ['nullable', 'array'],
        ]);

        $validated['is_enabled'] = $request->boolean('is_enabled');
        $content = $validated['content'] ?? [];
        $content = $this->preprocessStructuredContent($type, $content);
        $validated['content'] = $this->registry->normalizeContent($type, $content);
        $validated['settings'] = $this->registry->normalizeSettings($type, $validated['settings'] ?? null);

        return $validated;
    }

    /**
     * @param  array<string, mixed>  $content
     * @return array<string, mixed>
     */
    private function preprocessStructuredContent(string $type, array $content): array
    {
        if ($type === 'table') {
            if (! empty($content['columns_csv']) && is_string($content['columns_csv'])) {
                $content['columns'] = array_values(array_filter(array_map(
                    static fn (string $col): string => trim($col),
                    explode(',', $content['columns_csv'])
                )));
                unset($content['columns_csv']);
            }

            foreach ((array) ($content['rows'] ?? []) as $index => $row) {
                if (! is_array($row) || empty($row['cells_csv']) || ! is_string($row['cells_csv'])) {
                    continue;
                }

                $content['rows'][$index]['cells'] = array_map(
                    static fn (string $cell): string => trim($cell),
                    explode('|', $row['cells_csv'])
                );
                unset($content['rows'][$index]['cells_csv']);
            }
        }

        return $content;
    }
}
