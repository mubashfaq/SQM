<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->default('INV-POS-00');
            $table->string('customer_type')->nullable();
            $table->string('customer')->nullable();
            $table->date('invoice_date')->nullable();
            $table->date('due_date')->nullable();
            $table->integer('total_units')->nullable();
            $table->decimal('sub_total', 10, 2)->nullable();
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('discounted_amount', 10, 2)->nullable();
            $table->integer('plan_id')->nullable();
            $table->decimal('membership_dicount', 10, 2)->nullable();
            $table->decimal('membership_discount_percentage', 10, 2)->nullable();
            $table->decimal('membership_dicounted_total', 10, 2)->nullable();
            $table->decimal('gst', 10, 2)->nullable();
            $table->decimal('grand_total', 10, 2)->nullable();
            $table->string('created_by')->nullable();
            $table->string('payment_status')->default('Un-Paid');
            $table->string('payment_method')->nullable();
            $table->string('payment_id')->nullable();
            $table->integer('active')->default(0);
            $table->integer('is_deleted')->default(0);
            $table->timestamps();
        });
        Schema::create('invoices_meta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->string('invoice_number');
            $table->unsignedBigInteger('product_id');
            $table->string('product_name');
            $table->float('units');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('unit_total_price', 10, 2);
            $table->float('per_kg_price')->nullable();
            $table->float('default_weight')->nullable();
            $table->string('gst_status', 191)->nullable();
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('invoices_meta');
    }
}
