<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\HomeSection\app\Models\WorkSection;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('work_section_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(WorkSection::class)->constrained();
            $table->string('lang_code');
            $table->string('title')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_section_translations');
    }
};
