<?php

use App\Models\AboutUsPage;
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
        Schema::create('about_us_page_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(AboutUsPage::class)->cascadeOnDelete()->index();
            $table->string('lang_code');
            $table->longText('about_description')->nullable();
            $table->longText('mission_description')->nullable();
            $table->longText('vision_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_us_page_translations');
    }
};
