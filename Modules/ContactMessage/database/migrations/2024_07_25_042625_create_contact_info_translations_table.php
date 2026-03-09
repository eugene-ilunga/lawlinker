<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\ContactMessage\app\Models\ContactInfo;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('contact_info_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ContactInfo::class)->constrained();
            $table->string('lang_code');
            $table->string('header')->nullable();
            $table->text('description')->nullable();
            $table->text('about')->nullable();
            $table->text('copyright')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('contact_info_translations');
    }
};
