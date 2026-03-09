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
        Schema::create('about_us_pages', function (Blueprint $table) {
            $table->id();
            $table->string('about_image')->nullable();
            $table->string('background_image')->nullable();
            $table->boolean('status')->default(1);
            $table->string('mission_image')->nullable();
            $table->boolean('mission_status')->default(1);
            $table->string('vision_image')->nullable();
            $table->boolean('vision_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_us_pages');
    }
};
