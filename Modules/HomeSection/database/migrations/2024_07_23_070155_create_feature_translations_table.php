<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\HomeSection\app\Models\Feature;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feature_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Feature::class)->cascadeOnDelete();
            $table->string('lang_code');
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_translations');
    }
};
