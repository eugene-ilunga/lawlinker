<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->string('order_id');
            $table->integer('appointment_qty');
            $table->double('amount_usd');
            $table->string('payment_method');
            $table->decimal('total_payment', 8, 2);
            $table->string('payment_transaction_id')->nullable();
            $table->text('payment_description')->nullable();
            $table->boolean('payment_status')->default(0);
            $table->boolean('order_status')->default(0);
            $table->boolean('show_notification')->default(0);
            $table->string('gateway_charge')->nullable();
            $table->string('payable_with_charge')->nullable();
            $table->string('payable_currency')->nullable();
            $table->string('paid_amount');
            $table->string('approved_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
