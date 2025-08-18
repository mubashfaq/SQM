<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_cards', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('coupon_code')->nullable();
            $table->enum('variant', ['gift_card', 'promotion', 'coupon'])->default('promotion');
            $table->enum('usage', ['one_time', 'multiple_times', 'one_time_per_user'])->default('one_time');
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->integer('discount')->nullable();
            $table->integer('price')->nullable();
            $table->integer('total_used')->default(0);
            $table->string('status')->default('active');
            $table->enum('is_expirable', [0, 1])->default(0);
            $table->integer('expire_after_weeks')->nullable();
            $table->string('created_by')->nullable();
            $table->string('expired_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->string('expired_at')->nullable();
            $table->string('deleted_at')->nullable();
            $table->string('reason')->nullable();
            $table->timestamps();
        });

        Schema::create('gift_card_purchases', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('card_id');
            $table->string('coupon_code')->unique();
            $table->enum('customer_type', ['authenticated', 'guest']);
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->string('business_name')->nullable();
            $table->string('street_address');
            $table->string('suburb');
            $table->string('city');
            $table->string('state');
            $table->string('post_code');
            $table->string('payment_status');
            $table->string('payment_method');
            $table->string('payment_intent');
            $table->string('channel');
            $table->string('total_amount');
            $table->string('purchasing_date');
            $table->string('remaining_amount')->nullable();
            $table->string('expiry_date')->nullable();
            $table->enum('is_used', [0, 1])->default(0);
            $table->enum('is_expired', [0, 1])->default(0);
            $table->integer('used_times')->default(0);
            $table->timestamps();
        });

        Schema::create('gift_card_useds', function (Blueprint $table){
            $table->id();
            $table->bigInteger('card_id');
            $table->bigInteger('user_id');
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
        Schema::dropIfExists('gift_cards');
        Schema::dropIfExists('gift_cards_purchases');
    }
}
