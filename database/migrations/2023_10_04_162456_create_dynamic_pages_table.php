<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDynamicPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dynamic_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('active')->default(0);
            $table->string('permalink');
            $table->integer('permalink_status');
            $table->string('hero_heading');
            $table->text('content')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_tags')->nullable();
            $table->string('seo_description')->nullable();
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
        Schema::dropIfExists('dynamic_pages');
    }
}
