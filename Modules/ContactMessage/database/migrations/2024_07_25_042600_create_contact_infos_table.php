<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('contact_infos', function (Blueprint $table) {
            $table->id();
            $table->string('top_bar_email')->nullable();
            $table->string('top_bar_phone')->nullable();
            $table->text('email')->nullable();
            $table->text('phone')->nullable();
            $table->string('address')->nullable();
            $table->text('map_embed_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('contact_infos');
    }
};
