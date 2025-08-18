<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('sku');
            $table->string('thumbnail_image');
            $table->string('cover_image')->nullable();
            $table->timestamps();
        });

        Schema::create('child_categories', function (Blueprint $table) {
            $table->id();
            $table->string('main_parent_category');
            $table->string('child_title');
            $table->string('child_sku');
            $table->timestamps();
        });

        Schema::create('grand_child_categories', function (Blueprint $table) {
            $table->id();
            $table->string('mid_parent_category');
            $table->string('grand_title');
            $table->string('grand_sku');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('child_categories');
        Schema::dropIfExists('grand_child_categories');
        
    }
}
