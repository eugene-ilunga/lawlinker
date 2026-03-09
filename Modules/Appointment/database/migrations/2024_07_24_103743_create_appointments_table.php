<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Day\app\Models\Day;
use Modules\Lawyer\app\Models\Lawyer;
use Modules\Order\app\Models\Order;
use Modules\Schedule\app\Models\Schedule;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Order::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Day::class)->constrained();
            $table->foreignIdFor(Schedule::class)->constrained();
            $table->foreignIdFor(Lawyer::class)->constrained();
            $table->boolean('already_treated')->default(0);
            $table->date('date');
            $table->double('appointment_fee_usd');
            $table->string('appointment_fee');
            $table->string('payable_currency')->nullable();
            $table->boolean('payment_status')->default(0);
            $table->string('payment_transaction_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->text('payment_description')->nullable();
            $table->text('subject')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('appointments');
    }
};
