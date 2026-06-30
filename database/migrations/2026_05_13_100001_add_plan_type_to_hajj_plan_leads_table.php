<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hajj_plan_leads', function (Blueprint $table) {
            $table->string('plan_type', 32)->default('hajj')->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('hajj_plan_leads', function (Blueprint $table) {
            $table->dropColumn('plan_type');
        });
    }
};
