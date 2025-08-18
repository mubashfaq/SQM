<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('permalink');
            $table->string('logo_image')->nullable();
            $table->string('created_by');
            $table->integer('active')->default(0);
            $table->string('cover_image')->nullable();
            $table->timestamps();
        });
        Schema::create('brands_meta', function (Blueprint $table) {
            $table->id();
            $table->integer('brand_id');
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
        Schema::dropIfExists('brands');
        Schema::dropIfExists('brands_meta');
    }
}
