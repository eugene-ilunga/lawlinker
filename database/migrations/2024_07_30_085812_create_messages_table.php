<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lawyer_id');
            $table->unsignedBigInteger('user_id');
            $table->text('message');
            $table->boolean('lawyer_view')->default(false);
            $table->boolean('user_view')->default(false);
            $table->boolean('send_lawyer')->default(false);
            $table->boolean('send_user')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('messages');
    }
};
