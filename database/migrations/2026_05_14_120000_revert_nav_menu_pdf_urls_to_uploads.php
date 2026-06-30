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
        '/assets/pdf/company/IFS-Rating-of-5th-Pillar-Family-Takaful-Limited.pdf' => '/assets/pdf/company/IFS-Rating-of-5th-Pillar-Family-Takaful-Limited.pdf',
        '/assets/pdf/company/Code-of-Conduct-Corporate.pdf' => '/assets/pdf/company/Code-of-Conduct-Corporate.pdf',
        '/assets/pdf/company/Waqf-Deed-with-addition-of-Amendment-2-4-26.pdf' => '/assets/pdf/company/Waqf-Deed-with-addition-of-Amendment-2-4-26.pdf',
        '/assets/pdf/company/PTF-Policies.pdf' => '/assets/pdf/company/PTF-Policies.pdf',
        '/assets/pdf/investors/Notice-of-AGM-2026.pdf' => '/assets/pdf/investors/Notice-of-AGM-2026.pdf',
        '/assets/pdf/investors/Notice-of-EoGM-2025.pdf' => '/assets/pdf/investors/Notice-of-EoGM-2025.pdf',
        '/assets/pdf/investors/Notice-of-AGM-2025.pdf' => '/assets/pdf/investors/Notice-of-AGM-2025.pdf',
        '/assets/pdf/funds/web-page.pdf' => '/assets/pdf/funds/web-page.pdf',
        '/assets/pdf/investors/Unclaimed-Un-Enchased-Benefits-lIst-March-2026.pdf' => '/assets/pdf/investors/Unclaimed-Un-Enchased-Benefits-lIst-March-2026.pdf',
        '/assets/pdf/quick-links/Active-agents.pdf' => '/assets/pdf/quick-links/Active-agents.pdf',
        '/assets/pdf/quick-links/How-to-Launch-Complaints-and-Grievances-amended-as-per-18-1-24-2.pdf' => '/assets/pdf/quick-links/How-to-Launch-Complaints-and-Grievances-amended-as-per-18-1-24-2.pdf',
        '/assets/pdf/quick-links/Compliance-Certificate-24-For-website.pdf' => '/assets/pdf/quick-links/Compliance-Certificate-24-For-website.pdf',
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
