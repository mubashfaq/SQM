<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('permalink');
            $table->string('sku');
            $table->text('short_description')->nullable();
            $table->text('long_description')->nullable();
            $table->string('product_type')->nullable();
            $table->integer('active')->default(0);
            $table->string('category_id')->nullable();
            $table->string('child_category')->nullable();
            $table->string('grand_child_category')->nullable();
            $table->string('created_by');
            $table->string('area_group_id')->default(0);
            $table->string('brand_id')->nullable();
            $table->string('discount_id')->nullable();
            $table->string('featured_image')->nullable();
            $table->text('gallery_media')->nullable();
            $table->string('variant')->nullable();
            $table->timestamps();
        });
        Schema::create('products_meta', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->text('option');
            $table->string('value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('products_meta');
    }
}
