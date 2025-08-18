<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderAddressDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_address_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id');
            $table->bigInteger('default_billing_id')->nullable();
            $table->bigInteger('default_shiping_id')->nullable();
            $table->string('f_name')->nullable();
            $table->string('l_name')->nullable();
            $table->string('email')->nullable();
            $table->string('company')->nullable();
            $table->string('phone')->nullable();
            $table->text('street_address')->nullable();
            $table->string('post_code')->nullable();
            $table->string('suburb')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('address_type')->nullable();
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
        Schema::dropIfExists('order_address_details');
    }
}
