<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('financial_data', function (Blueprint $table) {
            $table->string('product', 32)->default('hajj')->after('id');
            $table->decimal('year_five', 15, 2)->nullable()->after('takaful_benefit');
            $table->decimal('year_seven', 15, 2)->nullable()->after('year_five');
            $table->index(['product', 'age', 'term', 'growth_rate'], 'financial_data_product_age_term_growth_idx');
        });
    }

    public function down(): void
    {
        Schema::table('financial_data', function (Blueprint $table) {
            $table->dropIndex('financial_data_product_age_term_growth_idx');
            $table->dropColumn(['product', 'year_five', 'year_seven']);
        });
    }
};
