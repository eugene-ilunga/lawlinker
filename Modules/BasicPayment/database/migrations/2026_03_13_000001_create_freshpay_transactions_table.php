<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('freshpay_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_db_id')->nullable()->index();
            $table->string('order_public_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('reference')->unique();
            $table->string('channel', 20)->default('web');
            $table->string('customer_number')->nullable();
            $table->string('operator', 50)->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('currency', 20)->default('USD');
            $table->string('status', 30)->default('processing')->index();
            $table->text('message')->nullable();
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->json('callback_payload')->nullable();
            $table->timestamp('finalized_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('freshpay_transactions');
    }
};
