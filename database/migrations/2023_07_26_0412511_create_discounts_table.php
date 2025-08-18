<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('discount_type');
            $table->integer('discount_value');
            $table->string('limited');
            $table->string('from_date');
            $table->string('end_date');
            $table->integer('active')->default(0);
            $table->integer('discount_for')->default(0);;
            $table->string('badge')->nullable();
            $table->string('badge_color')->nullable();
            $table->string('badge_text')->nullable();
            $table->text('badge_image')->nullable();
            $table->string('created_by');
            $table->timestamps();
        });
        Schema::create('discounts_meta', function (Blueprint $table) {
            $table->id();
            $table->integer('deiscount_id');
            $table->text('option');
            $table->string('value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discounts');
        Schema::dropIfExists('discounts_meta');
    }
}
