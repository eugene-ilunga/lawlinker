<?php

use Modules\Lawyer\app\Models\Lawyer;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lawyer_social_media', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Lawyer::class)->constrained()->cascadeOnDelete();
            $table->string('link')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lawyer_social_media');
    }
};
