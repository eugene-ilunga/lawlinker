<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\HomeSection\app\Models\SectionControl;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('section_control_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SectionControl::class)->constrained();
            $table->string('lang_code');
            $table->string('work_first_heading')->nullable();
            $table->text('work_second_heading')->nullable();
            $table->longText('work_description')->nullable();
            $table->string('service_first_heading')->nullable();
            $table->text('service_second_heading')->nullable();
            $table->longText('service_description')->nullable();
            $table->string('department_first_heading')->nullable();
            $table->text('department_second_heading')->nullable();
            $table->longText('department_description')->nullable();
            $table->string('client_first_heading')->nullable();
            $table->text('client_second_heading')->nullable();
            $table->longText('client_description')->nullable();
            $table->string('lawyer_first_heading')->nullable();
            $table->text('lawyer_second_heading')->nullable();
            $table->longText('lawyer_description')->nullable();
            $table->string('blog_first_heading')->nullable();
            $table->text('blog_second_heading')->nullable();
            $table->longText('blog_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('section_control_translations');
    }
};
