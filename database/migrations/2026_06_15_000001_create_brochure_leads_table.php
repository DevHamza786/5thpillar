<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brochure_leads', function (Blueprint $table) {
            $table->id();
            $table->string('brochure_key');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->text('address')->nullable();
            $table->string('gender', 10);
            $table->string('city', 120);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brochure_leads');
    }
};
