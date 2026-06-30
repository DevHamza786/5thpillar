<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fund_daily_snapshots', function (Blueprint $table) {
            $table->string('brk_bid')->nullable()->after('con_offer');
            $table->string('brk_offer')->nullable()->after('brk_bid');
        });
    }

    public function down(): void
    {
        Schema::table('fund_daily_snapshots', function (Blueprint $table) {
            $table->dropColumn(['brk_bid', 'brk_offer']);
        });
    }
};
