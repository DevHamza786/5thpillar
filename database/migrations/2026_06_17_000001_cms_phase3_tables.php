<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_tables', function (Blueprint $table) {
            $table->id();
            $table->string('key', 80)->unique();
            $table->string('label');
            $table->string('description')->nullable();
            $table->json('schema')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        Schema::create('cms_table_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cms_table_id')->constrained('cms_tables')->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->json('data');
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();

            $table->index(['cms_table_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_table_rows');
        Schema::dropIfExists('cms_tables');
    }
};
