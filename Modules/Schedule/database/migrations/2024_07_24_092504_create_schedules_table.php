<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Day\app\Models\Day;
use Modules\Lawyer\app\Models\Lawyer;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Day::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Lawyer::class)->constrained()->cascadeOnDelete();
            $table->string('start_time');
            $table->string('end_time');
            $table->integer('quantity');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('schedules');
    }
};
