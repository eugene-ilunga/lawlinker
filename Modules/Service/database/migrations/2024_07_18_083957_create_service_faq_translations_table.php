<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Service\app\Models\ServiceFaq;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('service_faq_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ServiceFaq::class)->cascadeOnDelete();
            $table->string('lang_code');
            $table->string('question')->nullable();
            $table->text('answer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('service_faq_translations');
    }
};
