<?php

use App\Models\NavMenuItem;
use App\Support\PublicPath;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Store menu PDF links as root-relative /uploads/... so they follow the
     * current host (APP_URL at browse time), not a baked localhost from seeding.
     */
    public function up(): void
    {
        if (! Schema::hasTable('nav_menu_items')) {
            return;
        }

        NavMenuItem::query()
            ->where('link_type', NavMenuItem::LINK_CUSTOM_URL)
            ->whereNotNull('custom_url')
            ->each(function (NavMenuItem $item): void {
                $next = PublicPath::normalizeMenuCustomUrl($item->custom_url);
                if ($next !== $item->custom_url) {
                    $item->custom_url = $next;
                    $item->save();
                }
            });
    }

    public function down(): void
    {
        // Irreversible without storing previous values.
    }
};
