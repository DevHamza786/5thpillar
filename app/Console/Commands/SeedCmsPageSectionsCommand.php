<?php

namespace App\Console\Commands;

use App\Models\Page;
use App\Models\PageSection;
use App\Models\CmsMedia;
use App\Support\FundManagersReportRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

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

        if ($payload['section_type'] === 'forms_catalog') {
            $registered = $this->registerFormsPdfsInLibrary($payload['content']);
            $this->info("Registered {$registered} Forms PDF(s) in Media Library (existing skipped).");
        }

        $this->info("Seeded {$payload['section_type']} primary section for page #{$page->id} ({$page->slug}).");

        return self::SUCCESS;
    }

    /**
     * @param  array<string, mixed>  $content
     */
    private function registerFormsPdfsInLibrary(array $content): int
    {
        $count = 0;

        foreach ((array) ($content['columns'] ?? []) as $column) {
            if (! is_array($column)) {
                continue;
            }
            foreach ((array) ($column['groups'] ?? []) as $group) {
                if (! is_array($group)) {
                    continue;
                }
                foreach ((array) ($group['items'] ?? []) as $item) {
                    if (! is_array($item)) {
                        continue;
                    }
                    $path = ltrim((string) ($item['path'] ?? ''), '/');
                    if ($path === '' || ! str_starts_with($path, 'assets/')) {
                        continue;
                    }
                    if (CmsMedia::query()->library()->where('path', $path)->exists()) {
                        continue;
                    }
                    $absolute = public_path($path);
                    if (! is_file($absolute)) {
                        $this->warn("Missing on disk, skipped library register: {$path}");

                        continue;
                    }
                    CmsMedia::create([
                        'disk' => CmsMedia::DISK_ASSETS,
                        'path' => $path,
                        'original_name' => basename($path),
                        'mime' => 'application/pdf',
                        'label' => (string) ($item['label'] ?: basename($path)),
                        'folder' => 'pdf/forms',
                        'asset_type' => CmsMedia::TYPE_PDF,
                        'file_size' => (int) File::size($absolute),
                    ]);
                    $count++;
                }
            }
        }

        return $count;
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
            'forms' => [
                'section_type' => 'forms_catalog',
                'heading' => 'Forms',
                'content' => [
                    'columns' => [
                        [
                            'title' => 'Group Family Takaful',
                            'title_ur' => '',
                            'groups' => [
                                [
                                    'heading' => 'Claim Forms:',
                                    'heading_ur' => '',
                                    'style' => 'plain',
                                    'open' => true,
                                    'items' => [
                                        ['label' => "Claimant's Statement – Claim Form D1 (Death).pdf", 'label_ur' => '', 'path' => 'assets/pdf/forms/1-Group-Claimants-Statement-Claim-Form-D1-Death.pdf'],
                                        ['label' => "Physician's Statement – Claim Form D2 (Death).pdf", 'label_ur' => '', 'path' => 'assets/pdf/forms/2-Group-Physicians-Statement-Claim-Form-D2-Death.pdf'],
                                        ['label' => "Disability Employer's Statement – Claim Form DS1 (Injury or Disability).pdf", 'label_ur' => '', 'path' => 'assets/pdf/forms/3-Group-Disability-Employers-Statement-Claim-Form-DS1-Injury-or-Disability.pdf'],
                                        ['label' => "Disability Physician's Statement – Claim Form DS2 (Permanent Disability).pdf", 'label_ur' => '', 'path' => 'assets/pdf/forms/4-Group-Disability-Physicians-Statement-Claim-Form-DS2-Permanent-Disability.pdf'],
                                        ['label' => "Physician's Statement – Claim Form DS2 (Injury or Disability).pdf", 'label_ur' => '', 'path' => 'assets/pdf/forms/5-Group-Physicians-Statement-Claim-Form-DS2-Injury-or-Disability.pdf'],
                                    ],
                                ],
                            ],
                        ],
                        [
                            'title' => 'Individual / Banca Takaful',
                            'title_ur' => '',
                            'groups' => [
                                [
                                    'heading' => 'Participant Services',
                                    'heading_ur' => '',
                                    'style' => 'accordion',
                                    'open' => true,
                                    'items' => [
                                        ['label' => 'Change Fund Distribution Form', 'label_ur' => '', 'path' => 'assets/pdf/forms/Change-Fund-Distribution.pdf'],
                                        ['label' => 'Change Of Nomination Guardianship Form', 'label_ur' => '', 'path' => 'assets/pdf/forms/Change-Of-Nomination-Guardianship-Form.pdf'],
                                        ['label' => 'Customer Information Updating Form', 'label_ur' => '', 'path' => 'assets/pdf/forms/Customer-Information-Updating-Form.pdf'],
                                        ['label' => 'Full-Or-Partial-Surrender-Form', 'label_ur' => '', 'path' => 'assets/pdf/forms/Full-Or-Partial-Surrender-Form.pdf'],
                                        ['label' => 'Lost PMD Form', 'label_ur' => '', 'path' => 'assets/pdf/forms/Lost-PMD-Form.pdf'],
                                        ['label' => 'Membership Amendment Request Form', 'label_ur' => '', 'path' => 'assets/pdf/forms/Membership-Amendment-Request-Form.pdf'],
                                        ['label' => 'Reinstatements Health Declaration Form – Hajj', 'label_ur' => '', 'path' => 'assets/pdf/forms/Reinstatements-Health-Declaration-Form-Hajj.pdf'],
                                        ['label' => 'Reinstatements Health Declaration Form – Saving', 'label_ur' => '', 'path' => 'assets/pdf/forms/Reinstatements-Health-Declaration-Form-Saving.pdf'],
                                        ['label' => 'Reinstatements Health Declaration Form – Umrah', 'label_ur' => '', 'path' => 'assets/pdf/forms/Reinstatements-Health-Declaration-Form-Umrah.pdf'],
                                        ['label' => 'Request-For-Net-PIF-Value-Takaful-form', 'label_ur' => '', 'path' => 'assets/pdf/forms/Reques-For-Net-PIF-Value-Takaful-form.pdf'],
                                    ],
                                ],
                                [
                                    'heading' => 'Claim Forms',
                                    'heading_ur' => '',
                                    'style' => 'accordion',
                                    'open' => true,
                                    'items' => [
                                        ['label' => 'Guidelines – How to Initiate Claims', 'label_ur' => '', 'path' => 'assets/pdf/forms/1-Guidelines-How-to-Initiate-Claims.pdf'],
                                        ['label' => 'Claim Intimation – Registration Form', 'label_ur' => '', 'path' => 'assets/pdf/forms/2-Claim-Intimation-Registration-Form.pdf'],
                                        ['label' => "Claimant's Statement – Claim Form (Death)", 'label_ur' => '', 'path' => 'assets/pdf/forms/3-Claimants-Statement-Claim-Form-Death-1.pdf'],
                                        ['label' => "Medical Attendant's Statement – Claim Form (Death)", 'label_ur' => '', 'path' => 'assets/pdf/forms/4-Medical-Attendants-Statement-Claim-Form-Death.pdf'],
                                        ['label' => "Claimant's Statement – Claim Form (Injury or Disability or Illness)", 'label_ur' => '', 'path' => 'assets/pdf/forms/5-Claimants-Statement-Claim-Form-Injury-or-Disability-or-Illness-1.pdf'],
                                        ['label' => "Medical Attendant's Statement – Claim Form (Injury or Disability or Illness)", 'label_ur' => '', 'path' => 'assets/pdf/forms/6-Medical-Attendants-Statement-Claim-Form-Injury-or-Disability-or-Illness.pdf'],
                                        ['label' => 'AML-CFT Questionnaire', 'label_ur' => '', 'path' => 'assets/pdf/forms/7.-AML-CFT-Questionnaire.pdf'],
                                        ['label' => 'Complaint Resolution Forum', 'label_ur' => '', 'path' => 'assets/pdf/forms/8-Complaint-Resolution-Forum.pdf'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'settings' => [
                    'role' => 'primary',
                ],
            ],
            default => null,
        };
    }
}
