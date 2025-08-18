<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    Schema::create('membership_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_name');
            $table->integer('discount_percentage');
            $table->string('duration');
            $table->decimal('price', 8, 2);
            $table->string('badge_text')->nullable();
            $table->text('description')->nullable();
            $table->integer('created_by');
            $table->integer('active')->default(0);
            $table->integer('signup')->default(0);
            $table->timestamps();
        });
            Schema::create('membership_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('plan_id');
            $table->integer('customer_id');
            $table->string('starting_date');
            $table->string('ending_date');
            $table->string('subscription_id')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('expired')->default(0);
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
        Schema::dropIfExists('membership_plans');
        Schema::dropIfExists('membership_subscriptions');
    }
}
