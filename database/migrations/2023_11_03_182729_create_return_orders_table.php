<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id');
            $table->bigInteger('user_id');
            $table->bigInteger('gift_card_id')->nullable();
            $table->string('user_email')->nullable();
            $table->string('return_type');
            $table->string('return_via');
            $table->string('grandTotal');
            $table->tinyInteger('admin_delete')->default(0)->nullable();
            $table->timestamps();
        });

        Schema::create('return_order_metas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('return_id');
            $table->bigInteger('product_id');
            $table->bigInteger('quantity');
            $table->string('price');
            $table->string('subTotal');
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
        Schema::dropIfExists('return_orders');
        Schema::dropIfExists('return_order_metas');
    }
}
