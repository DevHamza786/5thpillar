<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nav_menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('nav_menu_items')->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('label');
            $table->string('link_type', 32)->default('none');
            $table->string('page_slug')->nullable();
            $table->string('route_name')->nullable();
            $table->string('custom_url', 2048)->nullable();
            $table->foreignId('cms_media_id')->nullable()->constrained('cms_media')->nullOnDelete();
            $table->boolean('open_new_tab')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nav_menu_items');
    }
};
