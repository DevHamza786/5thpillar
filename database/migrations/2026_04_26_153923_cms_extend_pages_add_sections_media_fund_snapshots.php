<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('password');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->string('view_key')->nullable()->after('slug');
            $table->string('meta_title')->nullable()->after('view_key');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('hero_title')->nullable()->after('meta_description');
            $table->string('masthead_bg')->nullable()->after('hero_title');
            $table->boolean('is_published')->default(true)->after('masthead_bg');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('section_type')->default('content');
            $table->string('heading')->nullable();
            $table->longText('body_html')->nullable();
            $table->timestamps();
        });

        Schema::create('cms_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->nullable()->constrained('pages')->nullOnDelete();
            $table->foreignId('page_section_id')->nullable()->constrained('page_sections')->nullOnDelete();
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('original_name')->nullable();
            $table->string('mime')->nullable();
            $table->string('label')->nullable();
            $table->timestamps();
        });

        Schema::create('fund_daily_snapshots', function (Blueprint $table) {
            $table->id();
            $table->date('price_date')->unique();
            $table->string('agg_bid')->nullable();
            $table->string('agg_offer')->nullable();
            $table->string('bal_bid')->nullable();
            $table->string('bal_offer')->nullable();
            $table->string('con_bid')->nullable();
            $table->string('con_offer')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fund_daily_snapshots');
        Schema::dropIfExists('cms_media');
        Schema::dropIfExists('page_sections');

        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn([
                'view_key',
                'meta_title',
                'meta_description',
                'hero_title',
                'masthead_bg',
                'is_published',
                'created_at',
                'updated_at',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};
