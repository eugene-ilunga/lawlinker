<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Lawyer\app\Models\Department;
use Modules\Lawyer\app\Models\Location;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('lawyers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Department::class)->constrained();
            $table->foreignIdFor(Location::class)->constrained();
            $table->string('name');
            $table->text('slug');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone');
            $table->string('years_of_experience')->nullable();
            $table->decimal('fee')->default(0.00);
            $table->string('image')->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('show_homepage')->default(1);
            $table->string('forget_password_token')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('email_verified_token')->nullable();
            $table->decimal('wallet_balance', 8, 2)->default(0.00);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('lawyers');
    }
};
