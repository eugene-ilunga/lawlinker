<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\NewsLetter\app\Models\SubscriberContent;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('subscriber_content_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SubscriberContent::class)->constrained();
            $table->string('lang_code');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('subscriber_content_translations');
    }
};
