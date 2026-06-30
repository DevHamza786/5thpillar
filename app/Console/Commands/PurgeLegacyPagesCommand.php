<?php

namespace App\Console\Commands;

use App\Models\Page;
use Illuminate\Console\Command;

class PurgeLegacyPagesCommand extends Command
{
    protected $signature = 'cms:purge-legacy-pages
                            {--dry-run : List matching pages without changing them}
                            {--delete : Permanently delete pages instead of setting to draft}';

    protected $description = 'Unpublish or delete legacy WordPress demo placeholder pages listed in config/cms.php';

    public function handle(): int
    {
        $slugs = config('cms.legacy_demo_slugs', []);

        if ($slugs === []) {
            $this->warn('No legacy slugs configured in config/cms.php.');

            return self::SUCCESS;
        }

        $pages = Page::query()
            ->whereIn('slug', $slugs)
            ->orWhereIn('view_key', $slugs)
            ->orderBy('slug')
            ->get();

        if ($pages->isEmpty()) {
            $this->info('No matching legacy pages found in the database.');

            return self::SUCCESS;
        }

        $this->info(sprintf('Found %d legacy page(s).', $pages->count()));

        foreach ($pages as $page) {
            $this->line(sprintf(
                ' - [%s] %s (slug: %s, view_key: %s, status: %s)',
                $page->id,
                $page->title,
                $page->slug,
                $page->view_key ?: '—',
                $page->status ?? ($page->is_published ? 'published' : 'draft')
            ));
        }

        if ($this->option('dry-run')) {
            $this->comment('Dry run — no changes made.');

            return self::SUCCESS;
        }

        if (! $this->confirm('Apply changes to these pages?', true)) {
            $this->comment('Cancelled.');

            return self::SUCCESS;
        }

        $count = 0;

        foreach ($pages as $page) {
            if ($this->option('delete')) {
                $page->sections()->delete();
                $page->media()->delete();
                $page->delete();
            } else {
                $page->update([
                    'status' => Page::STATUS_DRAFT,
                    'status_ur' => Page::STATUS_DRAFT,
                    'is_published' => false,
                ]);
            }

            $count++;
        }

        $action = $this->option('delete') ? 'deleted' : 'set to draft';
        $this->info(sprintf('%d page(s) %s.', $count, $action));

        return self::SUCCESS;
    }
}
