<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('f_name');
            $table->string('l_name');
            $table->string('email');
            $table->string('company')->nullable();
            $table->string('phone');
            $table->string('street_address');
            $table->string('city');
            $table->bigInteger('post_code');
            $table->string('suburb')->nullable();
            $table->string('state');
            $table->string('address_type');
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
        Schema::dropIfExists('user_addresses');
    }
}
