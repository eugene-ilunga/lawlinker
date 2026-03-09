<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Lawyer\app\Models\Lawyer;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('lawyer_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Lawyer::class)->cascadeOnDelete();
            $table->string('lang_code');
            $table->string('designations')->nullable();
            $table->text('about')->nullable();
            $table->text('address')->nullable();
            $table->longText('educations')->nullable();
            $table->longText('experience')->nullable();
            $table->longText('qualifications')->nullable();
            $table->text('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('lawyer_translations');
    }
};
