<?php

namespace App\Console\Commands;

use App\Services\LegacyAssetMigrator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MigrateLegacyAssetsCommand extends Command
{
    protected $signature = 'assets:migrate-legacy
                            {--dry-run : Preview changes without writing}
                            {--skip-files : Skip physical file moves}
                            {--skip-refs : Skip reference updates}
                            {--cleanup : Delete public/uploads and public/wp-content after migration}
                            {--report= : Write JSON report to path}';

    protected $description = 'Migrate legacy WordPress uploads/wp-content assets into public/assets/*';

    public function handle(LegacyAssetMigrator $migrator): int
    {
        $dryRun = (bool) $this->option('dry-run');

        if ($dryRun) {
            $this->warn('Dry run — no files or database rows will be modified.');
        }

        $this->info('Phase 1: Audit');
        $audit = $migrator->audit();
        $this->table(
            ['Metric', 'Value'],
            [
                ['Path map entries', $audit['path_map_entries']],
                ['Source files found', $audit['source_files']['found']],
                ['Source files missing', $audit['source_files']['missing']],
                ['uploads/ file count', $audit['legacy_dirs']['uploads_files']],
                ['wp-content/ file count', $audit['legacy_dirs']['wp_content_files']],
            ]
        );
        if ($audit['database'] !== []) {
            $this->line('Database legacy references:');
            foreach ($audit['database'] as $key => $count) {
                $this->line("  {$key}: {$count}");
            }
        }

        if (! $this->option('skip-files')) {
            $this->info('Phase 2: Migrate files');
            $result = $migrator->migrateFiles($dryRun);
            $this->line("Moved: {$result['moved']}, skipped (already present): {$result['skipped']}, missing: ".count($result['missing']));
            if ($result['missing'] !== []) {
                $this->warn('Missing sources (first 10):');
                foreach (array_slice($result['missing'], 0, 10) as $path) {
                    $this->line("  - {$path}");
                }
            }

            $pdfDir = public_path('assets/pdfs');
            $pdfTarget = public_path('assets/pdf');
            if (is_dir($pdfDir) && ! $dryRun) {
                if (is_dir($pdfTarget)) {
                    File::copyDirectory($pdfDir, $pdfTarget);
                    File::deleteDirectory($pdfDir);
                } else {
                    rename($pdfDir, $pdfTarget);
                }
                $this->line('Renamed assets/pdfs → assets/pdf');
            }
        }

        if (! $this->option('skip-refs')) {
            $this->info('Phase 3: Update references');
            $refs = $migrator->updateReferences($dryRun);
            $this->line("Updated {$refs['files']} files and {$refs['database_rows']} database rows.");
        }

        $this->info('Phase 5: Validate');
        $broken = $migrator->validate();
        if ($broken === []) {
            $this->info('All mapped destination files exist.');
        } else {
            $this->warn(count($broken).' mapped assets missing on disk (first 10):');
            foreach (array_slice($broken, 0, 10) as $path) {
                $this->line("  - {$path}");
            }
        }

        if ($this->option('cleanup') && ! $dryRun) {
            if ($broken !== []) {
                $this->error('Cleanup aborted — fix missing assets first or run without --cleanup.');

                return self::FAILURE;
            }
            $this->info('Phase 6: Cleanup legacy directories');
            $migrator->cleanupLegacyDirectories(false);
            $this->line('Removed public/uploads, public/wp-content, and config/upload-to-assets-map.php');
        }

        if ($reportPath = $this->option('report')) {
            file_put_contents($reportPath, json_encode([
                'audit' => $audit,
                'broken' => $broken,
                'dry_run' => $dryRun,
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $this->line("Report written to {$reportPath}");
        }

        return self::SUCCESS;
    }
}
