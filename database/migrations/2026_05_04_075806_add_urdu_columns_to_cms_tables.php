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
        Schema::table('pages', function (Blueprint $table) {
            $table->string('title_ur')->nullable()->after('title');
            $table->text('content_ur')->nullable()->after('content');
            $table->string('hero_title_ur')->nullable()->after('hero_title');
            $table->string('meta_title_ur')->nullable()->after('meta_title');
            $table->text('meta_description_ur')->nullable()->after('meta_description');
        });

        Schema::table('page_sections', function (Blueprint $table) {
            $table->string('heading_ur')->nullable()->after('heading');
            $table->text('body_html_ur')->nullable()->after('body_html');
        });

        Schema::table('nav_menu_items', function (Blueprint $table) {
            $table->string('label_ur')->nullable()->after('label');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['title_ur', 'content_ur', 'hero_title_ur', 'meta_title_ur', 'meta_description_ur']);
        });

        Schema::table('page_sections', function (Blueprint $table) {
            $table->dropColumn(['heading_ur', 'body_html_ur']);
        });

        Schema::table('nav_menu_items', function (Blueprint $table) {
            $table->dropColumn(['label_ur']);
        });
    }
};
