<?php

use App\Models\NavMenuItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Restore navigation PDF links to /uploads/... where files already exist
     * (reverses 2026_05_12_120000 and 2026_05_13_100000 path changes in custom_url).
     *
     * @var array<string, string>
     */
    private array $revertMap = [
        '/assets/pdfs/company/IFS-Rating-of-5th-Pillar-Family-Takaful-Limited.pdf' => '/uploads/2026/03/IFS-Rating-of-5th-Pillar-Family-Takaful-Limited.pdf',
        '/assets/pdfs/company/Code-of-Conduct-Corporate.pdf' => '/uploads/2023/09/Code-of-Conduct-Corporate.pdf',
        '/assets/pdfs/company/Waqf-Deed-with-addition-of-Amendment-2-4-26.pdf' => '/uploads/2026/04/Waqf-Deed-with-addition-of-Amendment-2-4-26.pdf',
        '/assets/pdfs/company/PTF-Policies.pdf' => '/uploads/2023/12/PTF-Policies.pdf',
        '/assets/pdfs/investors/Notice-of-AGM-2026.pdf' => '/uploads/2026/04/Notice-of-AGM-2026.pdf',
        '/assets/pdfs/investors/Notice-of-EoGM-2025.pdf' => '/uploads/2026/04/Notice-of-EoGM-2025.pdf',
        '/assets/pdfs/investors/Notice-of-AGM-2025.pdf' => '/uploads/2026/04/Notice-of-AGM-2025.pdf',
        '/assets/pdfs/funds/web-page.pdf' => '/uploads/2025/2025/01/web-page.pdf',
        '/assets/pdfs/investors/Unclaimed-Un-Enchased-Benefits-lIst-March-2026.pdf' => '/uploads/2026/04/Unclaimed-Un-Enchased-Benefits-lIst-March-2026.pdf',
        '/assets/pdfs/quick-links/Active-agents.pdf' => '/uploads/2026/04/Active-agents.pdf',
        '/assets/pdfs/quick-links/How-to-Launch-Complaints-and-Grievances-amended-as-per-18-1-24-2.pdf' => '/uploads/2024/2024/01/How-to-Launch-Complaints-and-Grievances-amended-as-per-18-1-24-2.pdf',
        '/assets/pdfs/quick-links/Compliance-Certificate-24-For-website.pdf' => '/uploads/2024/2024/12/Compliance-Certificate-24-For-website.pdf',
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
                foreach ($this->revertMap as $from => $to) {
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

        $forward = [];
        foreach ($this->revertMap as $from => $to) {
            $forward[$to] = $from;
        }

        NavMenuItem::query()
            ->where('link_type', NavMenuItem::LINK_CUSTOM_URL)
            ->whereNotNull('custom_url')
            ->each(function (NavMenuItem $item) use ($forward): void {
                $url = (string) $item->custom_url;
                $next = $url;
                foreach ($forward as $from => $to) {
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
};
