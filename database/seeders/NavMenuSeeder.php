<?php

namespace Database\Seeders;

use App\Models\NavMenuItem;
use App\Services\SiteNavigationService;
use Illuminate\Database\Seeder;

class NavMenuSeeder extends Seeder
{
    public function run(): void
    {
        if (NavMenuItem::query()->exists()) {
            return;
        }

        $tree = app(SiteNavigationService::class)->defaultMenuSeedTree();
        $this->seedBranch($tree, null);
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    private function seedBranch(array $items, ?int $parentId): void
    {
        foreach (array_values($items) as $index => $raw) {
            $item = new NavMenuItem;
            $item->parent_id = $parentId;
            $item->sort_order = $index;
            $item->label = $raw['label'];
            $item->open_new_tab = ! empty($raw['external']);

            if (! empty($raw['children'])) {
                $item->link_type = NavMenuItem::LINK_NONE;
                $item->save();
                $this->seedBranch($raw['children'], $item->id);

                continue;
            }

            if (! empty($raw['slug'])) {
                $item->link_type = NavMenuItem::LINK_PAGE_SLUG;
                $item->page_slug = $raw['slug'];
            } elseif (! empty($raw['route'])) {
                $item->link_type = NavMenuItem::LINK_NAMED_ROUTE;
                $item->route_name = $raw['route'];
            } elseif (! empty($raw['url'])) {
                if ($raw['url'] === route('home')) {
                    $item->link_type = NavMenuItem::LINK_HOME;
                } else {
                    $item->link_type = NavMenuItem::LINK_CUSTOM_URL;
                    $item->custom_url = $raw['url'];
                }
            } else {
                $item->link_type = NavMenuItem::LINK_NONE;
            }

            $item->save();
        }
    }
}
