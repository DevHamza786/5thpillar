<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('financial_data', function (Blueprint $table) {
            $table->decimal('growth_rate', 5, 4)->after('annual_contribution')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financial_data', function (Blueprint $table) {
            $table->dropColumn('growth_rate');
        });
    }
};
