<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceReturnsMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_returns_meta', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->integer('invoice_return_id');
            $table->integer('product_id');
            $table->decimal('unit_price',10,2);
            $table->integer('total_units');
            $table->integer('return_units');
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
        Schema::dropIfExists('invoice_returns_meta');
    }
}
