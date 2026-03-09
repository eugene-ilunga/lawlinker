<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HomeSection\app\Models\WorkSectionFaq;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('work_section_faq_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(WorkSectionFaq::class);
            $table->string('lang_code');
            $table->string('question')->nullable();
            $table->text('answer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_section_faq_translations');
    }
};
