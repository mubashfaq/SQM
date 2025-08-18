<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->default('INV-OS-00');
            $table->bigInteger('user_id');
            $table->string('f_name');
            $table->string('l_name');
            $table->string('email');
            $table->string('company')->nullable();
            $table->string('phone');
            $table->text('street_address')->nullable();
            $table->string('post_code');
            $table->string('suburb');
            $table->string('state');
            $table->text('address')->nullable();
            $table->string('city');
            $table->string('status');
            $table->string('subTotal');
            $table->string('totalGst')->nullable();
            $table->string('total_amount');
            $table->string('auth_discount_per')->nullable();
            $table->string('auth_discount')->nullable();
            $table->string('grand_total')->nullable();
            $table->string('discountedAmount');
            $table->string('discountPlan')->nullable();
            $table->string('channel')->nullable();
            $table->string('payment_status');
            $table->string('payment_method');
            $table->string('delivery_charges');
            $table->string('delivery_method')->nullable();
            $table->string('order_notes')->nullable();
            $table->float('delivered_price')->nullable();
            $table->string('stripe_payment_method_id')->nullable();
            $table->string('stripe_customer_id')->nullable();
            $table->tinyInteger('admin_delete')->default(0);
            $table->enum('user_type', ['guest', 'authenticated']);
            $table->timestamps();
        });
        Schema::create('order_metas', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('product_id');
            $table->string('product_title');
            $table->float('quantity'); 
            $table->float('delivered_weight')->nullable(); 
            $table->bigInteger('price');
            $table->float('qp_subtotal');
            $table->string('payment_intent_id')->nullable();
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
        Schema::dropIfExists('orders_metas');
        Schema::dropIfExists('orders');
    }
}
