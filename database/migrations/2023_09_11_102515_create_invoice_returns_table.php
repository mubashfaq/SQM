<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_returns', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->integer('created_by');
            $table->date('return_date');
            $table->string('status');
            $table->integer('units')->nullable();
            $table->decimal('sub_total', 10, 2)->nullable();
            $table->decimal('general_discount', 10, 2)->nullable();
            $table->decimal('general_discounted_amount', 10, 2)->nullable();
            $table->decimal('membership_discount', 10, 2)->nullable();
            $table->decimal('membership_discounted_amount', 10, 2)->nullable();
            $table->decimal('gst', 10, 2)->nullable();
            $table->decimal('grand_total', 10, 2)->nullable();
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
        Schema::dropIfExists('invoice_returns');
    }
}
