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
        Schema::create('zoom_meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Lawyer::class)->constrained();
            $table->integer('admin_id')->default(0);
            $table->text('topic');
            $table->string('start_time');
            $table->string('duration');
            $table->string('meeting_id');
            $table->string('password');
            $table->string('join_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('zoom_meetings');
    }
};
