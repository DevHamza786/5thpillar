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
        '/assets/pdf/company/IFS-Rating-of-5th-Pillar-Family-Takaful-Limited.pdf' => '/assets/pdf/company/IFS-Rating-of-5th-Pillar-Family-Takaful-Limited.pdf',
        '/assets/pdf/company/Code-of-Conduct-Corporate.pdf' => '/assets/pdf/company/Code-of-Conduct-Corporate.pdf',
        '/assets/pdf/company/Waqf-Deed-with-addition-of-Amendment-2-4-26.pdf' => '/assets/pdf/company/Waqf-Deed-with-addition-of-Amendment-2-4-26.pdf',
        '/assets/pdf/company/PTF-Policies.pdf' => '/assets/pdf/company/PTF-Policies.pdf',
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
