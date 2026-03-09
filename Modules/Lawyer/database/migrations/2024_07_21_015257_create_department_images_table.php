<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Lawyer\app\Models\Department;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('department_images', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Department::class)->constrained();
            $table->string('small_image');
            $table->string('large_image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('department_images');
    }
};
