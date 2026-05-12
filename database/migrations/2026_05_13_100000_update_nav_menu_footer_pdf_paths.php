<?php

use App\Models\NavMenuItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @var array<string, string>
     */
    private array $pathMap = [
        '/uploads/2026/2026/04/Active-agents.pdf' => '/assets/pdfs/quick-links/Active-agents.pdf',
        '/uploads/2024/2024/01/How-to-Launch-Complaints-and-Grievances-amended-as-per-18-1-24-2.pdf' => '/assets/pdfs/quick-links/How-to-Launch-Complaints-and-Grievances-amended-as-per-18-1-24-2.pdf',
        '/uploads/2024/2024/12/Compliance-Certificate-24-For-website.pdf' => '/assets/pdfs/quick-links/Compliance-Certificate-24-For-website.pdf',
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
