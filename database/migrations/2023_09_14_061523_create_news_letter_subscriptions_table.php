<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsLetterSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_letter_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('email');
            $table->string('state')->nullable();
            $table->string('business_type')->nullable();
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
        Schema::dropIfExists('news_letter_subscriptions');
    }
}
