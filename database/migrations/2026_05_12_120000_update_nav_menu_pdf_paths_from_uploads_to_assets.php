<?php

use App\Models\NavMenuItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Point seeded / CMS navigation PDF links from missing WordPress-style
     * /uploads/... paths to public/assets/pdfs/... (place PDFs there on deploy).
     *
     * @var array<string, string>
     */
    private array $pathMap = [
        '/uploads/2026/2026/03/IFS-Rating-of-5th-Pillar-Family-Takaful-Limited.pdf' => '/assets/pdfs/company/IFS-Rating-of-5th-Pillar-Family-Takaful-Limited.pdf',
        '/uploads/2023/2023/09/Code-of-Conduct-Corporate.pdf' => '/assets/pdfs/company/Code-of-Conduct-Corporate.pdf',
        '/uploads/2026/2026/04/Waqf-Deed-with-addition-of-Amendment-2-4-26.pdf' => '/assets/pdfs/company/Waqf-Deed-with-addition-of-Amendment-2-4-26.pdf',
        '/uploads/2023/2023/12/PTF-Policies.pdf' => '/assets/pdfs/company/PTF-Policies.pdf',
        '/uploads/2026/2026/04/Notice-of-AGM-2026.pdf' => '/assets/pdfs/investors/Notice-of-AGM-2026.pdf',
        '/uploads/2026/2026/04/Notice-of-EoGM-2025.pdf' => '/assets/pdfs/investors/Notice-of-EoGM-2025.pdf',
        '/uploads/2026/2026/04/Notice-of-AGM-2025.pdf' => '/assets/pdfs/investors/Notice-of-AGM-2025.pdf',
        '/uploads/2025/2025/01/web-page.pdf' => '/assets/pdfs/funds/web-page.pdf',
        '/uploads/2026/2026/04/Unclaimed-Un-Enchased-Benefits-lIst-March-2026.pdf' => '/assets/pdfs/investors/Unclaimed-Un-Enchased-Benefits-lIst-March-2026.pdf',
    ];

    public function up(): void
    {
        if (! Schema::hasTable('nav_menu_items')) {
            return;
        }

        NavMenuItem::query()
            ->where('link_type', NavMenuItem::LINK_CUSTOM_URL)
            ->whereNotNull('custom_url')
            ->each(function (NavMenuItem $item): void {
                $url = (string) $item->custom_url;
                $next = $url;
                foreach ($this->pathMap as $from => $to) {
                    if (str_contains($next, $from)) {
                        $next = str_replace($from, $to, $next);
                    }
                }
                if ($next !== $url) {
                    $item->custom_url = $next;
                    $item->save();
                }
            });
    }

    public function down(): void
    {
        if (! Schema::hasTable('nav_menu_items')) {
            return;
        }

        NavMenuItem::query()
            ->where('link_type', NavMenuItem::LINK_CUSTOM_URL)
            ->whereNotNull('custom_url')
            ->each(function (NavMenuItem $item): void {
                $url = (string) $item->custom_url;
                $next = $url;
                foreach ($this->pathMap as $from => $to) {
                    if (str_contains($next, $to)) {
                        $next = str_replace($to, $from, $next);
                    }
                }
                if ($next !== $url) {
                    $item->custom_url = $next;
                    $item->save();
                }
            });
    }
};
