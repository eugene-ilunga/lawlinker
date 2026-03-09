<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Modules\Lawyer\app\Models\Location;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Day\app\Models\Day;
use Modules\Lawyer\app\Models\Department;
use Modules\Lawyer\app\Models\Lawyer;
use Modules\Schedule\app\Models\Schedule;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shopping_carts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Lawyer::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Department::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Schedule::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Location::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Day::class)->constrained()->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('qty')->default(1);
            $table->integer('price');
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopping_carts');
    }
};
