<?php

namespace App\Console\Commands;

use App\Models\Page;
use App\Models\PageSection;
use App\Support\FundManagersReportRepository;
use Illuminate\Console\Command;

class SeedCmsPageSectionsCommand extends Command
{
    protected $signature = 'cms:seed-page-sections
                            {slug : Page slug to seed}
                            {--force : Replace existing CMS primary section}';

    protected $description = 'Seed structured CMS sections from hardcoded page data (Phase 2 pilot)';

    public function handle(): int
    {
        $slug = (string) $this->argument('slug');
        $page = Page::query()->where('slug', $slug)->orWhere('view_key', $slug)->first();

        if ($page === null) {
            $this->error("Page not found for slug/view_key: {$slug}");

            return self::FAILURE;
        }

        $payload = $this->sectionPayload($slug);

        if ($payload === null) {
            $this->error("No seed definition for slug: {$slug}");

            return self::FAILURE;
        }

        $existing = $page->sections()
            ->where('section_type', $payload['section_type'])
            ->where('settings->role', 'primary')
            ->first();

        if ($existing !== null && ! $this->option('force')) {
            $this->warn('Primary section already exists. Use --force to replace.');

            return self::SUCCESS;
        }

        if ($existing !== null) {
            $existing->delete();
        }

        $maxOrder = (int) $page->sections()->max('sort_order');

        PageSection::create([
            'page_id' => $page->id,
            'section_type' => $payload['section_type'],
            'heading' => $payload['heading'] ?? null,
            'content' => $payload['content'],
            'settings' => $payload['settings'],
            'is_enabled' => true,
            'sort_order' => $maxOrder + 1,
        ]);

        $this->info("Seeded {$payload['section_type']} primary section for page #{$page->id} ({$page->slug}).");

        return self::SUCCESS;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function sectionPayload(string $slug): ?array
    {
        return match ($slug) {
            'financial-statements' => [
                'section_type' => 'pdf_table',
                'content' => [
                    'title' => 'Download Our Annual Report',
                    'title_ur' => '',
                    'column_label' => 'Annual Report',
                    'column_label_ur' => '',
                    'download_label' => 'Click Here',
                    'download_label_ur' => '',
                    'rows' => [
                        ['label' => 'Annual Report 2025', 'label_ur' => '', 'path' => 'assets/pdf/financial/Annual-Report-2025.pdf'],
                        ['label' => 'Annual Report 2024', 'label_ur' => '', 'path' => 'assets/pdf/financial/Annual-Report-2024-1.pdf'],
                        ['label' => 'Annual Report 2023', 'label_ur' => '', 'path' => 'assets/pdf/financial/Annual-Report_compressed-2023.pdf'],
                        ['label' => 'Annual Report 2022', 'label_ur' => '', 'path' => 'assets/pdf/financial/5PTFL-Accounts-Dec-2022-For-Publishing.pdf'],
                        ['label' => 'Annual Report 2021', 'label_ur' => '', 'path' => 'assets/pdf/financial/FS-Dec-2021-Signed-Audit-Report.pdf'],
                    ],
                ],
                'settings' => [
                    'role' => 'primary',
                    'wrapper_class' => 'laravel-financial-statements-page',
                ],
            ],
            'fund-managers-report' => [
                'section_type' => 'pdf_table',
                'content' => [
                    'title' => '',
                    'title_ur' => '',
                    'column_label' => "Fund Manager's Report",
                    'column_label_ur' => '',
                    'download_label' => 'Click Here',
                    'download_label_ur' => '',
                    'rows' => collect(FundManagersReportRepository::reports())
                        ->map(fn (array $row) => [
                            'label' => $row['label'] ?? '',
                            'label_ur' => '',
                            'path' => $row['path'] ?? '',
                        ])
                        ->values()
                        ->all(),
                ],
                'settings' => [
                    'role' => 'primary',
                    'wrapper_class' => 'laravel-financial-statements-page',
                ],
            ],
            'accounts-of-unit-linked-funds' => [
                'section_type' => 'pdf_table',
                'content' => [
                    'title' => 'Download Accounting Statements',
                    'title_ur' => '',
                    'column_label' => 'Unit Linked Funds',
                    'column_label_ur' => '',
                    'download_label' => 'Click Here',
                    'download_label_ur' => '',
                    'rows' => [
                        ['label' => 'December 2025', 'label_ur' => '', 'path' => 'assets/pdf/unit-linked/Unit-Linked-Accounts-Dec-2025-Signed.pdf'],
                        ['label' => 'December 2024', 'label_ur' => '', 'path' => 'assets/pdf/unit-linked/Unit-Linked-Accounts-Dec-2024-Signed.pdf'],
                        ['label' => 'December 2023', 'label_ur' => '', 'path' => 'assets/pdf/unit-linked/Statement-Of-Unit-Linked-Dec-2023-Signed.pdf'],
                    ],
                ],
                'settings' => [
                    'role' => 'primary',
                    'wrapper_class' => 'laravel-financial-statements-page',
                ],
            ],
            default => null,
        };
    }
}
