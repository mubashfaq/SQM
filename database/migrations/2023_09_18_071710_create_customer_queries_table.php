<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_queries', function (Blueprint $table) {
            $table->id();
            $table->string('f_name');
            $table->string('l_name');
            $table->string('c_email');
            $table->string('c_phone');
            $table->string('c_state');
            $table->text('c_message');
            $table->unsignedBigInteger('product_id');
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
        Schema::dropIfExists('customer_queries');
    }
}
