<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_temp_variation', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('created_by')->nullable();
            $table->text('gallery_media')->nullable();
            $table->unsignedInteger('featuredImage')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_temp_variation');
    }
};
