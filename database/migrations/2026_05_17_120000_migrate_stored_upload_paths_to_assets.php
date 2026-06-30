<?php

use App\Models\NavMenuItem;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /** @var array<string, string> $pathMap */
        $pathMap = require config_path('upload-to-assets-map.php');

        $replacements = [];
        foreach ($pathMap as $from => $to) {
            $replacements['/'.$from] = '/'.$to;
            $replacements[$from] = $to;
        }

        uksort($replacements, static fn (string $a, string $b): int => strlen($b) <=> strlen($a));

        if (Schema::hasTable('nav_menu_items')) {
            NavMenuItem::query()
                ->whereNotNull('custom_url')
                ->each(function (NavMenuItem $item) use ($replacements): void {
                    $url = (string) $item->custom_url;
                    $next = str_replace(array_keys($replacements), array_values($replacements), $url);
                    if ($next !== $url) {
                        $item->custom_url = $next;
                        $item->save();
                    }
                });
        }

        if (Schema::hasTable('pages')) {
            Page::query()->each(function (Page $page) use ($replacements): void {
                $changed = false;
                foreach (['content', 'content_ur', 'masthead_bg', 'masthead_bg_ur'] as $column) {
                    if (! isset($page->{$column}) || ! is_string($page->{$column})) {
                        continue;
                    }
                    $value = $page->{$column};
                    $next = str_replace(array_keys($replacements), array_values($replacements), $value);
                    if ($next !== $value) {
                        $page->{$column} = $next;
                        $changed = true;
                    }
                }
                if ($changed) {
                    $page->save();
                }
            });
        }

        if (Schema::hasTable('page_sections')) {
            PageSection::query()->each(function (PageSection $section) use ($replacements): void {
                $changed = false;
                foreach (['body_html'] as $column) {
                    if (! isset($section->{$column}) || ! is_string($section->{$column})) {
                        continue;
                    }
                    $value = $section->{$column};
                    $next = str_replace(array_keys($replacements), array_values($replacements), $value);
                    if ($next !== $value) {
                        $section->{$column} = $next;
                        $changed = true;
                    }
                }
                if ($changed) {
                    $section->save();
                }
            });
        }
    }

    public function down(): void
    {
        /** @var array<string, string> $pathMap */
        $pathMap = require config_path('upload-to-assets-map.php');

        $replacements = [];
        foreach ($pathMap as $from => $to) {
            $replacements['/'.$to] = '/'.$from;
            $replacements[$to] = $from;
        }

        uksort($replacements, static fn (string $a, string $b): int => strlen($b) <=> strlen($a));

        if (Schema::hasTable('nav_menu_items')) {
            NavMenuItem::query()
                ->whereNotNull('custom_url')
                ->each(function (NavMenuItem $item) use ($replacements): void {
                    $url = (string) $item->custom_url;
                    $next = str_replace(array_keys($replacements), array_values($replacements), $url);
                    if ($next !== $url) {
                        $item->custom_url = $next;
                        $item->save();
                    }
                });
        }

        if (Schema::hasTable('pages')) {
            Page::query()->each(function (Page $page) use ($replacements): void {
                $changed = false;
                foreach (['content', 'content_ur', 'masthead_bg', 'masthead_bg_ur'] as $column) {
                    if (! isset($page->{$column}) || ! is_string($page->{$column})) {
                        continue;
                    }
                    $value = $page->{$column};
                    $next = str_replace(array_keys($replacements), array_values($replacements), $value);
                    if ($next !== $value) {
                        $page->{$column} = $next;
                        $changed = true;
                    }
                }
                if ($changed) {
                    $page->save();
                }
            });
        }

        if (Schema::hasTable('page_sections')) {
            PageSection::query()->each(function (PageSection $section) use ($replacements): void {
                $changed = false;
                foreach (['body_html'] as $column) {
                    if (! isset($section->{$column}) || ! is_string($section->{$column})) {
                        continue;
                    }
                    $value = $section->{$column};
                    $next = str_replace(array_keys($replacements), array_values($replacements), $value);
                    if ($next !== $value) {
                        $section->{$column} = $next;
                        $changed = true;
                    }
                }
                if ($changed) {
                    $section->save();
                }
            });
        }
    }
};
