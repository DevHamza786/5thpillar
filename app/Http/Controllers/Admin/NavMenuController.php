<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsMedia;
use App\Models\NavMenuItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class NavMenuController extends Controller
{
    public function index(): View
    {
        $items = NavMenuItem::query()
            ->with('parent')
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->get();

        return view('admin.navigation.index', [
            'items' => $items,
        ]);
    }

    public function create(): View
    {
        return view('admin.navigation.create', [
            'item' => new NavMenuItem,
            'parentOptions' => $this->parentOptions(),
            'mediaOptions' => $this->mediaOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        NavMenuItem::create($data);

        return redirect()->route('admin.navigation.index')->with('status', __('Menu item added.'));
    }

    public function edit(NavMenuItem $navMenuItem): View
    {
        return view('admin.navigation.edit', [
            'item' => $navMenuItem,
            'parentOptions' => $this->parentOptions(excludeId: $navMenuItem->id),
            'mediaOptions' => $this->mediaOptions(),
        ]);
    }

    public function update(Request $request, NavMenuItem $navMenuItem): RedirectResponse
    {
        $data = $this->validated($request, $navMenuItem->id);
        $navMenuItem->update($data);

        return redirect()->route('admin.navigation.index')->with('status', __('Menu item updated.'));
    }

    public function destroy(NavMenuItem $navMenuItem): RedirectResponse
    {
        $navMenuItem->delete();

        return redirect()->route('admin.navigation.index')->with('status', __('Menu item removed.'));
    }

    /**
     * @return array<string, string>
     */
    private function parentOptions(?int $excludeId = null): array
    {
        $choices = ['' => __('— Top level —')];
        $items = NavMenuItem::query()->orderBy('parent_id')->orderBy('sort_order')->get();

        foreach ($items as $c) {
            if ($excludeId !== null) {
                if ((int) $c->id === $excludeId) {
                    continue;
                }
                if ($this->itemHasAncestor($items, (int) $c->id, $excludeId)) {
                    continue;
                }
            }
            $depth = $this->depth($items, $c);
            $prefix = $depth > 0 ? str_repeat('— ', $depth) : '';
            $choices[(string) $c->id] = $prefix.$c->label;
        }

        return $choices;
    }

    /**
     * @param  Collection<int, NavMenuItem>  $items
     */
    private function itemHasAncestor($items, int $itemId, int $ancestorId): bool
    {
        $current = $itemId;
        for ($g = 0; $g < 100; $g++) {
            $row = $items->firstWhere('id', $current);
            if ($row === null || $row->parent_id === null) {
                return false;
            }
            if ((int) $row->parent_id === $ancestorId) {
                return true;
            }
            $current = (int) $row->parent_id;
        }

        return false;
    }

    /**
     * @param  Collection<int, NavMenuItem>  $items
     */
    private function depth($items, NavMenuItem $item): int
    {
        $d = 0;
        $current = $item->parent_id;
        $guard = 0;
        while ($current !== null && $guard < 100) {
            $d++;
            $row = $items->firstWhere('id', $current);
            $current = $row ? $row->parent_id : null;
            $guard++;
        }

        return $d;
    }

    /**
     * @return array<int, string>
     */
    private function mediaOptions(): array
    {
        return CmsMedia::query()
            ->whereNull('page_id')
            ->orderByDesc('id')
            ->get()
            ->mapWithKeys(fn ($m) => [$m->id => ($m->label ?? $m->original_name).' ('.($m->mime ?? 'file').')'])
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $parentRules = ['nullable', 'exists:nav_menu_items,id'];
        if ($ignoreId !== null) {
            $parentRules[] = Rule::notIn([$ignoreId]);
        }

        if ($request->input('parent_id') === '' || $request->input('parent_id') === '0') {
            $request->merge(['parent_id' => null]);
        }

        $data = $request->validate([
            'parent_id' => $parentRules,
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:99999'],
            'label' => ['required', 'string', 'max:255'],
            'link_type' => ['required', 'string', Rule::in([
                NavMenuItem::LINK_NONE,
                NavMenuItem::LINK_HOME,
                NavMenuItem::LINK_PAGE_SLUG,
                NavMenuItem::LINK_NAMED_ROUTE,
                NavMenuItem::LINK_CUSTOM_URL,
                NavMenuItem::LINK_MEDIA,
            ])],
            'page_slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'route_name' => ['nullable', 'string', 'max:255'],
            'custom_url' => ['nullable', 'string', 'max:2048'],
            'cms_media_id' => ['nullable', 'exists:cms_media,id'],
            'open_new_tab' => ['sometimes', 'boolean'],
        ]);

        $data['parent_id'] = filled($data['parent_id'] ?? null) ? (int) $data['parent_id'] : null;
        $data['sort_order'] = isset($data['sort_order']) ? (int) $data['sort_order'] : 0;
        $data['open_new_tab'] = $request->boolean('open_new_tab');

        if ($data['link_type'] !== NavMenuItem::LINK_PAGE_SLUG) {
            $data['page_slug'] = null;
        }
        if ($data['link_type'] !== NavMenuItem::LINK_NAMED_ROUTE) {
            $data['route_name'] = null;
        }
        if ($data['link_type'] !== NavMenuItem::LINK_CUSTOM_URL) {
            $data['custom_url'] = null;
        }
        if ($data['link_type'] !== NavMenuItem::LINK_MEDIA) {
            $data['cms_media_id'] = null;
        }

        return $data;
    }
}
