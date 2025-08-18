<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('permalink');
            $table->text('description')->nullable();
            $table->integer('active')->default(0);
            $table->integer('discount_id')->nullable();
            $table->string('created_by');
            $table->timestamps();
        });
        Schema::create('collections_meta', function (Blueprint $table) {
            $table->id();
            $table->integer('collection_id');
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
        Schema::dropIfExists('collections');
        Schema::dropIfExists('collections_meta');
    }
}
