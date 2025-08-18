<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShippingAddressInOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('s_f_name')->nullable();
            $table->string('s_l_name')->nullable();
            $table->string('s_email')->nullable();
            $table->string('s_company')->nullable();
            $table->string('s_phone')->nullable();
            $table->text('s_street_address')->nullable();
            $table->string('s_post_code')->nullable();
            $table->string('s_suburb')->nullable();
            $table->string('s_state')->nullable();
            $table->string('s_city')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('s_f_name')->nullable();
            $table->string('s_l_name')->nullable();
            $table->string('s_email')->nullable();
            $table->string('s_company')->nullable();
            $table->string('s_phone')->nullable();
            $table->text('s_street_address')->nullable();
            $table->string('s_post_code')->nullable();
            $table->string('s_suburb')->nullable();
            $table->string('s_state')->nullable();
            $table->string('s_city')->nullable();
        });
    }
}
