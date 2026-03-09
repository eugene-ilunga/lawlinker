<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Faq\app\Models\FaqCategory;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('faq_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(FaqCategory::class)->cascadeOnDelete();
            $table->string('lang_code');
            $table->string('title');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faq_category_translations');
    }
};
