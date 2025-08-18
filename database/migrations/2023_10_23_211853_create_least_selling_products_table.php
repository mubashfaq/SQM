<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeastSellingProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('least_selling_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->string('title')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('permalink')->nullable();
            $table->string('quantity')->nullable();
            $table->string('order_count')->nullable();
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
        Schema::dropIfExists('least_selling_products');
    }
}
