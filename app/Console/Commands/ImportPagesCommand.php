<?php

namespace App\Console\Commands;

use App\Models\Page;
use Illuminate\Console\Command;

class ImportPagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pages:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import WordPress pages from storage/app/pages.csv';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $path = storage_path('app/pages.csv');

        if (! is_file($path)) {
            $this->error('CSV file not found at storage/app/pages.csv.');

            return self::FAILURE;
        }
        $handle = fopen($path, 'r');

        if ($handle === false) {
            $this->error("Unable to open {$path}.");

            return self::FAILURE;
        }

        $headers = fgetcsv($handle);

        if ($headers === false) {
            fclose($handle);
            $this->error('The CSV file is empty.');

            return self::FAILURE;
        }

        $headers = array_map(function ($header) {
            $header = (string) $header;

            return trim(preg_replace('/^\xEF\xBB\xBF/', '', $header));
        }, $headers);

        $requiredColumns = ['Title', 'Slug', 'Content'];
        $missingColumns = array_diff($requiredColumns, $headers);

        if ($missingColumns !== []) {
            fclose($handle);
            $this->error('Missing required CSV columns: '.implode(', ', $missingColumns));

            return self::FAILURE;
        }

        $created = 0;
        $updated = 0;
        $skipped = 0;
        $line = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $line++;

            if ($this->rowIsEmpty($row)) {
                continue;
            }

            if (count($row) !== count($headers)) {
                $this->warn("Skipping line {$line} because the column count does not match the header row.");
                $skipped++;

                continue;
            }

            $record = array_combine($headers, $row);

            if ($record === false) {
                $this->warn("Skipping line {$line} because the row could not be combined with the header row.");
                $skipped++;

                continue;
            }

            $title = trim((string) ($record['Title'] ?? ''));
            $slug = trim((string) ($record['Slug'] ?? ''));
            $content = (string) ($record['Content'] ?? '');

            if ($slug === '') {
                $this->warn("Skipping line {$line} because the slug is empty.");
                $skipped++;

                continue;
            }

            $page = Page::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title !== '' ? $title : $slug,
                    'content' => $content,
                ]
            );

            if ($page->wasRecentlyCreated) {
                $created++;
            } else {
                $updated++;
            }
        }

        fclose($handle);

        $this->info("Pages import completed. Created: {$created}, Updated: {$updated}, Skipped: {$skipped}.");

        return self::SUCCESS;
    }

    /**
     * Determine whether the provided CSV row is empty.
     *
     * @param  array<int, string|null>  $row
     */
    protected function rowIsEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if ($value !== null && trim($value) !== '') {
                return false;
            }
        }

        return true;
    }
}
