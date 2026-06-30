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
        Schema::create('financial_data', function (Blueprint $table) {
            $table->id();
            $table->integer('age')->index();
            $table->integer('term')->index();
            $table->decimal('annual_contribution', 15, 2);
            $table->decimal('takaful_benefit', 15, 2);
            $table->decimal('year_ten', 15, 2)->nullable();
            $table->decimal('year_fifteen', 15, 2)->nullable();
            $table->decimal('year_twenty', 15, 2)->nullable();
            $table->decimal('year_twenty_five', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_data');
    }
};
