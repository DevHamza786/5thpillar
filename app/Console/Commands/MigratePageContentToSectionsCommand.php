<?php

namespace App\Console\Commands;

use App\Models\Page;
use App\Services\CmsContentMigrator;
use Illuminate\Console\Command;

class MigratePageContentToSectionsCommand extends Command
{
    protected $signature = 'cms:migrate-content-to-sections
                            {slug? : Single page slug to migrate}
                            {--all : Migrate all pages with legacy HTML content}
                            {--force : Replace existing sections}
                            {--dry-run : Preview section count without writing}';

    protected $description = 'Convert legacy pages.content HTML into structured CMS page sections';

    public function handle(CmsContentMigrator $migrator): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $force = (bool) $this->option('force');

        $pages = $this->resolvePages();

        if ($pages->isEmpty()) {
            $this->warn('No pages matched for migration.');

            return self::SUCCESS;
        }

        $total = 0;

        foreach ($pages as $page) {
            $count = $migrator->migrate($page, $force, $dryRun);
            if ($count === 0) {
                $this->line("  Skipped {$page->slug} (no content or sections already exist)");

                continue;
            }

            $total += $count;
            $verb = $dryRun ? 'Would create' : 'Created';
            $this->info("{$verb} {$count} sections for {$page->slug}");
        }

        $this->info($dryRun
            ? "Dry run complete — {$total} sections would be created."
            : "Migration complete — {$total} sections created.");

        return self::SUCCESS;
    }

    private function resolvePages()
    {
        $slug = $this->argument('slug');

        if ($slug) {
            return Page::query()
                ->where('slug', $slug)
                ->orWhere('view_key', $slug)
                ->get();
        }

        if ($this->option('all')) {
            return Page::query()
                ->where(function ($query): void {
                    $query->whereNotNull('content')->where('content', '!=', '');
                })
                ->orderBy('sort_order')
                ->get();
        }

        $this->error('Provide a slug or use --all');

        return collect();
    }
}
