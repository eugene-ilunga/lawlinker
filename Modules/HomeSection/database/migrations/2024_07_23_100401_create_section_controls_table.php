<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('section_controls', function (Blueprint $table) {
            $table->id();
            $table->string('feature_how_many')->default(3);
            $table->boolean('feature_status')->default(true);
            $table->string('work_how_many')->default(3);
            $table->boolean('work_status')->default(true);
            $table->string('service_how_many')->default(6);
            $table->boolean('service_status')->default(true);
            $table->string('department_how_many')->default(6);
            $table->boolean('department_status')->default(true);
            $table->string('client_how_many')->default(4);
            $table->boolean('client_status')->default(true);
            $table->string('lawyer_how_many')->default(6);
            $table->boolean('lawyer_status')->default(true);
            $table->string('blog_how_many')->default(4);
            $table->boolean('blog_status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('section_controls');
    }
};
