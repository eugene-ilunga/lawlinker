<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Lawyer\app\Models\Lawyer;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('zoom_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Lawyer::class)->constrained();
            $table->text('zoom_account_id');
            $table->text('zoom_api_key');
            $table->text('zoom_api_secret');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zoom_credentials');
    }
};
