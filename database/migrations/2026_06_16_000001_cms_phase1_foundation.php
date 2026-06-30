<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('meta_keywords')->nullable()->after('meta_description');
            $table->string('og_image')->nullable()->after('meta_keywords');
            $table->unsignedInteger('sort_order')->default(0)->after('is_published');
            $table->string('status', 20)->default('published')->after('sort_order');
        });

        Schema::table('page_sections', function (Blueprint $table) {
            $table->json('content')->nullable()->after('body_html');
            $table->json('settings')->nullable()->after('content');
            $table->boolean('is_enabled')->default(true)->after('settings');
        });

        Schema::table('cms_media', function (Blueprint $table) {
            $table->string('folder', 120)->nullable()->after('label');
            $table->string('asset_type', 20)->default('file')->after('folder');
            $table->unsignedBigInteger('file_size')->nullable()->after('asset_type');
            $table->string('alt_text')->nullable()->after('file_size');
        });

        DB::table('pages')->where('is_published', true)->update(['status' => 'published']);
        DB::table('pages')->where('is_published', false)->update(['status' => 'draft']);
    }

    public function down(): void
    {
        Schema::table('cms_media', function (Blueprint $table) {
            $table->dropColumn(['folder', 'asset_type', 'file_size', 'alt_text']);
        });

        Schema::table('page_sections', function (Blueprint $table) {
            $table->dropColumn(['content', 'settings', 'is_enabled']);
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['meta_keywords', 'og_image', 'sort_order', 'status']);
        });
    }
};
