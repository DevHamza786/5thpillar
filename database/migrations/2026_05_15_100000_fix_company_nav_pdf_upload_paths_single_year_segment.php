<?php

use App\Models\NavMenuItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Company nav PDFs live under uploads/{year}/{month}/ (single year segment),
     * not uploads/{year}/{year}/{month}/.
     *
     * @var array<string, string>
     */
    private array $pathMap = [
        '/uploads/2026/2026/03/IFS-Rating-of-5th-Pillar-Family-Takaful-Limited.pdf' => '/uploads/2026/03/IFS-Rating-of-5th-Pillar-Family-Takaful-Limited.pdf',
        '/uploads/2023/2023/09/Code-of-Conduct-Corporate.pdf' => '/uploads/2023/09/Code-of-Conduct-Corporate.pdf',
        '/uploads/2026/2026/04/Waqf-Deed-with-addition-of-Amendment-2-4-26.pdf' => '/uploads/2026/04/Waqf-Deed-with-addition-of-Amendment-2-4-26.pdf',
        '/uploads/2023/2023/12/PTF-Policies.pdf' => '/uploads/2023/12/PTF-Policies.pdf',
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

        $reverse = [];
        foreach ($this->pathMap as $from => $to) {
            $reverse[$to] = $from;
        }

        NavMenuItem::query()
            ->where('link_type', NavMenuItem::LINK_CUSTOM_URL)
            ->whereNotNull('custom_url')
            ->each(function (NavMenuItem $item) use ($reverse): void {
                $url = (string) $item->custom_url;
                $next = $url;
                foreach ($reverse as $from => $to) {
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
