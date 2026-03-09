<?php

use App\Models\Admin;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Blog\app\Models\BlogCategory;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Admin::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(BlogCategory::class)->constrained()->index();
            $table->text('slug');
            $table->string('thumbnail_image')->nullable();
            $table->string('image')->nullable();
            $table->boolean('show_homepage')->default(false);
            $table->boolean('is_feature')->default(false);
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
